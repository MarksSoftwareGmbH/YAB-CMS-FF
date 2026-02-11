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
namespace YabCmsFf\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentArticles
 * @property \Cake\ORM\Association\BelongsTo $ArticleTypes
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Domains
 * @property \Cake\ORM\Association\HasMany $ArticleArticleTypeAttributeValues
 * @property \Cake\ORM\Association\HasMany $ChildArticles
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * You can use this method to define associations, attach behaviors
     * define validation and do any other initialization logic you need.
     *
     * ```
     *  public function initialize(array $config)
     *  {
     *      $this->belongsTo('Users');
     *      $this->belongsToMany('Tagging.Tags');
     *      $this->setPrimaryKey('something_else');
     *  }
     * ```
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', ['recoverOrder' => ['lft' => 'ASC']]);
        $this->addBehavior('Search.Search');
        $this->addBehavior('YabCmsFf.Trackable');
        $this->addBehavior('YabCmsFf.Deletable');
        $this->addBehavior('YabCmsFf.Datetime');

        $this->belongsTo('ArticleTypes', [
            'foreignKey' => 'article_type_id',
            'joinType' => 'INNER',
            'className' => 'YabCmsFf.ArticleTypes'
        ]);
        $this->belongsTo('ParentArticles', [
            'className' => 'YabCmsFf.Articles',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'YabCmsFf.Users'
        ]);
        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'className' => 'YabCmsFf.Domains'
        ]);

        $this->hasMany('ArticleArticleTypeAttributeValues', [
            'foreignKey' => 'article_id',
            'className' => 'YabCmsFf.ArticleArticleTypeAttributeValues',
            'dependent' => true
        ]);
        $this->hasMany('ChildArticles', [
            'className' => 'YabCmsFf.Articles',
            'foreignKey' => 'parent_id',
            'dependent' => true
        ]);

        $this->belongsToMany('Categories', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'categories_articles',
            'className' => 'YabCmsFf.Categories',
            'sort' => ['CategoriesArticles.position' => 'ASC'] // Sort on joinTable
        ]);

        // Setup search filter using search manager
        $this->getBehavior('Search')->searchManager()
            ->value('domain', [
                'fields' => ['Domains.name']
            ])
            ->value('locale', [
                'fields' => ['Articles.locale']
            ])
            ->value('articleType', [
                'fields' => ['ArticleTypes.alias']
            ])
            ->add('search', 'Search.Like', [
                'multiValue' => true,
                'multiValueSeparator' => ' ',
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => [
                    'ArticleTypes.title',
                    'ArticleTypes.alias',
                    'ArticleTypes.description',
                    'ArticleArticleTypeAttributeValues.value',
                ],
            ]);
    }

    /**
     * Default table columns.
     *
     * @var array
     */
    public $tableColumns = [
        'id',
        'parent_id',
        'article_type_id',
        'user_id',
        'domain_id',
        'lft',
        'rght',
        'locale',
        'promote_start',
        'promote_end',
        'promote',
        'status',
        'created',
        'modified',
    ];

    /**
     * Returns the default validator object. Subclasses can override this function
     * to add a default validation set to the validator object.
     *
     * @param \Cake\Validation\Validator $validator The validator that can be modified to
     * add some rules to it.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->allowEmptyString('uuid_id');

        $validator
            ->integer('lft')
            ->allowEmptyString('lft');

        $validator
            ->integer('rght')
            ->allowEmptyString('rght');

        $validator
            ->requirePresence('locale', 'create')
            ->notBlank('locale');

        $validator
            ->dateTime('promote_start')
            ->allowEmptyString('promote_start');

        $validator
            ->dateTime('promote_end')
            ->allowEmptyString('promote_end');

        $validator
            ->boolean('promote')
            ->requirePresence('promote', 'create')
            ->allowEmptyString('promote');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->integer('deleted_by')
            ->allowEmptyString('deleted_by');

        return $validator;
    }

    /**
     * Returns a RulesChecker object after modifying the one that was supplied.
     *
     * Subclasses should override this method in order to initialize the rules to be applied to
     * entities saved by this instance.
     *
     * @param \Cake\Datasource\RulesChecker $rules The rules object to be modified.
     * @return \Cake\Datasource\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentArticles'));
        $rules->add($rules->existsIn(['article_type_id'], 'ArticleTypes'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['domain_id'], 'Domains'));

        return $rules;
    }

    /**
     * Find promoted method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findPromoted(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ParentArticles',
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ])
            ->where([
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <='    => $options['date'],
                'Articles.promote'             => 1,
                'Articles.status'              => 1,
            ])
            ->orderBy(['Articles.created' => $options['articles_order']]);

        return $query;
    }

    /**
     * Find index method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findIndex(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ParentArticles',
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ])
            ->matching('ArticleTypes', function ($q) use ($options) {
                return $q
                    ->where(['ArticleTypes.alias' => $options['article_type']]);
            })
            ->where([
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <= '   => $options['date'],
                'Articles.status'              => 1,
            ])
            ->orderBy(['Articles.created' => $options['articles_order']]);

        return $query;
    }

    /**
     * Find sitemap method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findSitemap(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ParentArticles',
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ])
            ->where([
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <='    => $options['date'],
                'Articles.status'              => 1,
            ])
            ->orderBy(['Articles.modified' => $options['articles_order']]);

        return $query;
    }

    /**
     * Find all articles method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findAllArticles(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }

        $query
            ->contain([
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ]);

        return $query;
    }

    /**
     * Find by article type method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findByArticleType(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ParentArticles',
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ])
            ->matching('ArticleTypes', function ($q) use ($options) {
                return $q->where(['ArticleTypes.alias' => $options['article_type']]);
            });

        // Individual order key
        if (empty($options['order_key'])) {
            $options['order_key'] = 'created';
        }
        // Order direction
        if (empty($options['order_direction'])) {
            $options['order_direction'] = 'ASC';
        }
        $query->orderBy(['Articles' . '.' . $options['order_key'] => $options['order_direction']]);

        if ($options['locale'] !== 'en_US') {
            $query->where(['Articles.locale LIKE' => '%' . $options['locale'] . '%']);
        }

        return $query;
    }

    /**
     * Find by article type and id method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findByArticleTypeAndId(SelectQuery $query, array $options)
    {
        $options['article_type'] = Text::slug($options['article_type']);

        $query
            ->matching('ArticleArticleTypeAttributeValues.ArticleTypeAttributes.ArticleTypes', function ($q) use ($options) {
                $foreignKey = empty($options['data']['id'])? null: $options['data']['id'];
                return $q
                    ->where([
                        'ArticleTypes.alias'                        => $options['article_type'],
                        'ArticleTypeAttributes.alias'               => 'foreign_key',
                        'ArticleArticleTypeAttributeValues.value'   => $foreignKey,
                        'Articles.locale'                           => $options['data']['locale'],
                    ]);
            })
            ->contain(['ArticleArticleTypeAttributeValues.ArticleTypeAttributes']);

        return $query;
    }

    /**
     * Find by slug method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findBySlug(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes'],
                'Users',
            ])
            ->matching('ArticleArticleTypeAttributeValues', function ($q) use ($options) {
                return $q->where(['ArticleArticleTypeAttributeValues.value' => $options['slug']]);
            })
            ->where([
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <='    => $options['date'],
                'Articles.status'              => $options['status'],
            ]);

        return $query;
    }

    /**
     * Find by article type and slug method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findByArticleTypeAndSlug(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        if (empty($options['locale'])) {
            $options['locale'] = 'en_US';
        }

        $query
            ->contain([
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes'],
                'Users',
            ])
            ->matching('ArticleTypes', function ($q) use ($options) {
                return $q->where(['ArticleTypes.alias' => $options['article_type']]);
            })
            ->matching('ArticleArticleTypeAttributeValues', function ($q) use ($options) {
                return $q->where(['ArticleArticleTypeAttributeValues.value' => $options['slug']]);
            })
            ->where([
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <= '   => $options['date'],
                'Articles.status'              => 1,
            ]);

        return $query;
    }

    /**
     * Find by slug and id method.
     *
     * @param SelectQuery $query
     * @param array $options
     * @return SelectQuery
     */
    public function findBySlugAndId(SelectQuery $query, array $options)
    {
        if (array_key_exists('options', $options)) {
            $options = $options['options'];
        }
        $query
            ->contain([
                'ArticleTypes' => [
                    'ArticleTypeAttributes',
                    'fields' => [
                        'ArticleTypes.id',
                        'ArticleTypes.title',
                        'ArticleTypes.alias',
                        'ArticleTypes.description',
                    ]
                ],
                'ArticleArticleTypeAttributeValues' => ['ArticleTypeAttributes']
            ])
            ->matching('ArticleArticleTypeAttributeValues', function ($q) use ($options) {
                return $q->where(['ArticleArticleTypeAttributeValues.value' => $options['slug']]);
            })
            ->where([
                'Articles.id'                  => $options['id'],
                'Articles.domain_id'           => $options['domain_id'],
                'Articles.locale LIKE'         => '%' . $options['locale'] . '%',
                'Articles.promote_start <= '   => $options['date'],
                'Articles.status'              => 1,
            ]);

        return $query;
    }

    /**
     * Get type with article types method.
     *
     * @param string $articleType
     * @return mixed
     */
    private function getTypeWithArticleTypes(string $articleType)
    {
        return $this->ArticleTypes
            ->find()
            ->where(['alias' => Text::slug($articleType)])
            ->contain('ArticleTypeAttributes')
            ->firstOrFail();
    }

    /**
     * Api save method.
     *
     * @param array $data
     * @return bool
     */
    public function apiSave(array $data)
    {
        $article = $this
            ->find('byArticleTypeAndId', [
                'data'          => $data,
                'article_type'  => Text::slug($data['article_type']),
            ])
            ->first();

        if (empty($article)) {
            return $this->apiCreate($data);
        }

        return $this->apiUpdate($article, $data);
    }

    /**
     * Api create method.
     *
     * @param array $data
     *
     * @return bool|mixed
     */
    private function apiCreate(array $data)
    {
        // Get article type
        $type = $this->getTypeWithArticleTypes(Text::slug($data['article_type']));

        $entity                 = $this->newEmptyEntity();
        $entity->parent_id      = empty($data['parent_id'])? null: urldecode($data['parent_id']);
        $entity->article_type   = $type;
        $entity->user_id        = empty($data['user_id'])? null: urldecode($data['user_id']);
        $entity->domain_id      = empty($data['domain_id'])? null : urldecode($data['domain_id']);
        $entity->locale         = empty($data['locale'])? 'en_US': urldecode($data['locale']);
        $entity->promote_start  = empty($data['promote_start'])? null: urldecode($data['promote_start']);
        $entity->promote_end    = empty($data['promote_end'])? null: urldecode($data['promote_end']);
        $entity->promote        = empty($data['promote'])? 0: urldecode($data['promote']);
        $entity->status         = empty($data['status'])? 0: urldecode($data['status']);
        $entity->article_article_type_attribute_values = [];

        if (!empty($data['id'])) {
            $data['foreign_key'] = $data['id'];
        }

        $article = $this->patchArticle($type, $entity, $data);

        if ($this->save($article)) {
            return $article;
        } else {
            return false;
        }
    }

    /**
     * Api update method.
     *
     * @param object $article
     * @param array $data
     * @return bool|mixed
     */
    private function apiUpdate(object $article, array $data)
    {
        $type = $this->getTypeWithArticleTypes($article->article_type->alias);

        $existentArticle = $this->get($article->id);
        $this->patchEntity($existentArticle, ['modified' => date('Y-m-d H:i:s')]);
        if ($this->save($article)) {

            // Update values
            foreach ($article->article_article_type_attribute_values as $value) {

                $attributeAlias = $value->article_type_attribute->alias;
                $attribute = $this->ArticleArticleTypeAttributeValues->get($value->id);

                if (is_array(json_decode($value->value, true))) {

                    $comparisonValue = json_decode($value->value, true);
                    array_walk_recursive($comparisonValue, [$this, 'encode']);

                } else {

                    $comparisonValue = urlencode($value->value);
                }

                if (array_key_exists($attributeAlias, $data)) {

                    if ($comparisonValue == $data[$attributeAlias]) {
                        unset($data[$attributeAlias]);
                        continue;
                    }

                    if (!is_array($data[$attributeAlias])) {

                        $newValue['value'] = urldecode($data[$attributeAlias]);
                        $this->ArticleArticleTypeAttributeValues->patchEntity($attribute, $newValue);

                        if ($this->ArticleArticleTypeAttributeValues->save($attribute)) {
                            unset($data[$attributeAlias]);
                            continue;
                        }
                    }

                    $newValue['value'] = urldecode(json_encode($data[$attributeAlias]));
                    $this->ArticleArticleTypeAttributeValues->patchEntity($attribute, $newValue);

                    if ($this->ArticleArticleTypeAttributeValues->save($attribute)) {
                        unset($data[$attributeAlias]);
                        continue;
                    }

                } else {

                    if ($attributeAlias !== 'foreign_key') {
                        $this->ArticleArticleTypeAttributeValues->delete($value);
                    }

                }
            }

            // Add new values
            if (!empty($data)) {

                foreach ($type->article_type_attributes as $attribute) {

                    //alias exists in send
                    if (array_key_exists($attribute->alias, $data)) {

                        $attributeAlias = $attribute->alias;
                        $newData = '';

                        if (isset($data[$attributeAlias])) {
                            $newData = $data[$attributeAlias];
                        }

                        if (is_array($newData)) {
                            $newData = json_encode($newData);
                        }

                        $entity = $this->ArticleArticleTypeAttributeValues->newEntity([
                            'article_type_attribute_id' => $attribute->id,
                            'article_id'                => $article->id,
                            'value'                     => urldecode($newData)
                        ]);

                        $this->ArticleArticleTypeAttributeValues->save($entity);
                    }
                }
            }

            return $article;
        } else {
            return false;
        }
    }

    /**
     * Patch article method.
     *
     * @param object $type
     * @param object $article
     * @param array $data
     * @return mixed
     */
    private function patchArticle(object $type, object $article, array $data)
    {
        foreach ($type->article_type_attributes as $attribute) {

            // Condition for default entity creation
            if (isset($data[$attribute->alias])) {

                if (is_array($data[$attribute->alias])) {
                    $data[$attribute->alias] = json_encode($data[$attribute->alias]);
                }
                $entity = $this->ArticleArticleTypeAttributeValues->newEntity([
                    'value'                     => urldecode($data[$attribute->alias]),
                    'article_type_attribute_id' => $attribute->id
                ]);

                array_push($article->article_article_type_attribute_values, $entity);
            }
        }

        return $article;
    }

    /**
     * Encode method.
     *
     * @param $item
     * @param $key
     */
    public function encode(&$item, $key)
    {
        $item = urlencode($item);
    }
}
