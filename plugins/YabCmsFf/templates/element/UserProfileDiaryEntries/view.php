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

$frontendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendLinkTextColor')):
    $frontendLinkTextColor = Configure::read('YabCmsFf.settings.frontendLinkTextColor');
endif;

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
    !empty($userProfileDiaryEntry->user->user_profile->first_name) &&
    !empty($userProfileDiaryEntry->user->user_profile->middle_name) &&
    !empty($userProfileDiaryEntry->user->user_profile->last_name)
):
    $userProfileName = htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->first_name) . ' '
        . htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->middle_name) . ' '
        . htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->last_name);
elseif (
    !empty($userProfileDiaryEntry->user->user_profile->first_name) &&
    !empty($userProfileDiaryEntry->user->user_profile->last_name)
):
    $userProfileName = htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->first_name) . ' '
        . htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->last_name);
elseif (!empty($userProfileDiaryEntry->user->user_profile->last_name)):
    $userProfileName = htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->last_name);
else:
    $userProfileName = htmlspecialchars_decode($userProfileDiaryEntry->user->user_profile->first_name);
endif;

// Title
$this->assign('title', __d('yab_cms_ff', '{userProfileName} Diary Entry "{entryTitle}"', ['userProfileName' => $userProfileName, 'entryTitle' => htmlspecialchars_decode($userProfileDiaryEntry->entry_title)]));

$this->Html->meta('robots', 'index, follow', ['block' => true]);
$this->Html->meta('author', $userProfileName, ['block' => true]);
$this->Html->meta('description', __d('yab_cms_ff', '{userProfileName} Diary Entry "{entryTitle}"', ['userProfileName' => $userProfileName, 'entryTitle' => htmlspecialchars_decode($userProfileDiaryEntry->entry_title)]), ['block' => true]);

$this->Html->meta([
    'property'  => 'og:title',
    'content'   => __d('yab_cms_ff', '{userProfileName} Diary Entry "{entryTitle}"', ['userProfileName' => $userProfileName, 'entryTitle' => htmlspecialchars_decode($userProfileDiaryEntry->entry_title)]),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:description',
    'content'   => strip_tags(htmlspecialchars_decode($userProfileDiaryEntry->entry_body)),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:url',
    'content'   => $this->Url->build([
        'plugin'        => 'YabCmsFf',
        'controller'    => 'UserProfileDiaryEntries',
        'action'        => 'view',
        'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
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
    'content'   => 'article',
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
            'foreignKey'    => h($userProfileDiaryEntry->user->user_profile->foreign_key),
        ],
    ],
    [
        'title' => __d('yab_cms_ff', 'Diary entries'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'UserProfileDiaryEntries',
            'action'        => 'index',
            'foreignKey'    => h($userProfileDiaryEntry->user->user_profile->foreign_key),
        ],
    ],
    ['title' => htmlspecialchars_decode($userProfileDiaryEntry->entry_title)],
]); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= htmlspecialchars_decode($userProfileDiaryEntry->entry_title); ?>
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
                    <div class="card-body">

                        <?php
                            if (
                                $session->check('Auth.User.id') &&
                                ($session->read('Auth.User.id') == $userProfileDiaryEntry->user->user_profile->user_id)
                            ):
                        ?>
                            <div class="post">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Add a diary entry')
                                    . ' '
                                    . $this->Html->tag('i', '', ['class' => 'fas fa-plus']),
                                    'javascript:void(0)',
                                    [
                                        'title'         => __d('yab_cms_ff', 'Add a diary entry'),
                                        'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-block',
                                        'type'          => 'button',
                                        'data-toggle'   => 'collapse',
                                        'data-target'   => '#collapse_add_diary_entry_collapse',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <div class="collapse" id="collapse_add_diary_entry_collapse">
                                    <hr data-content="<?= __d('yab_cms_ff', 'Add'); ?>" class="hr-text">
                                    <?= $this->Form->create(null, [
                                        'role'  => 'form',
                                        'type'  => 'file',
                                        'url' => [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfileDiaryEntries',
                                            'action'        => 'add',
                                        ],
                                        'class' => 'form-horizontal user-profile-diary-entry-add',
                                    ]); ?>
                                    <?= $this->Form->control('entry_title', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'     => 'col-sm-2 col-form-label',
                                            'text'      => $this->Html->tag('p', __d('yab_cms_ff', 'Title') . '*', ['class' => 'text-danger']),
                                            'escape'    => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => true,
                                        'maxlenght' => 254,
                                    ]); ?>
                                    <?= $this->Form->control('entry_body', [
                                        'type'  => 'textarea',
                                        'label' => [
                                            'class'     => 'col-sm-2 col-form-label',
                                            'text'      => $this->Html->tag('p', __d('yab_cms_ff', 'Text') . '*', ['class' => 'text-danger']),
                                            'escape'    => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'entry_body',
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('entry_avatar', [
                                        'type'      => 'hidden',
                                        'value'     => '/yab_cms_ff/img/avatars/avatar.jpg',
                                    ]); ?>
                                    <?= $this->Form->control('entry_avatar_file', [
                                        'type'      => 'file',
                                        'accept'    => 'image/jpeg,image/jpg,image/gif',
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Avatar') . ' ' . '(jpg/gif)',
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                    ]); ?>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>
                                <?= $this->Html->scriptBlock(
                                    '$(function() {
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
                                        $(\'.user-profile-diary-entry-add\').submit(function(event) {
                                            $(\'.entry_body\').summernote(\'destroy\');
                                        });
                                        $(\'.user-profile-diary-entry-add\').validate({
                                            rules: {
                                                entry_title: {
                                                    required: true
                                                }
                                            },
                                            messages: {
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

                        <div class="post">
                            <div class="user-block">
                                <?= $this->Html->image(
                                    h($userProfileDiaryEntry->entry_avatar),
                                    [
                                        'alt'   => h($userProfileDiaryEntry->entry_avatar),
                                        'class' => 'img-circle img-bordered-sm',
                                    ]); ?>
                                <span class="username">
                                <?= htmlspecialchars_decode($userProfileDiaryEntry->entry_title); ?>
                            </span>
                                <span class="description"><?= h($userProfileDiaryEntry->created->nice()); ?></span>
                            </div>
                            <?= htmlspecialchars_decode($userProfileDiaryEntry->entry_body); ?>

                            <?php if ($session->check('Auth.User.id')): ?>
                                <p><?= $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fas fa-star mr-1'])
                                    . ' '
                                    . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->entry_star_counter), ['class' => 'text-dark star-counter-' . h($userProfileDiaryEntry->foreign_key)]) . ')',
                                    'javascript:void(0)',
                                    [
                                        'title'         => __d('yab_cms_ff', 'Give a star'),
                                        'id'            => 'starCounter' . h($userProfileDiaryEntry->foreign_key),
                                        'class'         => 'text-warning text-sm mr-2',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                    . ' '
                                    . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->view_counter), ['class' => 'text-dark']) . ')',
                                    'javascript:void(0)',
                                    [
                                        'title'         => __d('yab_cms_ff', 'Views'),
                                        'class'         => 'text-success text-sm mr-2',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                    . ' '
                                    . __d('yab_cms_ff', 'Telegram'),
                                    'https://t.me/share/url' . '?'
                                    . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileDiaryEntry->entry_title)) . '&'
                                    . 'url=' . $this->Url->build([
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'UserProfileDiaryEntries',
                                        'action'        => 'view',
                                        'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
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
                                    . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileDiaryEntry->entry_title)) . '&'
                                    . 'url=' . $this->Url->build([
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'UserProfileDiaryEntries',
                                        'action'        => 'view',
                                        'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
                                    ], ['fullBase' => true]),
                                    [
                                        'target'        => '_blank',
                                        'class'         => 'text-sm mr-2',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                <?php
                                    if (
                                        $session->check('Auth.User.id') &&
                                        ($session->read('Auth.User.id') == $userProfileDiaryEntry->user->user_profile->user_id)
                                    ):
                                ?>
                                    <?= $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fas fa-edit mr-1']),
                                        'javascript:void(0)',
                                        [
                                            'title'         => __d('yab_cms_ff', 'Edit'),
                                            'class'         => 'float-right btn-tool text-' . h($frontendLinkTextColor),
                                            'data-toggle'   => 'collapse',
                                            'data-target'   => '#collapse_' . h($userProfileDiaryEntry->foreign_key) . '_collapse',
                                            'escapeTitle'   => false,
                                        ]); ?>
                                <?php endif; ?>
                                </p>
                                <?php
                                    if (
                                        $session->check('Auth.User.id') &&
                                        ($session->read('Auth.User.id') == $userProfileDiaryEntry->user->user_profile->user_id)
                                    ):
                                ?>
                                    <div class="collapse" id="collapse_<?= h($userProfileDiaryEntry->foreign_key); ?>_collapse">
                                        <hr data-content="<?= __d('yab_cms_ff', 'Edit'); ?>" class="hr-text">
                                        <?= $this->Form->create(null, [
                                            'role'  => 'form',
                                            'type'  => 'file',
                                            'url' => [
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'UserProfileDiaryEntries',
                                                'action'        => 'edit',
                                            ],
                                            'class' => 'form-horizontal user-profile-diary-entry-edit-' . h($userProfileDiaryEntry->foreign_key),
                                        ]); ?>
                                        <?= $this->Form->control('foreign_key', [
                                            'type'  => 'hidden',
                                            'value'     => h($userProfileDiaryEntry->foreign_key),
                                            'required'  => true,
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_title', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'     => 'col-sm-2 col-form-label',
                                                'text'      => $this->Html->tag('p', __d('yab_cms_ff', 'Title') . '*', ['class' => 'text-danger']),
                                                'escape'    => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => true,
                                            'value'     => htmlspecialchars_decode($userProfileDiaryEntry->entry_title),
                                            'maxlenght' => 254,
                                        ]); ?>
                                        <?= $this->Form->control('entry_body', [
                                            'type'  => 'textarea',
                                            'label' => [
                                                'class'     => 'col-sm-2 col-form-label',
                                                'text'      => $this->Html->tag('p', __d('yab_cms_ff', 'Text') . '*', ['class' => 'text-danger']),
                                                'escape'    => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'entry_body_' . h($userProfileDiaryEntry->foreign_key),
                                            'required'  => true,
                                            'value'     => htmlspecialchars_decode($userProfileDiaryEntry->entry_body),
                                        ]); ?>
                                        <?= $this->Form->control('entry_avatar', [
                                            'type'      => 'hidden',
                                            'value'     => h($userProfileDiaryEntry->entry_avatar),
                                        ]); ?>
                                        <?= $this->Form->control('entry_avatar_file', [
                                            'type'      => 'file',
                                            'accept'    => 'image/jpeg,image/jpg,image/gif',
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Avatar') . ' ' . '(jpg/gif)',
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                        ]); ?>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                            </div>
                                        </div>
                                        <?= $this->Form->end(); ?>

                                        <?= $this->Html->scriptBlock(
                                            '$(function() {
                                                // Initialize summernote
                                                $(\'.entry_body_' . h($userProfileDiaryEntry->foreign_key) . '\').summernote({
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
                                                $(\'.user-profile-diary-entry-edit-' . h($userProfileDiaryEntry->foreign_key) . '\').submit(function(event) {
                                                    $(\'.entry_body_' . h($userProfileDiaryEntry->foreign_key) . '\').summernote(\'destroy\');
                                                });
                                                $(\'.user-profile-diary-entry-edit-' . h($userProfileDiaryEntry->foreign_key) . '\').validate({
                                                    rules: {
                                                        entry_title: {
                                                            required: true
                                                        }
                                                    },
                                                    messages: {
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

                                <?= $this->Html->scriptBlock(
                                    '$(function() {
                                    $(\'#starCounter' . h($userProfileDiaryEntry->foreign_key) . '\').on(\'click\', function() {
                                        $.ajax({
                                            beforeSend: function(xhr) {
                                                xhr.setRequestHeader(\'X-CSRF-Token\', \'' . $this->getRequest()->getAttribute('csrfToken') . '\');
                                            },
                                            url: \'' . $this->Url->build([
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'UserProfileDiaryEntries',
                                                'action'        => 'countUp',
                                            ]) . '\',
                                            data: {foreign_key: \'' . h($userProfileDiaryEntry->foreign_key) . '\'},
                                            type: \'JSON\',
                                            method: \'POST\',
                                            success: function(data) {
                                                $(\'.star-counter-' . h($userProfileDiaryEntry->foreign_key) . '\').html(data.entryStarCounter);
                                            }
                                        });
                                    });
                                });',
                                ['block' => 'scriptBottom']); ?>
                            <?php else: ?>
                                <p><?= $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fas fa-star mr-1'])
                                    . ' '
                                    . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->entry_star_counter), ['class' => 'text-dark']) . ')',
                                    'javascript:void(0)',
                                    [
                                        'title'         => __d('yab_cms_ff', 'Give a star'),
                                        'class'         => 'text-warning text-sm mr-2',
                                        'data-toggle'   => 'modal',
                                        'data-target'   => '#needLoginModal',
                                        'escapeTitle'   => false,
                                    ]); ?>
                                    <?= $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                        . ' '
                                        . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->view_counter), ['class' => 'text-dark']) . ')',
                                        'javascript:void(0)',
                                        [
                                            'title'         => __d('yab_cms_ff', 'Views'),
                                            'class'         => 'text-success text-sm mr-2',
                                            'escapeTitle'   => false,
                                        ]); ?>
                                    <?= $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                        . ' '
                                        . __d('yab_cms_ff', 'Telegram'),
                                        'https://t.me/share/url' . '?'
                                        . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileDiaryEntry->entry_title)) . '&'
                                        . 'url=' . $this->Url->build([
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfileDiaryEntries',
                                            'action'        => 'view',
                                            'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
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
                                        . 'text=' . rawurlencode(htmlspecialchars_decode($userProfileDiaryEntry->entry_title)) . '&'
                                        . 'url=' . $this->Url->build([
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfileDiaryEntries',
                                            'action'        => 'view',
                                            'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
                                        ], ['fullBase' => true]),
                                        [
                                            'target'        => '_blank',
                                            'class'         => 'text-sm mr-2',
                                            'escapeTitle'   => false,
                                        ]); ?></p>
                                <div class="modal fade" id="needLoginModal" tabindex="-1" role="dialog" aria-labelledby="needLoginModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="needLoginModalLabel"><?= __d('yab_cms_ff', 'Your action require the login'); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="<?= __d('yab_cms_ff', 'Close'); ?>">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-user-plus', 'style' => 'width: 24px; height: 22px; margin: 0 13px -2px -7px;'])
                                                    . __d('yab_cms_ff', 'Register'),
                                                    [
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'Users',
                                                        'action'        => 'register',
                                                    ],
                                                    [
                                                        'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block mb-3',
                                                        'style'         => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                                        'escapeTitle'   => false,
                                                    ]); ?>
                                                <p class="text-center font-weight-light">- <?= __d('yab_cms_ff', 'Or login'); ?> -</p>
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-sign-in-alt', 'style' => 'width: 24px; height: 22px; margin: 0 13px -2px -7px;'])
                                                    . __d('yab_cms_ff', 'Login'),
                                                    [
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'Users',
                                                        'action'        => 'login',
                                                    ],
                                                    [
                                                        'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block mb-0',
                                                        'style'         => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                                        'escapeTitle'   => false,
                                                    ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'users' . DS . 'profile'); ?>
