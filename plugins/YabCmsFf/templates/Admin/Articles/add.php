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
);
// Breadcrumb
$this->Breadcrumbs->add([
    [
        'title' => __d('yab_cms_ff', 'Dashboard'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Dashboards',
            'action'        => 'dashboard',
        ]
    ],
    [
        'title' => $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller')),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Articles',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Add {title}', ['title' => h($articleType->title)])]
]); ?>

<?= $this->Form->create($article, [
    'url' => [
        'plugin'            => 'YabCmsFf',
        'controller'        => 'Articles',
        'action'            => 'add',
        'articleTypeAlias'  => h($articleType->alias),
    ],
    'class' => 'form-general',
]); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('plus'); ?> <?= __d('yab_cms_ff', 'Add article'); ?>
                </h3>
            </div>
            <div class="card-body">

                <?= $this->Form->control('article_type_id', [
                    'type'  => 'hidden',
                    'value' => h($articleType->id),
                ]); ?>
                <?= $this->Form->control('parent_id', ['type' => 'hidden']); ?>

                <?php foreach ($articleType->article_type_attributes as $key => $articleTypeAttribute):

                    echo $this->Form->control('article_article_type_attribute_values' . '.' . h($key) . '.' . 'id');

                    echo $this->Form->control(
                        'article_article_type_attribute_values' . '.' . h($key) . '.' . 'article_type_attribute_id',
                        [
                            'type'  => 'hidden',
                            'value' => h($articleTypeAttribute->id),
                        ]);

                    if (empty($articleTypeAttribute->article_type_attribute_choices)):

                        if ($articleTypeAttribute->empty_value):
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'         => h($articleTypeAttribute->title),
                                    'placeholder'   => h($articleTypeAttribute->title),
                                    'class'         => h($articleTypeAttribute->foreign_key),
                                ]);
                        else:
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'         => h($articleTypeAttribute->title),
                                    'placeholder'   => h($articleTypeAttribute->title),
                                    'class'         => h($articleTypeAttribute->foreign_key),
                                    'required',
                                ]);
                        endif;

                    else:

                        if ($articleTypeAttribute->empty_value):
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'options'       => !empty($articleTypeAttribute->article_type_attribute_choices)? $this->YabCmsFf->inputOptions($articleTypeAttribute->article_type_attribute_choices): '',
                                    'class'         => 'select2',
                                    'style'         => 'width: 100%',
                                    'empty'         => true,
                                    'label'         => h($articleTypeAttribute->title),
                                    'placeholder'   => h($articleTypeAttribute->title),
                                ]);
                        else:
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'options'       => !empty($articleTypeAttribute->article_type_attribute_choices)? $this->YabCmsFf->inputOptions($articleTypeAttribute->article_type_attribute_choices) : '',
                                    'class'         => 'select2',
                                    'style'         => 'width: 100%',
                                    'empty'         => true,
                                    'label'         => h($articleTypeAttribute->title),
                                    'placeholder'   => h($articleTypeAttribute->title),
                                    'required',
                                ]);
                        endif;

                    endif;

                endforeach;
                ?>

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
                <?= $this->Form->control('user_id', [
                    'label'     => __d('yab_cms_ff', 'Author'),
                    'options'   => !empty($this->YabCmsFf->users())? $this->YabCmsFf->users(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('domain_id', [
                    'label'     => __d('yab_cms_ff', 'Domain'),
                    'options'   => !empty($this->YabCmsFf->domains())? $this->YabCmsFf->domains(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('locale', [
                    'type'      => 'select',
                    'label'     => __d('yab_cms_ff', 'Locale'),
                    'options'   => !empty($this->YabCmsFf->localeCodes())? $this->YabCmsFf->localeCodes(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required'  => true,
                ]); ?>
                <div class="form-group">
                <?= $this->Form->control('categories._ids', [
                    'type'      => 'select',
                    'multiple'  => 'select',
                    'options'   => !empty($this->YabCmsFf->categories())? $this->YabCmsFf->categories(): [],
                    'label'     => __d('yab_cms_ff', 'Categories'),
                    'class'     => 'duallistbox',
                ]); ?>
                </div>
                <?= $this->Form->control('promote_start', [
                    'type'      => 'text',
                    'label'     => __d('yab_cms_ff', 'Start'),
                    'class'     => 'datetimepicker',
                    'format'    => 'd.m.Y H:i',
                    'default'   => date('d.m.Y H:i'),
                ]); ?>
                <?= $this->Form->control('promote_end', [
                    'type'      => 'text',
                    'label'     => __d('yab_cms_ff', 'End'),
                    'class'     => 'datetimepicker',
                    'format'    => 'd.m.Y H:i',
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('promote', ['id' => 'promote', 'class' => 'custom-control-input', 'checked' => false, 'required' => false]); ?>
                        <label class="custom-control-label" for="promote"><?= __d('yab_cms_ff', 'Promote'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('status', ['id' => 'status', 'class' => 'custom-control-input', 'checked' => false, 'required' => false]); ?>
                        <label class="custom-control-label" for="status"><?= __d('yab_cms_ff', 'Status'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        $this->request->getSession()->read('Request.HTTP_REFERER'),
                        [
                            'class'         => 'btn btn-danger float-right',
                            'escapeTitle'   => false,
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
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'bootstrap4-duallistbox' . DS . 'bootstrap-duallistbox.min'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'bootstrap4-duallistbox' . DS . 'jquery.bootstrap-duallistbox.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datetimepicker' . DS . 'jquery.datetimepicker'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'datetimepicker' . DS . 'build' . DS . 'jquery.datetimepicker.full.min',
    ['block' => 'scriptBottom']); ?>

<?php $ckeditorScript = ''; ?>
<?php foreach ($articleType->article_type_attributes as $key => $articleTypeAttribute): ?>
    <?php $textareaId = 'article-article-type-attribute-values' . '-' . h($key) . '-' . 'value'; ?>
    <?php if ($articleTypeAttribute->wysiwyg): ?>
        <?php $ckeditorScript .= '
            $(\'#' . h($textareaId) . '\').summernote();
            $(\'.form-general\').submit(function(event) {
                $(\'#' . h($textareaId) . '\').summernote(\'destroy\');
            });'; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?= $this->Html->scriptBlock(
    '$(function() {
        ' . $ckeditorScript . '
        // Initialize select2
        $(\'.select2\').select2();
        // Initialize duallistbox
        $(\'.duallistbox\').bootstrapDualListbox();
        // Initialize datetimepicker
        $(\'.datetimepicker\').datetimepicker({
            format:\'d.m.Y H:i\',
            lang:\'en\'
        });
        $(\'.form-general\').validate({
            rules: {
                locale: {
                    required: true
                }
            },
            messages: {
                locale: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid locale') . '\'
                }
            },
            errorElement: \'span\',
            errorPlacement: function (error, element) {
                error.addClass(\'invalid-feedback\');
                element.closest(\'.form-group\').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass(\'is-invalid\');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass(\'is-invalid\');
            }
        });
    });',
    ['block' => 'scriptBottom']); ?>
