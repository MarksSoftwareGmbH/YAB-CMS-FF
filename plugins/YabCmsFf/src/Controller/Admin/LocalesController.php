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
use Cake\Utility\Hash;

/**
 * Locales Controller
 *
 * @property \YabCmsFf\Model\Table\LocalesTable $Locales
 */
class LocalesController extends AppController
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
            'name',
            'native',
            'code',
            'weight',
            'status',
        ],
        'order' => ['weight' => 'ASC']
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
        $query = $this->Locales
            ->find('search', search: $this->getRequest()->getQueryParams());

        YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('locales', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $locale = $this->Locales->get($id, contain: [
            'Domains' => function ($q) {
                return $q->orderBy(['Domains.name' => 'ASC']);
            }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeViewRender', $this, ['Locale' => $locale]);

        $this->set('locale', $locale);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $locale = $this->Locales->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $associated = ['Domains'];
            $locale = $this->Locales->patchEntity($locale, $this->getRequest()->getData(), ['associated' => $associated]);
            YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeAdd', $this, ['Locale' => $locale]);
            if ($this->Locales->save($locale)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Locales.onAddSuccess', $this, ['Locale' => $locale]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The locale has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Locales.onAddFailure', $this, ['Locale' => $locale]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The locale could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeAddRender', $this, ['Locale' => $locale]);

        $this->set('locale', $locale);
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
        $locale = $this->Locales->get($id, contain: [
            'Domains' => function ($q) {
                return $q->orderBy(['Domains.name' => 'ASC']);
            }
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $associated = ['Users', 'Domains'];
            $locale = $this->Locales->patchEntity($locale, $this->getRequest()->getData(), ['associated' => $associated]);
            YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeEdit', $this, ['Locale' => $locale]);
            if ($this->Locales->save($locale)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Locales.onEditSuccess', $this, ['Locale' => $locale]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The locale has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Locales.onEditFailure', $this, ['Locale' => $locale]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The locale could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeEditRender', $this, ['Locale' => $locale]);

        $this->set('locale', $locale);
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
        $locale = $this->Locales->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Locales.beforeDelete', $this, ['Locale' => $locale]);
        if ($this->Locales->delete($locale)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Locales.onDeleteSuccess', $this, ['Locale' => $locale]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The locale has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Locales.onDeleteFailure', $this, ['Locale' => $locale]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The locale could not be deleted. Please, try again.'),
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
                $locales = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($locales) && !empty($locales)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($locales[0])) {
                    $headerArray = $this->Locales->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($locales[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $locales);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($locales as $locale) {
                    $dateTime = DateTime::now();
                    $existent = $this->Locales
                        ->find('all')
                        ->where([
                            'name' => $locale['name'],
                            'native' => $locale['native'],
                            'code' => $locale['code'],
                        ])
                        ->first();

                    if (empty($existent)) {
                        $entity = $this->Locales->newEmptyEntity(); // create
                        $locale = $this->Locales->patchEntity(
                            $entity,
                            Hash::merge(
                                $locale,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->Locales->save($locale)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->Locales->get($existent->id); // update
                        $locale = $this->Locales->patchEntity(
                            $existent,
                            Hash::merge(
                                $locale,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->Locales->save($locale)) {
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
        $locales = $this->Locales->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Locales->tableColumns;
        $extract = [
            'id',
            'foreign_key',
            'name',
            'native',
            'code',
            'weight',
            function ($row) {
                return ($row['status'] == true)? 1: 0;
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('locales'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'locales',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
