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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use YabCmsFf\Controller\Admin\AppController;
use YabCmsFf\Utility\YabCmsFf;

/**
 * ArticleArticleTypeAttributeValues Controller
 *
 * @property \YabCmsFf\Model\Table\ArticleArticleTypeAttributeValuesTable $ArticleArticleTypeAttributeValues
 */
class ArticleArticleTypeAttributeValuesController extends AppController
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
            'article_id',
            'article_type_attribute_id',
            'value',
            'created',
            'modified',
            'Articles.global_title',
            'ArticleTypeAttributes.alias',
        ],
        'order' => ['article_id' => 'ASC']
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
        $query = $this->ArticleArticleTypeAttributeValues
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'ArticleTypeAttributes' => function ($q) {
                    return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
                },
                'Articles.ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ]);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('articleArticleTypeAttributeValues', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->get($id, contain: [
            'ArticleTypeAttributes' => function ($q) {
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            },
            'Articles.ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeViewRender', $this, [
            'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
        ]);

        $this->set('articleArticleTypeAttributeValue', $articleArticleTypeAttributeValue);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->patchEntity(
                $articleArticleTypeAttributeValue,
                $this->getRequest()->getData()
            );
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeAdd', $this, [
                'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            ]);
            if ($this->ArticleArticleTypeAttributeValues->save($articleArticleTypeAttributeValue)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onAddSuccess', $this, [
                    'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute value has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onAddFailure', $this, [
                    'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute value could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleArticleTypeAttributeValues->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            )
            ->limit(100);

        $articles = $this->ArticleArticleTypeAttributeValues->Articles
            ->find('list', keyField: 'id', valueField: 'global_title')
            ->contain(['ArticleArticleTypeAttributeValues.ArticleTypeAttributes'])
            ->limit(100);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeAddRender', $this, [
            'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            'ArticleTypeAttributes' => $articleTypeAttributes,
            'Articles' => $articles,
        ]);

        $this->set(compact('articleArticleTypeAttributeValue', 'articleTypeAttributes', 'articles'));
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
        $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->patchEntity(
                $articleArticleTypeAttributeValue,
                $this->getRequest()->getData()
            );
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeEdit', $this, [
                'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            ]);
            if ($this->ArticleArticleTypeAttributeValues->save($articleArticleTypeAttributeValue)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onEditSuccess', $this, [
                    'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute value has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onEditFailure', $this, [
                    'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute value could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleArticleTypeAttributeValues->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            )
            ->limit(100);

        $articles = $this->ArticleArticleTypeAttributeValues->Articles
            ->find('list', keyField: 'id', valueField: 'global_title')
            ->contain(['ArticleArticleTypeAttributeValues.ArticleTypeAttributes'])
            ->limit(100);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeEditRender', $this, [
            'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            'ArticleTypeAttributes' => $articleTypeAttributes,
            'Articles' => $articles,
        ]);

        $this->set(compact('articleArticleTypeAttributeValue', 'articleTypeAttributes', 'articles'));
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
        $articleArticleTypeAttributeValue = $this->ArticleArticleTypeAttributeValues->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.beforeDelete', $this, [
            'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
        ]);
        if ($this->ArticleArticleTypeAttributeValues->delete($articleArticleTypeAttributeValue)) {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onDeleteSuccess', $this, [
                'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type attribute value has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleArticleTypeAttributeValues.onDeleteFailure', $this, [
                'ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValue,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type attribute value could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Export xlsx method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportXlsx()
    {
        $articleArticleTypeAttributeValues = $this->ArticleArticleTypeAttributeValues->find('all');
        $header = $this->ArticleArticleTypeAttributeValues->tableColumns;

        $articleArticleTypeAttributeValuesArray = [];
        foreach($articleArticleTypeAttributeValues as $articleArticleTypeAttributeValue) {
            $articleArticleTypeAttributeValueArray = [];
            $articleArticleTypeAttributeValueArray['id'] = $articleArticleTypeAttributeValue->id;
            $articleArticleTypeAttributeValueArray['article_id'] = $articleArticleTypeAttributeValue->article_id;
            $articleArticleTypeAttributeValueArray['article_type_attribute_id'] = $articleArticleTypeAttributeValue->article_type_attribute_id;
            $articleArticleTypeAttributeValueArray['value'] = $articleArticleTypeAttributeValue->value;
            $articleArticleTypeAttributeValueArray['created'] = empty($articleArticleTypeAttributeValue->created)? NULL: $articleArticleTypeAttributeValue->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArticleTypeAttributeValueArray['modified'] = empty($articleArticleTypeAttributeValue->modified)? NULL: $articleArticleTypeAttributeValue->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleArticleTypeAttributeValuesArray[] = $articleArticleTypeAttributeValueArray;
        }
        $articleArticleTypeAttributeValues = $articleArticleTypeAttributeValuesArray;

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
        foreach ($articleArticleTypeAttributeValues as $dataEntity) {
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
        $articleArticleTypeAttributeValues = $this->ArticleArticleTypeAttributeValues->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->ArticleArticleTypeAttributeValues->tableColumns;
        $extract = [
            'id',
            'article_id',
            'article_type_attribute_id',
            'value',
            function ($row) {
                return empty($row['created'])? NULL: $row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            function ($row) {
                return empty($row['modified'])? NULL: $row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('articleArticleTypeAttributeValues'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'articleArticleTypeAttributeValues',
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
        $articleArticleTypeAttributeValues = $this->ArticleArticleTypeAttributeValues->find('all');

        $articleArticleTypeAttributeValuesArray = [];
        foreach($articleArticleTypeAttributeValues as $articleArticleTypeAttributeValue) {
            $articleArticleTypeAttributeValueArray = [];
            $articleArticleTypeAttributeValueArray['id'] = $articleArticleTypeAttributeValue->id;
            $articleArticleTypeAttributeValueArray['article_id'] = $articleArticleTypeAttributeValue->article_id;
            $articleArticleTypeAttributeValueArray['article_type_attribute_id'] = $articleArticleTypeAttributeValue->article_type_attribute_id;
            $articleArticleTypeAttributeValueArray['value'] = $articleArticleTypeAttributeValue->value;
            $articleArticleTypeAttributeValueArray['created'] = empty($articleArticleTypeAttributeValue->created)? NULL: $articleArticleTypeAttributeValue->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArticleTypeAttributeValueArray['modified'] = empty($articleArticleTypeAttributeValue->modified)? NULL: $articleArticleTypeAttributeValue->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleArticleTypeAttributeValuesArray[] = $articleArticleTypeAttributeValueArray;
        }
        $articleArticleTypeAttributeValues = ['ArticleArticleTypeAttributeValues' => ['ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValuesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'xml'));
        $this->set(compact('articleArticleTypeAttributeValues'));
        $this
            ->viewBuilder()
            ->setClassName('Xml')
            ->setOptions(['serialize' => 'articleArticleTypeAttributeValues']);
    }

    /**
     * Export json method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportJson()
    {
        $articleArticleTypeAttributeValues = $this->ArticleArticleTypeAttributeValues->find('all');

        $articleArticleTypeAttributeValuesArray = [];
        foreach($articleArticleTypeAttributeValues as $articleArticleTypeAttributeValue) {
            $articleArticleTypeAttributeValueArray = [];
            $articleArticleTypeAttributeValueArray['id'] = $articleArticleTypeAttributeValue->id;
            $articleArticleTypeAttributeValueArray['article_id'] = $articleArticleTypeAttributeValue->article_id;
            $articleArticleTypeAttributeValueArray['article_type_attribute_id'] = $articleArticleTypeAttributeValue->article_type_attribute_id;
            $articleArticleTypeAttributeValueArray['value'] = $articleArticleTypeAttributeValue->value;
            $articleArticleTypeAttributeValueArray['created'] = empty($articleArticleTypeAttributeValue->created)? NULL: $articleArticleTypeAttributeValue->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArticleTypeAttributeValueArray['modified'] = empty($articleArticleTypeAttributeValue->modified)? NULL: $articleArticleTypeAttributeValue->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articleArticleTypeAttributeValuesArray[] = $articleArticleTypeAttributeValueArray;
        }
        $articleArticleTypeAttributeValues = ['ArticleArticleTypeAttributeValues' => ['ArticleArticleTypeAttributeValue' => $articleArticleTypeAttributeValuesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'json'));
        $this->set(compact('articleArticleTypeAttributeValues'));
        $this
            ->viewBuilder()
            ->setClassName('Json')
            ->setOptions(['serialize' => 'articleArticleTypeAttributeValues']);
    }
}
