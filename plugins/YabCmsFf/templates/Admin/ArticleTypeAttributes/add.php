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
use Cake\Utility\Text;

$backendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.backendBoxColor')):
    $backendBoxColor = Configure::read('YabCmsFf.settings.backendBoxColor');
endif;

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
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
    ['title' => __d('yab_cms_ff', 'Add type attribute')]
], ['class' => 'breadcrumb-item']); ?>
<?= $this->Form->create($articleTypeAttribute, [
    'role'  => 'form',
    'type'  => 'file',
    'class' => 'form-general',
]); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('plus'); ?> <?= __d('yab_cms_ff', 'Add type attribute'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('uuid_id', [
                    'type'      => 'hidden',
                    'value'     => Text::uuid(),
                ]); ?>
                <?= $this->Form->control('title', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Title') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('alias', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Alias') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'slug border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('description', [
                    'type'      => 'textarea',
                    'required'  => false,
                ]); ?>
                <div class="form-group row">
                    <div class="col-12">
                        <?= $this->Html->link(
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Add link(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Add link(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_add_links',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_add_links">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Add link(s)'); ?>" class="hr-text">
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
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Add image(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Add image(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_add_images',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_add_images">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Add image(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <?= $this->Form->control('image_1', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '1' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_2', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '2' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_3', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '3' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_4', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '4' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_5', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '5' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_6', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '6' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_7', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '7' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_8', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '8' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('image_9', [
                        'type'      => 'file',
                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Image') . ' ' . '9' . ' ' . '(jpg/gif)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
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
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Add video(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Add video(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_add_videos',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_add_videos">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Add video(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <?= $this->Form->control('video_1', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '1' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_2', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '2' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_3', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '3' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_4', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '4' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_5', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '5' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_6', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '6' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_7', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '7' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_8', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '8' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('video_9', [
                        'type'      => 'file',
                        'accept'    => 'video/mp4',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'Video') . ' ' . '9' . ' ' . '(mp4)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
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
                            $this->Html->icon('plus') . ' ' . __d('yab_cms_ff', 'Add PDF(s)'),
                            'javascript:void(0)',
                            [
                                'title'         => __d('yab_cms_ff', 'Add PDF(s)'),
                                'class'         => 'text-primary',
                                'data-toggle'   => 'collapse',
                                'data-target'   => '#collapse_article_type_attribute_add_pdf',
                                'escapeTitle'   => false,
                            ]); ?>
                    </div>
                </div>
                <div class="collapse" id="collapse_article_type_attribute_add_pdf">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <hr data-content="<?= __d('yab_cms_ff', 'Add PDF(s)'); ?>" class="hr-text">
                        </div>
                    </div>
                    <?= $this->Form->control('pdf_1', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '1' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_2', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '2' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_3', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '3' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_4', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '4' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_5', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '5' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_6', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '6' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_7', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '7' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_8', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '8' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                    <?= $this->Form->control('pdf_9', [
                        'type'      => 'file',
                        'accept'    => 'application/pdf',
                        'label'     => [
                            'class'         => 'col-sm-2 col-form-label',
                            'text'          => __d('yab_cms_ff', 'PDF') . ' ' . '9' . ' ' . '(pdf)',
                            'escapeTitle'   => false,
                        ],
                        'templates'     => [
                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                        ],
                        'required'  => false,
                    ]); ?>
                </div>

            </div>
        </div>
    </section>
    <section class="col-lg-4 connectedSortable">
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('cog'); ?> <?= __d('yab_cms_ff', 'Actions'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('foreign_key', [
                    'type'              => 'text',
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'count-chars',
                    'required'          => false,
                ]); ?>
                <?= $this->Form->control('type', [
                    'type'      => 'select',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Type') . '*',
                        'class' => 'text-danger',
                    ],
                    'options'   => !empty($this->YabCmsFf->inputTypesList())? $this->YabCmsFf->inputTypesList(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('article_types._ids', [
                    'type'      => 'select',
                    'multiple'  => 'checkbox',
                    'label' => [
                        'text' => __d('yab_cms_ff', 'Article types') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Add article type'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypes',
                                    'action'        => 'add',
                                ],
                                [
                                    'target'        => '_blank',
                                    'class'         => 'text-' . h($backendLinkTextColor),
                                    'escapeTitle'   => false,
                                ])
                            . ')',
                        'class'     => 'text-danger',
                        'escape'    => false,
                    ],
                    'options'   => !empty($articleTypes)? $articleTypes: [],
                    'default'   => !empty($articleTypes)? array_keys($articleTypes): [],
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('empty_value', ['id' => 'emptyValue', 'class' => 'custom-control-input', 'checked' => false, 'required' => false]); ?>
                        <label class="custom-control-label" for="emptyValue"><?= __d('yab_cms_ff', 'Leave attribute empty?'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('wysiwyg', ['id' => 'wysiwyg', 'class' => 'custom-control-input', 'checked' => false, 'required' => false]); ?>
                        <label class="custom-control-label" for="wysiwyg"><?= __d('yab_cms_ff', 'Activate WYSIWYG editor?'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success shadow rounded']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypeAttributes',
                            'action'        => 'index',
                        ],
                        [
                            'class'         => 'btn btn-danger shadow rounded float-right',
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
        $(\'.count-chars\').keyup(function () {
            var charInput = this.value;
            var charInputLength = this.value.length;
            const maxChars = $(this).data(\'chars-max\');
            const messageColor = $(this).data(\'msg-color\');
            var inputId = this.getAttribute(\'id\');
            var messageDivId = inputId + \'Message\';
            var remainingMessage = \'\';

            if (charInputLength >= maxChars) {
                $(\'#\' + inputId).val(charInput.substring(0, maxChars));
                remainingMessage = \'0 ' . __d('yab_cms_ff', 'character remaining') . '\' ;
            } else {
                remainingMessage = (maxChars - charInputLength) + \' ' . __d('yab_cms_ff', 'character(s) remaining') . '\';
            }
            if ($(\'#\' + messageDivId).length == 0) {
                $(\'#\' + inputId).after(\'<div id="\' + messageDivId + \'" class="text-\' + messageColor + \' font-weight-bold">\' + remainingMessage + \'</div>\');
            } else {
                $(\'#\' + messageDivId).text(remainingMessage);
            }
        });
    });',
    ['block' => 'scriptBottom']); ?>
