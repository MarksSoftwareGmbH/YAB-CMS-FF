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
use Cake\Utility\Text;

// Get session object
$session = $this->getRequest()->getSession();
?>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= __d('yab_cms_ff', 'Project detail'); ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <?php if (isset($article->project_hours) && !empty($article->project_hours)): ?>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted"><?= __d('yab_cms_ff', 'Estimated hours'); ?></span>
                                        <span class="info-box-number text-center bg-dark mb-0"><?= h($article->project_hours); ?> (<?= __d('yab_cms_ff', 'hours'); ?>)</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($article->project_hours_spent) && !empty($article->project_hours_spent)): ?>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted"><?= __d('yab_cms_ff', 'Hours spent'); ?></span>
                                        <span class="info-box-number text-center bg-dark mb-0"><?= h($article->project_hours_spent); ?> (<?= __d('yab_cms_ff', 'hours'); ?>)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted"><?= __d('yab_cms_ff', 'Satoshi spent'); ?> (<?= __d('yab_cms_ff', '1 satoshi eq 1 second'); ?>)</span>
                                        <span class="info-box-number text-center bg-dark mb-0">
                                            <?= $this->Number->format($article->project_hours_spent * 0.00003600, ['places' => 8]); ?> (BTC)
                                        </span>
                                        <span class="info-box-text text-center text-muted">
                                            <?= $this->Html->link(
                                                __d('yab_cms_ff', 'Check out the Bitcoin price here'),
                                                'https://bitbo.io/',
                                                [
                                                    'target'    => '__blank',
                                                    'class'     => 'text-info',
                                                ]); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?= $article->body; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h4 class="text-light bg-dark">
                        &nbsp;<i class="fas fa-edit"></i> <?= $article->global_title; ?>
                    </h3>
                    <?php if (!empty($article->subtitle)): ?>
                        <p class="text-info">
                            <?= $article->subtitle; ?>
                        </p>
                    <?php endif; ?>
                    <br />
                    <dl class="row">
                        <?php if (isset($article->project_team_members) && !empty($article->project_team_members)): ?>
                            <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Project leader'); ?></dt>
                            <dd class="col-sm-8"><?= h($article->project_team_members); ?>&ensp;<?= $this->Html->image(
                                '/yab_cms_ff/img/avatars/' . Text::slug(strtolower($article->project_team_members), ['replacement' => '_']) . '.' . 'jpg',
                                [
                                    'alt'   => $article->project_team_members,
                                    'title' => $article->project_team_members,
                                    'class' => 'img-size-50 user-image img-circle elevation-2',
                                ]); ?></dd>
                        <?php endif; ?>
                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                        <dd class="col-sm-8"><?= h($article->created); ?></dd>
                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                        <dd class="col-sm-8"><?= h($article->modified); ?></dd>
                        <dt class="col-sm-4"><?= __d('yab_cms_ff', 'Status'); ?></dt>
                        <dd class="col-sm-8"><?= $this->YabCmsFf->status(h($article->status)); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
