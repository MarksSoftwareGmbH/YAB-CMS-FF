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

$frontendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendButtonColor')):
    $frontendButtonColor = Configure::read('YabCmsFf.settings.frontendButtonColor');
endif;

$frontendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendBoxColor')):
    $frontendBoxColor = Configure::read('YabCmsFf.settings.frontendBoxColor');
endif;

$userProfileName = '';
if (
    !empty($userProfile->first_name) &&
    !empty($userProfile->middle_name) &&
    !empty($userProfile->last_name)
):
    $userProfileName = htmlspecialchars_decode($userProfile->first_name) . ' '
        . htmlspecialchars_decode($userProfile->middle_name) . ' '
        . htmlspecialchars_decode($userProfile->last_name);
elseif (
    !empty($userProfile->first_name) &&
    !empty($userProfile->last_name)
):
    $userProfileName = htmlspecialchars_decode($userProfile->first_name) . ' '
        . htmlspecialchars_decode($userProfile->last_name);
elseif (!empty($userProfile->last_name)):
    $userProfileName = htmlspecialchars_decode($userProfile->last_name);
else:
    $userProfileName = htmlspecialchars_decode($userProfile->first_name);
endif;

// Title
$this->assign('title', __d('yab_cms_ff', '{userProfileName} timeline entries', ['userProfileName' => $userProfileName]));

$this->Html->meta('robots', 'index, follow', ['block' => true]);
$this->Html->meta('author', $userProfileName, ['block' => true]);
$this->Html->meta('description', __d('yab_cms_ff', '{userProfileName} timeline entries', ['userProfileName' => $userProfileName]), ['block' => true]);

$this->Html->meta([
    'property'  => 'og:title',
    'content'   => __d('yab_cms_ff', '{userProfileName} timeline entries', ['userProfileName' => $userProfileName]),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:description',
    'content'   => strip_tags(htmlspecialchars_decode($userProfile->about_me)),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:url',
    'content'   => $this->Url->build([
        'plugin'        => 'YabCmsFf',
        'controller'    => 'UserProfiles',
        'action'        => 'view',
        'foreignKey'    => h($userProfile->foreign_key),
    ], ['fullBase' => true]),
    'block' => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:locale',
    'content'   => $session->read('Locale.code'),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:type',
    'content'   => 'profile',
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:site_name',
    'content'   => 'Yet another boring CMS for FREE',
    'block'     => 'meta',
]);

// Breadcrumb
$this->Breadcrumbs->add([
    [
        'title' => __d('yab_cms_ff', 'Yet another boring CMS for FREE'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Articles',
            'action'        => 'promoted',
        ],
    ],
    [
        'title' => __d('yab_cms_ff', 'Profiles'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'UserProfiles',
            'action'        => 'index',
        ],
    ],
    [
        'title' => $userProfileName,
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'UserProfiles',
            'action'        => 'view',
            'foreignKey'    => h($userProfile->foreign_key),
        ],
    ],
    ['title' => __d('yab_cms_ff', 'Timeline entries')],
]);
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', '{userProfileName} timeline entries', ['userProfileName' => $userProfileName]); ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <?= $this->element('breadcrumb'); ?>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-<?= h($frontendBoxColor); ?> card-outline">

                    <div class="card-header">
                        <?= $this->Form->create(null, [
                            'url' => [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'UserProfileTimelineEntries',
                                'action'        => 'index',
                                'foreignKey'    => h($userProfile->foreign_key),
                            ],
                        ]); ?>
                        <?= $this->Form->control('search', [
                            'type'          => 'text',
                            'value'         => $this->getRequest()->getQuery('search'),
                            'label'         => false,
                            'placeholder'   => __d('yab_cms_ff', 'Search {userProfileName} timeline entries', ['userProfileName' => $userProfileName]) . '...',
                            'append' => $this->Form->button(
                                    __d('yab_cms_ff', 'Search'),
                                    ['class' => 'btn btn-' . h($frontendButtonColor)]
                                )
                                . ' '
                                . $this->Html->link(
                                    __d('yab_cms_ff', 'Reset'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'UserProfileTimelineEntries',
                                        'action'        => 'index',
                                        'foreignKey'    => h($userProfile->foreign_key),
                                    ],
                                    [
                                        'class'         => 'btn btn-' . h($frontendButtonColor),
                                        'escapeTitle'   => false,
                                    ]
                                ),
                        ]); ?>
                        <?= $this->Form->end(); ?>
                    </div>

                    <div class="card-body">

                        <?php
                            if (
                                $session->check('Auth.User.id') &&
                                ($session->read('Auth.User.id') == $userProfile->user_id)
                            ):
                        ?>
                            <div class="post">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Add a timeline entry')
                                    . ' '
                                    . $this->Html->tag('i', '', ['class' => 'fas fa-plus']),
                                    'javascript:void(0)',
                                    [
                                        'title'         => __d('yab_cms_ff', 'Add a timeline entry'),
                                        'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                        'type'          => 'button',
                                        'data-toggle'   => 'collapse',
                                        'data-target'   => '#collapse_add_timeline_entry_collapse',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <div class="collapse" id="collapse_add_timeline_entry_collapse">
                                    <hr data-content="<?= __d('yab_cms_ff', 'Add'); ?>" class="hr-text">
                                    <?= $this->Form->create(null, [
                                        'role'  => 'form',
                                        'type'  => 'file',
                                        'url' => [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfileTimelineEntries',
                                            'action'        => 'add',
                                        ],
                                        'class' => 'form-horizontal user-profile-timeline-entry-add',
                                    ]); ?>
                                    <?= $this->Form->control('entry_no', [
                                        'type'  => 'number',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'No.')),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                        'value'     => time(),
                                        'maxlenght' => 11,
                                    ]); ?>
                                    <?= $this->Form->control('entry_ref_no', [
                                        'type'      => 'select',
                                        'options'   => !empty($userProfileTimelineEntriesList)? $userProfileTimelineEntriesList: [],
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Reference No.')),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'style'     => 'width: 100%',
                                        'empty'     => true,
                                        'required'  => false,
                                        'maxlenght' => 254,
                                    ]); ?>
                                    <?= $this->Form->control('entry_date', [
                                        'type' => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Date') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class' => 'datetimepicker',
                                        'format' => 'd.m.Y H:i:s',
                                        'default' => date('d.m.Y H:i:s'),
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('entry_type', [
                                        'type'      => 'select',
                                        'options'   => [
                                            'text'      => __d('yab_cms_ff', 'Text'),
                                            'link'      => __d('yab_cms_ff', 'Link(s)'),
                                            'image'     => __d('yab_cms_ff', 'Image(s)'),
                                            'video'     => __d('yab_cms_ff', 'Video(s)'),
                                            'pdf'       => __d('yab_cms_ff', 'PDF(s)'),
                                            'tab'       => __d('yab_cms_ff', 'Guitar Pro tab'),
                                        ],
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Type') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'style'     => 'width: 100%',
                                        'empty'     => true,
                                        'required'  => true,
                                        'maxlenght' => 254,
                                    ]); ?>
                                    <?= $this->Form->control('entry_title', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Title') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => true,
                                        'maxlenght' => 254,
                                    ]); ?>
                                    <?= $this->Form->control('entry_subtitle', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Subtitle')),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                        'maxlenght' => 254,
                                    ]); ?>
                                    <?= $this->Form->control('entry_body', [
                                        'type'  => 'textarea',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Text')),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'entry_body',
                                        'required'  => false,
                                    ]); ?>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <?= $this->Html->link(
                                                $this->Html->tag('i', '', ['class' => 'fas fa-plus']) . ' ' . __d('yab_cms_ff', 'Add link(s)'),
                                                'javascript:void(0)',
                                                [
                                                    'title'         => __d('yab_cms_ff', 'Add link(s)'),
                                                    'class'         => 'text-primary',
                                                    'data-toggle'   => 'collapse',
                                                    'data-target'   => '#collapse_user_profile_timeline_entry_add_link',
                                                    'escapeTitle'   => false,
                                                ]); ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_user_profile_timeline_entry_add_link">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr data-content="<?= __d('yab_cms_ff', 'Add link(s)'); ?>" class="hr-text">
                                            </div>
                                        </div>
                                        <?= $this->Form->control('entry_link_1', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '1'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_2', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '2'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_3', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '3'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_4', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '4'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_5', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '5'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_6', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '6'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_7', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '7'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_8', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '8'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_link_9', [
                                            'type'  => 'url',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Link') . ' ' . '9'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                            'maxlenght' => 254,
                                        ]); ?>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <?= $this->Html->link(
                                                $this->Html->tag('i', '', ['class' => 'fas fa-plus']) . ' ' . __d('yab_cms_ff', 'Add image(s)'),
                                                'javascript:void(0)',
                                                [
                                                    'title'         => __d('yab_cms_ff', 'Add image(s)'),
                                                    'class'         => 'text-primary',
                                                    'data-toggle'   => 'collapse',
                                                    'data-target'   => '#collapse_user_profile_timeline_entry_add_image',
                                                    'escapeTitle'   => false,
                                                ]); ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_user_profile_timeline_entry_add_image">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr data-content="<?= __d('yab_cms_ff', 'Add image(s)'); ?>" class="hr-text">
                                            </div>
                                        </div>

                                        <?= $this->Form->control('entry_image_1', [
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
                                        <?= $this->Form->control('entry_image_2', [
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
                                        <?= $this->Form->control('entry_image_3', [
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
                                        <?= $this->Form->control('entry_image_4', [
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
                                        <?= $this->Form->control('entry_image_5', [
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
                                        <?= $this->Form->control('entry_image_6', [
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
                                        <?= $this->Form->control('entry_image_7', [
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
                                        <?= $this->Form->control('entry_image_8', [
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
                                        <?= $this->Form->control('entry_image_9', [
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
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <?= $this->Html->link(
                                                $this->Html->tag('i', '', ['class' => 'fas fa-plus']) . ' ' . __d('yab_cms_ff', 'Add video(s)'),
                                                'javascript:void(0)',
                                                [
                                                    'title'         => __d('yab_cms_ff', 'Add video(s)'),
                                                    'class'         => 'text-primary',
                                                    'data-toggle'   => 'collapse',
                                                    'data-target'   => '#collapse_user_profile_timeline_entry_add_video',
                                                    'escapeTitle'   => false,
                                                ]); ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_user_profile_timeline_entry_add_video">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr data-content="<?= __d('yab_cms_ff', 'Add video(s)'); ?>" class="hr-text">
                                            </div>
                                        </div>
                                        <?= $this->Form->control('entry_video_1', [
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
                                        <?= $this->Form->control('entry_video_2', [
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
                                        <?= $this->Form->control('entry_video_3', [
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
                                        <?= $this->Form->control('entry_video_4', [
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
                                        <?= $this->Form->control('entry_video_5', [
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
                                        <?= $this->Form->control('entry_video_6', [
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
                                        <?= $this->Form->control('entry_video_7', [
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
                                        <?= $this->Form->control('entry_video_8', [
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
                                        <?= $this->Form->control('entry_video_9', [
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
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <?= $this->Html->link(
                                                $this->Html->tag('i', '', ['class' => 'fas fa-plus']) . ' ' . __d('yab_cms_ff', 'Add PDF(s)'),
                                                'javascript:void(0)',
                                                [
                                                    'title'         => __d('yab_cms_ff', 'Add PDF(s)'),
                                                    'class'         => 'text-primary',
                                                    'data-toggle'   => 'collapse',
                                                    'data-target'   => '#collapse_user_profile_timeline_entry_add_pdf',
                                                    'escapeTitle'   => false,
                                                ]); ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_user_profile_timeline_entry_add_pdf">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr data-content="<?= __d('yab_cms_ff', 'Add PDF(s)'); ?>" class="hr-text">
                                            </div>
                                        </div>
                                        <?= $this->Form->control('entry_pdf_1', [
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
                                        <?= $this->Form->control('entry_pdf_2', [
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
                                        <?= $this->Form->control('entry_pdf_3', [
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
                                        <?= $this->Form->control('entry_pdf_4', [
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
                                        <?= $this->Form->control('entry_pdf_5', [
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
                                        <?= $this->Form->control('entry_pdf_6', [
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
                                        <?= $this->Form->control('entry_pdf_7', [
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
                                        <?= $this->Form->control('entry_pdf_8', [
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
                                        <?= $this->Form->control('entry_pdf_9', [
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

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <?= $this->Html->link(
                                                $this->Html->tag('i', '', ['class' => 'fas fa-plus']) . ' ' . __d('yab_cms_ff', 'Add Guitar Pro tab'),
                                                'javascript:void(0)',
                                                [
                                                    'title'         => __d('yab_cms_ff', 'Add Guitar Pro tab'),
                                                    'class'         => 'text-primary',
                                                    'data-toggle'   => 'collapse',
                                                    'data-target'   => '#collapse_user_profile_timeline_entry_add_guitar_pro_tab',
                                                    'escapeTitle'   => false,
                                                ]); ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_user_profile_timeline_entry_add_guitar_pro_tab">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr data-content="<?= __d('yab_cms_ff', 'Add Guitar Pro tab'); ?>" class="hr-text">
                                            </div>
                                        </div>
                                        <?= $this->Form->control('entry_guitar_pro', [
                                            'type'      => 'file',
                                            'label'     => [
                                                'class'     => 'col-sm-2 col-form-label',
                                                'text'      => __d('yab_cms_ff', 'Guitar Pro file') . ' ' . '(gp3 / gp4 / gp5 / gpx / gp)',
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
                                        <div class="offset-sm-2 col-sm-10">
                                            <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>

                                <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datetimepicker' . DS . 'jquery.datetimepicker'); ?>
                                <?= $this->Html->script(
                                    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datetimepicker' . DS . 'build' . DS . 'jquery.datetimepicker.full.min',
                                    ['block' => 'scriptBottom']
                                ); ?>
                                <?= $this->Html->scriptBlock(
                                    '$(function() {
                                        // Initialize datetimepicker
                                        $(\'.datetimepicker\').datetimepicker({
                                            format:\'d.m.Y H:i:s\',
                                            lang:\'en\'
                                        });
                                        // Initialize summernote
                                        $(\'.entry_body\').summernote({
                                            toolbar: [
                                                [\'style\', [\'style\']],
                                                [\'font\', [\'bold\', \'underline\', \'clear\']],
                                                [\'fontname\', [\'fontname\']],
                                                [\'color\', [\'color\']],
                                                [\'para\', [\'ul\', \'ol\', \'paragraph\']],
                                                [\'table\', [\'table\']],
                                                [\'insert\', [\'link\']],
                                                [\'view\', [\'fullscreen\']]
                                            ],
                                            placeholder: \'' . __d('yab_cms_ff', 'Please enter a valid entry text') . '\',
                                            tabsize: 2,
                                            height: 100
                                        });
                                        $(\'.user-profile-timeline-entry-add\').submit(function(event) {
                                            $(\'.entry_body\').summernote(\'destroy\');
                                        });
                                        $(\'.user-profile-timeline-entry-add\').validate({
                                            rules: {
                                                entry_date: {
                                                    required: true
                                                },
                                                entry_type: {
                                                    required: true
                                                },
                                                entry_title: {
                                                    required: true
                                                }
                                            },
                                            messages: {
                                                entry_date: {
                                                    required: \'' . __d('yab_cms_ff', 'Please enter a valid entry date') . '\'
                                                },
                                                entry_type: {
                                                    required: \'' . __d('yab_cms_ff', 'Please select a valid entry type') . '\'
                                                },
                                                entry_title: {
                                                    required: \'' . __d('yab_cms_ff', 'Please enter a valid entry title') . '\'
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
                            </div>
                        <?php endif; ?>

                        <?php if (isset($userProfileTimelineEntries) && !empty($userProfileTimelineEntries)): ?>
                            <?php $entriesCounter = count($userProfileTimelineEntries); ?>
                            <div class="timeline timeline-inverse">

                                <?php $entryDateLabel = ''; ?>
                                <?php $count = 0; ?>
                                <?php foreach ($userProfileTimelineEntries as $userProfileTimelineEntry): ?>
                                    <?php if ($userProfileTimelineEntry->entry_date->format('Y-m-d') !== $entryDateLabel): ?>
                                        <div class="time-label">
                                                <span class="bg-<?= h($frontendBoxColor); ?>">
                                                  <?= h($userProfileTimelineEntry->entry_date->format('M d, Y')); ?>
                                                </span>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'text'): ?>
                                            <i class="fas fa-comment-alt bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'link'): ?>
                                            <i class="fas fa-link bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'image'): ?>
                                            <i class="fas fa-image bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'video'): ?>
                                            <i class="fas fa-video bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'pdf'): ?>
                                            <i class="fas fa-file-pdf bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <?php if ($userProfileTimelineEntry->entry_type === 'tab'): ?>
                                            <i class="fas fa-guitar bg-<?= h($frontendBoxColor); ?>"></i>
                                        <?php endif; ?>
                                        <div class="timeline-item" id="timelineEntry<?= h($userProfileTimelineEntry->entry_no); ?>">
                                            <h3 class="timeline-header">
                                                <?php
                                                    if (
                                                        !empty($userProfileTimelineEntry->entry_title) &&
                                                        !empty($userProfileTimelineEntry->entry_date)
                                                    ):
                                                ?>
                                                    <?= $this->Html->link(
                                                        htmlspecialchars_decode($userProfileTimelineEntry->entry_title),
                                                        [
                                                            'plugin'        => 'YabCmsFf',
                                                            'controller'    => 'UserProfileTimelineEntries',
                                                            'action'        => 'viewBySlug',
                                                            'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                            'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                        ],
                                                        ['escapeTitle' => false]); ?>
                                                    <i class="fas fa-clock-o"></i> <?= h($userProfileTimelineEntry->entry_date->format('M d, Y g:i:s a')); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_subtitle)): ?>
                                                    <?= htmlspecialchars_decode($userProfileTimelineEntry->entry_subtitle); ?>
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_no)): ?>
                                                <small><?= $this->Html->link(
                                                        __d('yab_cms_ff', 'No. {entryNo} {linkIcon}', [
                                                            'entryNo' => h($userProfileTimelineEntry->entry_no),
                                                            'linkIcon' => $this->Html->tag('i', '', ['class' => 'fas fa-link'])
                                                        ]),
                                                        [
                                                            'plugin'        => 'YabCmsFf',
                                                            'controller'    => 'UserProfileTimelineEntries',
                                                            'action'        => 'viewBySlug',
                                                            'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                            'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                        ],
                                                        ['escapeTitle' => false]); ?></small>
                                                <?php endif; ?>
                                            </h3>

                                            <div class="timeline-body">
                                                <?php if (!empty($userProfileTimelineEntry->entry_ref_no)): ?>
                                                    <a href="#timelineEntry<?= h($userProfileTimelineEntry->entry_ref_no); ?>">
                                                        >><?= h($userProfileTimelineEntry->entry_ref_no); ?>
                                                    </a><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_body)): ?>
                                                    <?= htmlspecialchars_decode($userProfileTimelineEntry->entry_body); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_1)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_1),
                                                        h($userProfileTimelineEntry->entry_link_1),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_2)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_2),
                                                        h($userProfileTimelineEntry->entry_link_2),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_3)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_3),
                                                        h($userProfileTimelineEntry->entry_link_3),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_4)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_4),
                                                        h($userProfileTimelineEntry->entry_link_4),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_5)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_5),
                                                        h($userProfileTimelineEntry->entry_link_5),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_6)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_6),
                                                        h($userProfileTimelineEntry->entry_link_6),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_7)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_7),
                                                        h($userProfileTimelineEntry->entry_link_7),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_8)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_8),
                                                        h($userProfileTimelineEntry->entry_link_8),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>
                                                <?php if (!empty($userProfileTimelineEntry->entry_link_9)): ?>
                                                    <?= $this->Html->link(
                                                        h($userProfileTimelineEntry->entry_link_9),
                                                        h($userProfileTimelineEntry->entry_link_9),
                                                        ['target' => '_blank']); ?><br />
                                                    <br />
                                                <?php endif; ?>

                                                <?php
                                                    if (
                                                        !empty($userProfileTimelineEntry->entry_image_1_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_2_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_3_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_4_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_5_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_6_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_7_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_8_file) ||
                                                        !empty($userProfileTimelineEntry->entry_image_9_file)
                                                    ):
                                                ?>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <?php if (!empty($userProfileTimelineEntry->entry_image_1_file)): ?>
                                                                <?= $this->Html->link(
                                                                    $this->Html->image(
                                                                        h($userProfileTimelineEntry->entry_image_1_file),
                                                                        [
                                                                            'alt'   => h($userProfileTimelineEntry->entry_image_1),
                                                                            'class' => 'img-fluid full-width-image mb-3',
                                                                        ]),
                                                                    h($userProfileTimelineEntry->entry_image_1_file),
                                                                    [
                                                                        'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                        'data-toggle'   => 'lightbox',
                                                                        'data-title'    => h($userProfileTimelineEntry->entry_image_1),
                                                                        'data-gallery'  => 'gallery',
                                                                        'escapeTitle'   => false,
                                                                    ]); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php
                                                            if (
                                                                !empty($userProfileTimelineEntry->entry_image_2_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_3_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_4_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_5_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_6_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_7_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_8_file) ||
                                                                !empty($userProfileTimelineEntry->entry_image_9_file)
                                                            ):
                                                        ?>
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_2_file)): ?>
                                                                        <?= $this->Html->link(
                                                                            $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_2_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_2),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]),
                                                                            h($userProfileTimelineEntry->entry_image_2_file),
                                                                            [
                                                                                'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                                'data-toggle'   => 'lightbox',
                                                                                'data-title'    => h($userProfileTimelineEntry->entry_image_2),
                                                                                'data-gallery'  => 'gallery',
                                                                                'escapeTitle'   => false,
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_3_file)): ?>
                                                                        <?= $this->Html->link(
                                                                            $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_3_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_3),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]),
                                                                            h($userProfileTimelineEntry->entry_image_3_file),
                                                                            [
                                                                                'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                                'data-toggle'   => 'lightbox',
                                                                                'data-title'    => h($userProfileTimelineEntry->entry_image_3),
                                                                                'data-gallery'  => 'gallery',
                                                                                'escapeTitle'   => false,
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_4_file)): ?>
                                                                        <?= $this->Html->link(
                                                                            $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_4_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_4),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]),
                                                                            h($userProfileTimelineEntry->entry_image_4_file),
                                                                            [
                                                                                'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                                'data-toggle'   => 'lightbox',
                                                                                'data-title'    => h($userProfileTimelineEntry->entry_image_4),
                                                                                'data-gallery'  => 'gallery',
                                                                                'escapeTitle'   => false,
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_5_file)): ?>
                                                                        <?= $this->Html->link(
                                                                            $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_5_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_5),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]),
                                                                            h($userProfileTimelineEntry->entry_image_5_file),
                                                                            [
                                                                                'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                                'data-toggle'   => 'lightbox',
                                                                                'data-title'    => h($userProfileTimelineEntry->entry_image_5),
                                                                                'data-gallery'  => 'gallery',
                                                                                'escapeTitle'   => false,
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php
                                                        if (
                                                            !empty($userProfileTimelineEntry->entry_image_6_file) ||
                                                            !empty($userProfileTimelineEntry->entry_image_7_file) ||
                                                            !empty($userProfileTimelineEntry->entry_image_8_file) ||
                                                            !empty($userProfileTimelineEntry->entry_image_9_file)
                                                        ):
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_image_6_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_6_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_6),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]),
                                                                        h($userProfileTimelineEntry->entry_image_6_file),
                                                                        [
                                                                            'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                            'data-toggle'   => 'lightbox',
                                                                            'data-title'    => h($userProfileTimelineEntry->entry_image_6),
                                                                            'data-gallery'  => 'gallery',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_image_7_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_7_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_7),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]),
                                                                        h($userProfileTimelineEntry->entry_image_7_file),
                                                                        [
                                                                            'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                            'data-toggle'   => 'lightbox',
                                                                            'data-title'    => h($userProfileTimelineEntry->entry_image_7),
                                                                            'data-gallery'  => 'gallery',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_image_8_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_8_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_8),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]),
                                                                        h($userProfileTimelineEntry->entry_image_8_file),
                                                                        [
                                                                            'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                            'data-toggle'   => 'lightbox',
                                                                            'data-title'    => h($userProfileTimelineEntry->entry_image_8),
                                                                            'data-gallery'  => 'gallery',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_image_9_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_9_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_9),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]),
                                                                        h($userProfileTimelineEntry->entry_image_9_file),
                                                                        [
                                                                            'class'         => 'gallery-' . h($userProfileTimelineEntry->foreign_key),
                                                                            'data-toggle'   => 'lightbox',
                                                                            'data-title'    => h($userProfileTimelineEntry->entry_image_9),
                                                                            'data-gallery'  => 'gallery',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <br />
                                                <?php endif; ?>

                                                <?php
                                                    if (
                                                        !empty($userProfileTimelineEntry->entry_video_1_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_2_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_3_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_4_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_5_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_6_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_7_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_8_file) ||
                                                        !empty($userProfileTimelineEntry->entry_video_9_file)
                                                    ):
                                                ?>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <?php if (!empty($userProfileTimelineEntry->entry_video_1_file)): ?>
                                                                <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                    <video height="100" width="100" controls>
                                                                        <source src="<?= h($userProfileTimelineEntry->entry_video_1_file); ?>">
                                                                    </video>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php
                                                            if (
                                                                !empty($userProfileTimelineEntry->entry_video_2_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_3_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_4_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_5_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_6_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_7_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_8_file) ||
                                                                !empty($userProfileTimelineEntry->entry_video_9_file)
                                                            ):
                                                        ?>
                                                            <div class="col-sm-6">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_video_2_file)): ?>
                                                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                                <video height="100" width="100" controls>
                                                                                    <source src="<?= h($userProfileTimelineEntry->entry_video_2_file); ?>">
                                                                                </video>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_video_3_file)): ?>
                                                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                                <video height="100" width="100" controls>
                                                                                    <source src="<?= h($userProfileTimelineEntry->entry_video_3_file); ?>">
                                                                                </video>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_video_4_file)): ?>
                                                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                                <video height="100" width="100" controls>
                                                                                    <source src="<?= h($userProfileTimelineEntry->entry_video_4_file); ?>">
                                                                                </video>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_video_5_file)): ?>
                                                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                                <video height="100" width="100" controls>
                                                                                    <source src="<?= h($userProfileTimelineEntry->entry_video_5_file); ?>">
                                                                                </video>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php
                                                        if (
                                                            !empty($userProfileTimelineEntry->entry_video_6_file) ||
                                                            !empty($userProfileTimelineEntry->entry_video_7_file) ||
                                                            !empty($userProfileTimelineEntry->entry_video_8_file) ||
                                                            !empty($userProfileTimelineEntry->entry_video_9_file)
                                                        ):
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_video_6_file)): ?>
                                                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                        <video height="100" width="100" controls>
                                                                            <source src="<?= h($userProfileTimelineEntry->entry_video_6_file); ?>">
                                                                        </video>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_video_7_file)): ?>
                                                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                        <video height="100" width="100" controls>
                                                                            <source src="<?= h($userProfileTimelineEntry->entry_video_7_file); ?>">
                                                                        </video>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_video_8_file)): ?>
                                                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                        <video height="100" width="100" controls>
                                                                            <source src="<?= h($userProfileTimelineEntry->entry_video_8_file); ?>">
                                                                        </video>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_video_9_file)): ?>
                                                                    <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                                        <video height="100" width="100" controls>
                                                                            <source src="<?= h($userProfileTimelineEntry->entry_video_9_file); ?>">
                                                                        </video>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <br />
                                                <?php endif; ?>

                                                <?php
                                                    if (
                                                        !empty($userProfileTimelineEntry->entry_pdf_1_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_2_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_3_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_4_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_5_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_6_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_7_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_8_file) ||
                                                        !empty($userProfileTimelineEntry->entry_pdf_9_file)
                                                    ):
                                                ?>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <?php if (!empty($userProfileTimelineEntry->entry_pdf_1_file)): ?>
                                                                <?= $this->Html->link(
                                                                    $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                    . h($userProfileTimelineEntry->entry_pdf_1),
                                                                    h($userProfileTimelineEntry->entry_pdf_1_file),
                                                                    [
                                                                        'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                        'target'        => '_blank',
                                                                        'escapeTitle'   => false,
                                                                    ]); ?>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php
                                                            if (
                                                                !empty($userProfileTimelineEntry->entry_pdf_2_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_3_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_4_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_5_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_6_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_7_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_8_file) ||
                                                                !empty($userProfileTimelineEntry->entry_pdf_9_file)
                                                            ):
                                                        ?>
                                                            <div class="col-sm-6">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_pdf_2_file)): ?>
                                                                            <?= $this->Html->link(
                                                                                $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                                . h($userProfileTimelineEntry->entry_pdf_2),
                                                                                h($userProfileTimelineEntry->entry_pdf_2_file),
                                                                                [
                                                                                    'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                                    'target'        => '_blank',
                                                                                    'escapeTitle'   => false,
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_pdf_3_file)): ?>
                                                                            <?= $this->Html->link(
                                                                                $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                                . h($userProfileTimelineEntry->entry_pdf_3),
                                                                                h($userProfileTimelineEntry->entry_pdf_3_file),
                                                                                [
                                                                                    'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                                    'target'        => '_blank',
                                                                                    'escapeTitle'   => false,
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_pdf_4_file)): ?>
                                                                            <?= $this->Html->link(
                                                                                $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                                . h($userProfileTimelineEntry->entry_pdf_4),
                                                                                h($userProfileTimelineEntry->entry_pdf_4_file),
                                                                                [
                                                                                    'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                                    'target'        => '_blank',
                                                                                    'escapeTitle'   => false,
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_pdf_5_file)): ?>
                                                                            <?= $this->Html->link(
                                                                                $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                                . h($userProfileTimelineEntry->entry_pdf_5),
                                                                                h($userProfileTimelineEntry->entry_pdf_5_file),
                                                                                [
                                                                                    'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                                    'target'        => '_blank',
                                                                                    'escapeTitle'   => false,
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php
                                                        if (
                                                            !empty($userProfileTimelineEntry->entry_pdf_6_file) ||
                                                            !empty($userProfileTimelineEntry->entry_pdf_7_file) ||
                                                            !empty($userProfileTimelineEntry->entry_pdf_8_file) ||
                                                            !empty($userProfileTimelineEntry->entry_pdf_9_file)
                                                        ):
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_pdf_6_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                        . h($userProfileTimelineEntry->entry_pdf_6),
                                                                        h($userProfileTimelineEntry->entry_pdf_6_file),
                                                                        [
                                                                            'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                            'target'        => '_blank',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_pdf_7_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                        . h($userProfileTimelineEntry->entry_pdf_7),
                                                                        h($userProfileTimelineEntry->entry_pdf_7_file),
                                                                        [
                                                                            'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                            'target'        => '_blank',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_pdf_8_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                        . h($userProfileTimelineEntry->entry_pdf_8),
                                                                        h($userProfileTimelineEntry->entry_pdf_8_file),
                                                                        [
                                                                            'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                            'target'        => '_blank',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if (!empty($userProfileTimelineEntry->entry_pdf_9_file)): ?>
                                                                    <?= $this->Html->link(
                                                                        $this->Html->tag('i', '', ['class' => 'fas fa-download']) . ' '
                                                                        . h($userProfileTimelineEntry->entry_pdf_9),
                                                                        h($userProfileTimelineEntry->entry_pdf_9_file),
                                                                        [
                                                                            'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                                                            'target'        => '_blank',
                                                                            'escapeTitle'   => false,
                                                                        ]); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <br />
                                                <?php endif; ?>

                                                <?php if (!empty($userProfileTimelineEntry->entry_guitar_pro_file)):?>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <div class="at-wrap at-wrap-<?= h($userProfileTimelineEntry->entry_no); ?>">
                                                                <div class="at-content">
                                                                    <div class="at-viewport">
                                                                        <div class="at-main-<?= h($userProfileTimelineEntry->entry_no); ?>"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'alphatab' . DS . 'alphatab'); ?>
                                                            <?= $this->Html->script(
                                                                'https://cdn.jsdelivr.net/npm/@coderline/alphatab@latest/dist/alphaTab.js',
                                                                ['block' => 'scriptBottom']); ?>
                                                            <?= $this->Html->scriptBlock(
                                                                '$(function() {
                                                                    const wrapper_' . h($userProfileTimelineEntry->entry_no) . ' = document.querySelector(\'.at-wrap-' . h($userProfileTimelineEntry->entry_no) . '\');
                                                                    const main_' . h($userProfileTimelineEntry->entry_no) . ' = wrapper_' . h($userProfileTimelineEntry->entry_no) . '.querySelector(\'.at-main-' . h($userProfileTimelineEntry->entry_no) . '\');
                                                                    const settings_' . h($userProfileTimelineEntry->entry_no) . ' = {
                                                                        file: \'' . $userProfileTimelineEntry->entry_guitar_pro_file . '\',
                                                                    };
                                                                    const api = new alphaTab.AlphaTabApi(main_' . h($userProfileTimelineEntry->entry_no) . ', settings_' . h($userProfileTimelineEntry->entry_no) . ');
                                                                });',
                                                                ['block' => 'scriptBottom']); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <p><?= $this->Html->link(
                                                        $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                                        . ' '
                                                        . '(' . $this->Html->tag('span', h($userProfileTimelineEntry->view_counter), ['class' => 'text-dark']) . ')',
                                                        'javascript:void(0)',
                                                        [
                                                            'title'         => __d('yab_cms_ff', 'Views'),
                                                            'class'         => 'text-success text-sm mr-2',
                                                            'escapeTitle'   => false,
                                                        ]); ?>
                                                    <?= $this->Html->link(
                                                        $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                                        . ' '
                                                        . __d('yab_cms_ff', 'Link'),
                                                        [
                                                            'plugin'        => 'YabCmsFf',
                                                            'controller'    => 'UserProfileTimelineEntries',
                                                            'action'        => 'viewBySlug',
                                                            'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                            'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                        ],
                                                        [
                                                            'class'         => 'text-sm mr-2',
                                                            'escapeTitle'   => false,
                                                        ]); ?>
                                                    <?= $this->Html->link(
                                                        $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                                        . ' '
                                                        . __d('yab_cms_ff', 'Telegram'),
                                                        'https://t.me/share/url' . '?'
                                                        . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileTimelineEntry->entry_title)) . '&'
                                                        . 'url=' . $this->Url->build([
                                                            'plugin'        => 'YabCmsFf',
                                                            'controller'    => 'UserProfileTimelineEntries',
                                                            'action'        => 'viewBySlug',
                                                            'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                            'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                        ], ['fullBase' => true]),
                                                        [
                                                            'target'        => '_blank',
                                                            'class'         => 'text-sm mr-2',
                                                            'escapeTitle'   => false,
                                                        ]); ?>
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                                    . ' '
                                                    . __d('yab_cms_ff', 'Twitter'),
                                                    'https://twitter.com/intent/tweet' . '?'
                                                    . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileTimelineEntry->entry_title)) . '&'
                                                    . 'url=' . $this->Url->build([
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'UserProfileTimelineEntries',
                                                        'action'        => 'viewBySlug',
                                                        'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                        'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                    ], ['fullBase' => true]),
                                                    [
                                                        'target'        => '_blank',
                                                        'class'         => 'text-sm mr-2',
                                                        'escapeTitle'   => false,
                                                    ]); ?><p>

                                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'ekko-lightbox' . DS . 'ekko-lightbox'); ?>
                                                    <?= $this->Html->script(
                                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'ekko-lightbox' . DS . 'ekko-lightbox',
                                                        ['block' => 'scriptBottom']); ?>
                                                    <?= $this->Html->scriptBlock(
                                                        '$(function() {
                                                            $(\'.gallery-' . h($userProfileTimelineEntry->foreign_key) . '\').click(function() {
                                                                event.preventDefault();
                                                                $(this).ekkoLightbox({
                                                                    alwaysShowClose: true
                                                                });
                                                            });
                                                        });',
                                                        ['block' => 'scriptBottom']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $entryDateLabel = $userProfileTimelineEntry->entry_date->format('Y-m-d'); ?>
                                    <?php $count++; ?>

                                    <?php if ($entriesCounter == $count): ?>
                                        <div><i class="far fa-clock bg-gray"></i></div>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            </div>
                        <?php else: ?>
                            <?= __d('yab_cms_ff', 'No timeline entries yet'); ?>
                        <?php endif; ?>

                    </div>
                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'users' . DS . 'profile'); ?>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'yab_cms_ff.select2'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        // Initialize select2
        $(\'.select2\').select2();
    });',
    ['block' => 'scriptBottom']); ?>