<?php

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
use Cake\Core\Configure;

// Get session object
$session = $this->getRequest()->getSession();

$backendButtonColor = 'light';
if (Configure::check('YabCmsFf.settings.backendButtonColor')):
    $backendButtonColor = Configure::read('YabCmsFf.settings.backendButtonColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . h($userProfile->full_name)
);
// Breadcrumb
$this->Breadcrumbs->add([
    [
        'title' => __d('yab_cms_ff', 'Dashboard'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Dashboards',
            'action'        => 'dashboard',
        ]
    ],
    [
        'title' => $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller')),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'UserProfiles',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($userProfile->full_name)]
]); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($userProfile->full_name); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'UserProfiles',
                            'action'        => 'index',
                        ],
                        'cols' => ['input' => 12],
                    ]); ?>
                    <?= $this->Form->control('search', [
                        'type'          => 'text',
                        'label'         => false,
                        'placeholder'   => __d('yab_cms_ff', 'Search') . '...',
                        'style'         => 'width: 150px;',
                        'append'        => $this->Form->button(
                                __d('yab_cms_ff', 'Filter'),
                                ['class' => 'btn btn-' . h($backendButtonColor)]
                            )
                            . ' '
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Reset'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'UserProfiles',
                                    'action'        => 'index',
                                ],
                                [
                                    'class'         => 'btn btn-' . h($backendButtonColor),
                                    'escapeTitle'   => false,
                                ]
                            ),
                    ]); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Id'); ?></dt>
                    <dd class="col-sm-9"><?= h($userProfile->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'User'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($userProfile->user->name_username_email)): ?>
                            <?= $userProfile->has('user')?
                                $this->Html->link(h($userProfile->user->name_username_email), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Users',
                                    'action'        => 'view',
                                    'id'            => h($userProfile->user->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Foreign key'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->foreign_key)? '-': h($userProfile->foreign_key); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Prefix'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->prefix)? '-': h($userProfile->prefix); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Salutation'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->salutation)? '-': h($userProfile->salutation); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Suffix'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->suffix)? '-': h($userProfile->suffix); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'First name'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->first_name)? '-': h($userProfile->first_name); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Middle name'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->middle_name)? '-': h($userProfile->middle_name); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Last name'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->last_name)? '-': h($userProfile->last_name); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Gender'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->gender)? '-': h($userProfile->gender); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Birthday'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->birthday)? '-': h($userProfile->birthday); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Website'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->website)? '-': h($userProfile->website); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Telephone'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->telephone)? '-': h($userProfile->telephone); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Mobilephone'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->mobilephone)? '-': h($userProfile->mobilephone); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Fax'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->fax)? '-': h($userProfile->fax); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Company'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->company)? '-': h($userProfile->company); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Street'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->street)? '-': h($userProfile->street); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Street addition'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->street_addition)? '-': h($userProfile->street_addition); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Postcode'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->postcode)? '-': h($userProfile->postcode); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'City'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->city)? '-': h($userProfile->city); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Region'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($userProfile->region->name)): ?>
                            <?= $userProfile->has('region')? h($userProfile->region->name): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Country'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($userProfile->country->name)): ?>
                            <?= $userProfile->has('country')? h($userProfile->country->name): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'About me'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->about_me)? '-': htmlspecialchars_decode($userProfile->about_me); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Tags'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->tags)? '-': htmlspecialchars_decode($userProfile->tags); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Timezone'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->timezone)? '-': h($userProfile->timezone); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->image)? '-': h($userProfile->image); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'View counter'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->view_counter)? '-': h($userProfile->view_counter); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Status'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($userProfile->status)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->created)? '-': h($userProfile->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($userProfile->modified)? '-': h($userProfile->modified->format('d.m.Y H:i:s')); ?></dd>
                </dl>
                <hr/>
                <?= $this->Html->link(
                    $this->Html->icon('list') . ' ' . __d('yab_cms_ff', 'Index'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'UserProfiles',
                        'action'        => 'index',
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Html->link(
                    $this->Html->icon('edit') . ' ' . __d('yab_cms_ff', 'Edit'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'UserProfiles',
                        'action'        => 'edit',
                        'id'            => h($userProfile->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'UserProfiles',
                        'action'        => 'delete',
                        'id'            => h($userProfile->id),
                    ],
                    [
                        'confirm' => __d(
                            'yab_cms_ff',
                            'Are you sure you want to delete "{fullName}"?',
                            ['fullName' => h($userProfile->full_name)]
                        ),
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
            </div>
        </div>
    </div>
</div>
