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

$frontendNavbarColor = 'dark';
if (Configure::check('YabCmsFf.settings.frontendNavbarColor')):
    $frontendNavbarColor = Configure::read('YabCmsFf.settings.frontendNavbarColor');
endif;

$frontendNavbarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.frontendNavbarTextColor')):
    $frontendNavbarTextColor = Configure::read('YabCmsFf.settings.frontendNavbarTextColor');
endif;

$frontendNavbarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendNavbarBackgroundColor')):
    $frontendNavbarBackgroundColor = Configure::read('YabCmsFf.settings.frontendNavbarBackgroundColor');
endif;

$frontendSidebarColor = 'dark';
if (Configure::check('YabCmsFf.settings.frontendSidebarColor')):
    $frontendSidebarColor = Configure::read('YabCmsFf.settings.frontendSidebarColor');
endif;

$frontendSidebarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.frontendSidebarTextColor')):
    $frontendSidebarTextColor = Configure::read('YabCmsFf.settings.frontendSidebarTextColor');
endif;

$frontendSidebarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendSidebarBackgroundColor')):
    $frontendSidebarBackgroundColor = Configure::read('YabCmsFf.settings.frontendSidebarBackgroundColor');
endif;

$frontendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendLinkTextColor')):
    $frontendLinkTextColor = Configure::read('YabCmsFf.settings.frontendLinkTextColor');
endif;

$frontendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendButtonColor')):
    $frontendButtonColor = Configure::read('YabCmsFf.settings.frontendButtonColor');
endif;

$frontendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendBoxColor')):
    $frontendBoxColor = Configure::read('YabCmsFf.settings.frontendBoxColor');
endif;
?>
<aside class="control-sidebar control-sidebar-<?= h($frontendSidebarColor); ?> customize-the-frontend">
    <div class="p-3 control-sidebar-content">
        <h5><?= __d('yab_cms_ff', 'Customize the frontend'); ?></h5>
        <hr class="mb-2">

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 13,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendNavbarColorOptions = [
                'white'     => __d('yab_cms_ff', 'White'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'dark'      => __d('yab_cms_ff', 'Dark'),
                'light'     => __d('yab_cms_ff', 'Light'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Navbar color'),
                'default'   => h($frontendNavbarColor),
                'options'   => h($frontendNavbarColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 14,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendNavbarTextColorOptions = [
                'white'     => __d('yab_cms_ff', 'White'),
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'secondary' => __d('yab_cms_ff', 'Secondary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'cyan'      => __d('yab_cms_ff', 'Cyan'),
                'gray'      => __d('yab_cms_ff', 'Gray'),
                'gray-dark' => __d('yab_cms_ff', 'Gray-Dark'),
                'dark'      => __d('yab_cms_ff', 'Dark'),
                'light'     => __d('yab_cms_ff', 'Light'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Navbar text color'),
                'default'   => h($frontendNavbarTextColor),
                'options'   => h($frontendNavbarTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type' => 'post',
            'url' => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 15,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendNavbarBackgroundColorOptions = [
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'secondary' => __d('yab_cms_ff', 'Secondary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'fuchsia'   => __d('yab_cms_ff', 'Fuchsia'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'olive'     => __d('yab_cms_ff', 'Olive'),
                'maroon'    => __d('yab_cms_ff', 'Maroon'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'lime'      => __d('yab_cms_ff', 'Lime'),
                'cyan'      => __d('yab_cms_ff', 'Cyan'),
                'gray'      => __d('yab_cms_ff', 'Gray'),
                'gray-dark' => __d('yab_cms_ff', 'Gray-Dark'),
                'dark'      => __d('yab_cms_ff', 'Dark'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Navbar background color'),
                'default'   => h($frontendNavbarBackgroundColor),
                'options'   => h($frontendNavbarBackgroundColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 16,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendSidebarColorOptions = [
                'dark'      => __d('yab_cms_ff', 'Dark'),
                'light'     => __d('yab_cms_ff', 'Light'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Sidebar color'),
                'default'   => h($frontendSidebarColor),
                'options'   => h($frontendSidebarColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 17,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendSidebarTextColorOptions = [
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'fuchsia'   => __d('yab_cms_ff', 'Fuchsia'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'maroon'    => __d('yab_cms_ff', 'Maroon'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'lime'      => __d('yab_cms_ff', 'Lime'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'olive'     => __d('yab_cms_ff', 'Olive'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Sidebar text color'),
                'default'   => h($frontendSidebarTextColor),
                'options'   => h($frontendSidebarTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 18,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendSidebarBackgroundColorOptions = [
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'fuchsia'   => __d('yab_cms_ff', 'Fuchsia'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'maroon'    => __d('yab_cms_ff', 'Maroon'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'lime'      => __d('yab_cms_ff', 'Lime'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'olive'     => __d('yab_cms_ff', 'Olive'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Sidebar background color'),
                'default'   => h($frontendSidebarBackgroundColor),
                'options'   => h($frontendSidebarBackgroundColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 19,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendLinkTextColorOptions = [
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'fuchsia'   => __d('yab_cms_ff', 'Fuchsia'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'maroon'    => __d('yab_cms_ff', 'Maroon'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'lime'      => __d('yab_cms_ff', 'Lime'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'olive'     => __d('yab_cms_ff', 'Olive'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Link text color'),
                'default'   => h($frontendLinkTextColor),
                'options'   => h($frontendLinkTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 20,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendButtonColorOptions = [
                'dark'                  => __d('yab_cms_ff', 'Dark'),
                'light'                 => __d('yab_cms_ff', 'Light'),
                'primary'               => __d('yab_cms_ff', 'Primary'),
                'secondary'             => __d('yab_cms_ff', 'Secondary'),
                'info'                  => __d('yab_cms_ff', 'Info'),
                'success'               => __d('yab_cms_ff', 'Success'),
                'warning'               => __d('yab_cms_ff', 'Warning'),
                'danger'                => __d('yab_cms_ff', 'Danger'),
                'outline-dark'          => __d('yab_cms_ff', 'Outline Dark'),
                'outline-light'         => __d('yab_cms_ff', 'Outline Light'),
                'outline-primary'       => __d('yab_cms_ff', 'Outline Primary'),
                'outline-secondary'     => __d('yab_cms_ff', 'Outline Secondary'),
                'outline-info'          => __d('yab_cms_ff', 'Outline Info'),
                'outline-success'       => __d('yab_cms_ff', 'Outline Success'),
                'outline-warning'       => __d('yab_cms_ff', 'Outline Warning'),
                'outline-danger'        => __d('yab_cms_ff', 'Outline Danger'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Button color'),
                'default'   => h($frontendButtonColor),
                'options'   => h($frontendButtonColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'prefix'        => 'Admin',
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 21,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $frontendBoxColorOptions = [
                'primary'   => __d('yab_cms_ff', 'Primary'),
                'secondary' => __d('yab_cms_ff', 'Secondary'),
                'info'      => __d('yab_cms_ff', 'Info'),
                'success'   => __d('yab_cms_ff', 'Success'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'danger'    => __d('yab_cms_ff', 'Danger'),
                'indigo'    => __d('yab_cms_ff', 'Indigo'),
                'purple'    => __d('yab_cms_ff', 'Purple'),
                'pink'      => __d('yab_cms_ff', 'Pink'),
                'navy'      => __d('yab_cms_ff', 'Navy'),
                'lightblue' => __d('yab_cms_ff', 'Lightblue'),
                'fuchsia'   => __d('yab_cms_ff', 'Fuchsia'),
                'teal'      => __d('yab_cms_ff', 'Teal'),
                'olive'     => __d('yab_cms_ff', 'Olive'),
                'maroon'    => __d('yab_cms_ff', 'Maroon'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'lime'      => __d('yab_cms_ff', 'Lime'),
                'cyan'      => __d('yab_cms_ff', 'Cyan'),
                'gray'      => __d('yab_cms_ff', 'Gray'),
                'gray-dark' => __d('yab_cms_ff', 'Gray-Dark'),
                'dark'      => __d('yab_cms_ff', 'Dark'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Box color'),
                'default'   => h($frontendBoxColor),
                'options'   => h($frontendBoxColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($frontendButtonColor) . ' bg-' . h($frontendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

</aside>

<?= $this->Html->scriptBlock(
    '$(function() {
        $(\'.customize-the-frontend\').hide();
    });',
    ['block' => 'scriptBottom']); ?>