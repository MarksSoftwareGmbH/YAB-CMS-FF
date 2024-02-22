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
namespace YabCmsFf\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use YabCmsFf\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\DateTime;

/**
 * Articles Controller
 *
 * Class ArticlesController
 * @package YabCmsFf\Controller
 */
class ArticlesController extends AppController
{

    /**
     * Http host
     *
     * @var string
     */
    private string $httpHost;

    /**
     * Domain id
     *
     * @var string
     */
    private int $domainId;

    /**
     * Locale
     *
     * @var string
     */
    private string $locale;

    /**
     *
     * @var array
     */
    public array $paginate = [
        'limit'     => 100,
        'maxLimit'  => 150,
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

        // Set http host by environment
        if (filter_var(env('HTTP_HOST'), FILTER_VALIDATE_IP) !== false) {
            $this->httpHost = env('HTTP_X_FORWARDED_HOST');
        } else {
            $this->httpHost = env('HTTP_HOST');
        }

        // Set domain id by http host
        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
        $domain = $Domains
            ->find()
            ->select(['id'])
            ->where(['url' => $this->httpHost])
            ->first();
        if (!empty($domain->id)) {
            $this->domainId = $domain->id;
        }
    }

    /**
     * Promoted method
     *
     * @return void
     */
    public function promoted()
    {
        $dateTime = DateTime::now();
        $query = $this->Articles
            ->find('promoted', options: [
                'search'            => $this->getRequest()->getQueryParams(),
                'domain_id'         => $this->domainId,
                'locale'            => $this->locale,
                'date'              => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'articles_order'    => 'DESC',
            ])
            ->contain([
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ])
            ->matching('ArticleArticleTypeAttributeValues')
            ->distinct(['Articles.id'])
            ->toArray();

        $this->Global->captcha($this);

        $this->set('articles', $query);
    }

    /**
     * Index method
     *
     * @param string|null $articleType
     * @return void
     */
    public function index(string $articleType = null)
    {
        $dateTime = DateTime::now();
        $query = $this->Articles
            ->find('index', options: [
                'search'            => $this->getRequest()->getQueryParams(),
                'domain_id'         => $this->domainId,
                'article_type'      => $articleType,
                'locale'            => $this->locale,
                'date'              => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'articles_order'    => 'DESC',
            ])
            ->contain([
                'ParentArticles',
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ])
            ->matching('ArticleArticleTypeAttributeValues')
            ->distinct(['Articles.id']);

        $this->set('articleType', $articleType);
        $this->set('articles', $this->paginate($query));
    }

    /**
     * Search method
     *
     * @return void
     */
    public function search()
    {
        $query = $this->Articles
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'ParentArticles',
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ])
            ->matching('ArticleArticleTypeAttributeValues')
            ->distinct(['Articles.id'])
            ->where([
                'Articles.domain_id'   => $this->domainId,
                'Articles.locale'      => $this->locale,
                'Articles.status'      => 1,
            ]);

        $this->set('search', $this->getRequest()->getQueryParams());
        $this->set('articles', $this->paginate($query));
    }

    /**
     * Sitemap method
     *
     * @return void
     */
    public function sitemap()
    {
        $dateTime = DateTime::now();
        $query = $this->Articles
            ->find('sitemap', options: [
                'domain_id'         => $this->domainId,
                'locale'            => $this->locale,
                'date'              => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'articles_order'    => 'ASC',
            ])
            ->contain([
                'ParentArticles',
                'ArticleTypes.ArticleTypeAttributes',
                'ArticleArticleTypeAttributeValues.ArticleTypeAttributes',
            ])
            ->matching('ArticleArticleTypeAttributeValues')
            ->distinct(['Articles.id']);

        $this->set('articles', $query);
    }

    /**
     * View method
     *
     * @param string|null $articleType
     * @param string|null $slug
     *
     * @return \Cake\Http\Response|void
     */
    public function view(string $articleType = null, string $slug = null)
    {
        $dateTime = DateTime::now();

        if (empty($slug)) {
            // Define articleType as slug
            $slug = $articleType;
            // Get article by slug
            $query = $this->Articles
                ->find('bySlug', options: [
                    'slug'      => $slug,
                    'domain_id' => $this->domainId,
                    'locale'    => $this->locale,
                    'date'      => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                    'status'    => 1,
                ])
                ->first();
        } else {
            // Get article by articleType and slug
            $query = $this->Articles
                ->find('byArticleTypeAndSlug', options: [
                    'article_type'  => $articleType,
                    'slug'          => $slug,
                    'domain_id'     => $this->domainId,
                    'locale'        => $this->locale,
                    'date'          => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                    'status'        => 1,
                ])
                ->first();
        }

        if (!$query) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The requested link could not be found. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
            return $this->redirect($this->referer());
        }

        $this->set('article', $query);
    }

    /**
     * Page view method
     *
     * @param string|null $slug
     *
     * @return \Cake\Http\Response|void
     */
    public function pageView(string $slug = null)
    {
        $dateTime = DateTime::now();

        // Get article for page view
        $query = $this->Articles
            ->find('bySlug', options: [
                'article_type'  => 'page',
                'slug'          => $slug,
                'domain_id'     => $this->domainId,
                'locale'        => $this->locale,
                'date'          => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'status'        => 1,
            ])
            ->contain([
                'ParentArticles' => [
                    'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes'],
                    'ChildArticles' => function ($q) {
                        return $q
                            ->orderBy(['created' => 'ASC'])
                            ->contain(['ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']]);
                    },
                ],
                'ChildArticles' => function ($q) {
                    return $q
                        ->orderBy(['created' => 'ASC'])
                        ->contain(['ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']]);
                },
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes'],
                'Users' => [
                    'fields' => [
                        'Users.id',
                        'Users.name',
                        'Users.email',
                    ],
                ],
            ])
            ->first();

        if (!$query) {
            $this->Flash->set(
                __d('yab_cms_ff', 'The requested link could not be found. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
            return $this->redirect($this->referer());
        }

        $this->set('article', $query);
    }
}
