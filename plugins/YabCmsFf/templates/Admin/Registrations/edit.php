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
    . h($registration->billing_name)
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
            'controller'    => 'Registrations',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'Edit registration')],
    ['title' => h($registration->billing_name)]
], ['class' => 'breadcrumb-item']); ?>
<?= $this->Form->create($registration, ['class' => 'form-general form-registration']); ?>
<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $this->Html->icon('edit'); ?> <?= __d('yab_cms_ff', 'Edit registration'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?= $this->Form->control('uuid_id', [
                    'type'      => 'hidden',
                    'value'     => !empty($registration->uuid_id)? h($registration->uuid_id): Text::uuid(),
                ]); ?>
                <?= $this->Form->control('billing_name', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing name') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_name_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_legal_form', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing legal form') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_vat_number', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_salutation', [
                    'type'      => 'select',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing salutation') . '*',
                        'class' => 'text-danger',
                    ],
                    'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                    'class'     => 'select2 border-danger',
                    'style'     => 'width: 100%',
                    'empty'     => false,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('billing_first_name', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing first name') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_middle_name', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_last_name', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing last name') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_management', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing management') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_email', [
                    'type'      => 'email',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing email') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_website', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_telephone', [
                    'type'      => 'number',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing telephone') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_mobilephone', [
                    'type'      => 'number',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_fax', [
                    'type'      => 'number',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_street', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing street') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_street_addition', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('billing_postcode', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing postcode') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_city', [
                    'type'      => 'text',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing city') . '*',
                        'class' => 'text-danger',
                    ],
                    'maxlength'         => 255,
                    'data-chars-max'    => 255,
                    'data-msg-color'    => 'success',
                    'class'             => 'border-danger count-chars',
                    'required'          => true,
                ]); ?>
                <?= $this->Form->control('billing_country', [
                    'type'      => 'select',
                    'label'     => [
                        'text'  => __d('yab_cms_ff', 'Billing country') . '*',
                        'class' => 'text-danger',
                    ],
                    'options'   => !empty($this->YabCmsFf->countries())? $this->YabCmsFf->countries(): [],
                    'class'     => 'select2 border-danger',
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
                    'type'  => 'select',
                    'label' => [
                        'text' => __d('enter_pulse', 'Type') . '*'
                            . ' '
                            . '('
                            . $this->Html->link(
                                __d('enter_pulse', 'Add type'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'RegistrationTypes',
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
                    'options'   => !empty($registrationTypes)? $registrationTypes: [],
                    'class'     => 'select2',
                    'style'     => 'width: 100%',
                    'empty'     => true,
                    'required'  => true,
                ]); ?>
                <?= $this->Form->control('newsletter_email', [
                    'type'      => 'email',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('register_excerpt', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('remark', [
                    'type'      => 'text',
                    'required'  => false,
                ]); ?>
                <?= $this->Form->control('ip', [
                    'type'      => 'text',
                    'required'  => false,
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
                    <?= $this->Form->button(__d('yab_cms_ff', 'Submit'), ['class' => 'btn btn-success shadow rounded']); ?>
                    <?= $this->Html->link(
                        __d('yab_cms_ff', 'Cancel'),
                        [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Registrations',
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
    'YabCmsFf' . '.' . 'admin' . DS . 'template' . DS . 'admin' . DS . 'registrations' . DS . 'form',
    ['block' => 'scriptBottom']); ?>
<?= $this->Html->scriptBlock(
    '$(function() {
        Registrations.init();
        // Initialize select2
        $(\'.select2\').select2();
        $(\'.form-registration\').validate({
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
        var checker = $(\'<div id="form-checker" style="position: fixed; bottom: 100px; right: 15px; background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 250px; z-index: 1050; overflow-y: auto; max-height: 300px;"></div>\');
        var toggleButton = $(\'<button id="form-checker-toggle" style="position: fixed; bottom: 70px; right: 15px; background: #218838; color: #fff; border: 1px solid transparent; padding: .375rem .75rem; border-radius: .25rem; line-height: 1.5; cursor: pointer; z-index: 1050; display: none;"><i class="fas fa-list"></i>' . ' ' . __d('yab_cms_ff', 'Required fields') . '</button>\');
        $(\'body\').append(checker).append(toggleButton);
        var closeButton = $(\'<button style="position: absolute; top: 5px; right: 5px; background: none; border: none; font-size: 0.9rem; cursor: pointer;"><i class="fas fa-times"></i></button>\');
        checker.append(closeButton);
        var form = $(\'.form-registration\');
        var requiredFields = form.find(\'input[required], select[required], textarea[required]\');
        var list = $(\'<ul style="list-style: none; padding: 0; margin: 0;"></ul>\');
        checker.append(\'<h5 class="mb-3" style="font-size: 1rem; font-weight: bold;">' . __d('yab_cms_ff', 'Required fields') . ':</h5>\');
        requiredFields.each(function() {
            var field = $(this);
            var id = field.attr(\'id\');
            var labelText = $(\'label[for="\' + id + \'"]\').text().trim();
            if (!labelText) {
                labelText = field.attr(\'name\').replace(/_/g, \' \').toUpperCase();
            }
            var item = $(\'<li class="mb-2"><a href="#\' + id + \'" class="d-flex align-items-center" style="text-decoration: none; color: #333;"><span class="status mr-2"></span><span class="field-name">\' + labelText + \'</span></a></li>\');
            list.append(item);
            function updateStatus() {
                var value = field.val();
                if (field.is(\'select\')) {
                    value = field.val();
                } else if (field.is(\'checkbox\')) {
                    value = field.is(\':checked\') ? \'checked\' : \'\';
                } else if (field.is(\'textarea\')) {
                    value = field.val().trim();
                } else {
                    value = field.val().trim();
                }
                var isFilled = !!value && value !== \'\';
                var status = item.find(\'.status\');
                var link = item.find(\'a\');
                var fieldName = item.find(\'.field-name\');
                if (isFilled) {
                    status.html(\'<i class="fas fa-check text-success"></i>\');
                    link.css(\'text-decoration\', \'none\');
                    fieldName.css(\'text-decoration\', \'none\');
                } else {
                    status.html(\'<i class="fas fa-times text-danger"></i>\');
                    link.css(\'text-decoration\', \'none\');
                    fieldName.css(\'text-decoration\', \'underline\');
                }
            }
            updateStatus();
            field.on(\'input change blur\', updateStatus);
        });
        checker.append(list);
        checker.on(\'click\', \'a\', function(e) {
            e.preventDefault();
            var targetId = $(this).attr(\'href\');
            $(\'html, body\').animate({
                scrollTop: $(targetId).offset().top - 100
            }, 500);
        });
        closeButton.on(\'click\', function() {
            checker.hide();
            toggleButton.show();
        });
        toggleButton.on(\'click\', function() {
            if (checker.is(\':visible\')) {
                checker.hide();
            } else {
                checker.show();
                toggleButton.hide();
            }
        });
        checker.hide();
        toggleButton.show();
    });',
    ['block' => 'scriptBottom']); ?>
