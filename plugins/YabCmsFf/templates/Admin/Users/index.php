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
    ['title' => $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))]
]); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?= $this->Form->create(null, [
                    'url' => [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Users',
                        'action'        => 'index',
                    ],
                ]); ?>
                <?= $this->Form->control('search', [
                    'type'          => 'text',
                    'value'         => $this->getRequest()->getQuery('search'),
                    'label'         => false,
                    'placeholder'   => __d('yab_cms_ff', 'Search') . '...',
                    'prepend'       => $this->element('Users' . DS . 'add_user')
                        . $this->element('Users' . DS . 'add_search_role', ['roles' => !empty($roles)? $roles: []])
                        . $this->element('Users' . DS . 'add_search_locale', ['locales' => !empty($locales)? $locales: []]),
                    'append' => $this->Form->button(
                            __d('yab_cms_ff', 'Filter'),
                            ['class' => 'btn btn-' . h($backendButtonColor)]
                        )
                        . ' '
                        . $this->Html->link(
                            __d('yab_cms_ff', 'Reset'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Users',
                                'action'        => 'index',
                            ],
                            [
                                'class'     => 'btn btn-' . h($backendButtonColor),
                                'escape'    => false,
                            ]
                        ),
                ]); ?>
                <?= $this->Form->end(); ?>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-hover table-data">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('Roles.title', __d('yab_cms_ff', 'Role')); ?></th>
                        <th><?= $this->Paginator->sort('Locales.name', __d('yab_cms_ff', 'Locale')); ?></th>
                        <th><?= $this->Paginator->sort('username', __d('yab_cms_ff', 'Username')); ?></th>
                        <th><?= $this->Paginator->sort('name', __d('yab_cms_ff', 'Name')); ?></th>
                        <th><?= $this->Paginator->sort('email', __d('yab_cms_ff', 'Email')); ?></th>
                        <th><?= $this->Paginator->sort('status', __d('yab_cms_ff', 'Status')); ?></th>
                        <th class="actions"><?= __d('yab_cms_ff', 'Actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= h($user->role->title); ?></td>
                            <td>
                                <?php if (!empty($user->locale->name)): ?>
                                    <?= $user->has('locale')? h($user->locale->name): '-'; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= h($user->username); ?></td>
                            <td><?= h($user->name); ?></td>
                            <td><?= h($user->email); ?></td>
                            <td><?= $this->YabCmsFf->status(h($user->status)); ?></td>
                            <td class="actions">
                                <?= $this->Html->link(
                                    $this->Html->icon('eye'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Users',
                                        'action'        => 'view',
                                        'id'            => h($user->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'View'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?=  $this->Html->link(
                                    $this->Html->icon('lock'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Users',
                                        'action'        => 'resetPassword',
                                        'id'            => h($user->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'Reset password'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->icon('edit'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Users',
                                        'action'        => 'edit',
                                        'id'            => h($user->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'Edit'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?= $this->Form->postLink(
                                    $this->Html->icon('trash'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Users',
                                        'action'        => 'delete',
                                        'id'            => h($user->id),
                                    ],
                                    [
                                        'confirm' => __d(
                                            'yab_cms_ff',
                                            'Are you sure you want to delete "{name}"?',
                                            ['name' => h($user->name)]
                                        ),
                                        'title'         => __d('yab_cms_ff', 'Delete'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->element('paginator'); ?>

        </div>
    </div>
</div>

<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'default',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        Default.init();
    });',
    ['block' => 'scriptBottom']); ?>
