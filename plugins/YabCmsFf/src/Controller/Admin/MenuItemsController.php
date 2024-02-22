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
use Cake\I18n\DateTime;
use YabCmsFf\Utility\YabCmsFf;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * MenuItems Controller
 *
 * @property \YabCmsFf\Model\Table\MenuItemsTable $MenuItems
 */
class MenuItemsController extends AppController
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
            'MenuItems.id',
            'MenuItems.parent_id',
            'MenuItems.menu_id',
            'MenuItems.domain_id',
            'MenuItems.foreign_key',
            'MenuItems.title',
            'MenuItems.alias',
            'MenuItems.sub_title',
            'MenuItems.link',
            'MenuItems.link_target',
            'MenuItems.link_rel',
            'MenuItems.lft',
            'MenuItems.rght',
            'MenuItems.locale',
            'MenuItems.status',
            'MenuItems.created',
            'MenuItems.modified',
            'Menus.title',
            'Domains.name',
        ],
        'order' => ['MenuItems.lft' => 'ASC']
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
        $query = $this->MenuItems
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'ParentMenuItems',
                'Menus',
                'Domains' => function ($q) {
                    return $q->orderBy(['Domains.name' => 'ASC']);
                }
            ]);

        $domains = $this->MenuItems->Domains
            ->find('list',
                order: ['Domains.name' => 'ASC'],
                keyField: 'name',
                valueField: 'name'
            )
            ->toArray();

        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list',
                conditions: ['Locales.status' => 1],
                order: ['Locales.weight' => 'ASC'],
                keyField: 'code',
                valueField: 'name'
            )
            ->toArray();

        $menus = $this->MenuItems->Menus
            ->find('list',
                order: ['Menus.title' => 'ASC'],
                keyField: 'title',
                valueField: 'alias'
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeIndexRender', $this, [
            'Query' => $query,
            'Domains' => $domains,
            'Locales' => $locales,
            'Menus' => $menus,
        ]);

        $this->set('menuItems', $this->paginate($query));
        $this->set(compact('domains', 'menus', 'locales'));
    }

    /**
     * Ajax move method
     *
     * @return void
     */
    public function ajaxMove()
    {
        if (!$this->getRequest()->is('ajax')) {
            $this->Flash->set(
                __d('yab_cms_ff', 'Invalid request. Please, try again with a ajax request.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        $response = false;
        if ($this->getRequest()->getQuery('draggedLft') > $this->getRequest()->getQuery('siblingLft')) {
            $movement = $this->MenuItems
                ->find()
                ->where([
                        'MenuItems.lft >' => $this->getRequest()->getQuery('siblingLft'),
                        'MenuItems.lft <=' => $this->getRequest()->getQuery('draggedLft')
                    ]
                )
                ->count();
            $menuItem = $this->MenuItems->get($this->getRequest()->getQuery('draggedId'));

            if ($this->MenuItems->moveUp($menuItem, $movement)) {
                $response = true;
            }
        }

        if ($this->getRequest()->getQuery('draggedLft') < $this->getRequest()->getQuery('siblingLft')) {
            $movement = $this->MenuItems
                ->find()
                ->where([
                        'MenuItems.lft <' => $this->getRequest()->getQuery('siblingLft'),
                        'MenuItems.lft >=' => $this->getRequest()->getQuery('draggedLft')
                    ]
                )
                ->count();
            $menuItem = $this->MenuItems->get($this->getRequest()->getQuery('draggedId'));

            if ($this->MenuItems->moveDown($menuItem, $movement)) {
                $response = true;
            }
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
        $this->viewBuilder()->setPlugin('YabCmsFf')->setLayout(false);
    }

    /**
     * Move up method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function moveUp(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'put']);
        $menuItem = $this->MenuItems->get($id);
        if ($this->MenuItems->moveUp($menuItem)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item has been moved up.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item could not be moved up. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Move down method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function moveDown(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'put']);
        $menuItem = $this->MenuItems->get($id);
        if ($this->MenuItems->moveDown($menuItem)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item has been moved down.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item could not be moved down. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $menuItem = $this->MenuItems->get($id, contain: [
            'Menus',
            'ParentMenuItems',
            'ChildMenuItems',
            'Domains' => function ($q) {
                return $q->orderBy(['Domains.name' => 'ASC']);
            }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeViewRender', $this, [
            'MenuItem' => $menuItem
        ]);

        $this->set('menuItem', $menuItem);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $menuItem = $this->MenuItems->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $menuItem = $this->MenuItems->patchEntity($menuItem, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeAdd', $this, [
                'MenuItem' => $menuItem,
            ]);
            if ($this->MenuItems->save($menuItem)) {
                YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onAddSuccess', $this, [
                    'MenuItem' => $menuItem,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu item has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onAddFailure', $this, [
                    'MenuItem' => $menuItem,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu item could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $parentMenuItems = $this->MenuItems
            ->find('treeList',
                keyPath: 'id',
                valuePath: function($entity) {
                    return $entity->title . ' ' . '(' . $this->MenuItems->Domains->getNameById($entity->domain_id) . ')';
                },
                spacer: '-> '
            )
            ->where(['MenuItems.status' => 1]);

        $menus = $this->MenuItems->Menus
            ->find('list', 
                order: ['Menus.locale' => 'ASC'],
                keyField: 'id',
                valueField: 'title_locale_domain',
                contain: [
                    'Domains' => [
                        'fields' => [
                            'Domains.id',
                            'Domains.name',
                        ],
                    ],
                ]
            )
            ->where(['Menus.status' => 1]);

        YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeAddRender', $this, [
            'MenuItem' => $menuItem,
            'ParentMenuItems' => $parentMenuItems,
            'Menus' => $menus,
        ]);

        $this->set(compact('menuItem', 'parentMenuItems', 'menus'));
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
        $menuItem = $this->MenuItems->get($id, contain: [
            'Menus',
            'ParentMenuItems',
            'ChildMenuItems',
            'Domains',
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $menuItem = $this->MenuItems->patchEntity($menuItem, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeEdit', $this, [
                'MenuItem' => $menuItem,
            ]);
            if ($this->MenuItems->save($menuItem)) {
                YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onEditSuccess', $this, [
                    'MenuItem' => $menuItem,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu item has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]);

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onEditFailure', $this, [
                    'MenuItem' => $menuItem,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu item could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]);
            }
        }

        $parentMenuItems = $this->MenuItems->ParentMenuItems
            ->find('treeList',
                keyPath: 'id',
                valuePath: function($entity) {
                    return $entity->title . ' ' . '(' . $this->MenuItems->Domains->getNameById($entity->domain_id) . ')';
                },
                spacer: '-> '
            )
            ->where([
                'ParentMenuItems.id !=' => $menuItem->id,
                'ParentMenuItems.status' => 1,
            ]);

        $menus = $this->MenuItems->Menus
            ->find('list',
                order: ['Menus.locale' => 'ASC'],
                keyField: 'id',
                valueField: 'title_locale_domain',
                contain: [
                    'Domains' => [
                        'fields' => [
                            'Domains.id',
                            'Domains.name',
                        ],
                    ],
                ]
            )
            ->where(['Menus.status' => 1]);

        YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeEditRender', $this, [
            'MenuItem' => $menuItem,
            'ParentMenuItems' => $parentMenuItems,
            'Menus' => $menus,
        ]);

        $this->set(compact('menuItem', 'parentMenuItems', 'menus'));
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
        $menuItem = $this->MenuItems->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.beforeDelete', $this, [
            'MenuItem' => $menuItem,
        ]);
        if ($this->MenuItems->delete($menuItem)) {
            YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onDeleteSuccess', $this, [
                'MenuItem' => $menuItem,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]);
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.MenuItems.onDeleteFailure', $this, [
                'MenuItem' => $menuItem,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu item could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Import method
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function import()
    {
        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $postData = $this->getRequest()->getData();

            // check if delimiter and enclosure are set
            ($postData['delimiter'] == ''? $del = ';': $del = $postData['delimiter']);
            ($postData['enclosure'] == ''? $encl = '"': $encl = $postData['enclosure']);

            if (in_array($postData['file']->getClientMediaType(), [
                'text/comma-separated-values',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext'
            ])) {
                $targetPath = TMP . $postData['file']->getClientFileName();
                $postData['file']->moveTo($targetPath);

                // Transform the csv cols and rows into a associative array based on the alias and rows
                $menuItems = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($menuItems) && !empty($menuItems)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($menuItems[0])) {
                    $headerArray = $this->MenuItems->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($menuItems[0]));
                    if (!empty($headerArrayDiff)) {
                        $this->Flash->set(
                            __d('yab_cms_ff', 'The uploaded CSV file is incorrectly structured. Please check the format or use a new CSV file.'),
                            ['element' => 'default', 'params' => ['class' => 'error']]
                        );
                        return $this->redirect(['action' => 'import']);
                    }
                } else {
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The uploaded CSV file is empty.'),
                        ['element' => 'default', 'params' => ['class' => 'error']]
                    );
                    return $this->redirect(['action' => 'import']);
                }

                // Log request
                if ($postData['log'] == 1) {
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $menuItems);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($menuItems as $menuItem) {
                    $dateTime = DateTime::now();
                    $existent = $this->MenuItems
                        ->find('all')
                        ->where([
                            'menu_id' => $menuItem['menu_id'],
                            'domain_id' => $menuItem['domain_id'],
                            'title' => $menuItem['title'],
                            'alias' => $menuItem['alias'],
                            'locale' => $menuItem['locale'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->MenuItems->newEmptyEntity(); // create
                        $menuItem = $this->MenuItems->patchEntity(
                            $entity,
                            Hash::merge(
                                $menuItem,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->MenuItems->save($menuItem)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->MenuItems->get($existent->id); // update
                        $menuItem = $this->MenuItems->patchEntity(
                            $existent,
                            Hash::merge(
                                $menuItem,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->MenuItems->save($menuItem)) {
                            $u++;
                        }
                    }
                }
                $this->Flash->set(
                    __d(
                        'yab_cms_ff',
                        'You imported {imported} and updated {updated} records.',
                        ['imported' => $i, 'updated' => $u]
                    ),
                    ['element' => 'default', 'params' => ['class' => 'success']]);
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'You can only send files with the csv extension csv. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'warning']]);
            }

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Export method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function export()
    {
        $menuItems = $this->MenuItems->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->MenuItems->tableColumns;
        $extract = [
            'id',
            'parent_id',
            'menu_id',
            'domain_id',
            'foreign_key',
            'title',
            'alias',
            'sub_title',
            'link',
            'link_target',
            'link_rel',
            'description',
            'lft',
            'rght',
            'locale',
            function ($row) {
                return ($row['status'] == true)? 1: 0;
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('menuItems'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'menuItems',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
