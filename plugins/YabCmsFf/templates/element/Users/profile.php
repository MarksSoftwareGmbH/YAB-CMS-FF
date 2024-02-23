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

// Title
$this->assign('title', __d('yab_cms_ff', 'Your user profile'));

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
    ['title' => __d('yab_cms_ff', 'Edit profile')]
]); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', 'Profile'); ?>
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
            <div class="col-md-3">

                <?php // Profile Image ?>
                <div class="card card-<?= h($frontendBoxColor); ?> card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <?php if (isset($userProfile) && !empty($userProfile->image)): ?>
                                <?= $this->Html->image(
                                    h($userProfile->image),
                                    [
                                        'alt'   => h($session->read('Auth.User.name')),
                                        'class' => 'profile-user-img img-fluid img-circle',
                                    ]); ?>
                            <?php else: ?>
                                <?= $this->Html->image(
                                    '/yab_cms_ff/img/avatars/avatar.jpg',
                                    [
                                        'alt'   => h($session->read('Auth.User.name')),
                                        'class' => 'profile-user-img img-fluid img-circle',
                                    ]); ?>
                            <?php endif; ?>
                        </div>
                        <h3 class="profile-username text-center">
                            <?= h($session->read('Auth.User.name')); ?>
                        </h3>
                        <p class="text-muted text-center">
                            <?= h($session->read('Auth.User.username')); ?>
                        </p>
                        <p class="text-muted text-center">
                            <?= __d('yab_cms_ff', 'Active since: {activationDate}', ['activationDate' => h($session->read('Auth.User.activation_date')->format('d.m.Y'))]); ?>
                        </p>
                    </div>
                </div>
                <?php // Profile Image End ?>

                <?php // About Me Box ?>
                <div class="card card-<?= h($frontendBoxColor); ?>">
                    <div class="card-header">
                        <h3 class="card-title"><?= __d('yab_cms_ff', 'About Me'); ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($userProfile) && !empty($userProfile->about_me)): ?>
                            <?= htmlspecialchars_decode($userProfile->about_me); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php // About Me Box End ?>

                <?php // Tags Box ?>
                <div class="card card-<?= h($frontendBoxColor); ?>">
                    <div class="card-header">
                        <h3 class="card-title"><?= __d('yab_cms_ff', 'Tags'); ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($userProfile) && !empty($userProfile->tags)): ?>
                            <input name="tags" value='<?= htmlspecialchars_decode($userProfile->tags); ?>' readonly>
                            <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'tagify'); ?>
                            <?= $this->Html->script(
                                'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'jQuery.tagify.min',
                                ['block' => 'scriptBottom']); ?>
                            <?= $this->Html->scriptBlock(
                                '$(function() {
                                    var inputTags = document.querySelector(\'input[name=tags]\');
                                    new Tagify(inputTags, {
                                        transformTag: transformTag,
                                    });
                                    function transformTag(tagData) {
                                        tagData.color = getRandomColor();
                                        tagData.style = \'--tag-bg:\' + tagData.color;
                                    }
                                    function getRandomColor() {
                                        function rand(min, max) {
                                            return min + Math.random() * (max - min);
                                        }
                                        var h = rand(1, 360)|0,
                                            s = rand(40, 70)|0,
                                            l = rand(65, 72)|0;
                                        return \'hsl(\' + h + \',\' + s + \'%,\' + l + \'%)\';
                                    }
                                });',
                                ['block' => 'scriptBottom']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php // Tags Box End ?>

            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">

                            <?php // Diary Pill ?>
                            <li class="nav-item">

                                <?php if (isset($userProfile) && !empty($userProfile->foreign_key)): ?>

                                    <?= $this->Html->link(
                                        __d('yab_cms_ff', 'Diary') . ' ' . '(' . $this->Html->tag('span', 0, ['class' => 'diary-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]) . ')',
                                        '#diaryTab',
                                        [
                                            'class'         => 'nav-link active',
                                            'data-toggle'   => 'tab',
                                            'escapeTitle'   => false,
                                        ]); ?>
                                    <?= $this->Html->scriptBlock(
                                        '$(function() {
                                            $(document).ready(function(){
                                                $.ajax({
                                                    beforeSend: function(xhr) {
                                                        xhr.setRequestHeader(\'X-CSRF-Token\', \'' . $this->getRequest()->getAttribute('csrfToken') . '\');
                                                    },
                                                    url: \'' . $this->Url->build([
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'UserProfiles',
                                                        'action'        => 'countDiaryEntries',
                                                    ]) . '\',
                                                    data: {foreign_key: \'' . htmlspecialchars_decode($userProfile->foreign_key) . '\'},
                                                    type: \'JSON\',
                                                    method: \'POST\',
                                                    success: function(data) {
                                                        $(\'.diary-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').html(data.diaryEntriesCounter);
                                                    }
                                                });
                                            });
                                        });',
                                        ['block' => 'scriptBottom']); ?>

                                <?php else: ?>

                                    <?= $this->Html->link(
                                        __d('yab_cms_ff', 'Diary'),
                                        '#diaryTab',
                                        [
                                            'class'         => 'nav-link active',
                                            'data-toggle'   => 'tab',
                                            'escapeTitle'   => false,
                                        ]); ?>

                                <?php endif; ?>

                            </li>
                            <?php // Diary Pill End ?>

                            <?php // Timeline Pill ?>
                            <li class="nav-item">

                                <?php if (isset($userProfile) && !empty($userProfile->foreign_key)): ?>

                                    <?= $this->Html->link(
                                        __d('yab_cms_ff', 'Timeline') . ' ' . '(' . $this->Html->tag('span', 0, ['class' => 'timeline-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]) . ')',
                                        '#timelineTab',
                                        [
                                            'class'         => 'nav-link',
                                            'data-toggle'   => 'tab',
                                            'escapeTitle'   => false,
                                        ]); ?>
                                    <?= $this->Html->scriptBlock(
                                        '$(function() {
                                            $(document).ready(function(){
                                                $.ajax({
                                                    beforeSend: function(xhr) {
                                                        xhr.setRequestHeader(\'X-CSRF-Token\', \'' . $this->getRequest()->getAttribute('csrfToken') . '\');
                                                    },
                                                    url: \'' . $this->Url->build([
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'UserProfiles',
                                                        'action'        => 'countTimelineEntries',
                                                    ]) . '\',
                                                    data: {foreign_key: \'' . htmlspecialchars_decode($userProfile->foreign_key) . '\'},
                                                    type: \'JSON\',
                                                    method: \'POST\',
                                                    success: function(data) {
                                                        $(\'.timeline-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').html(data.timelineEntriesCounter);
                                                    }
                                                });
                                            });
                                        });',
                                        ['block' => 'scriptBottom']); ?>

                                <?php else: ?>

                                    <?= $this->Html->link(
                                        __d('yab_cms_ff', 'Timeline'),
                                        '#timelineTab',
                                        [
                                            'class'         => 'nav-link',
                                            'data-toggle'   => 'tab',
                                            'escapeTitle'   => false,
                                        ]); ?>

                                <?php endif; ?>

                            </li>
                            <?php // Timeline Pill End ?>

                            <?php // Profile Data Pill ?>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Profile'),
                                    '#profileTab',
                                    [
                                        'class'         => 'nav-link',
                                        'data-toggle'   => 'tab',
                                        'escapeTitle'   => false,
                                    ]); ?>
                            </li>
                            <?php // Profile Data Pill End ?>

                            <?php // Account Data Pill ?>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Account'),
                                    '#accountTab',
                                    [
                                        'class'         => 'nav-link',
                                        'data-toggle'   => 'tab',
                                        'escapeTitle'   => false,
                                    ]); ?>
                            </li>
                            <?php // Account Data Pill End ?>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">

                            <?php // Diary Tab ?>
                            <div class="active tab-pane" id="diaryTab">
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
                                        <?= $this->Form->control('entry_body', [
                                            'type'  => 'textarea',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Text') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
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

                                <?php if (isset($userProfileDiaryEntries) && !empty($userProfileDiaryEntries)): ?>
                                    <?php foreach ($userProfileDiaryEntries as $userProfileDiaryEntry): ?>
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
                                            <p>
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-star mr-1'])
                                                    . ' '
                                                    . '(' . h($userProfileDiaryEntry->entry_star_counter) . ')',
                                                    'javascript:void(0)',
                                                    [
                                                        'class'         => 'link-black text-sm mr-2',
                                                        'escapeTitle'   => false,
                                                    ]); ?>
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                                    . ' '
                                                    . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->view_counter), ['class' => 'text-dark']) . ')',
                                                    'javascript:void(0)',
                                                    [
                                                        'title'         => __d('yab_cms_ff', 'Views'),
                                                        'class'         => 'text-black text-sm mr-2',
                                                        'escapeTitle'   => false,
                                                    ]); ?>
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-share mr-1'])
                                                    . ' '
                                                    . __d('yab_cms_ff', 'Link'),
                                                    [
                                                        'plugin'        => 'YabCmsFf',
                                                        'controller'    => 'UserProfileDiaryEntries',
                                                        'action'        => 'view',
                                                        'foreignKey'    => h($userProfileDiaryEntry->foreign_key),
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
                                                <?= $this->Html->link(
                                                    $this->Html->tag('i', '', ['class' => 'fas fa-edit']),
                                                    'javascript:void(0)',
                                                    [
                                                        'title'         => __d('yab_cms_ff', 'Edit'),
                                                        'class'         => 'float-right btn-tool text-' . h($frontendLinkTextColor),
                                                        'data-toggle'   => 'collapse',
                                                        'data-target'   => '#collapse_' . h($userProfileDiaryEntry->foreign_key) . '_collapse',
                                                        'escapeTitle'   => false,
                                                    ]); ?>
                                            </p>
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
                                                    'value'     => htmlspecialchars_decode($userProfileDiaryEntry->entry_title),
                                                    'maxlenght' => 254,
                                                ]); ?>
                                                <?= $this->Form->control('entry_body', [
                                                    'type'  => 'textarea',
                                                    'label' => [
                                                        'class'         => 'col-sm-2 col-form-label',
                                                        'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Text') . '*', ['class' => 'text-danger']),
                                                        'escapeTitle'   => false,
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
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <br />
                                    <?= __d('yab_cms_ff', 'No diary entries yet'); ?>
                                <?php endif; ?>
                            </div>
                            <?php // Diary Tab End ?>

                            <?php // Timeline Tab ?>
                            <div class="tab-pane" id="timelineTab">
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
                                                    'class'         => 'col-sm-2 col-form-label',
                                                    'text'          => __d('yab_cms_ff', 'Guitar Pro file') . ' ' . '(gp3 / gp4 / gp5 / gpx / gp)',
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
                                                    <strong>
                                                        <?= htmlspecialchars_decode($userProfileTimelineEntry->entry_title); ?>
                                                    </strong>
                                                    <i class="far fa-time"></i> <?= h($userProfileTimelineEntry->entry_date->format('M d, Y g:i:s a')); ?><br />
                                                    <br />
                                                    <?php if (!empty($userProfileTimelineEntry->entry_subtitle)): ?>
                                                        <?= htmlspecialchars_decode($userProfileTimelineEntry->entry_subtitle); ?>
                                                    <?php endif; ?>
                                                    <?= __d('yab_cms_ff', 'No. {entryNo} {linkIcon}', ['entryNo' => h($userProfileTimelineEntry->entry_no), 'linkIcon' => $this->Html->tag('i', '', ['class' => 'fas fa-link'])]); ?>
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
                                                                    <?= $this->Html->image(
                                                                        h($userProfileTimelineEntry->entry_image_1_file),
                                                                        [
                                                                            'alt'   => h($userProfileTimelineEntry->entry_image_1),
                                                                            'class' => 'img-fluid full-width-image mb-3',
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
                                                                            <?= $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_2_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_2),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_image_3_file)): ?>
                                                                            <?= $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_3_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_3),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_image_4_file)): ?>
                                                                            <?= $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_4_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_4),
                                                                                    'class' => 'img-fluid mb-3',
                                                                                ]); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php if (!empty($userProfileTimelineEntry->entry_image_5_file)): ?>
                                                                            <?= $this->Html->image(
                                                                                h($userProfileTimelineEntry->entry_image_5_file),
                                                                                [
                                                                                    'alt'   => h($userProfileTimelineEntry->entry_image_5),
                                                                                    'class' => 'img-fluid mb-3',
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
                                                                        <?= $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_6_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_6),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_7_file)): ?>
                                                                        <?= $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_7_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_7),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_8_file)): ?>
                                                                        <?= $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_8_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_8),
                                                                                'class' => 'img-fluid mb-3',
                                                                            ]); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <?php if (!empty($userProfileTimelineEntry->entry_image_9_file)): ?>
                                                                        <?= $this->Html->image(
                                                                            h($userProfileTimelineEntry->entry_image_9_file),
                                                                            [
                                                                                'alt'   => h($userProfileTimelineEntry->entry_image_9),
                                                                                'class' => 'img-fluid mb-3',
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
                                                                        const wrapper = document.querySelector(\'.at-wrap-' . h($userProfileTimelineEntry->entry_no) . '\');
                                                                        const main = wrapper.querySelector(\'.at-main-' . h($userProfileTimelineEntry->entry_no) . '\');
                                                                        const settings = {
                                                                            file: \'' . $userProfileTimelineEntry->entry_guitar_pro_file . '\',
                                                                        };
                                                                        const api = new alphaTab.AlphaTabApi(main, settings);
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
                                                                'class'         => 'text-sm mr-2 text-' . h($frontendLinkTextColor),
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
                                    <br />
                                    <?= __d('yab_cms_ff', 'No timeline entries yet'); ?>
                                <?php endif; ?>
                            </div>
                            <?php // Timeline Tab End ?>

                            <?php // Profile Data Tab ?>
                            <div class="tab-pane" id="profileTab">
                                <?php if (isset($userProfile) && !empty($userProfile)): ?>

                                    <?= $this->Form->create(null, [
                                        'role'  => 'form',
                                        'type'  => 'file',
                                        'url'   => [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfiles',
                                            'action'        => 'edit',
                                        ],
                                        'class' => 'form-horizontal user-profile-edit-' . htmlspecialchars_decode($userProfile->foreign_key),
                                    ]); ?>
                                    <?= $this->Form->control('uuid_id', [
                                        'type'  => 'hidden',
                                        'value' => h($userProfile->uuid_id),
                                    ]); ?>
                                    <?= $this->Form->control('foreign_key', [
                                        'type'      => 'text',
                                        'value'     => htmlspecialchars_decode($userProfile->foreign_key),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Foreign key') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => true,
                                        'readonly'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('timezone', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->timezone),
                                        'options'   => !empty($this->YabCmsFf->timezone())? $this->YabCmsFf->timezone(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Timezone') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('prefix', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->prefix),
                                        'options'   => !empty($this->YabCmsFf->prefixes())? $this->YabCmsFf->prefixes(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Prefix'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('salutation', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->salutation),
                                        'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Salutation') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('first_name', [
                                        'type'      => 'text',
                                        'value'     => htmlspecialchars_decode($userProfile->first_name),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'First name') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('middle_name', [
                                        'type'      => 'text',
                                        'value'     => htmlspecialchars_decode($userProfile->middle_name),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Middle name'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('last_name', [
                                        'type'      => 'text',
                                        'value'     => htmlspecialchars_decode($userProfile->last_name),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Last name') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('gender', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->gender),
                                        'options'   => [
                                            'Male'      => __d('yab_cms_ff', 'Male'),
                                            'Female'    => __d('yab_cms_ff', 'Female'),
                                        ],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Gender') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('telephone', [
                                        'type'          => 'number',
                                        'value'         => htmlspecialchars_decode($userProfile->telephone),
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Telephone'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('mobilephone', [
                                        'type'          => 'number',
                                        'value'         => htmlspecialchars_decode($userProfile->mobilephone),
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Mobilephone'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('fax', [
                                        'type'          => 'number',
                                        'value'         => htmlspecialchars_decode($userProfile->fax),
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Fax'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('website', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->website),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Website'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('company', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->company),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Company'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('street', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->street),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Street'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('street_addition', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->street_addition),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Street addition'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('postcode', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->postcode),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Postcode'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('city', [
                                        'type'  => 'text',
                                        'value' => htmlspecialchars_decode($userProfile->city),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'City'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('country_id', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->country_id),
                                        'options'   => !empty($this->YabCmsFf->countriesList())? $this->YabCmsFf->countriesList(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Country'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('status', [
                                        'type'      => 'select',
                                        'value'     => h($userProfile->status),
                                        'options'   => [
                                            0 => __d('yab_cms_ff', 'Inactive'),
                                            1 => __d('yab_cms_ff', 'Active'),
                                        ],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Status'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => false,
                                        'required'  => true,
                                    ]); ?>
                                    <hr />
                                    <?= $this->Form->control('about_me', [
                                        'type'  => 'textarea',
                                        'value' => htmlspecialchars_decode($userProfile->about_me),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'About Me'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'about_me_' . htmlspecialchars_decode($userProfile->foreign_key),
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('image', [
                                        'type'      => 'hidden',
                                        'value'     => htmlspecialchars_decode($userProfile->image),
                                    ]); ?>
                                    <?= $this->Form->control('image_file', [
                                        'type'      => 'file',
                                        'accept'    => 'image/jpeg,image/jpg',
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Image'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('tags', [
                                        'type'  => 'textarea',
                                        'value' => htmlspecialchars_decode($userProfile->tags),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Tags'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                    ]); ?>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10 mt-5">
                                            <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>

                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
                                    <?= $this->Html->script(
                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
                                        ['block' => 'scriptBottom']); ?>

                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'tagify'); ?>
                                    <?= $this->Html->script(
                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'jQuery.tagify.min',
                                        ['block' => 'scriptBottom']); ?>

                                    <?= $this->Html->scriptBlock(
                                        '$(function() {
                                            // Initialize select2
                                            $(\'.select2\').select2({ width:\'100%\'});
                                            // Initialize summernote
                                            $(\'.about_me_' . htmlspecialchars_decode($userProfile->foreign_key) . '\').summernote({
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
                                                placeholder: \'' . __d('yab_cms_ff', 'Please enter a valid about me text') . '\',
                                                tabsize: 2,
                                                height: 100
                                            });
                                            $(\'.user-profile-edit-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').submit(function(event) {
                                                $(\'.about_me_' . htmlspecialchars_decode($userProfile->foreign_key) . '\').summernote(\'destroy\');
                                            });
                                            $(\'.user-profile-edit-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').validate({
                                                rules: {
                                                    timezone: {
                                                        required: true
                                                    },
                                                    salutation: {
                                                        required: true
                                                    },
                                                    first_name: {
                                                        required: true
                                                    },
                                                    last_name: {
                                                        required: true
                                                    },
                                                    gender: {
                                                        required: true
                                                    }
                                                },
                                                messages: {
                                                    timezone: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a timezone') . '\'
                                                    },
                                                    salutation: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a salutation') . '\'
                                                    },
                                                    first_name: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid first name') . '\'
                                                    },
                                                    last_name: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid last name') . '\'
                                                    },
                                                    gender: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a gender') . '\'
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

                                            var inputTags = document.querySelector(\'textarea[name=tags]\');
                                            new Tagify(inputTags, {
                                                placeholder: \'' . __d('yab_cms_ff', 'Please enter 17 valid tags') . '\',
                                                delimiters: \',| \',
                                                backspace: \'edit\',
                                                maxTags: 17,
                                                transformTag: transformTag,
                                            });
                                            function transformTag(tagData) {
                                                tagData.color = getRandomColor();
                                                tagData.style = \'--tag-bg:\' + tagData.color;
                                            }
                                            function getRandomColor() {
                                                function rand(min, max) {
                                                    return min + Math.random() * (max - min);
                                                }
                                                var h = rand(1, 360)|0,
                                                    s = rand(40, 70)|0,
                                                    l = rand(65, 72)|0;
                                                return \'hsl(\' + h + \',\' + s + \'%,\' + l + \'%)\';
                                            }
                                        });',
                                        ['block' => 'scriptBottom']); ?>

                                <?php else: ?>

                                    <?= $this->Form->create(null, [
                                        'role'  => 'form',
                                        'type'  => 'file',
                                        'url'   => [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'UserProfiles',
                                            'action'        => 'add',
                                        ],
                                        'class' => 'form-horizontal user-profile-add',
                                    ]); ?>
                                    <?= $this->Form->control('uuid_id', [
                                        'type'  => 'hidden',
                                        'value' => Text::uuid(),
                                    ]); ?>
                                    <?= $this->Form->control('foreign_key', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Foreign key') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'value'     => Text::uuid(),
                                        'required'  => true,
                                        'readonly'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('timezone', [
                                        'type'      => 'select',
                                        'options'   => !empty($this->YabCmsFf->timezone())? $this->YabCmsFf->timezone(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Timezone') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('prefix', [
                                        'type'      => 'select',
                                        'options'   => !empty($this->YabCmsFf->prefixes())? $this->YabCmsFf->prefixes(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Prefix'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('salutation', [
                                        'type'      => 'select',
                                        'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Salutation') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('first_name', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'First name') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('middle_name', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Middle name'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('last_name', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Last name') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('gender', [
                                        'type'      => 'select',
                                        'options'   => [
                                            'Male'      => __d('yab_cms_ff', 'Male'),
                                            'Female'    => __d('yab_cms_ff', 'Female'),
                                        ],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Gender') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('telephone', [
                                        'type'          => 'number',
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Telephone'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('mobilephone', [
                                        'type'          => 'number',
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Mobilephone'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('fax', [
                                        'type'          => 'number',
                                        'placeholder'   => '00490123456789',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Fax'),
                                            'required'      => false,
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('website', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Website'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('company', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Company'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('street', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Street'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('street_addition', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Street addition'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('postcode', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Postcode'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('city', [
                                        'type'  => 'text',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'City'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => false,
                                    ]); ?>
                                    <?= $this->Form->control('country_id', [
                                        'type'      => 'select',
                                        'options'   => !empty($this->YabCmsFf->countriesList())? $this->YabCmsFf->countriesList(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Country'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('status', [
                                        'type'      => 'select',
                                        'options'   => [
                                            0 => __d('yab_cms_ff', 'Inactive'),
                                            1 => __d('yab_cms_ff', 'Active'),
                                        ],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Status'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => false,
                                        'required'  => true,
                                    ]); ?>
                                    <hr />
                                    <?= $this->Form->control('about_me', [
                                        'type'  => 'textarea',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'About Me'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'about_me',
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('tags', [
                                        'type'  => 'textarea',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Tags'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('image', [
                                        'type'      => 'hidden',
                                        'value'     => '/yab_cms_ff/img/avatars/avatar.jpg',
                                    ]); ?>
                                    <?= $this->Form->control('image_file', [
                                        'type'      => 'file',
                                        'accept'    => 'image/jpeg,image/jpg',
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Image'),
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

                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
                                    <?= $this->Html->script(
                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
                                        ['block' => 'scriptBottom']); ?>

                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'tagify'); ?>
                                    <?= $this->Html->script(
                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'jQuery.tagify.min',
                                        ['block' => 'scriptBottom']); ?>

                                    <?= $this->Html->scriptBlock(
                                        '$(function() {
                                            // Initialize select2
                                            $(\'.select2\').select2({ width:\'100%\'});
                                            // Initialize summernote
                                            $(\'.about_me\').summernote({
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
                                                placeholder: \'' . __d('yab_cms_ff', 'Please enter a valid about me text') . '\',
                                                tabsize: 2,
                                                height: 100
                                            });
                                            $(\'.user-profile-add\').submit(function(event) {
                                                $(\'.about_me\').summernote(\'destroy\');
                                            });
                                            $(\'.user-profile-add\').validate({
                                                rules: {
                                                    timezone: {
                                                        required: true
                                                    },
                                                    salutation: {
                                                        required: true
                                                    },
                                                    first_name: {
                                                        required: true
                                                    },
                                                    last_name: {
                                                        required: true
                                                    },
                                                    gender: {
                                                        required: true
                                                    }
                                                },
                                                messages: {
                                                    timezone: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a timezone') . '\'
                                                    },
                                                    salutation: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a salutation') . '\'
                                                    },
                                                    first_name: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid first name') . '\'
                                                    },
                                                    last_name: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid last name') . '\'
                                                    },
                                                    gender: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a gender') . '\'
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

                                            var inputTags = document.querySelector(\'textarea[name=tags]\');
                                            new Tagify(inputTags, {
                                                placeholder: \'' . __d('yab_cms_ff', 'Please enter 17 valid tags') . '\',
                                                delimiters: \',| \',
                                                backspace: \'edit\',
                                                maxTags: 17,
                                                transformTag: transformTag,
                                            });
                                            function transformTag(tagData) {
                                                tagData.color = getRandomColor();
                                                tagData.style = \'--tag-bg:\' + tagData.color;
                                            }
                                            function getRandomColor() {
                                                function rand(min, max) {
                                                    return min + Math.random() * (max - min);
                                                }
                                                var h = rand(1, 360)|0,
                                                    s = rand(40, 70)|0,
                                                    l = rand(65, 72)|0;
                                                return \'hsl(\' + h + \',\' + s + \'%,\' + l + \'%)\';
                                            }
                                        });',
                                        ['block' => 'scriptBottom']); ?>

                                <?php endif; ?>
                            </div>
                            <?php // Profile Data Tab End ?>

                            <?php // Account Data Tab ?>
                            <div class="tab-pane" id="accountTab">
                                <?php if (isset($userAccount) && !empty($userAccount)): ?>

                                    <?= $this->Form->create(null, [
                                        'url' => [
                                            'plugin'        => 'YabCmsFf',
                                            'controller'    => 'Users',
                                            'action'        => 'edit',
                                        ],
                                        'class' => 'form-horizontal user-edit-' . h($userAccount->id),
                                    ]); ?>
                                    <?= $this->Form->control('uuid_id', [
                                        'type'  => 'hidden',
                                        'value' => !empty($userAccount->uuid_id)? htmlspecialchars_decode($userAccount->uuid_id): Text::uuid(),
                                    ]); ?>
                                    <?= $this->Form->control('foreign_key', [
                                        'type'      => 'text',
                                        'value'     => !empty($userAccount->foreign_key)? htmlspecialchars_decode($userAccount->foreign_key): '',
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Foreign key')),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => false,
                                    ]); ?>
                                    <?= $this->Form->control('locale_id', [
                                        'type'      => 'select',
                                        'value'     => h($userAccount->locale_id),
                                        'options'   => !empty($this->YabCmsFf->localesList())? $this->YabCmsFf->localesList(): [],
                                        'label'     => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => __d('yab_cms_ff', 'Locale'),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'class'     => 'select2',
                                        'empty'     => true,
                                        'required'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('username', [
                                        'type'      => 'text',
                                        'value'     => h($userAccount->username),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Username') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates'     => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required'  => true,
                                        'readonly'  => true,
                                    ]); ?>
                                    <?= $this->Form->control('name', [
                                        'type'      => 'text',
                                        'value'     => htmlspecialchars_decode($userAccount->name),
                                        'label' => [
                                            'class'         => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Name') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <?= $this->Form->control('email', [
                                        'type'      => 'email',
                                        'value'     => htmlspecialchars_decode($userAccount->email),
                                        'label' => [
                                            'class'     => 'col-sm-2 col-form-label',
                                            'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Email') . '*', ['class' => 'text-danger']),
                                            'escapeTitle'   => false,
                                        ],
                                        'templates' => [
                                            'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                            'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                            'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                        ],
                                        'required' => true,
                                    ]); ?>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>

                                    <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
                                    <?= $this->Html->script(
                                        'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
                                        ['block' => 'scriptBottom']); ?>

                                    <?= $this->Html->scriptBlock(
                                        '$(function() {
                                            // Initialize select2
                                            $(\'.select2\').select2({ width:\'100%\'});
                                            $(\'.user-edit-' . h($userAccount->id) . '\').validate({
                                                rules: {
                                                    uuid_id: {
                                                        required: true
                                                    },
                                                    locale_id: {
                                                        required: true
                                                    },
                                                    username: {
                                                        required: true
                                                    },
                                                    name: {
                                                        required: true
                                                    },
                                                    email: {
                                                        required: true
                                                    }
                                                },
                                                messages: {
                                                    uuid_id: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid UUID id') . '\'
                                                    },
                                                    locale_id: {
                                                        required: \'' . __d('yab_cms_ff', 'Please select a locale') . '\'
                                                    },
                                                    username: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid username') . '\'
                                                    },
                                                    name: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid name') . '\'
                                                    },
                                                    email: {
                                                        required: \'' . __d('yab_cms_ff', 'Please enter a valid email address') . '\'
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
                                <?php endif; ?>
                            </div>
                            <?php // Account Data Tab End ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'users' . DS . 'profile'); ?>
