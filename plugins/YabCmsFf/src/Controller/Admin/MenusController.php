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
 * Menus Controller
 *
 * @property \YabCmsFf\Model\Table\MenusTable $Menus
 */
class MenusController extends AppController
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
            'domain_id',
            'foreign_key',
            'title',
            'alias',
            'description',
            'locale',
            'status',
            'created',
            'modified',
            'Domains.name',
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
        $query = $this->Menus
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'Domains' => function ($q) {
                    return $q->orderBy(['Domains.name' => 'ASC']);
                }
            ]);

        $domains = $this->Menus->Domains
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

        YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeIndexRender', $this, [
            'Query' => $query,
            'Domains' => $domains,
            'Locales' => $locales,
        ]);

        $this->set('menus', $this->paginate($query));
        $this->set(compact('domains', 'locales'));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $menu = $this->Menus->get($id, contain: [
            'Domains' => function ($q) {
                return $q->orderBy(['Domains.name' => 'ASC']);
            }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeViewRender', $this, ['Menu' => $menu]);

        $this->set('menu', $menu);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $menu = $this->Menus->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $menu = $this->Menus->patchEntity($menu, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeAdd', $this, ['Menu' => $menu]);
            if ($this->Menus->save($menu)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Menus.onAddSuccess', $this, ['Menu' => $menu]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Menus.onAddFailure', $this, ['Menu' => $menu]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeAddRender', $this, ['Menu' => $menu]);

        $this->set('menu', $menu);
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
        $menu = $this->Menus->get($id, contain: ['Domains']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $menu = $this->Menus->patchEntity($menu, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeEdit', $this, ['Menu' => $menu]);
            if ($this->Menus->save($menu)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Menus.onEditSuccess', $this, ['Menu' => $menu]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Menus.onEditFailure', $this, ['Menu' => $menu]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The menu could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeEditRender', $this, ['Menu' => $menu]);

        $this->set('menu', $menu);
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
        $menu = $this->Menus->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Menus.beforeDelete', $this, ['Menu' => $menu]);
        if ($this->Menus->delete($menu)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Menus.onDeleteSuccess', $this, ['Menu' => $menu]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Menus.onDeleteFailure', $this, ['Menu' => $menu]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The menu could not be deleted. Please, try again.'),
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
                $menus = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($menus) && !empty($menus)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($menus[0])) {
                    $headerArray = $this->Menus->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($menus[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $menus);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($menus as $menu) {
                    $dateTime = DateTime::now();
                    $existent = $this->Menus
                        ->find('all')
                        ->where([
                            'domain_id' => $menu['domain_id'],
                            'title' => $menu['title'],
                            'alias' => $menu['alias'],
                            'locale' => $menu['locale'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->Menus->newEmptyEntity(); // create
                        $menu = $this->Menus->patchEntity(
                            $entity,
                            Hash::merge(
                                $menu,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->Menus->save($menu)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->Menus->get($existent->id); // update
                        $menu = $this->Menus->patchEntity(
                            $existent,
                            Hash::merge(
                                $menu,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->Menus->save($menu)) {
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
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'You can only send files with the csv extension csv. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'warning']]
                );
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
        $menus = $this->Menus->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Menus->tableColumns;
        $extract = [
            'id',
            'domain_id',
            'foreign_key',
            'title',
            'alias',
            'description',
            'locale',
            function ($row) {
                return ($row['status'] == true)? 1: 0;
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('menus'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'menus',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
