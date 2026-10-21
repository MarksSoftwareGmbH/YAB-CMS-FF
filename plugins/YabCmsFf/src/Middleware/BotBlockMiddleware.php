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

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * BotBlockMiddleware
 *
 * Intercepts malicious bot scanner requests hitting predefined URL paths.
 * Triggers a localized flash message using native CakePHP formatting and redirects
 * the client to the root index path to prevent application resource exhaustion.
 */
class BotBlockMiddleware implements MiddlewareInterface
{
    /**
     * @var array List of URL path patterns identified as automated bot probes.
     */
    private array $botPaths = [
        // --- Configuration & Framework Secrets ---
        '/.env',
        '/config',
        '/composer.json',
        '/composer.lock',
        '/package.json',
        '/package-lock.json',
        '/web.config',
        '/.htaccess',
        '/config.php',
        '/boot.php',
        '/local.php',

        // --- VCS & IDE Metadata ---
        '/.git',
        '/.idea',
        '/.vscode',
        '/.svn',
        '/.DS_Store',

        // --- WordPress Probes ---
        '/wp-admin',
        '/wp-login',
        '/wp-content',
        '/wp-includes',
        '/xmlrpc.php',
        '/wp-config',
        '/wp-json',

        // --- Shop & CMS Probes ---
        '/app/etc',
        '/downloader',
        '/magento',
        '/shopware.php',
        '/administrator',
        '/install.php',

        // --- Backups & Database Dumps ---
        '/backup',
        '/backups',
        '/db.sql',
        '/database.sql',
        '/mysql.sql',
        '/dump.sql',
        '/site.zip',
        '/public.zip',
        '/www.zip',
        '/archive.zip',
        '/backup.tar.gz',

        // --- Admin Panels & Info Logs ---
        '/phpmyadmin',
        '/phpMyAdmin',
        '/pma',
        '/mysql',
        '/cpanel',
        '/webmin',
        '/cgi-bin',
        '/error_log',
        '/debug.log',
        '/phpinfo.php',
        '/info.php',
    ];

    /**
     * Process an inbound server request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR-7 Server Request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler PSR-15 Request Handler
     * @return \Psr\Http\Message\ResponseInterface PSR-7 Response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var ServerRequest $request */
        $path = $request->getUri()->getPath();

        foreach ($this->botPaths as $pattern) {
            if (str_starts_with($path, $pattern)) {
                // Emulate CakePHP FlashComponent array structure safely
                $session = $request->getSession();
                if ($session) {
                    $session->start();
                    $session->write('Flash.flash', [
                        [
                            'message' => __d('yab_cms_ff', 'The requested link could not be found. Please, try again.'),
                            'key'     => 'flash',
                            'element' => 'error',
                            'params'  => [
                                'class'  => 'error',
                                'escape' => true
                            ]
                        ]
                    ]);
                }
                // Halt pipeline execution and return immediate 302 Redirect response
                return (new Response())
                    ->withStatus(302)
                    ->withLocation('/');
            }
        }

        return $handler->handle($request);
    }
}