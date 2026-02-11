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
    . h($articleType->title)
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
            'controller'    => 'ArticleTypes',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Edit article type')],
    ['title' => h($articleType->title)]
], ['class' => 'breadcrumb-item']); ?>
<?= $this->Form->create($articleType, ['class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit article type'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('uuid_id', [
                    'type'  => 'hidden',
                    'value' => empty($articleType->uuid_id)? Text::uuid(): h($articleType->uuid_id),
                ]); ?>
                <?= $this->Form->control('title', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Title') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('alias', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Alias') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'slug border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('description', [
                    'type'      => 'textarea',
                    'required'  => false,
                ]); ?>
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
                <?= $this->Form->control('foreign_key', [
                    'type'              => 'text',
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'count-chars',
                    'required'          => false,
                ]); ?>
                <?= $this->Form->control('article_type_attributes._ids', [
                    'type'      => 'select',
                    'multiple'  => 'checkbox',
                    'label' => [
                        'text' => __d('yab_cms_ff', 'Article type attributes') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Add article type attribute'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'ArticleTypeAttributes',
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
                    'options' => !empty($articleTypeAttributes)? $articleTypeAttributes: [],
                ]); ?>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success shadow rounded']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypes',
                            'action'        => 'index',
                        ],
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
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'slug' . DS . 'jquery.slug',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'articleTypes' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        ArticleTypes.init();
        // Initialize select2
        $(\'.select2\').select2();
        $(\'.form-general\').validate({
            rules: {
                title: {
                    required: true
                },
                alias: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid title') . '\'
                },
                alias: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid alias') . '\'
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
