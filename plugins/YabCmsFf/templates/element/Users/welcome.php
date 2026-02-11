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

// Get session object
$session = $this->getRequest()->getSession();

$frontendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendButtonColor')):
    $frontendButtonColor = Configure::read('YabCmsFf.settings.frontendButtonColor');
endif;

$frontendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendBoxColor')):
    $frontendBoxColor = Configure::read('YabCmsFf.settings.frontendBoxColor');
endif;

// Title
$this->assign('title', __d('yab_cms_ff', 'Welcome {name}, your user account has been created automatically', ['name' => $userAccount->name]));

// Breadcrumb
$this->Breadcrumbs->addMany([
    [
        'title' => __d('yab_cms_ff', 'Go back'),
        'url' => 'javascript:history.back()',
    ],
    [
        'title' => __d('yab_cms_ff', 'Yet another boring CMS for FREE'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Articles',
            'action'        => 'promoted',
        ],
    ],
    ['title' => __d('yab_cms_ff', 'Welcome {name}, your user account has been created automatically', ['name' => $userAccount->name])]
], ['class' => 'breadcrumb-item']); ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', 'Welcome {name}, your user account has been created automatically', ['name' => $userAccount->name]); ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <?= $this->element('breadcrumb'); ?>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <?php // Account Avatar ?>
                <div class="card card-<?= h($frontendBoxColor); ?> card-outline">
                    <div class="card-body">
                        <div class="position-relative mb-3" style="min-height: 180px;">
                            <?php if ($session->check('Auth.User.avatar')): ?>
                                <?= $this->Html->image(
                                    $session->read('Auth.User.avatar'),
                                    [
                                        'alt'   => h($session->read('Auth.User.username')),
                                        'class' => 'img-fluid',
                                    ]); ?>
                            <?php else: ?>
                                <?= $this->Html->image(
                                    '/yab_cms_ff/img/avatars/avatar.jpg',
                                    [
                                        'alt'   => h($session->read('Auth.User.username')),
                                        'class' => 'img-fluid',
                                    ]); ?>
                            <?php endif; ?>
                            <div class="ribbon-wrapper ribbon-xl">
                                <div class="ribbon bg-success text-xl">
                                    <?= __d('yab_cms_ff', 'WELCOME'); ?>
                                </div>
                            </div>
                        </div>
                        <h3 class="profile-username text-center">
                            <?= h($session->read('Auth.User.name')); ?>
                        </h3>
                        <p class="text-muted text-center">
                            <?= h($session->read('Auth.User.username')); ?>
                        </p>
                        <p class="text-muted text-center">
                            <?= __d('yab_cms_ff', 'Active since: {activationDate}', ['activationDate' => h($userAccount->activation_date->format('d.m.Y'))]); ?>
                        </p>
                    </div>
                </div>
                <?php // Account Avatar End ?>

            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="timeline timeline-inverse">
                            <div class="time-label">
                                <span class="bg-<?= h($frontendBoxColor); ?>">
                                    <?= __d('yab_cms_ff', 'Your account'); ?>
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-user bg-<?= h($frontendBoxColor); ?>"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="far fa-clock"></i> <?= h($userAccount->modified->nice()); ?>
                                    </span>
                                    <h3 class="timeline-header">
                                        <strong><?= __d('yab_cms_ff', 'Please update your account data'); ?></strong>
                                    </h3>
                                    <div class="timeline-body">
                                        <?= $this->Form->create(null, [
                                            'url' => [
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'Users',
                                                'action'        => 'edit',
                                            ],
                                            'class' => 'form-horizontal user-edit-' . h($userAccount->id),
                                        ]); ?>
                                        <?= $this->Form->control('foreign_key', [
                                            'type'      => 'text',
                                            'value'     => !empty($userAccount->foreign_key)? htmlspecialchars_decode($userAccount->foreign_key): Text::uuid(),
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Foreign key') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => true,
                                            'readonly'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('locale_id', [
                                            'type'      => 'select',
                                            'value'     => h($userAccount->locale_id),
                                            'options'   => !empty($this->YabCmsFf->localesList())? $this->YabCmsFf->localesList(): [],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Locale'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('username', [
                                            'type'      => 'text',
                                            'value'     => h($userAccount->username),
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Username') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => true,
                                            'readonly'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('name', [
                                            'type'      => 'text',
                                            'value'     => htmlspecialchars_decode($userAccount->name),
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Name') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => true,
                                        ]); ?>
                                        <?= $this->Form->control('email', [
                                            'type'      => 'email',
                                            'value'     => htmlspecialchars_decode($userAccount->email),
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Email') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => true,
                                        ]); ?>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                            </div>
                                        </div>
                                        <?= $this->Form->end(); ?>

                                        <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
                                        <?= $this->Html->script(
                                            'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
                                            ['block' => 'scriptBottom']); ?>

                                        <?= $this->Html->scriptBlock(
                                            '$(function() {
                                                // Initialize select2
                                                $(\'.select2\').select2({ width:\'100%\'});
                                                $(\'.user-edit-' . h($userAccount->id) . '\').validate({
                                                    rules: {
                                                        foreign_key: {
                                                            required: true
                                                        },
                                                        locale_id: {
                                                            required: true
                                                        },
                                                        username: {
                                                            required: true
                                                        },
                                                        name: {
                                                            required: true
                                                        },
                                                        email: {
                                                            required: true
                                                        }
                                                    },
                                                    messages: {
                                                        foreign_key: {
                                                            required: \'' . __d('yab_cms_ff', 'Please enter a valid foreign key') . '\'
                                                        },
                                                        locale_id: {
                                                            required: \'' . __d('yab_cms_ff', 'Please select a locale') . '\'
                                                        },
                                                        username: {
                                                            required: \'' . __d('yab_cms_ff', 'Please enter a valid username') . '\'
                                                        },
                                                        name: {
                                                            required: \'' . __d('yab_cms_ff', 'Please enter a valid name') . '\'
                                                        },
                                                        email: {
                                                            required: \'' . __d('yab_cms_ff', 'Please enter a valid email address') . '\'
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
                                    </div>
                                </div>
                            </div>

                            <div class="time-label">
                                <span class="bg-<?= h($frontendBoxColor); ?>">
                                    <?= __d('yab_cms_ff', 'Your profile'); ?>
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-address-card bg-<?= h($frontendBoxColor); ?>"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">
                                        <strong><?= __d('yab_cms_ff', 'Please create your profile'); ?></strong>
                                    </h3>
                                    <div class="timeline-body">
                                        <?= $this->Form->create(null, [
                                            'role'  => 'form',
                                            'type'  => 'file',
                                            'url'   => [
                                                'plugin'        => 'YabCmsFf',
                                                'controller'    => 'UserProfiles',
                                                'action'        => 'add',
                                            ],
                                            'class' => 'form-horizontal user-profile-add',
                                        ]); ?>
                                        <?= $this->Form->control('foreign_key', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Foreign key') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'value'     => Text::uuid(),
                                            'required'  => true,
                                            'readonly'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('timezone', [
                                            'type'      => 'select',
                                            'options'   => !empty($this->YabCmsFf->timezone())? $this->YabCmsFf->timezone(): [],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Timezone') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('prefix', [
                                            'type'      => 'select',
                                            'options'   => !empty($this->YabCmsFf->prefixes())? $this->YabCmsFf->prefixes(): [],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Prefix'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => false,
                                        ]); ?>
                                        <?= $this->Form->control('salutation', [
                                            'type'      => 'select',
                                            'options'   => !empty($this->YabCmsFf->salutations())? $this->YabCmsFf->salutations(): [],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Salutation') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => true,
                                        ]); ?>
                                        <?= $this->Form->control('first_name', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'First name') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => true,
                                        ]); ?>
                                        <?= $this->Form->control('middle_name', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Middle name'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('last_name', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Last name') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => true,
                                        ]); ?>
                                        <?= $this->Form->control('gender', [
                                            'type'      => 'select',
                                            'options'   => [
                                                'Male'      => __d('yab_cms_ff', 'Male'),
                                                'Female'    => __d('yab_cms_ff', 'Female'),
                                            ],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => $this->Html->tag('p', __d('yab_cms_ff', 'Gender') . '*', ['class' => 'text-danger']),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => false,
                                        ]); ?>
                                        <?= $this->Form->control('telephone', [
                                            'type'          => 'number',
                                            'placeholder'   => '00490123456789',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Telephone'),
                                                'required'      => false,
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('mobilephone', [
                                            'type'          => 'number',
                                            'placeholder'   => '00490123456789',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Mobilephone'),
                                                'required'      => false,
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('fax', [
                                            'type'          => 'number',
                                            'placeholder'   => '00490123456789',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Fax'),
                                                'required'      => false,
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('website', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Website'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('company', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Company'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('street', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Street'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('street_addition', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Street addition'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('postcode', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Postcode'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('city', [
                                            'type'  => 'text',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'City'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required' => false,
                                        ]); ?>
                                        <?= $this->Form->control('country_id', [
                                            'type'      => 'select',
                                            'options'   => !empty($this->YabCmsFf->countriesList())? $this->YabCmsFf->countriesList(): [],
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Country'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'select2',
                                            'empty'     => true,
                                            'required'  => false,
                                        ]); ?>
                                        <hr />
                                        <?= $this->Form->control('about_me', [
                                            'type'  => 'textarea',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'About Me'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'class'     => 'about_me',
                                            'required'  => false,
                                        ]); ?>
                                        <?= $this->Form->control('tags', [
                                            'type'  => 'textarea',
                                            'label' => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Tags'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates' => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                        ]); ?>
                                        <?= $this->Form->control('image', [
                                            'type'      => 'hidden',
                                            'value'     => '/yab_cms_ff/img/avatars/avatar.jpg',
                                        ]); ?>
                                        <?= $this->Form->control('image_file', [
                                            'type'      => 'file',
                                            'accept'    => 'image/jpeg,image/jpg',
                                            'label'     => [
                                                'class'         => 'col-sm-2 col-form-label',
                                                'text'          => __d('yab_cms_ff', 'Image'),
                                                'escapeTitle'   => false,
                                            ],
                                            'templates'     => [
                                                'inputContainer'        => '<div class="form-group row {{type}}{{required}}">{{content}}{{help}}</div>',
                                                'inputContainerError'   => '<div class="form-group row {{type}}{{required}} invalid-feedback">{{content}}{{error}}{{help}}</div>',
                                                'formGroup'             => '{{label}}' . '<div class="col-sm-10">{{input}}</div>',
                                            ],
                                            'required'  => false,
                                        ]); ?>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <?= $this->Form->button(__d('yab_cms_ff', 'Save'), ['class' => 'btn btn-' . h($frontendButtonColor) . ' btn-block']); ?>
                                            </div>
                                        </div>
                                        <?= $this->Form->end(); ?>

                                        <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'css' . DS . 'select2.min'); ?>
                                        <?= $this->Html->script(
                                            'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'select2' . DS . 'js' . DS . 'select2.full.min',
                                            ['block' => 'scriptBottom']); ?>

                                        <?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'tagify'); ?>
                                        <?= $this->Html->script(
                                            'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'tagify' . DS . 'jQuery.tagify.min',
                                            ['block' => 'scriptBottom']); ?>

                                        <?= $this->Html->scriptBlock(
                                            '$(function() {
                                            // Initialize select2
                                            $(\'.select2\').select2({ width:\'100%\'});
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
                                            $(\'.user-profile-add\').submit(function(event) {
                                                $(\'.about_me\').summernote(\'destroy\');
                                            });
                                            $(\'.user-profile-add\').validate({
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
                                    </div>
                                </div>
                            </div>

                            <div>
                                <i class="far fa-check-circle bg-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->Html->css('YabCmsFf' . '.' . 'template' . DS . 'element' . DS . 'users' . DS . 'profile'); ?>
