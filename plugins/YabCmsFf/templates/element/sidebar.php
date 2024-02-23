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
use Cake\ORM\TableRegistry;

// Get session object
$session = $this->getRequest()->getSession();

$frontendSidebarColor = 'dark';
if (Configure::check('YabCmsFf.settings.frontendSidebarColor')):
    $frontendSidebarColor = Configure::read('YabCmsFf.settings.frontendSidebarColor');
endif;

$frontendSidebarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.frontendSidebarTextColor')):
    $frontendSidebarTextColor = Configure::read('YabCmsFf.settings.frontendSidebarTextColor');
endif;

$frontendSidebarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendSidebarBackgroundColor')):
    $frontendSidebarBackgroundColor = Configure::read('YabCmsFf.settings.frontendSidebarBackgroundColor');
endif;

$UserProfiles = TableRegistry::getTableLocator()->get('YabCmsFf.UserProfiles');
$userProfilesCount = $UserProfiles
    ->find()
    ->where(['status' => 1])
    ->count();
?>
<aside class="main-sidebar sidebar-<?= h($frontendSidebarColor); ?>-<?= h($frontendSidebarTextColor); ?> elevation-4">
    <?= $this->Html->link(
        $this->Html->image(
            'logo_icon.png',
            [
                'alt'   => 'YAB CMS FF',
                'class' => 'brand-image img-circle elevation-3',
                'style' => 'opacity: .8',
            ]
        )
        . $this->Html->tag('span', __d('yab_cms_ff', 'YAB CMS FF'), ['class' => 'brand-text font-weight-light']),
        '/',
        [
            'class'         => 'brand-link bg-' . h($frontendSidebarBackgroundColor),
            'escapeTitle'   => false,
        ]); ?>
    <div class="sidebar">
        <?php if ($session->check('Auth.User.id')): ?>
            <?php if ($session->check('Auth.User.avatar')): ?>
                <div class="user-panel accent-<?= h($frontendSidebarTextColor); ?> mt-3 pb-3 mb-3 d-flex">
                    <div class="image"><?= $this->Html->image(
                        $session->read('Auth.User.avatar'),
                        [
                            'alt'   => h($session->read('Auth.User.username')),
                            'class' => 'img-circle elevation-2',
                        ]); ?></div>
                    <div class="info"><?= $this->Html->link(
                        h($session->read('Auth.User.username')),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Users',
                            'action'        => 'profile',
                        ],
                        ['class' => 'd-block']); ?></div>
                </div>
            <?php else: ?>
                <div class="user-panel accent-<?= h($frontendSidebarTextColor); ?> mt-3 pb-3 mb-3 d-flex">
                    <div class="image"><?= $this->Html->image(
                        '/yab_cms_ff/img/avatars/avatar.jpg',
                        [
                            'alt'   => h($session->read('Auth.User.name')),
                            'class' => 'img-circle elevation-2',
                        ]); ?></div>
                    <div class="info"><?= $this->Html->link(
                        h($session->read('Auth.User.name')),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Users',
                            'action'        => 'profile',
                        ],
                        ['class' => 'd-block']); ?></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item"><?= $this->Html->link(
                    $this->Html->tag('i', '', ['class' => 'nav-icon fas fa-th'])
                    . $this->Html->tag('p', __d('yab_cms_ff', 'Dashboard')),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Dashboards',
                        'action'        => 'dashboard',
                    ],
                    [
                        'class'         => 'nav-link',
                        'escapeTitle'   => false,
                    ]); ?></li>
                <li class="nav-item"><?= $this->Html->link(
                    $this->Html->tag('i', '', ['class' => 'nav-icon fas fa-users'])
                    . $this->Html->tag('p', __d('yab_cms_ff', 'Profiles') . $this->Html->tag('span', h($userProfilesCount), ['class' => 'badge badge-primary right'])),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'UserProfiles',
                        'action'        => 'index',
                    ],
                    [
                        'class'         => 'nav-link',
                        'escapeTitle'   => false,
                    ]); ?></li>
                <li class="nav-item"><?= $this->Html->link(
                    $this->Html->tag('i', '', ['class' => 'nav-icon fas fa-edit'])
                    . $this->Html->tag('p', __d('yab_cms_ff', 'Projects') . $this->Html->tag('span', __d('yab_cms_ff', 'NEW'), ['class' => 'right badge badge-success'])),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Articles',
                        'action'        => 'index',
                        'articleType'   => 'project',
                    ],
                    [
                        'class'         => 'nav-link',
                        'escapeTitle'   => false,
                    ]); ?></li>
            </ul>
        </nav>
    </div>
</aside>
