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
use YabCmsFf\Utility\YabCmsFf;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;

/**
 * Registrations Controller
 *
 * @property \YabCmsFf\Model\Table\RegistrationsTable $Registrations
 */
class RegistrationsController extends AppController
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
            'registration_type_id',
            'billing_name',
            'billing_name_addition',
            'billing_legal_form',
            'billing_vat_number',
            'billing_salutation',
            'billing_first_name',
            'billing_middle_name',
            'billing_last_name',
            'billing_management',
            'billing_email',
            'billing_website',
            'billing_telephone',
            'billing_mobilephone',
            'billing_fax',
            'billing_street',
            'billing_street_addition',
            'billing_postcode',
            'billing_city',
            'billing_country',
            'shipping_name',
            'shipping_name_addition',
            'shipping_management',
            'shipping_email',
            'shipping_telephone',
            'shipping_mobilephone',
            'shipping_fax',
            'shipping_street',
            'shipping_street_addition',
            'shipping_postcode',
            'shipping_city',
            'shipping_country',
            'newsletter_email',
            'remark',
            'register_excerpt',
            'newsletter',
            'marketing',
            'terms_conditions',
            'privacy_policy',
            'ip',
            'created',
            'modified',
            'RegistrationTypes.title',
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
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $query = $this->Registrations
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain(['RegistrationTypes']);

        $registrationTypes = $this->Registrations->RegistrationTypes
            ->find('list', order: ['RegistrationTypes.title' => 'ASC'], keyField: 'alias', valueField: 'title')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeIndexRender', $this, [
            'Query' => $query,
            'ResignationTypes' => $registrationTypes,
        ]);

        $this->set('registrations', $this->paginate($query));
        $this->set('registrationTypes', $registrationTypes);
    }

    /**
     * View method
     *
     * @param int|null $id Registration id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $id = null)
    {
        $registration = $this->Registrations
            ->find()
            ->where(['Registrations.id' => $id])
            ->contain(['RegistrationTypes'])
            ->first();

        YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeViewRender', $this, [
            'Registration' => $registration,
        ]);

        $this->set('registration', $registration);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $registration = $this->Registrations->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $registration = $this->Registrations->patchEntity($registration, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeAdd', $this, ['Registration' => $registration]);
            if ($this->Registrations->save($registration)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onAddSuccess', $this, ['Registration' => $registration]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onAddFailure', $this, ['Registration' => $registration]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $registrationTypes = $this->Registrations->RegistrationTypes
            ->find('list', order: ['RegistrationTypes.title' => 'ASC'], keyField: 'id', valueField: 'title')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeAddRender', $this, [
            'Registration' => $registration,
            'RegistrationTypes' => $registrationTypes,
        ]);

        $this->set(compact(
            'registration',
            'registrationTypes'
        ));
    }

    /**
     * Edit method
     *
     * @param int|null $id Registration id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit(int $id = null)
    {
        $registration = $this->Registrations->get($id, contain: ['RegistrationTypes']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $registration = $this->Registrations->patchEntity($registration, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeEdit', $this, ['Registration' => $registration]);
            if ($this->Registrations->save($registration)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onEditSuccess', $this, ['Registration' => $registration]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onEditFailure', $this, ['Registration' => $registration]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The registration could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $registrationTypes = $this->Registrations->RegistrationTypes
            ->find('list', order: ['RegistrationTypes.title' => 'ASC'], keyField: 'id', valueField: 'title')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeEditRender', $this, [
            'Registration' => $registration,
            'RegistrationTypes' => $registrationTypes,
        ]);

        $this->set(compact(
            'registration',
            'registrationTypes'
        ));
    }

    /**
     * Delete method
     *
     * @param int|null $id Registration id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $registration = $this->Registrations->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Registrations.beforeDelete', $this, [
            'Registration' => $registration,
        ]);
        if ($this->Registrations->delete($registration)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onDeleteSuccess', $this, [
                'Registration' => $registration,
            ]);
            $connection = ConnectionManager::get('default');
            if ($connection) {
                $connection->delete($this->Registrations->getTable(), ['id' => $id]);
            }
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Registrations.onDeleteFailure', $this, [
                'Registration' => $registration,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The registration could not be deleted. Please, try again.'),
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
                $registrations = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($registrations) && !empty($registrations)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($registrations[0])) {
                    $headerArray = $this->Registrations->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($registrations[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $registrations);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($registrations as $registration) {
                    $dateTime = DateTime::now();
                    $existent = $this->Registrations
                        ->find('all')
                        ->where([
                            'billing_name' => $registration['billing_name'],
                            'billing_email' => $registration['billing_email'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->Registrations->newEmptyEntity(); // create
                        $registration = $this->Registrations->patchEntity(
                            $entity,
                            Hash::merge(
                                $registration,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->Registrations->save($registration)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->Registrations->get($existent->id); // update
                        $registration = $this->Registrations->patchEntity(
                            $existent,
                            Hash::merge(
                                $registration,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->Registrations->save($registration)) {
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
        $registrations = $this->Registrations->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Registrations->tableColumns;
        $extract = [
            'id',
            'registration_type_id',
            'billing_name',
            'billing_name_addition',
            'billing_legal_form',
            'billing_vat_number',
            'billing_salutation',
            'billing_first_name',
            'billing_middle_name',
            'billing_last_name',
            'billing_management',
            'billing_email',
            'billing_website',
            'billing_telephone',
            'billing_mobilephone',
            'billing_fax',
            'billing_street',
            'billing_street_addition',
            'billing_postcode',
            'billing_city',
            'billing_country',
            'shipping_name',
            'shipping_name_addition',
            'shipping_management',
            'shipping_email',
            'shipping_telephone',
            'shipping_mobilephone',
            'shipping_fax',
            'shipping_street',
            'shipping_street_addition',
            'shipping_postcode',
            'shipping_city',
            'shipping_country',
            'newsletter_email',
            'remark',
            'register_excerpt',
            function ($row) {
                return ($row['newsletter'] == true)? 1: 0;
            },
            function ($row) {
                return ($row['marketing'] == true)? 1: 0;
            },
            function ($row) {
                return ($row['terms_conditions'] == true)? 1: 0;
            },
            function ($row) {
                return ($row['privacy_policy'] == true)? 1: 0;
            },
            'ip',
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('registrations'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'registrations',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
