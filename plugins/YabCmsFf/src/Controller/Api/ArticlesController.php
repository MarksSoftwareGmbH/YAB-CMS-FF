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
namespace YabCmsFf\Controller\Api;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\View\JsonView;
use Cake\View\XmlView;

/**
 * Class ArticlesController
 *
 * @package Api\Controller
 */
class ArticlesController extends AppController
{

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
        'order' => ['created' => 'DESC']
    ];

    /**
     * Get the View classes this controller can perform content negotiation with.
     *
     * Each view class must implement the `getContentType()` hook method
     * to participate in negotiation.
     *
     * @see \Cake\Http\ContentTypeNegotiation
     * @return list<string>
     */
    public function viewClasses(): array
    {
        return [JsonView::class, XmlView::class];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->Crud->on('beforePaginate', function (Event $event) {
            if (
                ($this->getRequest()->getQuery('fields') !== null) &&
                !empty($this->getRequest()->getQuery('fields'))
            ) {
                $select = explode(',', $this->getRequest()->getQuery('fields'));
                $this->paginate($event->getSubject()->query->select($select));
            }
            $event->getSubject()->query->contain([
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ]);
        });

        $this->Crud->on('afterPaginate', function(Event $event) {
            foreach ($event->getSubject()->entities as $entity) {
                unset($entity->article_article_type_attribute_values);
                unset($entity->article_type);
                unset($entity->_matchingData);
            }
        });

        return $this->Crud->execute();
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return mixed
     */
    public function view(int $id = null)
    {
        $this->Crud->on('beforeFind', function (Event $event) {
            if (
                ($this->getRequest()->getQuery('fields') !== null) &&
                !empty($this->getRequest()->getQuery('fields'))
            ) {
                $select = explode(',', $this->getRequest()->getQuery('fields'));
                $event->getSubject()->query->select($select);
            }
            if (
                ($this->getRequest()->getQuery('contain') !== null) &&
                ($this->getRequest()->getQuery('contain') == 1)
            ) {
                $event->getSubject()->query->contain([
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
                ]);
            }
        });

        $this->Crud->on('afterFind', function(Event $event) {
            $entity = $event->getSubject()->entity;
            unset($entity->article_article_type_attribute_values);
            unset($entity->article_type);
            unset($entity->_matchingData);
        });

        return $this->Crud->execute();
    }

    /**
     * Add method
     *
     * @return mixed
     */
    public function add()
    {
        $this->Crud->on('beforeSave', function ($event) {
            $event->stopPropagation();
        });
        $this->Articles->apiSave($this->getRequest()->getData());

        return $this->Crud->execute();
    }

    /**
     * Edit method
     *
     * @param int|null $id
     * @return mixed
     */
    public function edit(int $id = null)
    {
        $this->Crud->on('beforeSave', function ($event) {
            $event->stopPropagation();
        });
        $this->Articles->apiSave($this->getRequest()->getData());

        return $this->Crud->execute();
    }

    /**
     * Delete method
     *
     * @param int|null $id
     * @return mixed
     */
    public function delete(int $id = null)
    {
        $this->Crud->on('afterDelete', function (Event $event) {
            if ($event->getSubject()->success) {
                $connection = ConnectionManager::get('default');
                $connection->delete('articles', ['id' => $event->getSubject()->id]);
            }
        });

        return $this->Crud->execute();
    }

}
