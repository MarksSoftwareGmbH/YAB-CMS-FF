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

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . h($articleTypeAttribute->title)
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
            'controller'    => 'ArticleTypeAttributes',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Edit article type attribute')],
    ['title' => h($articleTypeAttribute->title)]
]); ?>

<?= $this->Form->create($articleTypeAttribute, [
    'role'  => 'form',
    'type'  => 'file',
    'class' => 'form-general',
    ]); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit article type attribute'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('title', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('alias', [
                    'type'      => 'text',
                    'class'     => 'slug',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('description', [
                    'type'      => 'textarea',
                    'class'     => 'description',
                    'required'  => false,
                ]); ?>

                <div class="form-group row">
                    <div class="col-12">
                        <?= $this->Html->link(
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Edit link(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Edit link(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_edit_links',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_edit_links">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Edit link(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <?= $this->Form->control('link_1', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '1',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_2', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '2',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_3', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '3',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_4', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '4',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_5', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '5',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_6', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '6',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_7', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '7',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_8', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '8',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('link_9', [
                        'type'  => 'url',
                        'label' => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Link') . ' ' . '9',
                            'escapeTitle'   => false,
                        ],
                        'templates' => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <?= $this->Html->link(
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Edit image(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Edit image(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_edit_images',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_edit_images">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Edit image(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '1' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_1) && !empty($articleTypeAttribute->image_1_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_1_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_1); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_1', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '2' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_2) && !empty($articleTypeAttribute->image_2_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_2_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_2); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_2', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '3' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_3) && !empty($articleTypeAttribute->image_3_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_3_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_3); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_3', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '4' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_4) && !empty($articleTypeAttribute->image_4_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_4_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_4); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_4', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '5' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_5) && !empty($articleTypeAttribute->image_5_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_5_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_5); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_5', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '6' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_6) && !empty($articleTypeAttribute->image_6_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_6_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_6); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_6', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '7' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_7) && !empty($articleTypeAttribute->image_7_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_7_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_7); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_7', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '8' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_8) && !empty($articleTypeAttribute->image_8_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_8_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_8); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_8', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Image') . ' ' . '9' . ' ' . '(jpg/gif)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->image_9) && !empty($articleTypeAttribute->image_9_file)): ?>
                                <?= $this->Html->image($articleTypeAttribute->image_9_file, ['width' => 25]); ?> (<?= h($articleTypeAttribute->image_9); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('image_9', [
                                'type'      => 'file',
                                'accept'    => 'image/jpeg,image/jpg,image/gif',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <?= $this->Html->link(
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Edit video(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Edit video(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_edit_videos',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_edit_videos">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Edit video(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '1' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_1) && !empty($articleTypeAttribute->video_1_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_1_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_1); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_1', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '2' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_2) && !empty($articleTypeAttribute->video_2_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_2_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_2); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_2', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '3' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_3) && !empty($articleTypeAttribute->video_3_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_3_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_3); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_3', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '4' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_4) && !empty($articleTypeAttribute->video_4_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_4_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_4); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_4', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '5' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_5) && !empty($articleTypeAttribute->video_5_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_5_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_5); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_5', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '6' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_6) && !empty($articleTypeAttribute->video_6_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_6_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_6); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_6', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '7' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_7) && !empty($articleTypeAttribute->video_7_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_7_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_7); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_7', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '8' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_8) && !empty($articleTypeAttribute->video_8_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_8_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_8); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_8', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'Video') . ' ' . '9' . ' ' . '(mp4)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->video_9) && !empty($articleTypeAttribute->video_9_file)): ?>
                                <video width=25 controls>
                                    <source src="<?= h($articleTypeAttribute->video_9_file); ?>">
                                </video>
                                (<?= h($articleTypeAttribute->video_9); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('video_9', [
                                'type'      => 'file',
                                'accept'    => 'video/mp4',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <?= $this->Html->link(
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Edit PDF(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Edit PDF(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_edit_pdf',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_edit_pdf">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Edit PDF(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '1' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_1) && !empty($articleTypeAttribute->pdf_1_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_1_file, ['target' => '_blank','escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_1); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_1', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '2' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_2) && !empty($articleTypeAttribute->pdf_2_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_2_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_2); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_2', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '3' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_3) && !empty($articleTypeAttribute->pdf_3_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_3_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_3); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_3', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '4' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_4) && !empty($articleTypeAttribute->pdf_4_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_4_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_4); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_4', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '5' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_5) && !empty($articleTypeAttribute->pdf_5_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_5_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_5); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_5', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '6' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_6) && !empty($articleTypeAttribute->pdf_6_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_6_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_6); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_6', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '7' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_7) && !empty($articleTypeAttribute->pdf_7_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_7_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_7); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_7', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '8' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_8) && !empty($articleTypeAttribute->pdf_8_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_8_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_8); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_8', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?= __d('yab_cms_ff', 'PDF') . ' ' . '9' . ' ' . '(pdf)'; ?></label>
                        <div class="col-sm-10">
                            <?php if (!empty($articleTypeAttribute->pdf_9) && !empty($articleTypeAttribute->pdf_9_file)): ?>
                                <?= $this->Html->link($this->Html->icon('file'), $articleTypeAttribute->pdf_9_file, ['target' => '_blank', 'escapeTitle' => false]); ?> (<?= h($articleTypeAttribute->pdf_9); ?>)<br />
                                <br />
                            <?php endif; ?>
                            <?= $this->Form->control('pdf_9', [
                                'type'      => 'file',
                                'accept'    => 'application/pdf',
                                'label'     => false,
                                'required'  => false,
                            ]); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="col-lg-4 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('cog'); ?> <?= __d('yab_cms_ff', 'Actions'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('foreign_key', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('type', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->inputTypesList())? $this->YabCmsFf->inputTypesList(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('article_types._ids', [
                    'type'      => 'select',
                    'multiple'  => 'checkbox',
                    'options'   => !empty($articleTypes)? $articleTypes: [],
                    'label'     => __d('yab_cms_ff', 'Types'),
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $emptyValue = $articleTypeAttribute->empty_value? true: false; ?>
                        <?= $this->Form->checkbox('empty_value', ['id' => 'emptyValue', 'class' => 'custom-control-input', 'checked' => $emptyValue, 'required' => false]); ?>
                        <label class="custom-control-label" for="emptyValue"><?= __d('yab_cms_ff', 'Leave attribute empty?'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $wysiwyg = $articleTypeAttribute->wysiwyg? true: false; ?>
                        <?= $this->Form->checkbox('wysiwyg', ['id' => 'wysiwyg', 'class' => 'custom-control-input', 'checked' => $wysiwyg, 'required' => false]); ?>
                        <label class="custom-control-label" for="wysiwyg"><?= __d('yab_cms_ff', 'Activate WYSIWYG editor?'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypeAttributes',
                            'action'        => 'index',
                        ],
                        [
                            'class'         => 'btn btn-danger float-right',
                            'escapeTitle'   => false,
                        ]); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->Form->end(); ?>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'yab_cms_ff.select2'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'slug' . DS . 'jquery.slug',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'articleTypeAttributes' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        ArticleTypeAttributes.init();
        // Initialize select2
        $(\'.select2\').select2();
        // Initialize summernote
        $(\'.description\').summernote();
        $(\'.form-general\').submit(function(event) {
            $(\'.description\').summernote(\'destroy\');
        });
        $(\'.form-general\').validate({
            rules: {
                title: {
                    required: true
                },
                alias: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid title') . '\'
                },
                alias: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid alias') . '\'
                }
            },
            errorElement: \'span\',
            errorPlacement: function (error, element) {
                error.addClass(\'invalid-feedback\');
                element.closest(\'.form-group\').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass(\'is-invalid\');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass(\'is-invalid\');
            }
        });
    });',
    ['block' => 'scriptBottom']); ?>
