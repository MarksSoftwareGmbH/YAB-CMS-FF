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
    . h($articleTypeAttributeChoice->value)
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
            'controller'    => 'ArticleTypeAttributeChoices',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($articleTypeAttributeChoice->value)]
]); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($articleTypeAttributeChoice->value); ?> - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypeAttributeChoices',
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
                                ['class' => 'btn btn-' . $backendButtonColor]
                            )
                            . ' '
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Reset'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypeAttributeChoices',
                                    'action'        => 'index',
                                ],
                                [
                                    'class'         => 'btn btn-' . $backendButtonColor,
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
                    <dd class="col-sm-9"><?= h($articleTypeAttributeChoice->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Type option'); ?></dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($articleTypeAttributeChoice->article_type_attribute->title_alias)): ?>
                            <?= $articleTypeAttributeChoice->has('article_type_attribute')?
                                $this->Html->link(h($articleTypeAttributeChoice->article_type_attribute->title_alias), [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypeAttributes',
                                    'action'        => 'view',
                                    'id'            => h($articleTypeAttributeChoice->article_type_attribute->id),
                                ]): '-'; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>  
                    </dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'UUID Id'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->uuid_id)? '-': h($articleTypeAttributeChoice->uuid_id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Foreign key'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->foreign_key)? '-': h($articleTypeAttributeChoice->foreign_key); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Value'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->value)? '-': h($articleTypeAttributeChoice->value); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_1)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_1), h($articleTypeAttributeChoice->link_1), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_2)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_2), h($articleTypeAttributeChoice->link_2), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_3)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_3), h($articleTypeAttributeChoice->link_3), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_4)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_4), h($articleTypeAttributeChoice->link_4), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_5)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_5), h($articleTypeAttributeChoice->link_5), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_6)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_6), h($articleTypeAttributeChoice->link_6), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_7)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_7), h($articleTypeAttributeChoice->link_7), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_8)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_8), h($articleTypeAttributeChoice->link_8), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Link 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->link_9)? '-': $this->Html->link(h($articleTypeAttributeChoice->link_9), h($articleTypeAttributeChoice->link_9), ['target' => '_blank']); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_1)? '-': $this->Html->image($articleTypeAttributeChoice->image_1_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_2)? '-': $this->Html->image($articleTypeAttributeChoice->image_2_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_3)? '-': $this->Html->image($articleTypeAttributeChoice->image_3_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_4)? '-': $this->Html->image($articleTypeAttributeChoice->image_4_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_5)? '-': $this->Html->image($articleTypeAttributeChoice->image_5_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_6)? '-': $this->Html->image($articleTypeAttributeChoice->image_6_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_7)? '-': $this->Html->image($articleTypeAttributeChoice->image_7_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_8)? '-': $this->Html->image($articleTypeAttributeChoice->image_8_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Image 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->image_9)? '-': $this->Html->image($articleTypeAttributeChoice->image_9_file, ['width' => 25]) . ' ' . h($articleTypeAttributeChoice->image_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_1)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_1_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_2)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_2_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_3)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_3_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_4)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_4_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_5)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_5_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_6)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_6_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_7)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_7_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_8)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_8_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Video 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->video_9)? '-': '<video width=25 controls><source src="' . $articleTypeAttributeChoice->video_9_file . '"></video>' . ' ' . h($articleTypeAttributeChoice->video_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 1'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_1)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_1_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_1); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 2'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_2)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_2_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_2); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 3'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_3)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_3_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_3); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 4'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_4)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_4_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_4); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 5'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_5)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_5_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_5); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 6'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_6)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_6_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_6); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 7'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_7)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_7_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_7); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 8'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_8)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_8_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_8); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'PDF 9'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->pdf_9)? '-': $this->Html->link($this->Html->icon('file'), $articleTypeAttributeChoice->pdf_9_file, ['target' => '_blank', 'escapeTitle' => false]) . ' ' . h($articleTypeAttributeChoice->pdf_9); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->created)? '-': h($articleTypeAttributeChoice->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($articleTypeAttributeChoice->modified)? '-': h($articleTypeAttributeChoice->modified->format('d.m.Y H:i:s')); ?></dd>
                </dl>
                <hr/>
                <?= $this->Html->link(
                    $this->Html->icon('list') . ' ' . __d('yab_cms_ff', 'Index'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'ArticleTypeAttributeChoices',
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
                        'controller'    => 'ArticleTypeAttributeChoices',
                        'action'        => 'edit',
                        'id'            => h($articleTypeAttributeChoice->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'ArticleTypeAttributeChoices',
                        'action'        => 'delete',
                        'id'            => h($articleTypeAttributeChoice->id),
                    ],
                    [
                        'confirm' => __d(
                            'yab_cms_ff',
                            'Are you sure, you want to delete "{value}"?',
                            ['value' => h($articleTypeAttributeChoice->value)]
                        ),
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
            </div>
        </div>
    </div>
</div>
