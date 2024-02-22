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
use Cake\Utility\Hash;
use YabCmsFf\Utility\YabCmsFf;

/**
 * RegistrationTypes Controller
 *
 * @property \YabCmsFf\Model\Table\RegistrationTypesTable $RegistrationTypes
 */
class RegistrationTypesController extends AppController
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
            'description',
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
        $query = $this->RegistrationTypes
            ->find('search', search: $this->getRequest()->getQueryParams());

        YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('registrationTypes', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $registrationType = $this->RegistrationTypes->get($id, contain: ['Registrations']);

        YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeViewRender', $this, ['RegistrationType' => $registrationType]);

        $this->set('registrationType', $registrationType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $registrationType = $this->RegistrationTypes->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $registrationType = $this->RegistrationTypes->patchEntity($registrationType, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeAdd', $this, ['RegistrationType' => $registrationType]);
            if ($this->RegistrationTypes->save($registrationType)) {
                YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onAddSuccess', $this, ['RegistrationType' => $registrationType]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration type has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onAddFailure', $this, ['RegistrationType' => $registrationType]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration type could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeAddRender', $this, [
            'RegistrationType' => $registrationType,
        ]);

        $this->set('registrationType', $registrationType);
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
        $registrationType = $this->RegistrationTypes->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $registrationType = $this->RegistrationTypes->patchEntity($registrationType, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeEdit', $this, ['RegistrationType' => $registrationType]);
            if ($this->RegistrationTypes->save($registrationType)) {
                YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onEditSuccess', $this, ['RegistrationType' => $registrationType]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration type has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onEditFailure', $this, ['RegistrationType' => $registrationType]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration type could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeEditRender', $this, [
            'RegistrationType' => $registrationType,
        ]);

        $this->set('registrationType', $registrationType);
    }

    /**
     * Copy method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function copy(int $id = null)
    {
        $this->getRequest()->allowMethod(['post']);

        $registrationType = $this->RegistrationTypes->get($id);
        $registrationType->setNew(true);
        $registrationType->unset('id');
        $registrationType->title = $registrationType->title . ' ' . __d('yab_cms_ff', '(Copy)');

        if ($this->RegistrationTypes->save($registrationType)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration type has been copied.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration type could not be copied. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
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
        $registrationType = $this->RegistrationTypes->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.beforeDelete', $this, [
            'RegistrationType' => $registrationType,
        ]);
        if ($this->RegistrationTypes->delete($registrationType)) {
            YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onDeleteSuccess', $this, [
                'RegistrationType' => $registrationType,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration type has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.RegistrationTypes.onDeleteFailure', $this, [
                'RegistrationType' => $registrationType,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration type could not be deleted. Please, try again.'),
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
                $registrationTypes = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($registrationTypes) && !empty($registrationTypes)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($registrationTypes[0])) {
                    $headerArray = $this->RegistrationTypes->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($registrationTypes[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $registrationTypes);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($registrationTypes as $registrationType) {
                    $dateTime = DateTime::now();
                    $existent = $this->RegistrationTypes
                        ->find('all')
                        ->where([
                            'title' => $registrationType['title'],
                            'alias' => $registrationType['alias'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->RegistrationTypes->newEntity(); // create
                        $registrationType = $this->RegistrationTypes->patchEntity(
                            $entity,
                            Hash::merge(
                                $registrationType,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->RegistrationTypes->save($registrationType)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->RegistrationTypes->get($existent->id); // update
                        $registrationType = $this->RegistrationTypes->patchEntity(
                            $existent,
                            Hash::merge(
                                $registrationType,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->RegistrationTypes->save($registrationType)) {
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
        $registrationTypes = $this->RegistrationTypes->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->RegistrationTypes->tableColumns;
        $extract = $this->RegistrationTypes->tableColumns;

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('registrationTypes'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'registrationTypes',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
