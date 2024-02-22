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
    . h($registration->billing_name)
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
            'controller'    => 'Registrations',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Edit registration')],
    ['title' => h($registration->billing_name)]
]); ?>

<?= $this->Form->create($registration, ['class' => 'form-general']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit registration'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('billing_name', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_name_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_legal_form', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_vat_number', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_salutation', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => false,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_first_name', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_middle_name', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_last_name', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_management', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_email', [
                    'type'      => 'email',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_website', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_telephone', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_mobilephone', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_fax', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_street', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_street_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_postcode', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_city', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_country', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->countries())? $this->YabCmsFf->countries(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('shipping_name', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_name_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_management', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_email', [
                    'type'      => 'email',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_telephone', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_mobilephone', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_fax', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_street', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_street_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_postcode', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_city', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('shipping_country', [
                    'type'      => 'select',
                    'options'   => !empty($this->YabCmsFf->countries())? $this->YabCmsFf->countries(): [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
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
                <?= $this->Form->control('registration_type_id', [
                    'type'      => 'select',
                    'options'   => !empty($registrationTypes)? $registrationTypes: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => false,
                    'required'  => true,
                    'label'     => __d('yab_cms_ff', 'Type'),
                ]); ?>
                <?= $this->Form->control('newsletter_email', [
                    'type'      => 'email',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('register_excerpt', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('remark', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('ip', [
                    'type'      => 'text',
                    'required'  => true,
                ]); ?>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $newsletter = $registration->newsletter? true: false; ?>
                        <?= $this->Form->checkbox('newsletter', ['id' => 'newsletter', 'class' => 'custom-control-input', 'checked' => $newsletter, 'required' => false]); ?>
                        <label class="custom-control-label" for="newsletter"><?= __d('yab_cms_ff', 'Newsletter'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $marketing = $registration->marketing? true: false; ?>
                        <?= $this->Form->checkbox('marketing', ['id' => 'marketing', 'class' => 'custom-control-input', 'checked' => $marketing, 'required' => false]); ?>
                        <label class="custom-control-label" for="marketing"><?= __d('yab_cms_ff', 'Marketing'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $termsConditions = $registration->terms_conditions? true: false; ?>
                        <?= $this->Form->checkbox('terms_conditions', ['id' => 'termsConditions', 'class' => 'custom-control-input', 'checked' => $termsConditions, 'required' => false]); ?>
                        <label class="custom-control-label" for="termsConditions"><?= __d('yab_cms_ff', 'Terms conditions'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <?php $privacyPolicy = $registration->privacy_policy? true: false; ?>
                        <?= $this->Form->checkbox('privacy_policy', ['id' => 'privacyPolicy', 'class' => 'custom-control-input', 'checked' => $privacyPolicy, 'required' => false]); ?>
                        <label class="custom-control-label" for="privacyPolicy"><?= __d('yab_cms_ff', 'Terms conditions'); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Registrations',
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
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'registrations' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        Registrations.init();
        // Initialize select2
        $(\'.select2\').select2();
        $(\'.form-general\').validate({
            rules: {
                billing_name: {
                    required: true
                },
                billing_vat_number: {
                    required: true
                }
            },
            messages: {
                billing_name: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid billing name') . '\'
                },
                billing_vat_number: {
                    required: \'' . __d('yab_cms_ff', 'Please enter a valid billing vat number') . '\'
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
