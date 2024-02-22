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
            'controller'    => 'UserProfiles',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Add user profile')]
]); ?>

<?= $this->Form->create($userProfile, [
    'role'  => 'form',
    'type'  => 'file',
    'class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('plus'); ?> <?= __d('yab_cms_ff', 'Add user profile'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('foreign_key', [
                    'type'      => 'text',
                    'value'     => Text::uuid(),
                    'required'  => true,
                    'readonly'  => true,
                ]); ?>
                <?= $this->Form->control('timezone', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->timezone())? $this->YabCmsFf->timezone(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('prefix', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->prefixes())? $this->YabCmsFf->prefixes(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('salutation', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('first_name', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('middle_name', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('last_name', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('gender', [
                    'type'      => 'select',
                    'options'   => [
                        'Male'      => __d('yab_cms_ff', 'Male'),
                        'Female'    => __d('yab_cms_ff', 'Female'),
                    ],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('telephone', [
                    'type'          => 'number',
                    'placeholder'   => '00490123456789',
                    'required'      => false,
                ]); ?>
                <?= $this->Form->control('mobilephone', [
                    'type'          => 'number',
                    'placeholder'   => '00490123456789',
                    'required'      => false,
                ]); ?>
                <?= $this->Form->control('fax', [
                    'type'          => 'number',
                    'placeholder'   => '00490123456789',
                    'required'      => false,
                ]); ?>
                <?= $this->Form->control('website', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('company', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('street', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('street_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('postcode', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('city', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('country_id', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->countriesList())? $this->YabCmsFf->countriesList(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('about_me', [
                    'type'      => 'textarea',
                    'class'     => 'about_me',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('image', [
                    'type'      => 'hidden',
                ]); ?>
                <?= $this->Form->control('image_file', [
                    'type'      => 'file',
                    'accept'    => 'image/jpeg,image/jpg',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('tags', [
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
                <?= $this->Form->control('user_id', [
                    'label'     => __d('yab_cms_ff', 'User'),
                    'options'   => !empty($users)? $users: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'required'  => true,
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?= $this->Form->checkbox('status', ['id' => 'status', 'class' => 'custom-control-input', 'checked' => true, 'required' => false]); ?>
                        <label class="custom-control-label" for="status"><?= __d('yab_cms_ff', 'Status'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'UserProfiles',
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
<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'tagify'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'jQuery.tagify.min',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'userProfiles' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        UserProfiles.init();
        // Initialize select2
        $(\'.select2\').select2();
        // Initialize summernote
        $(\'.about_me\').summernote({
            toolbar: [
                [\'style\', [\'style\']],
                [\'font\', [\'bold\', \'underline\', \'clear\']],
                [\'fontname\', [\'fontname\']],
                [\'color\', [\'color\']],
                [\'para\', [\'ul\', \'ol\', \'paragraph\']],
                [\'table\', [\'table\']],
                [\'insert\', [\'link\']],
                [\'view\', [\'fullscreen\']]
            ],
            placeholder: \'' . __d('yab_cms_ff', 'Please enter a valid about me text') . '\',
            tabsize: 2,
            height: 100
        });
        $(\'.form-general\').validate({
            rules: {
                timezone: {
                    required: true
                },
                salutation: {
                    required: true
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                gender: {
                    required: true
                }
            },
            messages: {
                timezone: {
                    required: \'' . __d('yab_cms_ff', 'Please select a timezone') . '\'
                },
                salutation: {
                    required: \'' . __d('yab_cms_ff', 'Please select a salutation') . '\'
                },
                first_name: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid first name') . '\'
                },
                last_name: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid last name') . '\'
                },
                gender: {
                    required: \'' . __d('yab_cms_ff', 'Please select a gender') . '\'
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
        var inputTags = document.querySelector(\'textarea[name=tags]\');
        new Tagify(inputTags, {
            placeholder: \'' . __d('yab_cms_ff', 'Please enter 17 valid tags') . '\',
            delimiters: \',| \',
            backspace: \'edit\',
            maxTags: 17,
            transformTag: transformTag,
        });
        function transformTag(tagData) {
            tagData.color = getRandomColor();
            tagData.style = \'--tag-bg:\' + tagData.color;
        }
        function getRandomColor() {
            function rand(min, max) {
                return min + Math.random() * (max - min);
            }
            var h = rand(1, 360)|0,
                s = rand(40, 70)|0,
                l = rand(65, 72)|0;
            return \'hsl(\' + h + \',\' + s + \'%,\' + l + \'%)\';
        }
    });',
    ['block' => 'scriptBottom']); ?>
