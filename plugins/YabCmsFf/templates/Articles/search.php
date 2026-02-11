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

// Get session object
$session = $this->getRequest()->getSession();

// Title
$this->assign('title', __d('yab_cms_ff', 'Search "{search}" overview', ['search' => $search['search']]));

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
    ['title' => __d('yab_cms_ff', 'Search "{search}" overview', ['search' => $search['search']])]
], ['class' => 'breadcrumb-item']); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', 'Search "{search}" overview', ['search' => $search['search']]); ?>
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
                <div class="card">
                    <div class="card-body">
                        <table id="articles" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 10%"><?= __d('yab_cms_ff', 'Type'); ?></strong></small></th>
                                <th style="width: 70%"><?= __d('yab_cms_ff', 'Title'); ?></strong></small></th>
                                <th style="width: 10%"><?= __d('yab_cms_ff', 'Created'); ?></strong></small></th>
                                <th style="width: 5%"><?= __d('yab_cms_ff', 'Status'); ?></strong></small></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $number = 1; ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td class="align-middle"><?= h($number); ?></td>
                                    <td class="align-middle"><?= h($article->article_type->title); ?></td>
                                    <td class="align-middle">
                                        <strong><?= $this->Html->link($article->global_title, '/' . h($article->article_type->alias) . '/' . h($article->slug)); ?></strong>
                                        <?php if (isset($article->subtitle) && !empty($article->subtitle)): ?>
                                            <br />
                                            <?= $article->subtitle; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle"><?= h($article->created); ?></td>
                                    <td class="align-middle"><?= $this->YabCmsFf->status(h($article->status)); ?></td>
                                </tr>
                                <?php $number++; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-bs4' . DS . 'css' . DS . 'dataTables.bootstrap4.min'); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-responsive' . DS . 'css' . DS . 'responsive.bootstrap4.min'); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'css' . DS . 'buttons.bootstrap4.min'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables' . DS . 'jquery.dataTables.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-bs4' . DS . 'js' . DS . 'dataTables.bootstrap4.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-responsive' . DS . 'js' . DS . 'dataTables.responsive.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-responsive' . DS . 'js' . DS . 'responsive.bootstrap4.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'js' . DS . 'dataTables.buttons.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'js' . DS . 'buttons.bootstrap4.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jszip' . DS . 'jszip.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'pdfmake' . DS . 'pdfmake.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'pdfmake' . DS . 'vfs_fonts',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'js' . DS . 'buttons.html5.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'js' . DS . 'buttons.print.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datatables-buttons' . DS . 'js' . DS . 'buttons.colVis.min',
    ['block' => 'scriptBottom']); ?>

<?= $this->Html->scriptBlock(
    '$(function() {
        // Initialize DataTables
        $(\'#articles\').DataTable({
            \'responsive\': true,
            \'lengthChange\': true,
            \'autoWidth\': false,
            \'buttons\': [\'copy\', \'csv\', \'excel\', \'pdf\', \'print\', \'colvis\'],
            \'pageLength\': 25,
            \'order\': [[1, \'asc\']]
        });
    });',
    ['block' => 'scriptBottom']); ?>
