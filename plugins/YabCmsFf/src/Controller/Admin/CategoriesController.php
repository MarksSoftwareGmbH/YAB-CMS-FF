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
 * Categories Controller
 *
 * @property \YabCmsFf\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
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
            'parent_id',
            'domain_id',
            'foreign_key',
            'lft',
            'rght',
            'name',
            'slug',
            'locale',
            'status',
            'created',
            'modified',
            'Domains.name',
        ],
        'order' => ['lft' => 'ASC']
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
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $query = $this->Categories
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain(['ParentCategories', 'Domains']);

        $domains = $this->Categories->Domains
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

        YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeIndexRender', $this, [
            'Query' => $query,
            'Domains' => $domains,
            'Locales' => $locales,
        ]);

        $this->set('categories', $this->paginate($query));
        $this->set(compact('domains', 'locales'));
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
            $movement = $this->Categories
                ->find()
                ->where([
                        'Categories.lft >' => $this->getRequest()->getQuery('siblingLft'),
                        'Categories.lft <=' => $this->getRequest()->getQuery('draggedLft')
                    ]
                )
                ->count();
            $category = $this->Categories->get($this->getRequest()->getQuery('draggedId'));

            if ($this->Caregories->moveUp($category, $movement)) {
                $response = true;
            }
        }

        if ($this->getRequest()->getQuery('draggedLft') < $this->getRequest()->getQuery('siblingLft')) {
            $movement = $this->Categories
                ->find()
                ->where([
                        'Categories.lft <' => $this->getRequest()->getQuery('siblingLft'),
                        'Categories.lft >=' => $this->getRequest()->getQuery('draggedLft')
                    ]
                )
                ->count();
            $category = $this->Categories->get($this->getRequest()->getQuery('draggedId'));

            if ($this->Categories->moveDown($category, $movement)) {
                $response = true;
            }
        }

        $this->set('response', $response);
        $this->set('_serialize', ['response']);
        $this->viewBuilder()->setPlugin('YabCmsFf')->setLayout(null);
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
        $category = $this->Categories->get($id);
        if ($this->Categories->moveUp($category)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The category has been moved up.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The category could not be moved up. Please, try again.'),
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
        $category = $this->Categories->get($id);
        if ($this->Categories->moveDown($category)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The category has been moved down.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The category could not be moved down. Please, try again.'),
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
        $category = $this->Categories->get($id, contain: ['ParentCategories', 'ChildCategories', 'Domains']);

        YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeViewRender', $this, ['Category' => $category]);

        $this->set('category', $category);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeAdd', $this, ['Category' => $category]);
            if ($this->Categories->save($category)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Categories.onAddSuccess', $this, ['Category' => $category]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The category has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Categories.onAddFailure', $this, ['Category' => $category]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The category could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $parentCategories = $this->Categories
            ->find('treeList',
                keyPath: 'id',
                valuePath: function($entity) {
                    return $entity->name_locale . ' ' . '(' . $this->Categories->Domains->getNameById($entity->domain_id) . ')';
                },
                spacer: '-> '
            )
            ->where(['Categories.status' => 1]);

        YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeAddRender', $this, [
            'Category' => $category,
            'ParentCategories' => $parentCategories,
        ]);

        $this->set(compact('category', 'parentCategories'));
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
        $category = $this->Categories->get($id, contain: ['Articles', 'Domains']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeEdit', $this, ['Category' => $category]);
            if ($this->Categories->save($category)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Categories.onEditSuccess', $this, ['Category' => $category]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The category has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Categories.onEditFailure', $this, ['Category' => $category]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The category could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $parentCategories = $this->Categories
            ->find('treeList',
                keyPath: 'id',
                valuePath: function($entity) {
                    return $entity->name_locale . ' ' . '(' . $this->Categories->Domains->getNameById($entity->domain_id) . ')';
                },
                spacer: '-> '
            )
            ->where(['Categories.status' => 1]);

        YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeEditRender', $this, [
            'Category' => $category,
            'ParentCategories' => $parentCategories,
        ]);

        $this->set(compact('category', 'parentCategories'));
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
        $category = $this->Categories->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Categories.beforeDelete', $this, ['Category' => $category]);
        if ($this->Categories->delete($category)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Categories.onDeleteSuccess', $this, ['Category' => $category]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The category has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Categories.onDeleteFailure', $this, ['Category' => $category]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The category could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Import method
     *
     * @return \Cake\Http\Response|null
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
                $categories = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($categories) && !empty($categories)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($categories[0])) {
                    $headerArray = $this->Categories->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($categories[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $categories);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($categories as $category) {
                    $dateTime = DateTime::now();
                    $existent = $this->Categories
                        ->find('all')
                        ->where([
                            'domain_id' => $category['domain_id'],
                            'name' => $category['name'],
                            'slug' => $category['slug'],
                            'locale' => $category['locale'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->Categories->newEmptyEntity(); // create
                        $category = $this->Categories->patchEntity(
                            $entity,
                            Hash::merge(
                                $category,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->Categories->save($category)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->Categories->get($existent->id); // update
                        $category = $this->Categories->patchEntity(
                            $existent,
                            Hash::merge(
                                $category,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->Categories->save($category)) {
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
        $categories = $this->Categories->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Categories->tableColumns;
        $extract = [
            'id',
            'parent_id',
            'domain_id',
            'foreign_key',
            'lft',
            'rght',
            'name',
            'slug',
            'description',
            'background_image',
            'meta_description',
            'meta_keywords',
            'locale',
            function ($row) {
                return ($row['status'] == true)? 1: 0;
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('categories'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'categories',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
