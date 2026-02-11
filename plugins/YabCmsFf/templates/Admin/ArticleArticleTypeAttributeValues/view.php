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

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . $this->Text->truncate(h($articleArticleTypeAttributeValue->value), 35, ['ellipsis' => '...', 'exact' => false])
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
            'controller'    => 'ArticleArticleTypeAttributeValues',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => $this->Text->truncate(h($articleArticleTypeAttributeValue->value), 35, ['ellipsis' => '...', 'exact' => false])]
], ['class' => 'breadcrumb-item']); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Text->truncate(
                        h($articleArticleTypeAttributeValue->value),
                        35,
                        ['ellipsis' => '...', 'exact' => false]
                    ); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleArticleTypeAttributeValues',
                            'action'        => 'index',
                        ],
                    ]); ?>
                    <?= $this->Form->control('search', [
                        'type'          => 'text',
                        'label'         => false,
                        'placeholder'   => __d('yab_cms_ff', 'Search') . '...',
                        'style'         => 'width: 150px;',
                        'append' => $this->Form->button(
                                __d('yab_cms_ff', 'Filter'),
                                ['class' => 'btn btn-' . h($backendButtonColor)]
                            )
                            . ' '
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Reset'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleArticleTypeAttributeValues',
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
                    <dd class="col-sm-9"><?= h($articleArticleTypeAttributeValue->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Title'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($articleArticleTypeAttributeValue->article->global_title)): ?>
                            <?= $articleArticleTypeAttributeValue->has('article')?
                                $this->Html->link(h($articleArticleTypeAttributeValue->article->global_title), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Articles',
                                    'action'        => 'view',
                                    'id'            => h($articleArticleTypeAttributeValue->article->id)
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Type option'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($articleArticleTypeAttributeValue->article->global_title)): ?>
                            <?= $articleArticleTypeAttributeValue->has('article_type_attribute')?
                                $this->Html->link(h($articleArticleTypeAttributeValue->article_type_attribute->alias), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypeAttributes',
                                    'action'        => 'view',
                                    'id'            => h($articleArticleTypeAttributeValue->article_type_attribute->id)
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Value'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleArticleTypeAttributeValue->value)? '-': h($articleArticleTypeAttributeValue->value); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleArticleTypeAttributeValue->created)? '-': h($articleArticleTypeAttributeValue->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($articleArticleTypeAttributeValue->created_by)]: h($articleArticleTypeAttributeValue->created_by); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleArticleTypeAttributeValue->modified)? '-': h($articleArticleTypeAttributeValue->modified->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($articleArticleTypeAttributeValue->modified_by)]: h($articleArticleTypeAttributeValue->modified_by); ?></dd>
                </dl>
                <hr/>
                <dl>
                    <dd>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <?= __d('yab_cms_ff', 'REST API request') . ':' . ' ' . '/api/article-article-type-attribute-values/' . h($articleArticleTypeAttributeValue->id) . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
                                        <pre>
                                            <code class="language-php">
                                                <?php $json = json_encode(['success' => true, 'data' => $articleArticleTypeAttributeValue]); ?>
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
                        'controller'    => 'ArticleArticleTypeAttributeValues',
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
                        'controller'    => 'ArticleArticleTypeAttributeValues',
                        'action'        => 'edit',
                        'id'            => h($articleArticleTypeAttributeValue->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'ArticleArticleTypeAttributeValues',
                        'action'        => 'delete',
                        'id'            => h($articleArticleTypeAttributeValue->id),
                    ],
                    [
                        'confirm' => __d(
                            'yab_cms_ff',
                            'Are you sure, you want to delete "{value}"?',
                            ['value' => $this->Text->truncate(
                                h($articleArticleTypeAttributeValue->value),
                                35,
                                ['ellipsis' => '...', 'exact' => false]
                            )]),
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