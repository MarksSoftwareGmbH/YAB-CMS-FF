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
use Cake\Utility\Inflector;

// Get session object
$session = $this->getRequest()->getSession();

$articleType = !empty($article->article_type->title)? $article->article_type->title: __d('yab_cms_ff', 'Article');

// Title
$this->assign('title', $articleType . ':' . ' ' . $article->global_title);

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
        'title' => !empty($articleType)? Inflector::pluralize(ucfirst($articleType)): __d('yab_cms_ff', 'Articles'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Articles',
            'action'        => 'index',
            'articleType'   => h($article->article_type->alias),
        ],
    ],
    ['title' => $article->global_title],
]); ?>
<?= $this->Html->meta('author', isset($article->user->name)? $article->user->name: 'Yet another boring CMS for FREE', ['block' => true]); ?>
<?= $this->Html->meta('description', isset($article->meta_description)? $article->meta_description: '', ['block' => true]); ?>
<?= $this->Html->meta('generator', 'Yet another boring CMS for FREE', ['block' => true]); ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= $articleType . ':' . ' ' . $article->global_title; ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <?= $this->element('breadcrumb'); ?>
            </div>
        </div>
    </div>
</section>

<?php if ($article->article_type->alias === 'project'): ?>
    <?= $this->element('Articles/view_project', ['article' => $article]); ?>
<?php else: ?>
    <?= $this->element('Articles/view', ['article' => $article]); ?>
<?php endif; ?>
