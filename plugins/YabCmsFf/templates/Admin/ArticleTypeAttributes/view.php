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
    . h($articleTypeAttribute->title)
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
            'controller'    => 'ArticleTypeAttributes',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($articleTypeAttribute->title)]
], ['class' => 'breadcrumb-item']); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($articleTypeAttribute->title); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypeAttributes',
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
                                    'controller'    => 'ArticleTypeAttributes',
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
                    <dd class="col-sm-9"><?= h($articleTypeAttribute->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'UUID'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->uuid_id)? '-': h($articleTypeAttribute->uuid_id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Foreign key'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->foreign_key)? '-': h($articleTypeAttribute->foreign_key); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Title'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->title)? '-': h($articleTypeAttribute->title); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Alias'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->alias)? '-': h($articleTypeAttribute->alias); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_1)? '-': $this->Html->link(h($articleTypeAttribute->link_1), h($articleTypeAttribute->link_1), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_2)? '-': $this->Html->link(h($articleTypeAttribute->link_2), h($articleTypeAttribute->link_2), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_3)? '-': $this->Html->link(h($articleTypeAttribute->link_3), h($articleTypeAttribute->link_3), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_4)? '-': $this->Html->link(h($articleTypeAttribute->link_4), h($articleTypeAttribute->link_4), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_5)? '-': $this->Html->link(h($articleTypeAttribute->link_5), h($articleTypeAttribute->link_5), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_6)? '-': $this->Html->link(h($articleTypeAttribute->link_6), h($articleTypeAttribute->link_6), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_7)? '-': $this->Html->link(h($articleTypeAttribute->link_7), h($articleTypeAttribute->link_7), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_8)? '-': $this->Html->link(h($articleTypeAttribute->link_8), h($articleTypeAttribute->link_8), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->link_9)? '-': $this->Html->link(h($articleTypeAttribute->link_9), h($articleTypeAttribute->link_9), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_1)? '-': $this->Html->image($articleTypeAttribute->image_1_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_2)? '-': $this->Html->image($articleTypeAttribute->image_2_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_3)? '-': $this->Html->image($articleTypeAttribute->image_3_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_4)? '-': $this->Html->image($articleTypeAttribute->image_4_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_5)? '-': $this->Html->image($articleTypeAttribute->image_5_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_6)? '-': $this->Html->image($articleTypeAttribute->image_6_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_7)? '-': $this->Html->image($articleTypeAttribute->image_7_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_8)? '-': $this->Html->image($articleTypeAttribute->image_8_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->image_9)? '-': $this->Html->image($articleTypeAttribute->image_9_file, ['width' => 25]) . ' ' . h($articleTypeAttribute->image_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_1)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_1_file . '"></video>' . ' ' . h($articleTypeAttribute->video_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_2)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_2_file . '"></video>' . ' ' . h($articleTypeAttribute->video_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_3)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_3_file . '"></video>' . ' ' . h($articleTypeAttribute->video_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_4)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_4_file . '"></video>' . ' ' . h($articleTypeAttribute->video_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_5)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_5_file . '"></video>' . ' ' . h($articleTypeAttribute->video_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_6)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_6_file . '"></video>' . ' ' . h($articleTypeAttribute->video_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_7)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_7_file . '"></video>' . ' ' . h($articleTypeAttribute->video_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_8)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_8_file . '"></video>' . ' ' . h($articleTypeAttribute->video_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->video_9)? '-': '<video width=25 controls><source src="' . $articleTypeAttribute->video_9_file . '"></video>' . ' ' . h($articleTypeAttribute->video_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_1)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_1_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_2)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_2_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_3)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_3_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_4)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_4_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_5)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_5_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_6)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_6_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_7)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_7_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_8)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_8_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->pdf_9)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_9_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttribute->pdf_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Empty value'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($articleTypeAttribute->empty_value)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'WYSIWYG'); ?></dt>
                    <dd class="col-sm-9"><?= $this->YabCmsFf->status(h($articleTypeAttribute->wysiwyg)); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->created)? '-': h($articleTypeAttribute->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($articleTypeAttribute->created_by)]: h($articleTypeAttribute->created_by); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttribute->modified)? '-': h($articleTypeAttribute->modified->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($articleTypeAttribute->modified_by)]: h($articleTypeAttribute->modified_by); ?></dd>
                </dl>
                <hr/>
                <dl>
                    <dt><?= __d('yab_cms_ff', 'Description'); ?></dt>
                    <dd><?= $this->Text->autoParagraph($articleTypeAttribute->description); ?></dd>
                </dl>
                <?php if (!empty($articleTypeAttribute->article_type_attribute_choices)): ?>
                    <hr />
                    <dl>
                        <dt><?= __d('yab_cms_ff', 'Type attribute choices'); ?></dt>
                        <dd>
                            <div class="row">
                                <div class="col-md-6">
                                    <ol>
                                        <?php foreach ($articleTypeAttribute->article_type_attribute_choices as $choice): ?>
                                            <li><?= $this->Html->link(h($choice->value), [
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'ArticleTypeAttributeChoices',
                                                'action'        => 'view',
                                                'id'            => h($choice->id),
                                            ]); ?></li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                        </dd>
                    </dl>
                    <hr/>
                <?php endif; ?>
                <?php if (!empty($articleTypeAttribute->article_types)): ?>
                    <dl>
                        <dt><?= __d('yab_cms_ff', 'Types'); ?></dt>
                        <dd>
                            <div class="row">
                                <div class="col-md-6">
                                    <ol>
                                        <?php foreach ($articleTypeAttribute->article_types as $type): ?>
                                            <li><?= $this->Html->link(h($type->title), [
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'ArticleTypes',
                                                'action'        => 'view',
                                                'id'            => h($type->id),
                                            ]); ?></li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                        </dd>
                    </dl>
                    <hr/>
                <?php endif; ?>
                <hr/>
                <dl>
                    <dd>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <?= __d('yab_cms_ff', 'REST API request') . ':' . ' ' . '/api/article-type-attributes/' . h($articleTypeAttribute->id) . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
                                        <pre>
                                            <code class="language-php">
                                                <?php $json = json_encode(['success' => true, 'data' => $articleTypeAttribute]); ?>
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
                        'controller'    => 'ArticleTypeAttributes',
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
                        'controller'    => 'ArticleTypeAttributes',
                        'action'        => 'edit',
                        'id'            => h($articleTypeAttribute->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]) ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'ArticleTypeAttributes',
                        'action'        => 'delete',
                        'id'            => h($articleTypeAttribute->id),
                    ],
                    [
                        'confirm' => __d(
                            'yab_cms_ff',
                            'Are you sure you want to delete "{title}"?',
                            ['title' => h($articleTypeAttribute->title)]
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