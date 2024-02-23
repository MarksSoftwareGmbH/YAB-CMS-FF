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
namespace YabCmsFf\Controller\Admin;

use YabCmsFf\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use YabCmsFf\Utility\YabCmsFf;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Text;

/**
 * Users Controller
 *
 * @property \YabCmsFf\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Locale
     *
     * @var string
     */
    private string $locale;

    /**
     * Pagination
     *
     * @var array
     */
    public array $paginate = [
        'limit' => 25,
        'maxLimit' => 50,
        'sortableFields' => [
            'id',
            'role_id',
            'locale_id',
            'username',
            'name',
            'email',
            'status',
            'last_login',
            'created',
            'modified',
            'Roles.title',
            'Locales.name',
        ],
        'order' => ['created' => 'DESC']
    ];

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     * @link https://book.cakephp.org/4/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $session = $this->getRequest()->getSession();
        $this->locale = $session->check('Locale.code')? $session->read('Locale.code'): 'en_US';
    }

    /**
     * Login method
     *
     * @return bool|\Cake\Http\Response|null
     */
    public function login()
    {
        if ($this->getRequest()->is('post')) {
            $event = YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeLogin', $this, []);
            if ($event->isStopped()) {
                return $this->redirect(['action' => 'login']);
            }

            /**
             * Use the configured authentication adapters, and attempt to identify the user
             * by credentials contained in $request.
             *
             * Triggers `Auth.afterIdentify` event which the authenticate classes can listen
             * to.
             *
             * @return array|false User record data, or false, if the user could not be identified.
             */
            $user = $this->Auth->identify();
            if ($user) {
                $event = YabCmsFf::dispatchEvent('Controller.Admin.Users.onLoginSuccess', $this, ['User' => $user]);
                if ($event->isStopped()) {
                    return $this->redirect(['action' => 'login']);
                }

                /**
                 * Set provided user info to storage as logged in user.
                 *
                 * The storage class is configured using `storage` config key or passing
                 * instance to AuthComponent::storage().
                 *
                 * @param array|\ArrayAccess $user User data.
                 * @return void
                 * @link https://book.cakephp.org/4/en/controllers/components/authentication.html#identifying-users-and-logging-them-in
                 */
                $this->Auth->setUser($user);

                $this->Flash->set(
                    __d('yab_cms_ff', 'You have successfully signed in.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $event = YabCmsFf::dispatchEvent('Controller.Admin.Users.onLoginFailure', $this, []);
                if ($event->isStopped()) {
                    return $this->redirect(['action' => 'login']);
                }
                $this->Flash->set(
                    __d('yab_cms_ff', 'You could not login. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect(['action' => 'login']);
            }
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeLogout', $this, []);
        $this->Flash->set(
            __d('yab_cms_ff', 'You have successfully signed out.'),
            ['element' => 'default', 'params' => ['class' => 'success']]
        );

        return $this->redirect($this->Auth->logout());
    }

    /**
     * Forgot method
     *
     * @return \Cake\Http\Response|null
     */
    public function forgot()
    {
        // Get session object
        $session = $this->getRequest()->getSession();

        if ($this->getRequest()->is('post')) {
            $session->write('YabCmsFf.Admin.User', $this->getRequest()->getData());

            $postData = $this->getRequest()->getData();

            if (
                $session->read('YabCmsFf.Captcha.result') !=
                $this->getRequest()->getData('captcha_result')
            ) {
                return $this->redirect($this->referer());
            }
            unset($postData['captcha_result']);

            YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeForgot', $this, []);

            $user = $this->Users
                ->find()
                ->where([
                    'username' => $postData['username'],
                    'email' => $postData['email'],
                    'status' => 1,
                ])
                ->first();
            if (!$user) {
                $this->Flash->set(
                    __d('yab_cms_ff', 'Invalid username, email or account blocked. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect(['action' => 'forgot']);
            }

            $resetToken = $this->Users->resetToken($user);
            if (!$resetToken) {
                $this->Flash->set(
                    __d('yab_cms_ff', 'An error occurred. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            $resetUrl = [
                '_full' => true,
                'plugin' => 'YabCmsFf',
                'controller' => 'Users',
                'action' => 'reset',
                'username' => isset($user->username)? $user->username: '',
                'token' => isset($user->token)? $user->token: '',
            ];
            if ($this->Users->sendResetPasswordEmail($user, $resetUrl, 'default', 'html')) {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onForgotSuccess', $this, ['User' => $user]);

                $this->Flash->set(
                    __d('yab_cms_ff', 'An email with further instructions has been sent to you.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
                if ($session->check('YabCmsFf.Admin.User')) {
                    $session->delete('YabCmsFf.Admin.User');
                    return $this->redirect(['action' => 'login']);
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onForgotFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'An email with further instructions could not be sent to you. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            return $this->redirect($this->referer());
        }

        $this->Global->captcha($this);
    }

    /**
     * Reset method
     *
     * @param string|null $username
     * @param string|null $token
     *
     * @return \Cake\Http\Response|null
     */
    public function reset(string $username = null, string $token = null)
    {
        // Get session object
        $session = $this->getRequest()->getSession();

        $user = $this->Users
            ->find('byToken', options: ['username' => $username, 'token' => $token])
            ->first();
        if (!$user) {
            $this->Flash->set(
                __d('yab_cms_ff', 'An error occurred. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect(['action' => 'login']);
        }

        if ($this->getRequest()->is('post')) {

            $postData = $this->getRequest()->getData();

            if (
                $session->read('YabCmsFf.Captcha.result') !=
                $this->getRequest()->getData('captcha_result')
            ) {
                return $this->redirect($this->referer());
            }
            unset($postData['captcha_result']);

            YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeReset', $this, ['User' => $user]);
            $user = $this->Users->changePasswordFromReset($user, $this->getRequest()->getData());
            if (!$user) {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onResetFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'An error occurred. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect($this->referer());
            }
            YabCmsFf::dispatchEvent('Controller.Admin.Users.onResetSuccess', $this, ['User' => $user]);
            $this->Flash->set(
                __d('yab_cms_ff', 'Your password has been reset successfully.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );

            if ($session->check('YabCmsFf.Captcha')) {
                $session->delete('YabCmsFf.Captcha');
                return $this->redirect(['action' => 'login']);
            }
        }

        $this->Global->captcha($this);

        $this->set('user', $user);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->Users
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'Locales',
                'Roles',
            ]);

        $roles = $this->Users->Roles->find('list');

        $locales = $this->Users->Locales
            ->find('list',
                conditions: ['Locales.status' => 1],
                order: ['Locales.weight' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeIndexRender', $this, [
            'Query' => $query,
            'Roles' => $roles,
            'Locales' => $locales,
        ]);

        $this->set('users', $this->paginate($query));
        $this->set(compact('roles', 'locales'));
    }

    /**
     * Profile method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function profile(int $id = null)
    {
        // Get session object
        $session = $this->getRequest()->getSession();

        // Auth user can enter just his own profile || Admin can enter the view
        if (!($session->read('Auth.User.id') == $id)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'You are not allowed to view this profile.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id, contain: [
            'Locales' => function ($q) {
                return $q->orderBy(['Locales.weight' => 'ASC']);
            },
            'Roles' => function ($q) {
                return $q->orderBy(['Roles.title' => 'ASC']);
            }
        ]);

        $Regions = TableRegistry::getTableLocator()->get('YabCmsFf.Regions');
        $regions = $Regions
            ->find('list',
                conditions: ['Regions.status' => 1],
                order: ['Regions.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list',
                conditions: ['Countries.status' => 1],
                order: ['Countries.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeProfileRender', $this, [
            'User' => $user,
            'Regions' => $regions,
            'Countries' => $countries,
        ]);

        $this->set(compact('user', 'regions', 'countries'));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $user = $this->Users->get($id, contain: [
            'Locales',
            'Roles',
        ]);

        $Regions = TableRegistry::getTableLocator()->get('YabCmsFf.Regions');
        $regions = $Regions
            ->find('list',
                conditions: ['Regions.status' => 1],
                order: ['Regions.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list',
                conditions: ['Countries.status' => 1],
                order: ['Countries.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeViewRender', $this, [
            'User' => $user,
            'Regions' => $regions,
            'Countries' => $countries,
        ]);

        $this->set(compact('user', 'regions', 'countries'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $user = $this->Users->patchEntity(
                $user,
                Hash::merge(
                    $this->getRequest()->getData(),
                    ['token' => Text::uuid(), 'activation_date' => date('Y-m-d H:i:s')]
                ),
                ['associated' => ['UserProfiles']]
            );
            YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeAdd', $this, ['User' => $user]);
            if ($this->Users->save($user)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onAddSuccess', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onAddFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $roles = $this->Users->Roles->find('list',
            order: ['Roles.title' => 'ASC'],
            keyField: 'id',
            valueField: 'title',
        );

        $locales = $this->Users->Locales->find('list',
            order: ['Locales.weight' => 'ASC'],
            keyField: 'id',
            valueField: 'name',
        );

        $Regions = TableRegistry::getTableLocator()->get('YabCmsFf.Regions');
        $regions = $Regions
            ->find('list',
                conditions: ['Regions.status' => 1],
                order: ['Regions.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list',
                conditions: ['Countries.status' => 1],
                order: ['Countries.name' => 'ASC'],
                keyField: 'id',
                valueField: 'name',
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeAddRender', $this, [
            'User' => $user,
            'Roles' => $roles,
            'Locales' => $locales,
            'Regions' => $regions,
            'Countries' => $countries,
        ]);

        $this->set(compact('user', 'locales', 'roles', 'regions', 'countries'));
    }

    /**
     * Edit method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function edit(int $id = null)
    {
        $user = $this->Users->get($id, contain: [
            'Locales',
            'Roles',
            'UserProfiles',
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity(
                $user,
                $this->getRequest()->getData(),
                ['associated' => ['UserProfiles']]
            );
            YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeEdit', $this, ['User' => $user]);
            if ($this->Users->save($user)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onEditSuccess', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onEditFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $roles = $this->Users->Roles->find('list', order: ['Roles.title' => 'ASC'], keyField: 'id', valueField: 'title');

        $locales = $this->Users->Locales->find('list', order: ['Locales.weight' => 'ASC'], keyField: 'id', valueField: 'name');

        $Regions = TableRegistry::getTableLocator()->get('YabCmsFf.Regions');
        $regions = $Regions
            ->find('list', conditions: ['Regions.status' => 1], order: ['Regions.name' => 'ASC'], keyField: 'id', valueField: 'name')
            ->toArray();

        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', conditions: ['Countries.status' => 1], order: ['Countries.name' => 'ASC'], keyField: 'id', valueField: 'name')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeEditRender', $this, [
            'User' => $user,
            'Roles' => $roles,
            'Locales' => $locales,
            'Regions' => $regions,
            'Countries' => $countries,
        ]);

        $this->set(compact('user', 'locales', 'roles', 'regions', 'countries'));
    }

    /**
     * Reset password method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function resetPassword(int $id = null)
    {
        $user = $this->Users->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeResetPassword', $this, ['User' => $user]);
            if ($this->Users->changePasswordFromReset($user, $this->getRequest()->getData())) {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onResetPasswordSuccess', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user password has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Users.onResetPasswordFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user password could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeResetPasswordRender', $this, ['User' => $user]);

        $this->set('user', $user);
    }

    /**
     * Delete method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function delete(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id, contain: ['Roles']);

        // User Admin can not be deleted!
        if ($user->role->title === 'Admin') {
            $this->Flash->set(
                __d('yab_cms_ff', 'You are not allowed to delete this user.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect(['action' => 'index']);
        }
        YabCmsFf::dispatchEvent('Controller.Admin.Users.beforeDelete', $this, ['User' => $user]);
        if ($this->Users->delete($user)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Users.onDeleteSuccess', $this, ['User' => $user]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The user has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Users.onDeleteFailure', $this, ['User' => $user]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The user could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }
}