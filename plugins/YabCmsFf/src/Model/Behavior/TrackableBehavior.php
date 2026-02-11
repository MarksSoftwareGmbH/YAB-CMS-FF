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
namespace YabCmsFf\Model\Behavior;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Trackable behavior
 *
 * Class TrackableBehavior
 * @package YabCmsFf\Model\Behavior
 */
class TrackableBehavior extends Behavior
{

    /**
     * Default settings
     */
    protected $_defaults = [
        'userModel' => 'YabCmsFf.Users',
        'fields' => [
            'created_by' => 'created_by',
            'modified_by' => 'modified_by',
        ],
    ];

    /**
     * Constructor
     */
    public function __construct(Table $table, array $config = [])
    {
        $config = Hash::merge($this->_defaults, $config);
        parent::__construct($table, $config);
    }

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this behavior.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        if ($this->_hasTrackableFields()) {
            $this->_setupBelongsTo();
        }
    }

    /**
     * Checks wether model has the required fields
     *
     * @return bool True if $model has the required fields
     */
    protected function _hasTrackableFields()
    {
        $fields = $this->getConfig('fields');
        return
            $this->_table->hasField($fields['created_by']) &&
            $this->_table->hasField($fields['modified_by']);
    }

    /**
     * Bind relationship on the fly
     */
    protected function _setupBelongsTo()
    {
        if ($this->_table->associations()->has('TrackableCreator')) {
            return;
        }

        $config = $this->getConfig();
        $this->_table->addAssociations([
            'belongsTo' => [
                'TrackableCreator' => [
                    'className' => $config['userModel'],
                    'foreignKey' => $config['fields']['created_by'],
                ],
                'TrackableUpdater' => [
                    'className' => $config['userModel'],
                    'foreignKey' => $config['fields']['modified_by'],
                ],
            ],
        ]);
    }

    /**
     * Fill the created_by and updated_by fields
     *
     * Note: Since shells do not have Sessions, created_by/updated_by fields
     * will not be populated. If a shell needs to populate these fields, you
     * can simulate a logged in user by setting `Trackable.Auth` config:
     *
     *   Configure::write('Trackable.User', array('id' => 1));
     *
     * Note that value stored in this variable overrides session data.
     * 
     * @param \Cake\Event\EventInterface<\Cake\ORM\Table> $event The beforeSave event that was fired
     * @param \Cake\Datasource\EntityInterface $entity The entity that is going to be saved
     * @param \ArrayObject<string, mixed> $options The options for the query
     * @return $event
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (!$this->_hasTrackableFields()) {
            $event->setResult(true);
            return;
        }

        $config = $this->getConfig();
        $User = TableRegistry::getTableLocator()->get($config['userModel']);
        $userPk = $User->getPrimaryKey();

        $user = Configure::read('Trackable.Auth.User');
        if (!$user && session_status() === PHP_SESSION_ACTIVE) {
            $user = Hash::get($_SESSION, 'Auth.User');
        }

        $userId = null;
        if ($user && is_array($user) && array_key_exists($userPk, $user)) {
            $userId = $user[$userPk];
        }

        if (!$userId) {
            $event->setResult(true);
            return;
        }

        $createdByField = $config['fields']['created_by'];
        $modifiedByField = $config['fields']['modified_by'];

        if ($entity->isNew() && empty($entity->get($createdByField))) {
            $entity->set($createdByField, $userId);
        }
        $entity->set($modifiedByField, $userId);

        $event->setResult(true);
    }
}
