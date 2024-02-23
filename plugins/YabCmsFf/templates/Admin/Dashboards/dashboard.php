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

$backendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.backendBoxColor')):
    $backendBoxColor = Configure::read('YabCmsFf.settings.backendBoxColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
);
// Breadcrumb
$this->Breadcrumbs->add([
    ['title' => __d('yab_cms_ff', 'Dashboard')]
]); ?>

<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($articlesCount)? 0: h($articlesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Articles'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('file'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Articles',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($articleTypesCount)? 0: h($articleTypesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Article types'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'ArticleTypes',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($articleTypeAttributesCount)? 0: h($articleTypeAttributesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Article type attributes'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'ArticleTypeAttributes',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($articleTypeAttributeChoicesCount)? 0: h($articleTypeAttributeChoicesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Article type attribute choices'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'ArticleTypeAttributeChoices',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($domainsCount)? 0: h($domainsCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Domains'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('globe'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Domains',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($localesCount)? 0: h($localesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Locales'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Locales',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($regionsCount)? 0: h($regionsCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Regions'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Regions',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($countriesCount)? 0: h($countriesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Countries'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Countries',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($usersCount)? 0: h($usersCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Users'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('user'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Users',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= h($backendBoxColor); ?>">
            <div class="inner">
                <h3><?= empty($rolesCount)? 0: h($rolesCount); ?></h3>
                <p><?= __d('yab_cms_ff', 'Active'); ?> <?= __d('yab_cms_ff', 'Roles'); ?></p>
            </div>
            <div class="icon"><?= $this->Html->icon('list'); ?></div>
            <?= $this->Html->link(
                __d('yab_cms_ff', 'More info')
                . ' '
                . $this->Html->icon('arrow-circle-right'),
                [
                    'prefix'        => 'Admin',
                    'plugin'        => 'YabCmsFf',
                    'controller'    => 'Roles',
                    'action'        => 'index',
                ],
                [
                    'class'         => 'small-box-footer',
                    'escapeTitle'   => false,
                ]); ?>
        </div>
    </div>

</div>
