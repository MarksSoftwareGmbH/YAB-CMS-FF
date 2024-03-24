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
 * UserProfileTimelineEntries Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry get($primaryKey, $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry newEntity($data = null, array $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry[] newEntities(array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry[] patchEntities($entities, array $data, array $options = [])
 * @method \YabCmsFf\Model\Entity\UserProfileTimelineEntry findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserProfileTimelineEntriesTable extends Table
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

        $this->setTable('user_profile_timeline_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search');
        $this->addBehavior('YabCmsFf.Trackable');
        $this->addBehavior('YabCmsFf.Deletable');
        $this->addBehavior('YabCmsFf.Datetime');

        $this->belongsTo('Users', [
            'foreignKey'    => 'user_id',
            'joinType'      => 'INNER',
            'className'     => 'YabCmsFf.Users',
        ]);

        // Setup search filter using search manager
        $this->searchManager()
            ->add('search', 'Search.Like', [
                'before'    => true,
                'after'     => true,
                'fieldMode'     => 'OR',
                'comparison'    => 'LIKE',
                'wildcardAny'   => '*',
                'wildcardOne'   => '?',
                'colType' => [
                    'entry_no' => 'string',
                    'entry_ref_no' => 'string',
                ],
                'fields' => [
                    'foreign_key',
                    'entry_no',
                    'entry_ref_no',
                    'entry_type',
                    'entry_title',
                    'entry_subtitle',
                    'entry_body',
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
        'user_id',
        'foreign_key',
        'entry_no',
        'entry_ref_no',
        'entry_date',
        'entry_type',
        'entry_title',
        'entry_subtitle',
        'entry_body',
        'entry_link_1',
        'entry_link_2',
        'entry_link_3',
        'entry_link_4',
        'entry_link_5',
        'entry_link_6',
        'entry_link_7',
        'entry_link_8',
        'entry_link_9',
        'entry_image_1',
        'entry_image_1_file',
        'entry_image_2',
        'entry_image_2_file',
        'entry_image_3',
        'entry_image_3_file',
        'entry_image_4',
        'entry_image_4_file',
        'entry_image_5',
        'entry_image_5_file',
        'entry_image_6',
        'entry_image_6_file',
        'entry_image_7',
        'entry_image_7_file',
        'entry_image_8',
        'entry_image_8_file',
        'entry_image_9',
        'entry_image_9_file',
        'entry_video_1',
        'entry_video_1_file',
        'entry_video_2',
        'entry_video_2_file',
        'entry_video_3',
        'entry_video_3_file',
        'entry_video_4',
        'entry_video_4_file',
        'entry_video_5',
        'entry_video_5_file',
        'entry_video_6',
        'entry_video_6_file',
        'entry_video_7',
        'entry_video_7_file',
        'entry_video_8',
        'entry_video_8_file',
        'entry_video_9',
        'entry_video_9_file',
        'entry_pdf_1',
        'entry_pdf_1_file',
        'entry_pdf_2',
        'entry_pdf_2_file',
        'entry_pdf_3',
        'entry_pdf_3_file',
        'entry_pdf_4',
        'entry_pdf_4_file',
        'entry_pdf_5',
        'entry_pdf_5_file',
        'entry_pdf_6',
        'entry_pdf_6_file',
        'entry_pdf_7',
        'entry_pdf_7_file',
        'entry_pdf_8',
        'entry_pdf_8_file',
        'entry_pdf_9',
        'entry_pdf_9_file',
        'entry_guitar_pro',
        'entry_guitar_pro_file',
        'view_counter',
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
            ->scalar('foreign_key')
            ->maxLength('foreign_key', 255)
            ->allowEmptyString('foreign_key');

        $validator
            ->integer('entry_no')
            ->allowEmptyString('entry_no');

        $validator
            ->integer('entry_ref_no')
            ->allowEmptyString('entry_ref_no');

        $validator
            ->dateTime('entry_date')
            ->allowEmptyDateTime('entry_date');

        $validator
            ->scalar('entry_type')
            ->maxLength('entry_type', 255)
            ->allowEmptyString('entry_type');

        $validator
            ->scalar('entry_title')
            ->maxLength('entry_title', 255)
            ->allowEmptyString('entry_title');

        $validator
            ->scalar('entry_subtitle')
            ->maxLength('entry_subtitle', 255)
            ->allowEmptyString('entry_subtitle');

        $validator
            ->allowEmptyString('entry_body');

        $validator
            ->allowEmptyString('entry_link_1');

        $validator
            ->allowEmptyString('entry_link_2');

        $validator
            ->allowEmptyString('entry_link_3');

        $validator
            ->allowEmptyString('entry_link_4');

        $validator
            ->allowEmptyString('entry_link_5');

        $validator
            ->allowEmptyString('entry_link_6');

        $validator
            ->allowEmptyString('entry_link_7');

        $validator
            ->allowEmptyString('entry_link_8');

        $validator
            ->allowEmptyString('entry_link_9');

        $validator
            ->allowEmptyString('entry_image_1');

        $validator
            ->allowEmptyString('entry_image_1_file');

        $validator
            ->allowEmptyString('entry_image_2');

        $validator
            ->allowEmptyString('entry_image_2_file');

        $validator
            ->allowEmptyString('entry_image_3');

        $validator
            ->allowEmptyString('entry_image_3_file');

        $validator
            ->allowEmptyString('entry_image_4');

        $validator
            ->allowEmptyString('entry_image_4_file');

        $validator
            ->allowEmptyString('entry_image_5');

        $validator
            ->allowEmptyString('entry_image_5_file');

        $validator
            ->allowEmptyString('entry_image_6');

        $validator
            ->allowEmptyString('entry_image_6_file');

        $validator
            ->allowEmptyString('entry_image_7');

        $validator
            ->allowEmptyString('entry_image_7_file');

        $validator
            ->allowEmptyString('entry_image_8');

        $validator
            ->allowEmptyString('entry_image_8_file');

        $validator
            ->allowEmptyString('entry_image_9');

        $validator
            ->allowEmptyString('entry_image_9_file');

        $validator
            ->allowEmptyString('entry_video_1');

        $validator
            ->allowEmptyString('entry_video_1_file');

        $validator
            ->allowEmptyString('entry_video_2');

        $validator
            ->allowEmptyString('entry_video_2_file');

        $validator
            ->allowEmptyString('entry_video_3');

        $validator
            ->allowEmptyString('entry_video_3_file');

        $validator
            ->allowEmptyString('entry_video_4');

        $validator
            ->allowEmptyString('entry_video_4_file');

        $validator
            ->allowEmptyString('entry_video_5');

        $validator
            ->allowEmptyString('entry_video_5_file');

        $validator
            ->allowEmptyString('entry_video_6');

        $validator
            ->allowEmptyString('entry_video_6_file');

        $validator
            ->allowEmptyString('entry_video_7');

        $validator
            ->allowEmptyString('entry_video_7_file');

        $validator
            ->allowEmptyString('entry_video_8');

        $validator
            ->allowEmptyString('entry_video_8_file');

        $validator
            ->allowEmptyString('entry_video_9');

        $validator
            ->allowEmptyString('entry_video_9_file');

        $validator
            ->allowEmptyString('entry_pdf_1');

        $validator
            ->allowEmptyString('entry_pdf_1_file');

        $validator
            ->allowEmptyString('entry_pdf_2');

        $validator
            ->allowEmptyString('entry_pdf_2_file');

        $validator
            ->allowEmptyString('entry_pdf_3');

        $validator
            ->allowEmptyString('entry_pdf_3_file');

        $validator
            ->allowEmptyString('entry_pdf_4');

        $validator
            ->allowEmptyString('entry_pdf_4_file');

        $validator
            ->allowEmptyString('entry_pdf_5');

        $validator
            ->allowEmptyString('entry_pdf_5_file');

        $validator
            ->allowEmptyString('entry_pdf_6');

        $validator
            ->allowEmptyString('entry_pdf_6_file');

        $validator
            ->allowEmptyString('entry_pdf_7');

        $validator
            ->allowEmptyString('entry_pdf_7_file');

        $validator
            ->allowEmptyString('entry_pdf_8');

        $validator
            ->allowEmptyString('entry_pdf_8_file');

        $validator
            ->allowEmptyString('entry_pdf_9');

        $validator
            ->allowEmptyString('entry_pdf_9_file');

        $validator
            ->allowEmptyString('entry_guitar_pro');

        $validator
            ->allowEmptyString('entry_guitar_pro_file');

        $validator
            ->integer('view_counter')
            ->requirePresence('view_counter', 'create')
            ->notBlank('view_counter');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
