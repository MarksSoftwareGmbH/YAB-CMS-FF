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

use Cake\Event\EventInterface;
use Cake\Http\CallbackStream;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use YabCmsFf\Controller\Admin\AppController;
use YabCmsFf\Utility\YabCmsFf;

/**
 * ArticleTypes Controller
 *
 * @property \YabCmsFf\Model\Table\ArticleTypesTable $ArticleTypes
 */
class ArticleTypesController extends AppController
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
        $query = $this->ArticleTypes
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'ArticleTypeAttributes' => function ($q) {
                    return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
                }
            ]);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('articleTypes', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(?int $id = null)
    {
        $articleType = $this->ArticleTypes->get($id, contain: [
            'ArticleTypeAttributes' => function ($q) {
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            }
        ]);

        $Users = TableRegistry::getTableLocator()->get('YabCmsFf.Users');
        $users = $Users
            ->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name_username')
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeViewRender', $this, [
            'ArticleType'   => $articleType,
            'Users'         => $users,
        ]);

        $this->set(compact('articleType', 'users'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $articleType = $this->ArticleTypes->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $articleType = $this->ArticleTypes->patchEntity($articleType, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeAdd', $this, [
                'ArticleType' => $articleType,
            ]);
            if ($this->ArticleTypes->save($articleType)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onAddSuccess', $this, [
                    'ArticleType' => $articleType,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onAddFailure', $this, [
                    'ArticleType' => $articleType,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleTypes->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            );

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeAddRender', $this, [
            'ArticleType' => $articleType,
            'ArticleTypeAttributes' => $articleTypeAttributes,
        ]);

        $this->set(compact('articleType', 'articleTypeAttributes'));
    }

    /**
     * Edit method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function edit(?int $id = null)
    {
        $articleType = $this->ArticleTypes->get($id, contain: ['ArticleTypeAttributes']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $articleType = $this->ArticleTypes->patchEntity(
                $articleType,
                $this->getRequest()->getdata(),
                ['associated' => ['ArticleTypeAttributes']]
            );
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeEdit', $this, [
                'ArticleType' => $articleType,
            ]);
            if ($this->ArticleTypes->save($articleType)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onEditSuccess', $this, [
                    'ArticleType' => $articleType,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onEditFailure', $this, [
                    'ArticleType' => $articleType,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleTypes->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            );

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeEditRender', $this, [
            'ArticleType' => $articleType,
            'ArticleTypeAttributes' => $articleTypeAttributes,
        ]);

        $this->set(compact('articleType', 'articleTypeAttributes'));
    }

    /**
     * Delete method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function delete(?int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $articleType = $this->ArticleTypes->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.beforeDelete', $this, [
            'ArticleType' => $articleType,
        ]);
        if ($this->ArticleTypes->delete($articleType)) {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onDeleteSuccess', $this, [
                'ArticleType' => $articleType,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypes.onDeleteFailure', $this, [
                'ArticleType' => $articleType,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]);
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
                $articleTypes = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($articleTypes) && !empty($articleTypes)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($articleTypes[0])) {
                    $headerArray = $this->ArticleTypes->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($articleTypes[0]));
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
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $articleTypes);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($articleTypes as $articleType) {
                    $dateTime = DateTime::now();
                    $existent = $this->ArticleTypes
                        ->find('all')
                        ->where([
                            'title' => $articleType['title'],
                            'alias' => $articleType['alias'],
                        ])
                        ->first();
                    if (empty($existent)) {
                        $entity = $this->ArticleTypes->newEmptyEntity(); // create
                        $articleType = $this->ArticleTypes->patchEntity(
                            $entity,
                            Hash::merge(
                                $articleType,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->ArticleTypes->save($articleType)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->ArticleTypes->get($existent->id); // update
                        $articleType = $this->ArticleTypes->patchEntity(
                            $existent,
                            Hash::merge(
                                $articleType,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->ArticleTypes->save($articleType)) {
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
     * Export xlsx method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportXlsx()
    {
        $articleTypes = $this->ArticleTypes->find('all');
        $header = $this->ArticleTypes->tableColumns;

        $articleTypesArray = [];
        foreach($articleTypes as $articleType) {
            $articleTypeArray = [];
            $articleTypeArray['id'] = $articleType->id;
            $articleTypeArray['foreign_key'] = $articleType->foreign_key;
            $articleTypeArray['title'] = $articleType->title;
            $articleTypeArray['alias'] = $articleType->alias;
            $articleTypeArray['description'] = $articleType->description;
            $articleTypeArray['created'] = empty($articleType->created)? NULL: $articleType->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleTypeArray['modified'] = empty($articleType->modified)? NULL: $articleType->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleTypesArray[] = $articleTypeArray;
        }
        $articleTypes = $articleTypesArray;

        $objSpreadsheet = new Spreadsheet();
        $objSpreadsheet->setActiveSheetIndex(0);

        $rowCount = 1;
        $colCount = 1;
        foreach ($header as $headerAlias) {
            $col = 'A';
            switch ($colCount) {
                case 2: $col = 'B'; break;
                case 3: $col = 'C'; break;
                case 4: $col = 'D'; break;
                case 5: $col = 'E'; break;
                case 6: $col = 'F'; break;
                case 7: $col = 'G'; break;
                case 8: $col = 'H'; break;
                case 9: $col = 'I'; break;
                case 10: $col = 'J'; break;
                case 11: $col = 'K'; break;
                case 12: $col = 'L'; break;
                case 13: $col = 'M'; break;
                case 14: $col = 'N'; break;
                case 15: $col = 'O'; break;
                case 16: $col = 'P'; break;
                case 17: $col = 'Q'; break;
                case 18: $col = 'R'; break;
                case 19: $col = 'S'; break;
                case 20: $col = 'T'; break;
                case 21: $col = 'U'; break;
                case 22: $col = 'V'; break;
                case 23: $col = 'W'; break;
                case 24: $col = 'X'; break;
                case 25: $col = 'Y'; break;
                case 26: $col = 'Z'; break;
            }

            $objSpreadsheet->getActiveSheet()->setCellValue($col . $rowCount, $headerAlias);
            $colCount++;
        }

        $rowCount = 1;
        foreach ($articleTypes as $dataEntity) {
            $rowCount++;

            $colCount = 1;
            foreach ($dataEntity as $dataProperty) {
                $col = 'A';
                switch ($colCount) {
                    case 2: $col = 'B'; break;
                    case 3: $col = 'C'; break;
                    case 4: $col = 'D'; break;
                    case 5: $col = 'E'; break;
                    case 6: $col = 'F'; break;
                    case 7: $col = 'G'; break;
                    case 8: $col = 'H'; break;
                    case 9: $col = 'I'; break;
                    case 10: $col = 'J'; break;
                    case 11: $col = 'K'; break;
                    case 12: $col = 'L'; break;
                    case 13: $col = 'M'; break;
                    case 14: $col = 'N'; break;
                    case 15: $col = 'O'; break;
                    case 16: $col = 'P'; break;
                    case 17: $col = 'Q'; break;
                    case 18: $col = 'R'; break;
                    case 19: $col = 'S'; break;
                    case 20: $col = 'T'; break;
                    case 21: $col = 'U'; break;
                    case 22: $col = 'V'; break;
                    case 23: $col = 'W'; break;
                    case 24: $col = 'X'; break;
                    case 25: $col = 'Y'; break;
                    case 26: $col = 'Z'; break;
                }

                $objSpreadsheet->getActiveSheet()->setCellValue($col . $rowCount, $dataProperty);
                $colCount++;
            }
        }

        foreach (range('A', $objSpreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objSpreadsheet
                ->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }
        $objSpreadsheetWriter = IOFactory::createWriter($objSpreadsheet, 'Xlsx');
        $stream = new CallbackStream(function () use ($objSpreadsheetWriter) {
            $objSpreadsheetWriter->save('php://output');
        });

        return $this->response
            ->withType('xlsx')
            ->withHeader('Content-Disposition', 'attachment;filename="' . strtolower($this->defaultTable) . '.' . 'xlsx"')
            ->withBody($stream);
    }

    /**
     * Export csv method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportCsv()
    {
        $articleTypes = $this->ArticleTypes->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->ArticleTypes->tableColumns;
        $extract = [
            'id',
            'foreign_key',
            'title',
            'alias',
            'description',
            function ($row) {
                return empty($row['created'])? NULL: $row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            function ($row) {
                return empty($row['modified'])? NULL: $row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('articleTypes'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'articleTypes',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }

    /**
     * Export xml method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportXml()
    {
        $articleTypes = $this->ArticleTypes->find('all');

        $articleTypesArray = [];
        foreach($articleTypes as $articleType) {
            $articleTypeArray = [];
            $articleTypeArray['id'] = $articleType->id;
            $articleTypeArray['foreign_key'] = $articleType->foreign_key;
            $articleTypeArray['title'] = $articleType->title;
            $articleTypeArray['alias'] = $articleType->alias;
            $articleTypeArray['description'] = $articleType->description;
            $articleTypeArray['created'] = empty($articleType->created)? NULL: $articleType->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleTypeArray['modified'] = empty($articleType->modified)? NULL: $articleType->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleTypesArray[] = $articleTypeArray;
        }
        $articleTypes = ['ArticleTypes' => ['ArticleType' => $articleTypesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'xml'));
        $this->set(compact('articleTypes'));
        $this
            ->viewBuilder()
            ->setClassName('Xml')
            ->setOptions(['serialize' => 'articleTypes']);
    }

    /**
     * Export json method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportJson()
    {
        $articleTypes = $this->ArticleTypes->find('all');

        $articleTypesArray = [];
        foreach($articleTypes as $articleType) {
            $articleTypeArray = [];
            $articleTypeArray['id'] = $articleType->id;
            $articleTypeArray['foreign_key'] = $articleType->foreign_key;
            $articleTypeArray['title'] = $articleType->title;
            $articleTypeArray['alias'] = $articleType->alias;
            $articleTypeArray['description'] = $articleType->description;
            $articleTypeArray['created'] = empty($articleType->created)? NULL: $articleType->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleTypeArray['modified'] = empty($articleType->modified)? NULL: $articleType->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleTypesArray[] = $articleTypeArray;
        }
        $articleTypes = ['ArticleTypes' => ['ArticleType' => $articleTypesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'json'));
        $this->set(compact('articleTypes'));
        $this
            ->viewBuilder()
            ->setClassName('Json')
            ->setOptions(['serialize' => 'articleTypes']);
    }
}
