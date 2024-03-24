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
    . h($menuItem->title)
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
            'controller'    => 'MenuItems',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($menuItem->title)]
]); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($menuItem->title); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'MenuItems',
                            'action'        => 'index',
                        ],
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
                                    'controller'    => 'MenuItems',
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
                    <dd class="col-sm-9"><?= h($menuItem->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Domain'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($menuItem->domain->name)): ?>
                            <?= $menuItem->has('domain')?
                                $this->Html->link(h($menuItem->domain->name), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Domains',
                                    'action'        => 'view',
                                    'id'            => h($menuItem->domain->id)
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Menu'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($menuItem->menu->title)): ?>
                            <?= $menuItem->has('menu')?
                                $this->Html->link($menuItem->menu->title, [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Menus',
                                    'action'        => 'view',
                                    'id'            => h($menuItem->menu->id)
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Parent'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($menuItem->parent_menu_item->title)): ?>
                            <?= $menuItem->has('parent_menu_item')?
                                $this->Html->link($menuItem->parent_menu_item->title, [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'MenuItems',
                                    'action'        => 'view',
                                    'id'            => h($menuItem->parent_menu_item->id)
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Title'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->title); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Alias'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->alias); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Subtitle'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->sub_title); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->link); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link target'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->link_target); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link rel'); ?></dt>
                    <dd class="col-sm-9"><?= h($menuItem->link_rel); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Left'); ?></dt>
                    <dd class="col-sm-9"><?= empty($menuItem->lft)? '-': $this->Number->format($menuItem->lft); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Right'); ?></dt>
                    <dd class="col-sm-9"><?= empty($menuItem->rght)? '-': $this->Number->format($menuItem->rght); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Locale'); ?></dt>
                    <dd class="col-sm-9"><?= empty($menuItem->locale)? '-': h($menuItem->locale); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Status'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($menuItem->status)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($menuItem->created)? '-': h($menuItem->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($menuItem->modified)? '-': h($menuItem->modified->format('d.m.Y H:i:s')); ?></dd>
                </dl>
                <hr/>
                <dl>
                    <dt><?= __d('yab_cms_ff', 'Description'); ?></dt>
                    <dd><?= $this->Text->autoParagraph($menuItem->description); ?></dd>
                </dl>
                <hr/>
                <dl>
                    <dd>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <?= __d('yab_cms_ff', 'REST API request') . ':' . ' ' . '/api/menu-items/' . h($menuItem->id) . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
                                        <pre>
                                            <code class="language-php">
                                                <?php $json = json_encode(['success' => true, 'data' => $menuItem]); ?>
                                                <?= print_r(json_decode($json), true); ?>
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </dd>
                </dl>
                <hr/>
                <?= $this->Html->link(
                    $this->Html->icon('list') . ' ' . __d('yab_cms_ff', 'Index'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'MenuItems',
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
                        'controller'    => 'MenuItems',
                        'action'        => 'edit',
                        'id'            => h($menuItem->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
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
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'prism' . DS . 'prism.min'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'prism' . DS . 'prism.min',
    ['block' => 'scriptBottom']); ?>