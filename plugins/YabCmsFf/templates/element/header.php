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

$frontendNavbarColor = 'dark';
if (Configure::check('YabCmsFf.settings.frontendNavbarColor')):
    $frontendNavbarColor = Configure::read('YabCmsFf.settings.frontendNavbarColor');
endif;

$frontendNavbarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.frontendNavbarTextColor')):
    $frontendNavbarTextColor = Configure::read('YabCmsFf.settings.frontendNavbarTextColor');
endif;

$frontendNavbarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendNavbarBackgroundColor')):
    $frontendNavbarBackgroundColor = Configure::read('YabCmsFf.settings.frontendNavbarBackgroundColor');
endif;

$frontendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendButtonColor')):
    $frontendButtonColor = Configure::read('YabCmsFf.settings.frontendButtonColor');
endif;

$frontendControlSidebar = '0';
if (Configure::check('YabCmsFf.settings.frontendControlSidebar')):
    $frontendControlSidebar = Configure::read('YabCmsFf.settings.frontendControlSidebar');
endif;
?>
<nav class="main-header navbar navbar-expand navbar-<?= h($frontendNavbarColor); ?> text-<?= h($frontendNavbarTextColor); ?> bg-<?= h($frontendNavbarBackgroundColor); ?> border-bottom-0 text-sm">
    <ul class="navbar-nav">
        <li class="nav-item">
            <?= $this->Html->link(
                $this->Html->icon('bars'),
                'javascript:void(0)',
                [
                    'class'         => 'nav-link',
                    'data-widget'   => 'pushmenu',
                    'role'          => 'button',
                    'escapeTitle'   => false,
                ]); ?>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <?= $this->Html->icon('search'); ?>
            </a>
            <div class="navbar-search-block">
                <?= $this->Form->create(null, [
                    'url' => [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Articles',
                        'action'        => 'search',
                    ],
                    'class' => 'form-inline',
                ]); ?>
                <div class="input-group input-group-sm">
                    <?= $this->Form->formGroup('search', [
                        'type'          => 'text',
                        'value'         => $this->getRequest()->getQuery('search'),
                        'label'         => false,
                        'placeholder'   => __d('yab_cms_ff', 'Search'),
                        'class'         => 'form-control form-control-navbar',
                    ]); ?>
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <?= $this->Html->icon('search'); ?>
                        </button>
                        <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                            <?= $this->Html->icon('times'); ?>
                        </button>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </li>
        <?php if ($session->check('Auth.User.id') && ($session->read('Auth.User.role.alias') === 'admin')): ?>
            <?php if ($frontendControlSidebar === '1'): ?>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                        <?= $this->Html->icon('th-large'); ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <li class="nav-item dropdown user-menu">
            <?php if ($session->check('Auth.User.id')): ?>
                <?php if ($session->check('Auth.User.avatar')): ?>
                    <?= $this->Html->link(
                        $this->Html->image(
                            $session->read('Auth.User.avatar'),
                            [
                                'alt'   => h($session->read('Auth.User.username')),
                                'class' => 'img-size-50 user-image img-circle elevation-2',
                            ]) .
                        $this->Html->tag('span', $session->read('Auth.User.username'), ['class' => 'd-none d-md-inline']),
                        'javascript:void(0)',
                        [
                            'class'         => 'nav-link dropdown-toggle',
                            'data-toggle'   => 'dropdown',
                            'escapeTitle'   => false,
                        ]); ?>
                <?php else: ?>
                    <?= $this->Html->link(
                        $this->Html->image(
                            '/yab_cms_ff/img/avatars/avatar.jpg',
                            [
                                'alt'   => h($session->read('Auth.User.name')),
                                'class' => 'img-size-50 user-image img-circle elevation-2',
                            ]) .
                        $this->Html->tag('span', $session->read('Auth.User.name'), ['class' => 'd-none d-md-inline']),
                        'javascript:void(0)',
                        [
                            'class'         => 'nav-link dropdown-toggle',
                            'data-toggle'   => 'dropdown',
                            'escapeTitle'   => false,
                        ]); ?>
                <?php endif; ?>
            <?php else: ?>
                <?= $this->Html->link(
                    $this->Html->image(
                        '/yab_cms_ff/img/avatars/avatar.jpg',
                        [
                            'alt'   => __d('yab_cms_ff', 'Logo'),
                            'class' => 'img-size-50 user-image img-circle elevation-2',
                        ]),
                    'javascript:void(0)',
                    [
                        'class'         => 'nav-link dropdown-toggle',
                        'data-toggle'   => 'dropdown',
                        'escapeTitle'   => false,
                    ]); ?>
            <?php endif; ?>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <?php if ($session->check('Auth.User.id')): ?>
                    <?php if ($session->check('Auth.User.avatar')): ?>
                        <li class="user-header bg-<?= h($frontendNavbarBackgroundColor); ?>"><?= $this->Html->image(
                            $session->read('Auth.User.avatar'),
                            [
                                'alt'   => h($session->read('Auth.User.username')),
                                'class' => 'img-size-50 img-circle elevation-2',
                            ]); ?><p><?= h($session->read('Auth.User.username')); ?><br /><small><?= h($session->read('Auth.User.email')); ?></small></p></li>
                    <?php else: ?>
                        <li class="user-header bg-<?= h($frontendNavbarBackgroundColor); ?>"><?= $this->Html->image(
                            '/yab_cms_ff/img/avatars/avatar.jpg',
                            [
                                'alt'   => h($session->read('Auth.User.name')),
                                'class' => 'img-size-50 img-circle elevation-2',
                            ]); ?><p><?= h($session->read('Auth.User.name')); ?><br /><small><?= h($session->read('Auth.User.email')); ?></small></p></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="user-footer">
                    <?php if ($session->check('Auth.User.id')): ?>
                        <?= $this->Html->link(
                            $this->Html->icon('user')
                            . ' '
                            . __d('yab_cms_ff', 'Edit profile'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Users',
                                'action'        => 'profile',
                            ],
                            [
                                'class'         => 'btn btn-' . h($frontendButtonColor),
                                'escapeTitle'   => false,
                            ]); ?>
                        <?= $this->Html->link(
                            $this->Html->icon('sign-out-alt')
                            . ' '
                            . __d('yab_cms_ff', 'Logout'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Users',
                                'action'        => 'logout',
                            ],
                            [
                                'class'         => 'btn btn-' . h($frontendButtonColor) . ' float-right',
                                'escapeTitle'   => false,
                            ]); ?>
                    <?php else: ?>
                        <?= $this->Html->link(
                            $this->Html->tag('i', '', ['class' => 'fas fa-user-plus', 'style' => 'width: 24px; height: 22px; margin: 0 13px -2px -7px;'])
                            . __d('yab_cms_ff', 'Register'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Users',
                                'action'        => 'register',
                            ],
                            [
                                'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block',
                                'style'         => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                'escapeTitle'   => false,
                            ]); ?>
                        <?= $this->Html->link(
                            $this->Html->tag('i', '', ['class' => 'fas fa-sign-in-alt', 'style' => 'width: 24px; height: 22px; margin: 0 13px -2px -7px;'])
                            . __d('yab_cms_ff', 'Login'),
                            [
                                'plugin'        => 'YabCmsFf',
                                'controller'    => 'Users',
                                'action'        => 'login',
                            ],
                            [
                                'class'         => 'btn btn-' . h($frontendButtonColor) . ' btn-flat btn-block',
                                'style'         => 'text-align: left; font-size: 16px !important; line-height: 20px; padding: 9px 21px 11px;',
                                'escapeTitle'   => false,
                            ]); ?>
                    <?php endif; ?>
                </li>
            </ul>
        </li>
        <?php if ($session->check('Auth.User.id') && ($session->read('Auth.User.role.alias') === 'admin')): ?>
            <li class="nav-item"><?= $this->Html->link(
                $this->Html->icon('th-large'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Dashboards',
                    'action'        => 'dashboard',
                ],
                [
                    'escapeTitle'   => false,
                    'class'         => 'nav-link',
                    'role'          => 'button',
                ]); ?></li>
        <?php endif; ?>
    </ul>
</nav>
<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'header'); ?>
