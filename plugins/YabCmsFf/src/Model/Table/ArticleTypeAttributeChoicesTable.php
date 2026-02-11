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
 * ArticleTypeAttributeChoices Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ArticleTypeAttributes
 */
class ArticleTypeAttributeChoicesTable extends Table
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

        $this->setTable('article_type_attribute_choices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search');
        $this->addBehavior('YabCmsFf.Trackable');
        $this->addBehavior('YabCmsFf.Deletable');

        $this->belongsTo('ArticleTypeAttributes', [
            'foreignKey' => 'article_type_attribute_id',
            'joinType' => 'INNER',
            'className' => 'YabCmsFf.ArticleTypeAttributes'
        ]);

        // Setup search filter using search manager
        $this->getBehavior('Search')->searchManager()
            ->add('search', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => [
                    'foreign_key',
                    'value',
                    'link_1',
                    'link_2',
                    'link_3',
                    'link_4',
                    'link_5',
                    'link_6',
                    'link_7',
                    'link_8',
                    'link_9',
                    'image_1',
                    'image_2',
                    'image_3',
                    'image_4',
                    'image_5',
                    'image_6',
                    'image_7',
                    'image_8',
                    'image_9',
                    'video_1',
                    'video_2',
                    'video_3',
                    'video_4',
                    'video_5',
                    'video_6',
                    'video_7',
                    'video_8',
                    'video_9',
                    'pdf_1',
                    'pdf_2',
                    'pdf_3',
                    'pdf_4',
                    'pdf_5',
                    'pdf_6',
                    'pdf_7',
                    'pdf_8',
                    'pdf_9',
                    'ArticleTypeAttributes.title',
                    'ArticleTypeAttributes.alias',
                    'ArticleTypeAttributes.type',
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
        'article_type_attribute_id',
        'foreign_key',
        'value',
        'link_1',
        'link_2',
        'link_3',
        'link_4',
        'link_5',
        'link_6',
        'link_7',
        'link_8',
        'link_9',
        'image_1',
        'image_1_file',
        'image_2',
        'image_2_file',
        'image_3',
        'image_3_file',
        'image_4',
        'image_4_file',
        'image_5',
        'image_5_file',
        'image_6',
        'image_6_file',
        'image_7',
        'image_7_file',
        'image_8',
        'image_8_file',
        'image_9',
        'image_9_file',
        'video_1',
        'video_1_file',
        'video_2',
        'video_2_file',
        'video_3',
        'video_3_file',
        'video_4',
        'video_4_file',
        'video_5',
        'video_5_file',
        'video_6',
        'video_6_file',
        'video_7',
        'video_7_file',
        'video_8',
        'video_8_file',
        'video_9',
        'video_9_file',
        'pdf_1',
        'pdf_1_file',
        'pdf_2',
        'pdf_2_file',
        'pdf_3',
        'pdf_3_file',
        'pdf_4',
        'pdf_4_file',
        'pdf_5',
        'pdf_5_file',
        'pdf_6',
        'pdf_6_file',
        'pdf_7',
        'pdf_7_file',
        'pdf_8',
        'pdf_8_file',
        'pdf_9',
        'pdf_9_file',
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
            ->requirePresence('value', 'create')
            ->notBlank('value');

        $validator
            ->allowEmptyString('link_1');

        $validator
            ->allowEmptyString('link_2');

        $validator
            ->allowEmptyString('link_3');

        $validator
            ->allowEmptyString('link_4');

        $validator
            ->allowEmptyString('link_5');

        $validator
            ->allowEmptyString('link_6');

        $validator
            ->allowEmptyString('link_7');

        $validator
            ->allowEmptyString('link_8');

        $validator
            ->allowEmptyString('link_9');

        $validator
            ->allowEmptyString('image_1');

        $validator
            ->allowEmptyString('image_1_file');

        $validator
            ->allowEmptyString('image_2');

        $validator
            ->allowEmptyString('image_2_file');

        $validator
            ->allowEmptyString('image_3');

        $validator
            ->allowEmptyString('image_3_file');

        $validator
            ->allowEmptyString('image_4');

        $validator
            ->allowEmptyString('image_4_file');

        $validator
            ->allowEmptyString('image_5');

        $validator
            ->allowEmptyString('image_5_file');

        $validator
            ->allowEmptyString('image_6');

        $validator
            ->allowEmptyString('image_6_file');

        $validator
            ->allowEmptyString('image_7');

        $validator
            ->allowEmptyString('image_7_file');

        $validator
            ->allowEmptyString('image_8');

        $validator
            ->allowEmptyString('image_8_file');

        $validator
            ->allowEmptyString('image_9');

        $validator
            ->allowEmptyString('image_9_file');

        $validator
            ->allowEmptyString('video_1');

        $validator
            ->allowEmptyString('video_1_file');

        $validator
            ->allowEmptyString('video_2');

        $validator
            ->allowEmptyString('video_2_file');

        $validator
            ->allowEmptyString('video_3');

        $validator
            ->allowEmptyString('video_3_file');

        $validator
            ->allowEmptyString('video_4');

        $validator
            ->allowEmptyString('video_4_file');

        $validator
            ->allowEmptyString('video_5');

        $validator
            ->allowEmptyString('video_5_file');

        $validator
            ->allowEmptyString('video_6');

        $validator
            ->allowEmptyString('video_6_file');

        $validator
            ->allowEmptyString('video_7');

        $validator
            ->allowEmptyString('video_7_file');

        $validator
            ->allowEmptyString('video_8');

        $validator
            ->allowEmptyString('video_8_file');

        $validator
            ->allowEmptyString('video_9');

        $validator
            ->allowEmptyString('video_9_file');

        $validator
            ->allowEmptyString('pdf_1');

        $validator
            ->allowEmptyString('pdf_1_file');

        $validator
            ->allowEmptyString('pdf_2');

        $validator
            ->allowEmptyString('pdf_2_file');

        $validator
            ->allowEmptyString('pdf_3');

        $validator
            ->allowEmptyString('pdf_3_file');

        $validator
            ->allowEmptyString('pdf_4');

        $validator
            ->allowEmptyString('pdf_4_file');

        $validator
            ->allowEmptyString('pdf_5');

        $validator
            ->allowEmptyString('pdf_5_file');

        $validator
            ->allowEmptyString('pdf_6');

        $validator
            ->allowEmptyString('pdf_6_file');

        $validator
            ->allowEmptyString('pdf_7');

        $validator
            ->allowEmptyString('pdf_7_file');

        $validator
            ->allowEmptyString('pdf_8');

        $validator
            ->allowEmptyString('pdf_8_file');

        $validator
            ->allowEmptyString('pdf_9');

        $validator
            ->allowEmptyString('pdf_9_file');

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
        $rules->add($rules->existsIn(['article_type_attribute_id'], 'ArticleTypeAttributes'));

        return $rules;
    }
}
