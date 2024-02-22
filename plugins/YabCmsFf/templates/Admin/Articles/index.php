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

$session = $this->request->getSession();
if (str_contains($this->getRequest()->getRequestTarget(), '?')):
    $session->write('Request.HTTP_REFERER', [
        'plugin'        => 'YabCmsFf',
        'controller'    => 'Articles',
        'action'        => 'index',
        '?'             => $this->getRequest()->getQuery(),
    ]);
else:
    $session->write('Request.HTTP_REFERER', [
        'plugin'        => 'YabCmsFf',
        'controller'    => 'Articles',
        'action'        => 'index',
    ]);
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
                        'controller'    => 'Articles',
                        'action'        => 'index',
                        '?'             => $this->getRequest()->getQuery(),
                    ],
                ]); ?>
                <?= $this->Form->control('search', [
                    'type'          => 'text',
                    'value'         => $this->getRequest()->getQuery('search'),
                    'label'         => false,
                    'placeholder'   => __d('yab_cms_ff', 'Search') . '...',
                    'prepend'       => $this->element('Articles' . DS . 'add_select_article_type', ['articleTypes' => !empty($articleTypes)? $articleTypes: []])
                        . $this->element('Articles' . DS . 'add_search_domain', ['domains' => !empty($domains)? $domains: []])
                        . $this->element('Articles' . DS . 'add_search_locale', ['locales' => !empty($locales)? $locales: []])
                        . $this->element('Articles' . DS . 'add_search_article_type', ['articleTypes' => !empty($articleTypes)? $articleTypes: []]),
                    'append' => $this->Form->button(
                            __d('yab_cms_ff', 'Filter'),
                            ['class' => 'btn btn-' . h($backendButtonColor)]
                        )
                        . ' '
                        . $this->Html->link(
                            __d('yab_cms_ff', 'Reset'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Articles',
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
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('Domains.name', __d('yab_cms_ff', 'Domain')); ?></th>
                        <th><?= $this->Paginator->sort('Customers.name', __d('yab_cms_ff', 'Customer')); ?></th>
                        <th><?= $this->Paginator->sort('ArticleTypes.title', __d('yab_cms_ff', 'Type')); ?></th>
                        <th><?= __d('yab_cms_ff', 'Title'); ?></th>
                        <th><?= __d('yab_cms_ff', 'Slug'); ?></th>
                        <th><?= $this->Paginator->sort('locale', __d('yab_cms_ff', 'Locale')); ?></th>
                        <th><?= $this->Paginator->sort('promote', __d('yab_cms_ff', 'Promote')); ?></th>
                        <th><?= $this->Paginator->sort('status', __d('yab_cms_ff', 'Status')); ?></th>
                        <th class="actions"><?= __d('yab_cms_ff', 'Actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr id="<?= h($article->id); ?>" data-lft="<?= h($article->lft); ?>">
                            <td>
                                <?php if ($article->has('domain')): ?>
                                    <?php if (isset($article->domain->name) && !empty($article->domain->name)): ?>
                                        <?= h($article->domain->name); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($article->has('customer')): ?>
                                    <?php if (isset($article->customer->name) && !empty($article->customer->name)): ?>
                                        <?= h($article->customer->name); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><?= h($article->article_type->title); ?></td>
                            <td><?= h($article->global_title); ?></td>
                            <td><?= h($article->global_slug); ?></td>
                            <td><?= h($article->locale); ?></td>
                            <td><?= $this->YabCmsFf->status(h($article->promote)); ?></td>
                            <td><?= $this->YabCmsFf->status(h($article->status)); ?></td>
                            <td class="actions">
                                <?= $this->Html->link(
                                    $this->Html->icon('eye'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Articles',
                                        'action'        => 'view',
                                        'id'            => h($article->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'View'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->icon('edit'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Articles',
                                        'action'        => 'edit',
                                        'id'            => h($article->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'Edit'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?= $this->Form->postLink(
                                    $this->Html->icon('copy'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Articles',
                                        'action'        => 'copy',
                                        'id'            => h($article->id),
                                    ],
                                    [
                                        'title'         => __d('yab_cms_ff', 'Copy'),
                                        'data-toggle'   => 'tooltip',
                                        'escape'        => false,
                                    ]); ?>
                                <?= $this->Form->postLink(
                                    $this->Html->icon('trash'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Articles',
                                        'action'        => 'delete',
                                        'id'            => h($article->id),
                                    ],
                                    [
                                        'confirm' => __d(
                                            'yab_cms_ff',
                                            'Are you sure you want to delete "{globalTitle}"?',
                                            ['globalTitle' => h($article->global_title)]
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
