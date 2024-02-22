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

/**
 * Roles Controller
 *
 * @property \YabCmsFf\Model\Table\RolesTable $Roles
 */
class RolesController extends AppController
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
            'foreign_key',
            'title',
            'alias',
            'created',
            'modified',
        ],
        'order' => ['title' => 'ASC']
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
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->Roles
            ->find('search', search: $this->getRequest()->getQueryParams());

        YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('roles', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $role = $this->Roles->get($id, contain: [
            'Users' => function ($q) {
                return $q->orderBy(['Users.username' => 'ASC']);
            }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeViewRender', $this, [
            'Role' => $role,
        ]);

        $this->set('role', $role);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $role = $this->Roles->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeAdd', $this, [
                'Role' => $role,
            ]);
            if ($this->Roles->save($role)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Roles.onAddSuccess', $this, [
                    'Role' => $role,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The role has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Roles.onAddFailure', $this, [
                    'Role' => $role,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The role could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $users = $this->Roles->Users->find('list', keyField: 'id', valueField: 'full_name_username');

        YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeAddRender', $this, [
            'Role' => $role,
            'Users' => $users,
        ]);

        $this->set(compact('role', 'users'));
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
        $role = $this->Roles->get($id, contain: [
            'Users' => function ($q) {
                return $q->orderBy(['Users.username' => 'ASC']);
            }
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeEdit', $this, [
                'Role' => $role,
            ]);
            if ($this->Roles->save($role)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Roles.onEditSuccess', $this, [
                    'Role' => $role,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The role has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Roles.onEditFailure', $this, [
                    'Role' => $role,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The role could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $users = $this->Roles->Users->find('list', keyField: 'id', valueField: 'full_name_username');

        YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeEditRender', $this, [
            'Role' => $role,
            'Users' => $users,
        ]);

        $this->set(compact('role', 'users'));
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
        $role = $this->Roles->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Roles.beforeDelete', $this, [
            'Role' => $role,
        ]);
        // Role Admin, Manager and Public can not be deleted!
        if (($role->title === 'Admin') ||
            ($role->title === 'Manager') ||
            ($role->title === 'Public')
        ) {
            $this->Flash->set(
                __d('yab_cms_ff', 'You are not allowed to delete this role.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect(['action' => 'index']);
        }

        if ($this->Roles->delete($role)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Roles.onDeleteSuccess', $this, [
                'Role' => $role,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The role has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Roles.onDeleteFailure', $this, [
                'Role' => $role,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The role could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]);
        }

        return $this->redirect(['action' => 'index']);
    }
}
