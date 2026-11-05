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
 * Automatically sets created_by and modified_by fields based on the current user.
 * Associations are added lazily in beforeSave to avoid early schema reflection
 * issues (especially under PHP 8.4 with CakePHP 5.3.2 schema bugs).
 *
 * Class TrackableBehavior
 * @package YabCmsFf\Model\Behavior
 */
class TrackableBehavior extends Behavior
{
    /**
     * Default settings
     */
    protected array $_defaults = [
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
     * No longer set up associations or check fields here.
     * We do it lazily in beforeSave() to prevent early schema introspection.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        // Nothing else here — avoids hasField() → Column object issues on PHP 8.4
    }

    /**
     * Fill the created_by and modified_by fields.
     *
     * Associations are added on-the-fly only if needed.
     * We use $entity->has() instead of $table->hasField() to prevent schema reflection.
     *
     * @param \Cake\Event\EventInterface $event The beforeSave event
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved
     * @param \ArrayObject $options Save options
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $config = $this->getConfig();
        $fields = $config['fields'];

        // Lazily add the belongsTo associations if not already present
        if (!$this->_table->associations()->has('TrackableCreator')) {
            $this->_setupBelongsTo();
        }

        // ────────────────────────────────────────────────
        // Determine current user ID
        // ────────────────────────────────────────────────
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

        // No valid user → skip (no error, just don't set the fields)
        if ($userId === null) {
            return;
        }

        // ────────────────────────────────────────────────
        // Apply the values — only if the field exists on this entity
        // ────────────────────────────────────────────────
        $createdByField = $fields['created_by'];
        $modifiedByField = $fields['modified_by'];

        if ($entity->isNew() && $entity->has($createdByField)) {
            $entity->set($createdByField, $userId);
        }

        if ($entity->has($modifiedByField)) {
            $entity->set($modifiedByField, $userId);
        }
    }

    /**
     * Bind belongsTo relationships on the fly (only called when needed)
     */
    protected function _setupBelongsTo(): void
    {
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
}