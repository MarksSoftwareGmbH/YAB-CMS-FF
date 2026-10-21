<?php
declare(strict_types=1);

/* 
 * MIT License
 *
 * Copyright (c) 2018-present, Marks Software GmbH (https://www.marks-software.de/)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace YabCmsFf\Middleware;

use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Cake\Utility\Text;
use Migrations\Migrations;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Throwable;

/**
 * Zero-Config SQLite Bootstrap Middleware for YAB CMS FF
 *
 * Automatically initializes /data/yab_cms_ff.db, runs migrations, seeds,
 * and registers the current HTTP host into the domains table on first request.
 */
class DatabaseBootstrapMiddleware implements MiddlewareInterface
{
    /**
     * Process an inbound server request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR-7 Server Request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler PSR-15 Request Handler
     * @return \Psr\Http\Message\ResponseInterface PSR-7 Response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dataDir = ROOT . DS . 'data';
        $dbFile = $dataDir . DS . 'yab_cms_ff.db';
        $lockFile = $dataDir . DS . '.installed';

        // Fast-path: If already installed and DB file is populated, proceed immediately
        if (is_file($lockFile) && is_file($dbFile) && filesize($dbFile) > 0) {
            return $handler->handle($request);
        }

        $this->bootstrapSqliteDatabase($request, $dataDir, $dbFile, $lockFile);

        return $handler->handle($request);
    }

    /**
     * Bootstraps the SQLite file, runs plugin migrations/seeds, and binds the current host.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR-7 Server Request
     * @param string $dataDir Path to data directory
     * @param string $dbFile Path to SQLite database file
     * @param string $lockFile Path to installation lock file
     * @return void
     * @throws \RuntimeException
     */
    private function bootstrapSqliteDatabase(
        ServerRequestInterface $request,
        string $dataDir,
        string $dbFile,
        string $lockFile
    ): void {
        // 1. Ensure /data directory exists and has proper permissions
        if (!is_dir($dataDir)) {
            if (!mkdir($dataDir, 0775, true) && !is_dir($dataDir)) {
                throw new RuntimeException(__d(
                    'yab_cms_ff',
                    'YAB CMS FF: Could not create directory "{0}". Please check write permissions.',
                    $dataDir
                ));
            }
        }

        // Acquire an exclusive lock to prevent concurrent first-request race conditions
        $mutexFile = $dataDir . DS . '.bootstrap.lock';
        $mutexHandle = fopen($mutexFile, 'c+');
        if ($mutexHandle === false || !flock($mutexHandle, LOCK_EX)) {
            throw new RuntimeException(__d(
                'yab_cms_ff',
                'YAB CMS FF: Could not acquire installation lock in "{0}".',
                $dataDir
            ));
        }

        try {
            // Double-check inside lock in case a parallel request just finished installing
            if (is_file($lockFile) && is_file($dbFile) && filesize($dbFile) > 0) {
                return;
            }

            // Create Apache protection file in case DocumentRoot is misconfigured
            $htaccessFile = $dataDir . DS . '.htaccess';
            if (!is_file($htaccessFile)) {
                file_put_contents($htaccessFile, "Require all denied\nDeny from all\n");
            }

            // 2. Initialize empty SQLite file & enable WAL mode
            if (!is_file($dbFile)) {
                if (!touch($dbFile) || !chmod($dbFile, 0664)) {
                    throw new RuntimeException(__d(
                        'yab_cms_ff',
                        'YAB CMS FF: Could not create or set permissions for SQLite file "{0}".',
                        $dbFile
                    ));
                }
            }

            $pdo = new PDO('sqlite:' . $dbFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA journal_mode = WAL;');
            $pdo->exec('PRAGMA foreign_keys = ON;');
            unset($pdo);

            // 3. Run YabCmsFf Migrations and Seeds
            $migrations = new Migrations();
            $status = $migrations->status(['connection' => 'default', 'plugin' => 'YabCmsFf']);

            if (!empty($status)) {
                if (!$migrations->migrate(['connection' => 'default', 'plugin' => 'YabCmsFf'])) {
                    throw new RuntimeException(__d(
                        'yab_cms_ff',
                        'YAB CMS FF: Database migration failed for plugin "YabCmsFf".'
                    ));
                }

                if (!$migrations->seed(['connection' => 'default', 'plugin' => 'YabCmsFf'])) {
                    throw new RuntimeException(__d(
                        'yab_cms_ff',
                        'YAB CMS FF: Database seeding failed for plugin "YabCmsFf".'
                    ));
                }
            }

            // 4. Register the initial host and scheme in the domains table
            $this->initializeDefaultDomain($request);

            // 5. Clear model cache and write lock file
            Cache::clear('_cake_model_');
            file_put_contents($lockFile, date('c') . " - YAB CMS FF initialized successfully.\n");
        } catch (Throwable $e) {
            Log::error(__d('yab_cms_ff', 'YAB CMS FF Auto-Bootstrap Error: {0}', $e->getMessage()));
            throw new RuntimeException(
                __d('yab_cms_ff', 'YAB CMS FF initialization error for "{0}": {1}', $dbFile, $e->getMessage()),
                (int)$e->getCode(),
                $e
            );
        } finally {
            flock($mutexHandle, LOCK_UN);
            fclose($mutexHandle);
            @unlink($mutexFile);
        }
    }

    /**
     * Seeds or updates the default domain record with the actual HTTP host and scheme.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR-7 Server Request
     * @return void
     */
    private function initializeDefaultDomain(ServerRequestInterface $request): void
    {
        $uri = $request->getUri();
        $host = $uri->getHost() ?: (string)env('HTTP_HOST', 'localhost');
        $scheme = $uri->getScheme() ?: (env('HTTPS') ? 'https' : 'http');

        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            $forwardedHost = env('HTTP_X_FORWARDED_HOST');
            if (!empty($forwardedHost)) {
                $host = trim(explode(',', (string)$forwardedHost)[0]);
            }
        }

        $connection = ConnectionManager::get('default');
        $now = DateTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');

        $domains = $connection->execute('SELECT id, url, theme FROM domains')->fetchAll('assoc');

        if (empty($domains)) {
            $connection->insert('domains', [
                'uuid_id'  => Text::uuid(),
                'scheme'   => $scheme,
                'url'      => $host,
                'name'     => 'Yet Another Boring CMS - Fast Forward',
                'theme'    => 'YabCmsFf',
                'created'  => $now,
                'modified' => $now,
            ]);

            return;
        }

        foreach ($domains as $domain) {
            if ($domain['url'] === 'yab-cms-ff-github.tld' && $domain['theme'] === 'YabCmsFf') {
                $connection->update('domains', [
                    'uuid_id'  => Text::uuid(),
                    'scheme'   => $scheme,
                    'url'      => $host,
                    'modified' => $now,
                ], ['id' => $domain['id']]);
            }
        }
    }
}