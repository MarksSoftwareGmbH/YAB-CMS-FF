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
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use YabCmsFf\Controller\Admin\AppController;
use YabCmsFf\Utility\YabCmsFf;

/**
 * Articles Controller
 *
 * @property \YabCmsFf\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
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
            'article_type_id',
            'user_id',
            'domain_id',
            'locale',
            'promote_start',
            'promote_end',
            'promote',
            'status',
            'created',
            'modified',
            'ArticleTypes.title',
            'Users.full_name',
            'Domains.name',
            'Categories.name',
        ],
        'order' => [
            'Articles.lft' => 'ASC',
        ]
    ];

    /**
     * File upload directory
     *
     * relative to the webroot.
     *
     * @var string
     * @access public
     */
    public $fileUploadDir = 'plugins/YabCmsFf/webroot/img/content';

    /**
     * File upload link
     *
     * relative to the /.
     *
     * @var string
     * @access public
     */
    public $fileUploadLink = '/yab_cms_ff/img/content';

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

        if (in_array($this->getRequest()->getParam('action'), ['fileUpload'])) {
            $this->FormProtection->setConfig('unlockedActions', ['fileUpload']);
        }

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
        $query = $this->Articles
            ->find('search', search: $this->getRequest()->getQueryParams(), locale: $this->locale, articles_order: 'ASC')
            ->contain([
                'ParentArticles',
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
                'Users',
                'Domains' => function ($q) {
                    return $q->orderBy(['Domains.name' => 'ASC']);
                },
            ])
            ->matching('ArticleArticleTypeAttributeValues')
            ->distinct(['Articles.id']);

        $domains = $this->Articles->Domains
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

        $articleTypes = $this->Articles->ArticleTypes
            ->find('list',
                order: ['ArticleTypes.alias' => 'ASC'],
                keyField: 'alias',
                valueField: 'title'
            )
            ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeIndexRender', $this, [
            'Query'     => $query,
            'Domains'   => $domains,
            'Locales'   => $locales,
            'ArticleTypes' => $articleTypes,
        ]);

        $this->set('articles', $this->paginate($query));
        $this->set(compact('domains', 'locales', 'articleTypes'));
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
                ['element' => 'default', 'params' => ['class' => 'error']]);
        }

        $response = false;
        if ($this->getRequest()->getQuery('draggedLft') > $this->getRequest()->getQuery('siblingLft')) {
            $article = $this->Articles->get($this->getRequest()->getQuery('draggedId'));
            if ($this->Articles->moveUp($article)) {
                $response = true;
            }
        }

        if ($this->getRequest()->getQuery('draggedLft') < $this->getRequest()->getQuery('siblingLft')) {
            $article = $this->Articles->get($this->getRequest()->getQuery('draggedId'));
            if ($this->Articles->moveDown($article)) {
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
        $article = $this->Articles->get($id);
        if ($this->Articles->moveUp($article)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The article has been moved up.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The article could not be moved up. Please, try again.'),
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
        $article = $this->Articles->get($id);
        if ($this->Articles->moveDown($article)) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The article has been moved down.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The article could not be moved down. Please, try again.'),
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
        $article = $this->Articles->get($id, contain: [
            'ParentArticles',
            'ChildArticles',
            'ArticleTypes.ArticleTypeAttributes' => function ($q) {
                return $q
                    ->orderBy(['ArticleTypeAttributes.alias' => 'ASC'])
                    ->contain([
                        'ArticleTypeAttributeChoices' => function ($q) {
                            return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                        }
                    ]);
            },
            'ArticleArticleTypeAttributeValues.ArticleTypeAttributes' => function ($q) {
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            },
            'Categories',
            'Domains' => function ($q) {
                return $q->orderBy(['Domains.name' => 'ASC']);
            },
            'Users',
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeViewRender', $this, ['Article' => $article]);

        $this->set('article', $article);

        $this->viewBuilder()->setOption('serialize', ['article']);
    }

    /**
     * Add method
     *
     * @param string|null $articleTypeAlias
     *
     * @return \Cake\Http\Response|null
     */
    public function add(string $articleTypeAlias = null)
    {
        // Get session
        $session = $this->request->getSession();

        // Get articleType
        $articleType = $this->Articles->ArticleTypes
            ->find('all',
                conditions: ['ArticleTypes.alias' => $articleTypeAlias],
                contain: [
                    'ArticleTypeAttributes' => function ($q) {
                        return $q
                            ->orderBy(['ArticleTypeAttributes.alias' => 'ASC'])
                            ->contain([
                                'ArticleTypeAttributeChoices' => function ($q) {
                                    return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                                }
                            ]);
                    },
                ],
                fields: [
                    'ArticleTypes.id',
                    'ArticleTypes.title',
                    'ArticleTypes.alias',
                    'ArticleTypes.description',
                ])
            ->first();

        if (!$articleType) {
            $this->Flash->set(
                __d(
                    'yab_cms_ff',
                    '{articleTypeAlias} could not be found. Please, try again.',
                    ['articleTypeAlias' => Inflector::singularize($articleTypeAlias)]
                ),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            if ($session->check('Request.HTTP_REFERER')) {
                return $this->redirect($session->read('Request.HTTP_REFERER'));
            } else {
                return $this->redirect(['action' => 'index']);
            }
        }

        $article = $this->Articles->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $associated = [
                'ParentArticles',
                'ArticleTypes',
                'ArticleArticleTypeAttributeValues',
                'Categories',
            ];
            $article = $this->Articles->patchEntity(
                $article,
                $this->getRequest()->getData(),
                ['associated' => $associated]
            );
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeAdd', $this, [
                'Article' => $article,
            ]);
            if ($this->Articles->save($article)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Articles.onAddSuccess', $this, [
                    'Article' => $article,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                if ($session->check('Request.HTTP_REFERER')) {
                    return $this->redirect($session->read('Request.HTTP_REFERER'));
                } else {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Articles.onAddFailure', $this, [
                    'Article' => $article,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeAddRender', $this, [
            'Article' => $article,
            'ArticleType' => $articleType
        ]);

        $this->set(compact('article', 'articleType'));

        $this->viewBuilder()->setOption('serialize', ['article', 'articleType']);
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
        // Get session
        $session = $this->request->getSession();

        $article = $this->Articles->get($id, contain: [
            'ParentArticles',
            'ChildArticles',
            'ArticleTypes.ArticleTypeAttributes' => function ($q) {
                return $q
                    ->orderBy(['ArticleTypeAttributes.alias' => 'ASC'])
                    ->contain([
                        'ArticleTypeAttributeChoices' => function ($q) {
                            return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                        }
                    ]);
            },
            'ArticleArticleTypeAttributeValues.ArticleTypeAttributes' => function ($q) {
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            },
            'Users',
            'Domains',
            'Categories',
        ]);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $associated = [
                'ParentArticles',
                'ArticleTypes',
                'ArticleArticleTypeAttributeValues',
                'Categories',
            ];
            $article = $this->Articles->patchEntity(
                $article,
                $this->getRequest()->getData(),
                ['associated' => $associated]
            );
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeEdit', $this, ['Article' => $article]);
            if ($this->Articles->save($article)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Articles.onEditSuccess', $this, ['Article' => $article]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                if ($session->check('Request.HTTP_REFERER')) {
                    return $this->redirect($session->read('Request.HTTP_REFERER'));
                } else {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Articles.onEditFailure', $this, ['Article' => $article]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeEditRender', $this, ['Article' => $article]);

        $this->set('article', $article);

        $this->viewBuilder()->setOption('serialize', ['article']);
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
        // Get session
        $session = $this->request->getSession();

        $this->getRequest()->allowMethod(['post']);

        $article = $this->Articles->get($id, contain: [
                'ParentArticles',
                'ChildArticles',
                'ArticleTypes.ArticleTypeAttributes' => function ($q) {
                    return $q
                        ->contain([
                            'ArticleTypeAttributeChoices' => function ($q) {
                                return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                            }
                        ])
                        ->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
                },
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes' => function ($q) {
                    return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
                },
                'Users',
                'Domains',
                'Categories',
        ]);
        $article->setNew(true);
        $article->unset('id');
        $article->promote_start = null;
        $article->promote_end = null;
        $article->promote = 0;
        $article->status = 0;

        foreach ($article->article_article_type_attribute_values as $attributeValue) {
            $attributeValue->setNew(true);
            $attributeValue->unset('id');
            switch ($attributeValue->article_type_attribute->alias) {
                case (
                    $attributeValue->article_type_attribute->alias == 'title' ||
                    $attributeValue->article_type_attribute->alias == 'name'
                ):
                    $attributeValue->value = $attributeValue->value . ' ' . __d('yab_cms_ff', '(Copy)');
                    break;
                case 'slug':
                    $attributeValue->value = $attributeValue->value . '-' . __d('yab_cms_ff', 'copy');
                    break;
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeCopy', $this, [
            'Article' => $article
        ]);
        if ($this->Articles->save($article)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.onCopySuccess', $this, [
                'Article' => $article
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article has been copied.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.onCopyFailure', $this, [
                'Article' => $article
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article could not be copied. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        if ($session->check('Request.HTTP_REFERER')) {
            return $this->redirect($session->read('Request.HTTP_REFERER'));
        } else {
            return $this->redirect(['action' => 'index']);
        }
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
        // Get session
        $session = $this->request->getSession();

        $this->getRequest()->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Articles.beforeDelete', $this, ['Article' => $article]);
        if ($this->Articles->delete($article)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.onDeleteSuccess', $this, ['Article' => $article]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Articles.onDeleteFailure', $this, ['Article' => $article]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        if ($session->check('Request.HTTP_REFERER')) {
            return $this->redirect($session->read('Request.HTTP_REFERER'));
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * File upload method
     *
     * @return \Cake\Http\Response
     */
    public function fileUpload()
    {
        $result = [
            'uploaded' => 0,
            'error' => [
                'message' => __d('yab_cms_ff', 'The file could not be uploaded or is too big.'),
            ],
        ];

        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {
            if (in_array($this->getRequest()->getData('upload.type'), [
                'image/jpeg',
                'image/jpg',
                'image/png',
            ])) {
                $file = $this->getRequest()->getData('upload');
                $fileName = $file['name'];
                $fileDestination = ROOT . DS . $this->fileUploadDir . DS . $fileName;

                if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
                    $fileLink = $this->fileUploadLink . DS . $fileName;
                    $result = [
                        'uploaded' => 1,
                        'fileName' => $fileName,
                        'url' => $fileLink,
                    ];
                }
            }
        }

        $this->response->getBody()->write(json_encode($result));
        $this->response = $this->response->withType('json');
        return $this->response;
    }

    /**
     * Export xlsx method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportXlsx()
    {
        $articles = $this->Articles->find('all');
        $header = $this->Articles->tableColumns;

        $articlesArray = [];
        foreach($articles as $article) {
            $articleArray = [];
            $articleArray['id'] = $article->id;
            $articleArray['parent_id'] = $article->parent_id;
            $articleArray['article_type_id'] = $article->article_type_id;
            $articleArray['user_id'] = $article->user_id;
            $articleArray['domain_id'] = $article->domain_id;
            $articleArray['lft'] = $article->lft;
            $articleArray['rght'] = $article->rght;
            $articleArray['locale'] = $article->locale;
            $articleArray['promote_start'] = empty($article->promote_start)? NULL: $article->promote_start->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote_end'] = empty($article->promote_end)? NULL: $article->promote_end->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote'] = ($article->promote == 1)? 1: 0;
            $articleArray['status'] = ($article->status == 1)? 1: 0;
            $articleArray['created'] = empty($article->created)? NULL: $article->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['modified'] = empty($article->modified)? NULL: $article->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articlesArray[] = $articleArray;
        }
        $articles = $articlesArray;

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
        foreach ($articles as $dataEntity) {
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
        $articles = $this->Articles->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Articles->tableColumns;
        $extract = [
            'id',
            'parent_id',
            'article_type_id',
            'user_id',
            'domain_id',
            'lft',
            'rght',
            'locale',
            function ($row) {
                return empty($row['promote_start'])? NULL: $row['promote_start']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            function ($row) {
                return empty($row['promote_end'])? NULL: $row['promote_end']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            function ($row) {
                return ($row['promote'] == 1)? 1: 0;
            },
            function ($row) {
                return ($row['status'] == 1)? 1: 0;
            },
            function ($row) {
                return empty($row['created'])? NULL: $row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            function ($row) {
                return empty($row['modified'])? NULL: $row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('articles'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'articles',
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
        $articles = $this->Articles->find('all');

        $articlesArray = [];
        foreach($articles as $article) {
            $articleArray = [];
            $articleArray['id'] = $article->id;
            $articleArray['parent_id'] = $article->parent_id;
            $articleArray['article_type_id'] = $article->article_type_id;
            $articleArray['user_id'] = $article->user_id;
            $articleArray['domain_id'] = $article->domain_id;
            $articleArray['lft'] = $article->lft;
            $articleArray['rght'] = $article->rght;
            $articleArray['locale'] = $article->locale;
            $articleArray['promote_start'] = empty($article->promote_start)? NULL: $article->promote_start->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote_end'] = empty($article->promote_end)? NULL: $article->promote_end->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote'] = ($article->promote == 1)? 1: 0;
            $articleArray['status'] = ($article->status == 1)? 1: 0;
            $articleArray['created'] = empty($article->created)? NULL: $article->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['modified'] = empty($article->modified)? NULL: $article->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articlesArray[] = $articleArray;
        }
        $articles = ['Articles' => ['Article' => $articlesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'xml'));
        $this->set(compact('articles'));
        $this
            ->viewBuilder()
            ->setClassName('Xml')
            ->setOptions(['serialize' => 'articles']);
    }

    /**
     * Export json method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function exportJson()
    {
        $articles = $this->Articles->find('all');

        $articlesArray = [];
        foreach($articles as $article) {
            $articleArray = [];
            $articleArray['id'] = $article->id;
            $articleArray['parent_id'] = $article->parent_id;
            $articleArray['article_type_id'] = $article->article_type_id;
            $articleArray['user_id'] = $article->user_id;
            $articleArray['domain_id'] = $article->domain_id;
            $articleArray['lft'] = $article->lft;
            $articleArray['rght'] = $article->rght;
            $articleArray['locale'] = $article->locale;
            $articleArray['promote_start'] = empty($article->promote_start)? NULL: $article->promote_start->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote_end'] = empty($article->promote_end)? NULL: $article->promote_end->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['promote'] = ($article->promote == 1)? 1: 0;
            $articleArray['status'] = ($article->status == 1)? 1: 0;
            $articleArray['created'] = empty($article->created)? NULL: $article->created->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $articleArray['modified'] = empty($article->modified)? NULL: $article->modified->i18nFormat('yyyy-MM-dd HH:mm:ss');

            $articlesArray[] = $articleArray;
        }
        $articles = ['Articles' => ['Article' => $articlesArray]];

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'json'));
        $this->set(compact('articles'));
        $this
            ->viewBuilder()
            ->setClassName('Json')
            ->setOptions(['serialize' => 'articles']);
    }
}
