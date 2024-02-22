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
 * Settings Controller
 *
 * @property \YabCmsFf\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
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
            'parameter',
            'name',
            'value',
            'title',
            'description',
            'Domains.name',
        ],
        'order' => ['parameter' => 'ASC']
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
        $query = $this->Settings
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'Domains' => function ($q) {
                    return $q->orderBy(['Domains.name' => 'ASC']);
                }
            ]);

        $domains = $this->Settings->Domains
            ->find('list', order: ['Domains.name' => 'ASC'], keyField: 'name', valueField: 'name')
            ->toArray();

        $parameters = $this->Settings
            ->find('list', order: ['Settings.parameter' => 'ASC'], keyField: 'parameter', valueField: 'parameter')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeIndexRender', $this, [
            'Query' => $query,
            'Domains' => $domains,
            'Parameters' => $parameters,
        ]);

        $this->set('settings', $this->paginate($query));
        $this->set(compact('domains', 'parameters'));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $setting = $this->Settings->get($id, contain: [
            'Domains' => function ($q) { return $q->orderBy(['Domains.name' => 'ASC']); }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeViewRender', $this, ['Setting' => $setting]);

        $this->set('setting', $setting);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $setting = $this->Settings->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeAdd', $this, ['Setting' => $setting]);
            if ($this->Settings->save($setting)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Settings.onAddSuccess', $this, ['Setting' => $setting]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The setting has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Settings.onAddFailure', $this, ['Setting' => $setting]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The setting could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeAddRender', $this, ['Setting' => $setting]);

        $this->set('setting', $setting);
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
        $setting = $this->Settings->get($id, contain: ['Domains']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeEdit', $this, ['Setting' => $setting]);
            if ($this->Settings->save($setting)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Settings.onEditSuccess', $this, ['Setting' => $setting]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The setting has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Settings.onEditFailure', $this, ['Setting' => $setting]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The setting could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            return $this->redirect($this->referer());
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeEditRender', $this, ['Setting' => $setting]);

        $this->set('setting', $setting);
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
        $setting = $this->Settings->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Settings.beforeDelete', $this, ['Setting' => $setting]);
        if ($this->Settings->delete($setting)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Settings.onDeleteSuccess', $this, ['Setting' => $setting]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The setting has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {#
            YabCmsFf::dispatchEvent('Controller.Admin.Settings.onDeleteFailure', $this, ['Setting' => $setting]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The setting could not be deleted. Please, try again.'),
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
                $settings = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($settings) && !empty($settings)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($settings[0])) {
                    $headerArray = $this->Settings->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($settings[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $settings);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($settings as $setting) {
                    $dateTime = DateTime::now();
                    $existent = $this->Settings
                        ->find('all')
                        ->where([
                            'parameter' => $setting['parameter'],
                            'name' => $setting['name'],
                            'title' => $setting['title'],
                        ])
                        ->first();

                    if (empty($existent)) {
                        $entity = $this->Settings->newEmptyEntity(); // create
                        $setting = $this->Settings->patchEntity(
                            $entity,
                            Hash::merge(
                                $setting,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->Settings->save($setting)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->Settings->get($existent->id); // update
                        $setting = $this->Settings->patchEntity(
                            $existent,
                            Hash::merge(
                                $setting,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->Settings->save($setting)) {
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
        $settings = $this->Settings->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Settings->tableColumns;
        $extract = $this->Settings->tableColumns;

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('settings'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'settings',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
