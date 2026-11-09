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
namespace YabCmsFf;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\PasswordIdentifier;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\ServerRequest;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Security;
use Psr\Http\Message\ServerRequestInterface;
use TinyAuth\Policy\RequestPolicy;
use YabCmsFf\Command\YabCmsFfCommand;

/**
 * Plugin for YabCmsFf
 */
class YabCmsFfPlugin extends BasePlugin implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        // Add constants, load configuration defaults.
        // By default will load `config/bootstrap.php` in the plugin.
        parent::bootstrap($app);
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        // Add routes.
        // By default will load `config/routes.php` in the plugin.
        parent::routes($routes);
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue = parent::middleware($middlewareQueue);

        $middlewareQueue->add(new AuthenticationMiddleware($this));
        $middlewareQueue->add(new AuthorizationMiddleware($this, [
            'requireAuthorizationCheck' => false,
        ]));

        return $middlewareQueue;
    }

    /**
     * Add commands for the plugin.
     *
     * @param \Cake\Console\CommandCollection $commands The command collection to update.
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Add console commands here.
        $commands = parent::console($commands);

        $commands->add('yab_cms_ff', YabCmsFfCommand::class);

        return $commands;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        // Add your services here
    }

    /**
     * Returns a service provider instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        $fields = [
            PasswordIdentifier::CREDENTIAL_USERNAME => 'username',
            PasswordIdentifier::CREDENTIAL_PASSWORD => 'password',
        ];

        $prefix = $request->getParam('prefix');
        if ($prefix === 'Api' || $request->is(['json', 'xml'])) {
            $service->setConfig([
                'unauthenticatedRedirect' => null,
                'queryParam' => null,
            ]);

            $service->loadAuthenticator('Authentication.Jwt', [
                'secretKey'     => Security::getSalt(),
                'queryParam'    => 'token',
                'header'        => 'Authorization',
                'tokenPrefix'   => 'Bearer',
                'returnPayload' => false,
                'identifier'    => [
                    'className' => 'Authentication.JwtSubject',
                    'resolver'  => [
                        'className' => 'Authentication.Orm',
                        'userModel' => 'YabCmsFf.Users',
                        'finder'    => 'auth',
                    ],
                ],
            ]);

            $service->loadAuthenticator('Authentication.Form', [
                'fields'     => $fields,
                'loginUrl'   => '/api/oauth/token',
                'identifier' => [
                    'className' => 'Authentication.Password',
                    'fields'    => $fields,
                    'resolver'  => [
                        'className' => 'Authentication.Orm',
                        'userModel' => 'YabCmsFf.Users',
                        'finder'    => 'auth',
                    ],
                ],
            ]);

            return $service;
        } else {
            $service->setConfig([
                'unauthenticatedRedirect' => '/',
                'queryParam' => 'redirect',
            ]);

            $service->loadAuthenticator('Authentication.Session', [
                'sessionKey' => 'Auth.User',
            ]);

            $service->loadAuthenticator('Authentication.Form', [
                'fields'     => $fields,
                'loginUrl'   => ($prefix === 'Admin')
                    ? Router::url(['plugin' => 'YabCmsFf', 'prefix' => 'Admin', 'controller' => 'Users', 'action' => 'login'])
                    : Router::url(['plugin' => 'YabCmsFf', 'controller' => 'Users', 'action' => 'login']),
                'identifier' => [
                    'className' => 'Authentication.Password',
                    'fields'    => $fields,
                    'resolver'  => [
                        'className' => 'Authentication.Orm',
                        'userModel' => 'YabCmsFf.Users',
                        'finder'    => 'auth',
                    ],
                ],
            ]);
        }

        return $service;
    }

    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $mapResolver = new MapResolver();
        $mapResolver->map(ServerRequest::class, new RequestPolicy([
            'rolesTable'        => 'YabCmsFf.Roles',
            'rolesTablePlugin'  => 'YabCmsFf',
            'usersTable'        => 'YabCmsFf.Users',
            'roleColumn'        => 'role_id',
            'multiRole'         => false,
            'aclFilePath'       => Plugin::configPath('YabCmsFf'),
            'autoClearCache'    => true,
        ]));

        return new AuthorizationService($mapResolver);
    }
}
