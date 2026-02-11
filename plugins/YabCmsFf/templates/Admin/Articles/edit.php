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
use Cake\Utility\Hash;
use Cake\Utility\Text;

$backendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.backendBoxColor')):
    $backendBoxColor = Configure::read('YabCmsFf.settings.backendBoxColor');
endif;

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . h($article->global_title)
);
// Breadcrumb
$this->Breadcrumbs->addMany([
    [
        'title' => __d('yab_cms_ff', 'Go back'),
        'url' => 'javascript:history.back()',
    ],
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
    ['title' => __d('yab_cms_ff', 'Edit article')],
    ['title' => h($article->global_title)]
], ['class' => 'breadcrumb-item']); ?>
<?= $this->Form->create($article, ['class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit article'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('uuid_id', [
                    'type'  => 'hidden',
                    'value' => empty($article->uuid_id)? Text::uuid(): h($article->uuid_id),
                ]); ?>
                <?= $this->Form->control('parent_id', [
                    'type' => 'hidden',
                ]); ?>
                <?= $this->Form->control('article_type_id', [
                    'type' => 'hidden',
                ]); ?>

                <?php foreach ($article->article_type->article_type_attributes as $key => $articleTypeAttribute):

                    // Get the article_article_type_attribute_value id by articleTypeAttribute id
                    $articleArticleTypeAttributeValueId = Hash::extract(
                        $article->article_article_type_attribute_values,
                        '{n}' . '[article_type_attribute_id = ' . h($articleTypeAttribute->id) . ']' . '.' . 'id'
                    );
                    // Get the article_article_type_attribute_value value by articleTypeAttribute id
                    $articleArticleTypeAttributeValue = Hash::extract(
                        $article->article_article_type_attribute_values,
                        '{n}' . '[article_type_attribute_id = ' . h($articleTypeAttribute->id) . ']' . '.' . 'value'
                    );

                    echo $this->Form->control('article_article_type_attribute_values' . '.' . h($key) . '.' . 'id', [
                        'type'  => 'hidden',
                        'value' => !empty($articleArticleTypeAttributeValueId)? $articleArticleTypeAttributeValueId[0]: '',
                    ]);

                    echo $this->Form->control('article_article_type_attribute_values' . '.' . h($key) . '.' . 'article_type_attribute_id', [
                        'type'  => 'hidden',
                        'value' => h($articleTypeAttribute->id),
                    ]);

                    if ($articleTypeAttribute->type !== 'select'):

                        if ($articleTypeAttribute->empty_value):
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'              => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'             => h($articleTypeAttribute->title),
                                    'value'             => !empty($articleArticleTypeAttributeValue)? $articleArticleTypeAttributeValue[0]: '',
                                    'class'             => h(text: $articleTypeAttribute->foreign_key) . ' ' . 'count-chars',
                                    'maxlength'         => 255,
                                    'data-chars-max'    => 255,
                                    'data-msg-color'    => 'success',
                                ]);
                        else:
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'              => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'             => [
                                        'text'      => h($articleTypeAttribute->title) . '*',
                                        'class'     => 'text-danger',
                                        'escape'    => false,
                                    ],
                                    'value'             => !empty($articleArticleTypeAttributeValue)? $articleArticleTypeAttributeValue[0]: '',
                                    'class'             => h($articleTypeAttribute->foreign_key) . ' ' . 'border-danger count-chars',
                                    'maxlength'         => 255,
                                    'data-chars-max'    => 255,
                                    'data-msg-color'    => 'success',
                                    'required',
                                ]);
                        endif;

                    else:

                        if ($articleTypeAttribute->empty_value):
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . h($key) . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'         => h($articleTypeAttribute->title),
                                    'options'       => !empty($articleTypeAttribute->article_type_attribute_choices)? $this->YabCmsFf->inputOptions($articleTypeAttribute->article_type_attribute_choices): '',
                                    'class'         => 'select2',
                                    'style'         => 'width: 100%',
                                    'empty'         => true,
                                    'value'         => !empty($articleArticleTypeAttributeValue)? $articleArticleTypeAttributeValue[0]: '',
                                ]);
                        else:
                            echo $this->Form->control(
                                'article_article_type_attribute_values' . '.' . $key . '.' . 'value',
                                [
                                    'type'          => $this->YabCmsFf->inputType($articleTypeAttribute->type),
                                    'label'         => [
                                        'text'      => h($articleTypeAttribute->title) . '*',
                                        'class'     => 'text-danger',
                                        'escape'    => false,
                                    ],
                                    'options'       => !empty($articleTypeAttribute->article_type_attribute_choices)? $this->YabCmsFf->inputOptions($articleTypeAttribute->article_type_attribute_choices): '',
                                    'class'         => 'select2',
                                    'style'         => 'width: 100%',
                                    'empty'         => true,
                                    'value'         => !empty($articleArticleTypeAttributeValue)? $articleArticleTypeAttributeValue[0]: '',
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
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('cog'); ?> <?= __d('yab_cms_ff', 'Actions'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('user_id', [
                    'type'  => 'select',
                    'label' => [
                        'text' => __d('yab_cms_ff', 'Author') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Add author'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Users',
                                    'action'        => 'add',
                                ],
                                [
                                    'target'        => '_blank',
                                    'class'         => 'text-' . h($backendLinkTextColor),
                                    'escapeTitle'   => false,
                                ])
                            . ')',
                        'class'     => 'text-danger',
                        'escape'    => false,
                    ],
                    'options'   => !empty($this->YabCmsFf->users())? $this->YabCmsFf->users(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('domain_id', [
                    'type'  => 'select',
                    'label' => [
                        'text' => __d('yab_cms_ff', 'Domain') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Add domain'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Domains',
                                    'action'        => 'add',
                                ],
                                [
                                    'target'        => '_blank',
                                    'class'         => 'text-' . h($backendLinkTextColor),
                                    'escapeTitle'   => false,
                                ])
                            . ')',
                        'class'     => 'text-danger',
                        'escape'    => false,
                    ],
                    'options'   => !empty($this->YabCmsFf->domains())? $this->YabCmsFf->domains(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('locale', [
                    'type'  => 'select',
                    'label' => [
                        'text' => __d('yab_cms_ff', 'Locale') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Add locale'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Locales',
                                    'action'        => 'add',
                                ],
                                [
                                    'target'        => '_blank',
                                    'class'         => 'text-' . h($backendLinkTextColor),
                                    'escapeTitle'   => false,
                                ])
                            . ')',
                        'class'     => 'text-danger',
                        'escape'    => false,
                    ],
                    'options'   => !empty($this->YabCmsFf->localeCodes())? $this->YabCmsFf->localeCodes(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <div class="form-group">
                    <?= $this->Form->control('categories._ids', [
                        'type'      => 'select',
                        'multiple'  => 'select',
                        'label' => [
                            'text' => __d('yab_cms_ff', 'Categories')
                                . ' '
                                . '('
                                . $this->Html->link(
                                    __d('yab_cms_ff', 'Add category'),
                                    [
                                        'plugin'        => 'YabCmsFf',
                                        'controller'    => 'Categories',
                                        'action'        => 'add',
                                    ],
                                    [
                                        'target'        => '_blank',
                                        'class'         => 'text-' . h($backendLinkTextColor),
                                        'escapeTitle'   => false,
                                    ])
                                . ')',
                            'escape'    => false,
                        ],
                        'options'   => !empty($this->YabCmsFf->categories())? $this->YabCmsFf->categories(): [],
                        'class'     => 'duallistbox',
                    ]); ?>
                </div>
                <?= $this->Form->control('promote_start', [
                    'type'      => 'text',
                    'label'     => __d('yab_cms_ff', 'Start'),
                    'class'     => 'datetimepicker',
                    'format'    => 'd.m.Y H:i',
                    'default'   => date('d.m.Y H:i'),
                    'value'     => !empty($article->promote_start)? $article->promote_start->format('d.m.Y H:i'): date('d.m.Y H:i'),
                ]); ?>
                <?= $this->Form->control('promote_end', [
                    'type'      => 'text',
                    'label'     => __d('yab_cms_ff', 'End'),
                    'class'     => 'datetimepicker',
                    'format'    => 'd.m.Y H:i',
                    'value'     => !empty($article->promote_end)? $article->promote_end->format('d.m.Y H:i'): '',
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $promote = $article->promote? true: false; ?>
                        <?= $this->Form->checkbox('promote', ['id' => 'promote', 'class' => 'custom-control-input', 'checked' => $promote, 'required' => false]); ?>
                        <label class="custom-control-label" for="promote"><?= __d('yab_cms_ff', 'Promote'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $status = $article->status? true: false; ?>
                        <?= $this->Form->checkbox('status', ['id' => 'status', 'class' => 'custom-control-input', 'checked' => $status, 'required' => false]); ?>
                        <label class="custom-control-label" for="status"><?= __d('yab_cms_ff', 'Status'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success shadow rounded']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        $this->request->getSession()->read('Request.HTTP_REFERER'),
                        [
                            'class'         => 'btn btn-danger shadow rounded float-right',
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
<?php foreach ($article->article_type->article_type_attributes as $key => $articleTypeAttribute): ?>
    <?php $textareaId = 'article-article-type-attribute-values' . '-' . h($key) . '-' . 'value'; ?>
    <?php if ($articleTypeAttribute->wysiwyg): ?>
        <?php $ckeditorScript .= '
            $(\'#' . h($textareaId) . '\').summernote();
            $(\'.form-general\').submit(function(event) {
                $(\'#' . h($textareaId) . '\').summernote(\'destroy\');
            });' ?>
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
        $(\'.count-chars\').keyup(function () {
            var charInput = this.value;
            var charInputLength = this.value.length;
            const maxChars = $(this).data(\'chars-max\');
            const messageColor = $(this).data(\'msg-color\');
            var inputId = this.getAttribute(\'id\');
            var messageDivId = inputId + \'Message\';
            var remainingMessage = \'\';

            if (charInputLength >= maxChars) {
                $(\'#\' + inputId).val(charInput.substring(0, maxChars));
                remainingMessage = \'0 ' . __d('yab_cms_ff', 'character remaining') . '\' ;
            } else {
                remainingMessage = (maxChars - charInputLength) + \' ' . __d('yab_cms_ff', 'character(s) remaining') . '\';
            }
            if ($(\'#\' + messageDivId).length == 0) {
                $(\'#\' + inputId).after(\'<div id="\' + messageDivId + \'" class="text-\' + messageColor + \' font-weight-bold">\' + remainingMessage + \'</div>\');
            } else {
                $(\'#\' + messageDivId).text(remainingMessage);
            }
        });
    });',
    ['block' => 'scriptBottom']); ?>
