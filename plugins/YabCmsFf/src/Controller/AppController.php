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
namespace YabCmsFf\Controller;

use App\Controller\AppController as BaseController;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Cake\Utility\Text;
use RuntimeException;
use Throwable;
use YabCmsFf\Controller\Traits\HookableComponentTrait;
use YabCmsFf\Configure\Engine\SettingsConfig;

class AppController extends BaseController
{

    use HookableComponentTrait;

    /**
     * Http host
     *
     * @var string|null
     */
    private ?string $httpHost = null;

    /**
     * Domain id
     *
     * @var int
     */
    private int $domainId = 1;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize($loadComponents = true): void
    {
        parent::initialize();

        $this->httpHost = $this->resolveHttpHost();
        $this->configureDatasourceEnvironment();
        $this->domainId = $this->resolveDomainId();

        if ($loadComponents) {
            if (PHP_SAPI !== 'cli') {
                $this->loadComponent('FormProtection');
            }

            $this->loadComponent('Search.Search', [
                'actions' => ['index', 'search', 'view'],
            ]);

            // Load all Components
            $this->_dispatchAfterInitialize();

            /**
             * Add a component to the controller's registry.
             *
             * This method will also set the component to a property.
             * For example:
             *
             * ```
             * $this->loadComponent('Acl.Acl');
             * ```
             *
             * Will result in a `Toolbar` property being set.
             *
             * @param string $name The name of the component to load.
             * @param array $config The config for the component.
             * @return \Cake\Controller\Component
             */
            $this->loadComponent('TinyAuth.Auth', [
                'loginAction' => [
                    'plugin' => 'YabCmsFf',
                    'controller' => 'Users',
                    'action' => 'login',
                ],
                'loginRedirect' => [
                    'prefix' => 'Admin',
                    'plugin' => 'YabCmsFf',
                    'controller' => 'Dashboards',
                    'action' => 'dashboard',
                ],
                'logoutRedirect' => [
                    'plugin' => 'YabCmsFf',
                    'controller' => 'Users',
                    'action' => 'login',
                ],
                'authError' => __d('yab_cms_ff', 'You are not authorized to access this location.'),
                'flash' => [
                    'element' => 'default',
                    'params' => ['class' => 'error'],
                ],
                'allowFilePath' => Plugin::configPath('YabCmsFf'),
                'autoClearCache' => true,
                'authenticate' => [
                    'TinyAuth.Form' => [
                        'fields' => [
                            'username' => 'username',
                            'password' => 'password',
                        ],
                        'userModel' => 'YabCmsFf.Users',
                        // Use finder method in users table
                        'finder' => 'auth',
                    ],
                ],
                'authorize' => [
                    'TinyAuth.Tiny' => [
                        'superAdminRole' => '1', // id of super admin role, which grants access to ALL resources
                        'superAdmin' => '1', // super admin, which grants access to ALL resources
                        'pivotTable' => 'roles_users', // Should be used in multi-roles setups
                        'aclFilePath' => Plugin::configPath('YabCmsFf'), // Possible to locate INI file at given path e.g. Plugin::configPath('Admin'), filePath is also available for shared config
                        'rolesTable' => 'YabCmsFf.Roles', // name of Configure key holding available roles OR class name of roles table
                        'usersTable' => 'YabCmsFf.Users', // name of the Users table
                        'autoClearCache' => true, // Set to true to delete cache automatically in debug mode, keep null for auto-detect
                    ],
                ],
            ]);
        }
    }

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     * @link https://book.cakephp.org/4/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeFilter(EventInterface $event): void
    {
        /**
         * Parent before filter callback.
         *
         * @param \Cake\Event\Event $event The beforeFilter event.
         * @return void
         */
        parent::beforeFilter($event);

        /**
         * Load Settings
         */
        Configure::config('settings', new SettingsConfig(null, $this->domainId));
        Configure::load('settings', 'settings');
    }

    /**
     * Called after the controller action is run, but before the view is rendered. You can use this method
     * to perform logic or set view variables that are required on every request.
     *
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     * @link https://book.cakephp.org/4/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeRender(EventInterface $event)
    {
        /**
         * Parent before render callback.
         *
         * @param \Cake\Event\Event $event The beforeRender event.
         * @return void
         */
        parent::beforeRender($event);

        /**
         * The view theme to use.
         *
         * @param string|null|false $theme Theme name. If null returns current theme.
         *   Use false to remove the current theme.
         * @return string|$this
         */
        $prefix = (string)$this->getRequest()->getParam('prefix');
        if ($prefix === 'Admin') {
            $theme = (string)Configure::read('YabCmsFf.settings.backendTheme', 'YabCmsFf');
        } else {
            $theme = (string)Configure::read('YabCmsFf.settings.frontendTheme', 'YabCmsFf');
        }

        /**
         * Adds helpers to use by merging with existing ones.
         *
         * @param array $helpers Helpers to use.
         * @return $this
         * @since 4.3.0
         */
        $this
            ->viewBuilder()
            ->setTheme($theme)
            ->addHelpers([
                // Extending the default Helper classes
                'Html' => [
                    'className'     => 'BootstrapUI.Html',
                    'iconDefaults'  => [
                        'tag'       => 'i',
                        'namespace' => 'fas',
                        'prefix'    => 'fa',
                    ],
                ],
                'Form' => ['className' => 'BootstrapUI.Form'],
                'Paginator' => ['className' => 'BootstrapUI.Paginator'],
                'Breadcrumbs' => ['className' => 'BootstrapUI.Breadcrumbs'],
                // YabCmsFf global custom view helper methods
                'YabCmsFf' => ['className' => 'YabCmsFf.YabCmsFf'],
                'Menu' => ['className' => 'YabCmsFf.Menu'],
            ]);
    }

    /**
     * Determines and validates the current HTTP host.
     *
     * @return string
     * @throws \RuntimeException
     */
    private function resolveHttpHost(): string
    {
        if (PHP_SAPI === 'cli') {
            return (string)env('HTTP_HOST', 'localhost');
        }

        $host = $this->getRequest()->host() ?: env('HTTP_HOST');
        if (empty($host)) {
            throw new RuntimeException(__d(
                'yab_cms_ff',
                'The HTTP_HOST environment variable is missing. Unable to determine the application domain.'
            ));
        }

        // Remove port number if present (e.g., localhost:8765 -> localhost)
        $cleanHost = explode(':', (string)$host)[0];

        if (filter_var($cleanHost, FILTER_VALIDATE_IP) !== false) {
            $forwarded = env('HTTP_X_FORWARDED_HOST');
            if (empty($forwarded)) {
                throw new RuntimeException(__d(
                    'yab_cms_ff',
                    'The HTTP_X_FORWARDED_HOST header is required when running behind a reverse proxy (HTTP_HOST is an IP address).'
                ));
            }
            // If multiple proxies exist, take the first forwarded host
            return trim(explode(',', (string)$forwarded)[0]);
        }

        return (string)$host;
    }

    /**
     * Maps the active datasource connection based on the runtime environment.
     *
     * @return void
     */
    private function configureDatasourceEnvironment(): void
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        $configuredConnections = ConnectionManager::configured();

        if (
            str_contains((string)$this->httpHost, 'localhost') &&
            in_array('development', $configuredConnections, true)
        ) {
            ConnectionManager::alias('development', 'default');
        } elseif (in_array('production', $configuredConnections, true)) {
            ConnectionManager::alias('production', 'default');
        }
    }

    /**
     * Resolves the current domain ID from the database or updates the placeholder seed domain.
     *
     * @return int
     * @throws \RuntimeException
     */
    private function resolveDomainId(): int
    {
        try {
            $Domains = $this->fetchTable('YabCmsFf.Domains');
            $domain = $Domains->find()
                ->select(['id'])
                ->where(['url' => $this->httpHost])
                ->first();

            if ($domain !== null) {
                return (int)$domain->id;
            }

            // Self-heal if the seeded placeholder domain still exists
            $placeholder = $Domains->find()
                ->where([
                    'url'   => 'yab-cms-ff-github.tld',
                    'theme' => 'YabCmsFf',
                ])
                ->first();

            $scheme = $this->getRequest()->scheme() ?: (env('HTTPS') ? 'https' : 'http');

            if ($placeholder !== null) {
                $placeholder->set([
                    'uuid_id'  => Text::uuid(),
                    'scheme'   => $scheme,
                    'url'      => $this->httpHost,
                    'modified' => DateTime::now(),
                ]);
                $Domains->saveOrFail($placeholder);

                return (int)$placeholder->id;
            }

            // Fallback to the first available domain record
            $firstDomain = $Domains->find()->select(['id'])->first();
            if ($firstDomain !== null) {
                return (int)$firstDomain->id;
            }
        } catch (Throwable $e) {
            Log::error(__d(
                'yab_cms_ff',
                'Failed to resolve domain for host "{0}": {1}',
                (string)$this->httpHost,
                $e->getMessage()
            ));

            throw new RuntimeException(
                __d('yab_cms_ff', 'Database initialization error: Unable to resolve domain configuration.'),
                (int)$e->getCode(),
                $e
            );
        }

        return 1;
    }
}
