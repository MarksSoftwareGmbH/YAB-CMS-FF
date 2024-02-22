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
            'controller'    => 'MenuItems',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Add menu item')]
]); ?>

<?= $this->Form->create($menuItem, ['class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('plus'); ?> <?= __d('yab_cms_ff', 'Add menu item'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('title', [
                    'type' => 'text',
                    'required' => true,
                ]); ?>
                <?= $this->Form->control('alias', [
                    'type' => 'text',
                    'class' => 'slug',
                    'required' => true,
                    'readonly' => true,
                ]); ?>
                <?= $this->Form->control('sub_title', [
                    'type' => 'text',
                    'required' => false,
                ]); ?>
                <?= $this->Form->control('link', [
                    'type' => 'text',
                    'required' => true,
                ]); ?>
                <?= $this->Form->control('link_target', [
                    'type' => 'text',
                    'default' => '_self',
                    'required' => false,
                ]); ?>
                <?= $this->Form->control('link_rel', [
                    'type' => 'text',
                    'required' => false,
                ]); ?>
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'class' => 'description',
                    'required' => false,
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
                <?= $this->Form->control('menu_id', [
                    'label' => __d('yab_cms_ff', 'Menu'),
                    'options' => !empty($menus)? $menus: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty' => false,
                ]); ?>
                <?= $this->Form->control('parent_id', [
                    'label' => __d('yab_cms_ff', 'Parent'),
                    'options' => !empty($parentMenuItems)? $parentMenuItems: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty' => true,
                ]); ?>
                <?= $this->Form->control('domain_id', [
                    'label' => __d('yab_cms_ff', 'Domain'),
                    'options' => !empty($this->YabCmsFf->domains())? $this->YabCmsFf->domains(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty' => true,
                    'required' => false,
                ]); ?>
                <?= $this->Form->control('locale', [
                    'type' => 'select',
                    'label' => __d('yab_cms_ff', 'Locale'),
                    'options' => !empty($this->YabCmsFf->localeCodes())? $this->YabCmsFf->localeCodes(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required' => true,
                ]); ?>
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
                        [
                            'plugin' => 'YabCmsFf',
                            'controller' => 'MenuItems',
                            'action' => 'index',
                        ],
                        [
                            'class' => 'btn btn-danger float-right',
                            'escape' => false,
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
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'menuItems' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        MenuItems.init();
        // Initialize select2
        $(\'.select2\').select2();
        // Initialize summernote
        $(\'.description\').summernote();
        $(\'.form-general\').submit(function(event) {
            $(\'.description\').summernote(\'destroy\');
        });
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
    });',
    ['block' => 'scriptBottom']); ?>
