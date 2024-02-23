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
                        'controller'    => 'MenuItems',
                        'action'        => 'index',
                    ],
                ]); ?>
                <?= $this->Form->control('search', [
                    'type' => 'text',
                    'value' => $this->getRequest()->getQuery('search'),
                    'label' => false,
                    'placeholder' => __d('yab_cms_ff', 'Search') . '...',
                    'prepend' => $this->element('MenuItems' . DS . 'add_menu_item')
                        . $this->element('MenuItems' . DS . 'add_search_domain', ['domains' => !empty($domains)? $domains: []])
                        . $this->element('MenuItems' . DS . 'add_search_locale', ['locales' => !empty($locales)? $locales: []])
                        . $this->element('MenuItems' . DS . 'add_search_menu', ['menus' => !empty($menus)? $menus: []]),
                    'append' => $this->Form->button(
                            __d('yab_cms_ff', 'Filter'),
                            ['class' => 'btn btn-' . h($backendButtonColor)]
                        )
                        . ' '
                        . $this->Html->link(
                            __d('yab_cms_ff', 'Reset'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'MenuItems',
                                'action'        => 'index',
                            ],
                            [
                                'class'         => 'btn btn-' . h($backendButtonColor),
                                'escapeTitle'   => false,
                            ]
                        )
                        . ' '
                        . $this->Html->link(
                            $this->Html->icon('upload') . ' ' . __d('yab_cms_ff', 'CSV'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'MenuItems',
                                'action'        => 'import',
                            ],
                            [
                                'class'         => 'btn btn-' . h($backendButtonColor),
                                'escapeTitle'   => false,
                                'title'         => __d('yab_cms_ff', 'Upload & import CSV'),
                            ]
                        )
                        . ' '
                        . $this->Html->link(
                            $this->Html->icon('download') . ' ' . __d('yab_cms_ff', 'CSV'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'MenuItems',
                                'action'        => 'export',
                                '_ext'          => 'csv',
                            ],
                            [
                                'class'         => 'btn btn-' . h($backendButtonColor),
                                'escapeTitle'   => false,
                                'title'         => __d('yab_cms_ff', 'Export & download CSV'),
                            ]
                        ),
                ]); ?>
                <?= $this->Form->end(); ?>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('Domains.name', __d('yab_cms_ff', 'Domain')); ?></th>
                        <th><?= $this->Paginator->sort('Menu.title', __d('yab_cms_ff', 'Menu')); ?></th>
                        <th><?= $this->Paginator->sort('parent_id', __d('yab_cms_ff', 'Parent')); ?></th>
                        <th><?= $this->Paginator->sort('title', __d('yab_cms_ff', 'Title')); ?></th>
                        <th><?= $this->Paginator->sort('alias', __d('yab_cms_ff', 'Alias')); ?></th>
                        <th><?= $this->Paginator->sort('sub_title', __d('yab_cms_ff', 'Subtitle')); ?></th>
                        <th><?= $this->Paginator->sort('link', __d('yab_cms_ff', 'Link')); ?></th>
                        <th><?= $this->Paginator->sort('lft', __d('yab_cms_ff', 'Left')); ?></th>
                        <th><?= $this->Paginator->sort('rght', __d('yab_cms_ff', 'Right')); ?></th>
                        <th><?= $this->Paginator->sort('locale', __d('yab_cms_ff', 'Locale')); ?></th>
                        <th><?= $this->Paginator->sort('status', __d('yab_cms_ff', 'Status')); ?></th>
                        <th class="actions"><?= __d('yab_cms_ff', 'Actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($menuItems as $menuItem): ?>
                        <tr id="<?= h($menuItem->id); ?>" data-lft="<?= h($menuItem->lft); ?>">
                            <td>
                                <?php if (!empty($menuItem->domain->name)): ?>
                                    <?= $menuItem->has('domain')? h($menuItem->domain->name): '-'; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($menuItem->menu->title)): ?>
                                    <?= $menuItem->has('menu')? h($menuItem->menu->title): '-'; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($menuItem->parent_menu_item->title)): ?>
                                    <?= $menuItem->has('parent_menu_item')? h($menuItem->parent_menu_item->title): '-'; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= h($menuItem->title); ?></td>
                            <td><?= h($menuItem->alias); ?></td>
                            <td><?= h($menuItem->sub_title); ?></td>
                            <td><?= h($menuItem->link); ?></td>
                            <td><?= $this->Number->format($menuItem->lft); ?></td>
                            <td><?= $this->Number->format($menuItem->rght); ?></td>
                            <td><?= h($menuItem->locale); ?></td>
                            <td><?= $this->YabCmsFf->status(h($menuItem->status)); ?></td>
                            <td class="actions">
                                <?= $this->Html->link(
                                    $this->Html->icon('eye'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'MenuItems',
                                        'action'        => 'view',
                                        'id'            => h($menuItem->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'View'),
                                        'data-toggle'   => 'tooltip',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->icon('edit'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'MenuItems',
                                        'action'        => 'edit',
                                        'id'            => h($menuItem->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'Edit'),
                                        'data-toggle'   => 'tooltip',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <?= $this->Form->postLink(
                                    $this->Html->icon('trash'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'MenuItems',
                                        'action'        => 'delete',
                                        'id'            => h($menuItem->id),
                                    ],
                                    [
                                        'confirm' => __d(
                                            'yab_cms_ff',
                                            'Are you sure you want to delete "{title}"?',
                                            ['title' => h($menuItem->title)]
                                        ),
                                        'title'         => __d('yab_cms_ff', 'Delete'),
                                        'data-toggle'   => 'tooltip',
                                        'escapeTitle'   => false,
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
