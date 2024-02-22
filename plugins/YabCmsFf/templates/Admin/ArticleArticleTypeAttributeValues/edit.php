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

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . $this->Text->truncate(h($articleArticleTypeAttributeValue->value), 35, ['ellipsis' => '...', 'exact' => false])
);
// Breadcrumb
$this->Breadcrumbs->add([
    [
        'title' => $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller')),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'ArticleArticleTypeAttributeValues',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Edit Type Attribute Value')],
    ['title' => $this->Text->truncate(h($articleArticleTypeAttributeValue->value), 35, ['ellipsis' => '...', 'exact' => false])]
]); ?>

<?= $this->Form->create($articleArticleTypeAttributeValue, ['class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit Type Attribute Value'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('article_id', [
                    'options'   => !empty($articles)? $articles: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => false,
                ]); ?>
                <?= $this->Form->control('article_type_attribute_id', [
                    'options'   => !empty($articleTypeAttributes)? $articleTypeAttributes: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => false,
                ]); ?>
                <?= $this->Form->input('value', [
                    'type'      => 'textarea',
                    'required'  => false,
                ]); ?>
            </div>
        </div>
    </section>
    <section class="col-lg-4 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('cog'); ?> <?= __d('yab_cms_ff', 'Actions'); ?>
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleArticleTypeAttributeValues',
                            'action'        => 'index',
                        ],
                        [
                            'class'     => 'btn btn-danger float-right',
                            'escape'    => false,
                        ]); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->Form->end(); ?>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'yab_cms_ff.select2'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'articleArticleTypeAttributeValues' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        ArticleArticleTypeAttributeValues.init();
        // Initialize select2
        $(\'.select2\').select2();
    });',
    ['block' => 'scriptBottom']); ?>
