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
use Cake\Utility\Hash;

// Get session object
$session = $this->getRequest()->getSession();

$backendButtonColor = 'light';
if (Configure::check('YabCmsFf.settings.backendButtonColor')):
    $backendButtonColor = Configure::read('YabCmsFf.settings.backendButtonColor');
endif;

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . $article->global_title
);
// Breadcrumb
$this->Breadcrumbs->addMany([
    [
        'title' => __d('yab_cms_ff', 'Go back'),
        'url' => 'javascript:history.back()',
    ],
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
            'controller'    => 'Articles',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($article->global_title)]
], ['class' => 'breadcrumb-item']); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($article->global_title); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Articles',
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
                                    'controller'    => 'Articles',
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
                    <dd class="col-sm-9"><?= h($article->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Parent article'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->parent_article->title)): ?>
                            <?= $article->has('parent_article')?
                                $this->Html->link(h($article->parent_article->title), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Articles',
                                    'action'        => 'view',
                                    'id'            => h($article->parent_article->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Type'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->article_type->title)): ?>
                            <?= $article->has('article_type')?
                                $this->Html->link(h($article->article_type->title), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypes',
                                    'action'        => 'view',
                                    'id'            => h($article->article_type->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Assigned') . ' ' . __d('yab_cms_ff', 'User'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->user->name)): ?>
                            <?= $article->has('user')?
                                $this->Html->link(h($article->user->name), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Users',
                                    'action'        => 'view',
                                    'id'            => h($article->user->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Domain'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->domain->name)): ?>
                            <?= $article->has('domain')?
                                $this->Html->link(h($article->domain->name), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Domains',
                                    'action'        => 'view',
                                    'id'            => h($article->domain->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Customer'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->customer->name)): ?>
                            <?= $article->has('customer')?
                                $this->Html->link(h($article->customer->name), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Customers',
                                    'action'        => 'view',
                                    'id'            => h($article->customer->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Left'); ?></dt>
                    <dd class="col-sm-9"><?= $this->Number->format($article->lft); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Right'); ?></dt>
                    <dd class="col-sm-9"><?= $this->Number->format($article->rght); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Locale'); ?></dt>
                    <dd class="col-sm-9"><?= empty($article->locale)? '-': h($article->locale); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Categories'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($article->categories)): ?>
                            <?php foreach($article->categories as $category): ?>
                                <?= $this->Html->link(h($category->name), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Categories',
                                    'action'        => 'view',
                                    'id'            => h($category->id),
                                ]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Promote') . ' ' . __d('yab_cms_ff', 'Start'); ?></dt>
                    <dd class="col-sm-9"><?= empty($article->promote_start)? '-': h($article->promote_start->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Promote') . ' ' . __d('yab_cms_ff', 'End'); ?></dt>
                    <dd class="col-sm-9"><?= empty($article->promote_end)? '-': h($article->promote_end->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Promote'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($article->promote)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Status'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($article->status)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($article->created)? '-': h($article->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($article->created_by)]: h($article->created_by); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($article->modified)? '-': h($article->modified->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($article->modified_by)]: h($article->modified_by); ?></dd>
                </dl>
                <hr/>
                <dl class="row">
                    <?php foreach ($article->article_type->article_type_attributes as $articleTypeAttribute): ?>
                        <?php $articleArticleTypeAttributeValue = Hash::extract(
                            $article->article_article_type_attribute_values,
                            '{n}' . '[article_type_attribute_id = ' . h($articleTypeAttribute->id) . ']' . '.' . 'value'); ?>
                        <dt class="col-sm-3"><?= h($articleTypeAttribute->title); ?></dt>
                        <dd class="col-sm-9"><?= empty($articleArticleTypeAttributeValue[0])? '-': $articleArticleTypeAttributeValue[0]; ?></dd>
                    <?php endforeach; ?>
                </dl>
                <hr/>
                <dl>
                    <dt><?= __d('yab_cms_ff', 'Type attributes'); ?></dt>
                    <dd>
                        <div class="row">
                            <div class="col-md-6">
                                <ol>
                                    <?php foreach ($article->article_type->article_type_attributes as $articleTypeAttribute): ?>
                                        <li><?= $this->Html->link(h($articleTypeAttribute->title_alias), [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'ArticleTypeAttributes',
                                            'action'        => 'view',
                                            'id'            => h($articleTypeAttribute->id),
                                        ]); ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                        </div>
                    </dd>
                </dl>
                <hr/>
                <dl>
                    <dd>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <?= __d('yab_cms_ff', 'REST API request') . ':' . ' ' . '/api/articles/' . h($article->id) . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
                                        <pre>
                                            <code class="language-php">
                                                <?php $json = json_encode(['success' => true, 'data' => $article]); ?>
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
                    $this->request->getSession()->read('Request.HTTP_REFERER'),
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Html->link(
                    $this->Html->icon('edit') . ' ' . __d('yab_cms_ff', 'Edit'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Articles',
                        'action'        => 'edit',
                        'id'            => h($article->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('copy') . ' ' . __d('yab_cms_ff', 'Copy'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Articles',
                        'action'        => 'copy',
                        'id'            => h($article->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
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