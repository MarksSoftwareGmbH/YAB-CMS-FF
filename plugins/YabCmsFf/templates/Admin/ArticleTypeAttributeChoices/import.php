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
            'controller'    => 'ArticleTypeAttributeChoices',
            'action'        => 'index',
        ]
    ],
    ['title' => ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))]
], ['class' => 'breadcrumb-item']); ?>

<?= $this->Form->create(null, [
    'url' => [
        'plugin'        => 'YabCmsFf',
        'controller'    => 'ArticleTypeAttributeChoices',
        'action'        => 'import',
    ],
    'type'  => 'file',
    'class' => 'form-general form-article-type-attribute-choice',
]); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card card-<?= h($backendBoxColor); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('upload'); ?> <?= __d('yab_cms_ff', 'Import'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('delimiter', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Delimiter') . '*',
                        'class' => 'text-danger',
                    ],
                    'default'   => ';',
                    'class'     => 'border-danger',
                    'readonly'  => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('enclosure', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Enclosure') . '*',
                        'class' => 'text-danger',
                    ],
                    'default'   => '"',
                    'class'     => 'border-danger',
                    'readonly'  => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('file', [
                    'type'      => 'file',
                    'accept'    => 'text/comma-separated-values,text/csv,application/csv',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Select file') . '*',
                        'class' => 'text-danger',
                    ],
                    'class'     => 'border-danger',
                    'required'  => true,
                    'help'      => __d('yab_cms_ff', 'Please use a valid csv file.')
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
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('log', ['id' => 'log', 'class' => 'custom-control-input', 'checked' => false, 'required' => false]); ?>
                        <label class="custom-control-label" for="log"><?= __d('yab_cms_ff', 'Log'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Import'), ['class' => 'btn btn-success shadow rounded']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'ArticleTypeAttributeChoices',
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

<?= $this->element('please_wait'); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        $(\'.form-article-type-attribute-choice\').validate({
            rules: {
                delimiter: {
                    required: true
                },
                enclosure: {
                    required: true
                }
            },
            messages: {
                delimiter: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid delimiter') . '\'
                },
                enclosure: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid enclosure') . '\'
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