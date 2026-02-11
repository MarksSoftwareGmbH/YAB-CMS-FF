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

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentCategories
 * @property \Cake\ORM\Association\BelongsTo $Domains
 * @property \Cake\ORM\Association\HasMany $ChildCategories
 * @property \Cake\ORM\Association\BelongsToMany $Articles
 *
 * @method \YabCmsFf\Model\Entity\Category get($primaryKey, $options = [])
 * @method \YabCmsFf\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \YabCmsFf\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \YabCmsFf\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\Category findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', ['recoverOrder' => ['Categories.lft' => 'ASC']]);
        $this->addBehavior('Search.Search');
        $this->addBehavior('YabCmsFf.Trackable');
        $this->addBehavior('YabCmsFf.Deletable');

        $this->belongsTo('ParentCategories', [
            'className' => 'YabCmsFf.Categories',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'className' => 'YabCmsFf.Domains'
        ]);

        $this->hasMany('ChildCategories', [
            'className' => 'YabCmsFf.Categories',
            'foreignKey' => 'parent_id'
        ]);

        $this->belongsToMany('Articles', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'article_id',
            'joinTable' => 'categories_articles',
            'className' => 'YabCmsFf.Articles',
        ]);

        // Setup search filter using search manager
        $this->getBehavior('Search')->searchManager()
            ->value('domain', [
                'fields' => ['Domains.name']
            ])
            ->value('locale', [
                'fields' => ['Categories.locale']
            ])
            ->add('search', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => [
                    'foreign_key',
                    'name',
                    'slug',
                    'description',
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
        'domain_id',
        'foreign_key',
        'lft',
        'rght',
        'name',
        'slug',
        'description',
        'background_image',
        'meta_description',
        'meta_keywords',
        'locale',
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
            ->allowEmptyString('foreign_key');

        $validator
            ->requirePresence('name', 'create')
            ->notBlank('name');

        $validator
            ->requirePresence('slug', 'create')
            ->notBlank('slug');

        $validator
            ->allowEmptyString('description');

        $validator
            ->allowEmptyString('background_image');

        $validator
            ->allowEmptyString('meta_description');

        $validator
            ->allowEmptyString('meta_keywords');

        $validator
            ->requirePresence('locale', 'create')
            ->notBlank('locale');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notBlank('status');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCategories'));
        $rules->add($rules->existsIn(['domain_id'], 'Domains'));

        return $rules;
    }
}
