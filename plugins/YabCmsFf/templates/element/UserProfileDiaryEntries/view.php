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
                                        'title'     => __d('yab_cms_ff', 'Give a star'),
                                        'id'        => 'starCounter' . h($userProfileDiaryEntry->foreign_key),
                                        'class'     => 'text-warning text-sm mr-2',
                                        'escape'    => false,
                                    ]); ?>
                                <?= $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                    . ' '
                                    . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->view_counter), ['class' => 'text-dark']) . ')',
                                    'javascript:void(0)',
                                    [
                                        'title'     => __d('yab_cms_ff', 'Views'),
                                        'class'     => 'text-success text-sm mr-2',
                                        'escape'    => false,
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
                                        'target'    => '_blank',
                                        'class'     => 'text-sm mr-2',
                                        'escape'    => false,
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
                                        'target'    => '_blank',
                                        'class'     => 'text-sm mr-2',
                                        'escape'    => false,
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
                                        'escape'        => false,
                                    ]); ?>
                                    <?= $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fas fa-eye mr-1'])
                                        . ' '
                                        . '(' . $this->Html->tag('span', h($userProfileDiaryEntry->view_counter), ['class' => 'text-dark']) . ')',
                                        'javascript:void(0)',
                                        [
                                            'title'     => __d('yab_cms_ff', 'Views'),
                                            'class'     => 'text-success text-sm mr-2',
                                            'escape'    => false,
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
                                            'target'    => '_blank',
                                            'class'     => 'text-sm mr-2',
                                            'escape'    => false,
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
                                            'target'    => '_blank',
                                            'class'     => 'text-sm mr-2',
                                            'escape'    => false,
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
                                                        'class'     => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block mb-3',
                                                        'style'     => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                                        'escape'    => false,
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
                                                        'class'     => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block mb-0',
                                                        'style'     => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                                        'escape'    => false,
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
