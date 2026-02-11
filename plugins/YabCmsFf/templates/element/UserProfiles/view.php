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

$locale = $session->check('Locale.code')? $session->read('Locale.code'): 'en_US';

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
$this->assign('title', __d('yab_cms_ff', '{userProfileName} Profile', ['userProfileName' => $userProfileName]));

$this->Html->meta('robots', 'index, follow', ['block' => true]);
$this->Html->meta('author', $userProfileName, ['block' => true]);
$this->Html->meta('description', __d('yab_cms_ff', '{userProfileName} Profile', ['userProfileName' => $userProfileName]), ['block' => true]);

$this->Html->meta([
    'property'  => 'og:title',
    'content'   => __d('yab_cms_ff', '{userProfileName} Profile', ['userProfileName' => $userProfileName]),
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
$this->Breadcrumbs->addMany([
    [
        'title' => __d('yab_cms_ff', 'Go back'),
        'url' => 'javascript:history.back()',
    ],
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
    ['title' => $userProfileName],
], ['class' => 'breadcrumb-item']); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', '{userProfileName} Profile', ['userProfileName' => $userProfileName]); ?>
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
                            <?php if (!empty($userProfile->image)): ?>
                                <?= $this->Html->image(
                                    h($userProfile->image),
                                    [
                                        'alt'   => h($userProfileName),
                                        'class' => 'profile-user-img img-fluid img-circle',
                                    ]); ?>
                            <?php else: ?>
                                <?= $this->Html->image(
                                    '/yab_cms_ff/img/avatar/avatar.jpg',
                                    [
                                        'alt'   => h($userProfileName),
                                        'class' => 'profile-user-img img-fluid img-circle',
                                    ]); ?>
                            <?php endif; ?>
                        </div>
                        <h3 class="profile-username text-center">
                            <?= h($userProfileName); ?>
                        </h3>
                        <?php if (isset($userProfile->company) && !empty($userProfile->company)): ?>
                            <p class="text-muted text-center">
                                <?= htmlspecialchars_decode($userProfile->company); ?>
                            </p>
                        <?php endif; ?>

                        <p class="text-muted text-center">
                            <?= __d('yab_cms_ff', 'Active since: {activationDate}', ['activationDate' => h($userProfile->created->nice())]); ?>
                        </p>
                    </div>
                </div>
                <?php // Profile Image End ?>

                <?php // About Me Box ?>
                <?php if (isset($userProfile) && !empty($userProfile->about_me)): ?>
                    <div class="card card-<?= h($frontendBoxColor); ?>">
                        <div class="card-header">
                            <h3 class="card-title"><?= __d('yab_cms_ff', 'About Me'); ?></h3>
                        </div>
                        <div class="card-body">
                            <?= htmlspecialchars_decode($userProfile->about_me); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php // About Me Box End ?>

                <?php // Tags Box ?>
                <?php if (isset($userProfile) && !empty($userProfile->tags)): ?>
                    <div class="card card-<?= h($frontendBoxColor); ?>">
                        <div class="card-header">
                            <h3 class="card-title"><?= __d('yab_cms_ff', 'Tags'); ?></h3>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                <?php endif; ?>
                <?php // Tags Box End ?>

            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">

                            <?php // Diary Pill ?>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Diary') . ' ' . '(' . $this->Html->tag('span', 0, ['class' => 'diary-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]) . ')',
                                    '#diary',
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
                            </li>
                            <?php // Diary Pill End ?>

                            <?php // Timeline Pill ?>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Timeline') . ' ' . '(' . $this->Html->tag('span', 0, ['class' => 'timeline-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]) . ')',
                                    '#timeline',
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
                            </li>
                            <?php // Timeline Pill End ?>

                            <?php // Data Pill ?>
                            <li class="nav-item">
                                <?= $this->Html->link(
                                    __d('yab_cms_ff', 'Data'),
                                    '#data',
                                    [
                                        'class'         => 'nav-link',
                                        'data-toggle'   => 'tab',
                                        'escapeTitle'   => false,
                                    ]); ?>
                            </li>
                            <?php // Data Pill End ?>

                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">

                            <?php // Diary Tab ?>
                            <div class="active tab-pane" id="diary">
                                <?php if (isset($userProfile->user->user_profile_diary_entries) && !empty($userProfile->user->user_profile_diary_entries)): ?>
                                    <?php foreach ($userProfile->user->user_profile_diary_entries as $userProfileDiaryEntry): ?>
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
                                                        ]); ?><p>
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
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?= __d('yab_cms_ff', 'No diary entries yet'); ?>
                                <?php endif; ?>
                            </div>
                            <?php // Diary Tab End ?>

                            <?php // Timeline Tab ?>
                            <div class="tab-pane" id="timeline">
                                <?php if (isset($userProfile->user->user_profile_timeline_entries) && !empty($userProfile->user->user_profile_timeline_entries)): ?>
                                    <?php $entriesCounter = count($userProfile->user->user_profile_timeline_entries); ?>
                                    <div class="timeline timeline-inverse">

                                        <?php $entryDateLabel = ''; ?>
                                        <?php $count = 0; ?>
                                        <?php foreach ($userProfile->user->user_profile_timeline_entries as $userProfileTimelineEntry): ?>
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
                                                        <i class="far fa-clock-o"></i> <?= h($userProfileTimelineEntry->entry_date->format('M d, Y g:i:s a')); ?><br />
                                                        <br />
                                                        <?php if (!empty($userProfileTimelineEntry->entry_subtitle)): ?>
                                                            <?= htmlspecialchars_decode($userProfileTimelineEntry->entry_subtitle); ?>
                                                        <?php endif; ?>
                                                        <small><?= $this->Html->link(
                                                            __d('yab_cms_ff', 'No. {entryNo} {linkIcon}', [
                                                                'entryNo'   => h($userProfileTimelineEntry->entry_no),
                                                                'linkIcon'  => $this->Html->tag('i', '', ['class' => 'fas fa-link'])
                                                            ]),
                                                            [
                                                                'plugin'        => 'YabCmsFf',
                                                                'controller'    => 'UserProfileTimelineEntries',
                                                                'action'        => 'viewBySlug',
                                                                'slug'          => $this->YabCmsFf->buildSlug(h($userProfileTimelineEntry->entry_title)),
                                                                'foreignKey'    => h($userProfileTimelineEntry->foreign_key),
                                                            ],
                                                            ['escapeTitle' => false]); ?></small>
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
                                                                    !empty($userProfileTimelineEntry->entry_video_6_file) ||
                                                                    !empty($userProfileTimelineEntry->entry_video_7_file) ||
                                                                    !empty($userProfileTimelineEntry->entry_video_8_file) ||
                                                                    !empty($userProfileTimelineEntry->entry_video_9_file)
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
                            <?php // Timeline Tab End ?>

                            <?php // Data Tab ?>
                            <div class="tab-pane" id="data">
                                <dl class="row">

                                    <?php if (!empty($userProfile->foreign_key)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Profile Id.'); ?></dt>
                                        <dd class="col-sm-8"><?= h($userProfile->foreign_key); ?></dd>
                                    <?php endif; ?>

                                    <?php if (
                                        !empty($userProfile->first_name) &&
                                        !empty($userProfile->middle_name) &&
                                        !empty($userProfile->last_name)
                                    ): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Name'); ?></dt>
                                        <dd class="col-sm-8"><?= htmlspecialchars_decode($userProfile->first_name) . ' ' . htmlspecialchars_decode($userProfile->middle_name) . ' ' . htmlspecialchars_decode($userProfile->last_name); ?></dd>
                                    <?php elseif (
                                        !empty($userProfile->first_name) &&
                                        !empty($userProfile->last_name)
                                    ): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Name'); ?></dt>
                                        <dd class="col-sm-8"><?= htmlspecialchars_decode($userProfile->first_name) . ' ' . htmlspecialchars_decode($userProfile->last_name); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->company)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Company'); ?></dt>
                                        <dd class="col-sm-8"><?= htmlspecialchars_decode($userProfile->company); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->street)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Street'); ?></dt>
                                        <dd class="col-sm-8">
                                            <?= htmlspecialchars_decode($userProfile->street); ?>
                                            <?php if (!empty($userProfile->street_addition)): ?>
                                                <?= htmlspecialchars_decode($userProfile->street_addition); ?>
                                            <?php endif; ?>
                                        </dd>
                                    <?php endif; ?>

                                    <?php if (
                                        !empty($userProfile->postcode) &&
                                        !empty($userProfile->city)
                                    ): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Address'); ?></dt>
                                        <dd class="col-sm-8">
                                            <?= htmlspecialchars_decode($userProfile->postcode); ?>
                                            <?= htmlspecialchars_decode($userProfile->city); ?>
                                        </dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->country_id)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Country'); ?></dt>
                                        <dd class="col-sm-8">
                                            <?= !empty($userProfile->country_id)? $this->YabCmsFf->countriesList($userProfile->country_id): ''; ?>
                                        </dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->website)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Website'); ?></dt>
                                        <dd class="col-sm-8"><?= $this->Html->link(
                                                h($userProfile->website),
                                                h($userProfile->website),
                                                [
                                                    'target'        => '_blank',
                                                    'escapeTitle'   => false,
                                                ]); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->telephone)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Telephone'); ?></dt>
                                        <dd class="col-sm-8"><?= $this->Html->link(
                                                h($userProfile->telephone),
                                                'tel:' . h($userProfile->telephone),
                                                [
                                                    'target'        => '_blank',
                                                    'escapeTitle'   => false,
                                                ]); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->mobilephone)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Mobilephone'); ?></dt>
                                        <dd class="col-sm-8"><?= $this->Html->link(
                                                h($userProfile->mobilephone),
                                                'tel:' . h($userProfile->mobilephone),
                                                [
                                                    'target'        => '_blank',
                                                    'escapeTitle'   => false,
                                                ]); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->fax)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'FAX'); ?></dt>
                                        <dd class="col-sm-8"><?= h($userProfile->fax); ?></dd>
                                    <?php endif; ?>

                                    <?php if (!empty($userProfile->timezone)): ?>
                                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Timezone'); ?></dt>
                                        <dd class="col-sm-8"><?= h($userProfile->timezone); ?></dd>
                                    <?php endif; ?>

                                </dl>
                            </div>
                            <?php // Data Tab End ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'users' . DS . 'profile'); ?>
