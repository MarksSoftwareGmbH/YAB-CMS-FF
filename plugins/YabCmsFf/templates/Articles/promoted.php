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

// Title
$this->assign('title', __d('yab_cms_ff', 'Yet another boring CMS for FREE'));

// Breadcrumb
$this->Breadcrumbs->add([
    ['title' => 'Yet another boring CMS for FREE'],
    ['title' => __d('yab_cms_ff', 'Recent CMS articles')]
]); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= ucfirst($this->getRequest()->getParam('action')); ?> <?= $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller')); ?>
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
            <div class="col-12">
                <div class="callout callout-<?= h($frontendBoxColor); ?>">
                    <h5><i class="fas fa-info"></i> <?= __d('yab_cms_ff', 'Important note');?>:</h5>
                    <strong><?= __d('yab_cms_ff', 'Yet another boring CMS for FREE is free.'); ?></strong><br />
                </div>
            </div>
        </div>
        <?php if (isset($articles) && !empty($articles)): ?>
            
            <?php $slides = []; ?>
            <?php foreach($articles as $article): ?>
                <?php if ($article->article_type->alias === 'slide'): ?>
                    <?php $slides[] = $article; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!empty($slides)): ?>
                <div class="row">
                    <div class="col-12">
                        <?= $this->element('Articles/promoted_slides', ['slides' => $slides]); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php $projects = []; ?>
            <?php foreach($articles as $article): ?>
                <?php if ($article->article_type->alias === 'project'): ?>
                    <?php $projects[] = $article; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!empty($projects)): ?>
                <div class="row">
                    <div class="col-12">
                        <?= $this->element('Articles/promoted_projects', ['projects' => $projects]); ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>
