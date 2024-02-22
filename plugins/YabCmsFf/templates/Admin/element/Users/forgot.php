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

// Get session object
$session = $this->getRequest()->getSession();

$backendButtonColor = 'light';
if (Configure::check('YabCmsFf.settings.backendButtonColor')):
    $backendButtonColor = Configure::read('YabCmsFf.settings.backendButtonColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->getRequest()->getParam('action'))
); ?>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <?= $this->element('flash'); ?>
                <div class="login-logo">
                    <?= $this->Html->link(
                        $this->Html->image(
                            'logo.png', [
                            'alt'   => __d('yab_cms_ff', 'Logo'),
                            'class' => 'img-fluid',
                        ]),
                        '/',
                        ['escape' => false]); ?>
                </div>
                <p class="login-box-msg text-sm">
                    <?= __d('yab_cms_ff', 'Create a new password'); ?>
                </p>
                <?php $this->Form->setTemplates([
                    'inputContainer'        => '{{content}}{{help}}',
                    'inputGroupContainer'   => '<div class="input-group mb-3">{{prepend}}{{content}}{{append}}</div>',
                ]); ?>
                <?= $this->Form->create(null, [
                    'url' => [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Users',
                        'action'        => 'forgot'
                    ],
                    'class' => 'form-login',
                ]); ?>
                <?= $this->Form->control('username', [
                    'append'        => $this->Html->icon('user'),
                    'type'          => 'text',
                    'label'         => false,
                    'required'      => true,
                    'placeholder'   => __d('yab_cms_ff', 'Username'),
                ]); ?>
                <?= $this->Form->control('email', [
                    'append'        => $this->Html->icon('envelope'),
                    'type'          => 'email',
                    'label'         => false,
                    'required'      => true,
                    'placeholder'   => __d('yab_cms_ff', 'Email'),
                ]); ?>
                <?= $this->Form->control('verify_email', [
                    'append'        => $this->Html->icon('envelope'),
                    'type'          => 'email',
                    'label'         => false,
                    'required'      => true,
                    'placeholder'   => __d('yab_cms_ff', 'Verify email'),
                ]); ?>
                <?= $this->Form->control('captcha_result', [
                    'append'        => $this->Html->icon('plus-square'),
                    'type'          => 'number',
                    'label'         => false,
                    'required'      => true,
                    'placeholder'   => $session->read('YabCmsFf.Captcha.digit_one')
                        . ' '
                        . '+'
                        . ' ' . $session->read('YabCmsFf.Captcha.digit_two')
                        . ' ' . '('
                        . __d('yab_cms_ff', 'Please calculate this addition')
                        . ')',
                ]); ?>
                <div class="row">
                    <div class="col-12">
                        <?= $this->Html->link(
                            __d('yab_cms_ff', 'Home'),
                            '/',
                            [
                                'class'     => 'btn btn-' . h($backendButtonColor),
                                'escape'    => false
                            ]); ?>
                        <?= $this->Form->button(
                            __d('yab_cms_ff', 'Create a new password'),
                            [
                                'class'     => 'btn btn-' . h($backendButtonColor) . ' float-right',
                                'escape'    => false
                            ]); ?>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>

    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jquery' . DS . 'jquery.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jquery-ui' . DS . 'jquery-ui.min', ['block' => 'scripts']); ?>

    <?= $this->Html->scriptBlock("$.widget.bridge('uibutton', $.ui.button)", ['block' => 'scripts']); ?>

    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'bootstrap' . DS . 'js' . DS . 'bootstrap.bundle.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jquery-validation' . DS . 'jquery.validate.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jquery-validation' . DS . 'additional-methods.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jqvmap' . DS . 'jquery.vmap.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jqvmap' . DS . 'maps' . DS . 'jquery.vmap.world', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'jquery-knob' . DS . 'jquery.knob.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'moment' . DS . 'moment.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'daterangepicker' . DS . 'daterangepicker', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tempusdominus-bootstrap-4' . DS . 'js' . DS . 'tempusdominus-bootstrap-4.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'summernote' . DS . 'summernote-bs4.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'overlayScrollbars' . DS . 'js' . DS . 'jquery.overlayScrollbars.min', ['block' => 'scripts']); ?>
    <?= $this->Html->script('YabCmsFf' . '.' . 'admin' . DS . 'adminlte.min', ['block' => 'scripts']); ?>

    <?= $this->Html->scriptBlock(
        '$(function() {
            $(".form-login").validate({
                rules: {
                    username: { required: true },
                    email: { required: true },
                    verify_email: { required: true }
                },
                messages: {
                    username: { required: "Please enter a username" },
                    email: { required: "Please enter a email" },
                    verify_email: { required: "Please verify the email" }
                },
                errorElement: "span",
                errorPlacement: function (error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                }
            });
        });',
        ['block' => 'scriptBottom']); ?>

    <?= $this->fetch('scripts'); ?>
    <?= $this->fetch('scriptBottom'); ?>
</body>
