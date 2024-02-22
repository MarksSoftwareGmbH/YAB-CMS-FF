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
}
