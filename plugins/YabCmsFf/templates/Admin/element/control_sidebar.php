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

$backendNavbarColor = 'dark';
if (Configure::check('YabCmsFf.settings.backendNavbarColor')):
    $backendNavbarColor = Configure::read('YabCmsFf.settings.backendNavbarColor');
endif;

$backendNavbarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.backendNavbarTextColor')):
    $backendNavbarTextColor = Configure::read('YabCmsFf.settings.backendNavbarTextColor');
endif;

$backendNavbarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendNavbarBackgroundColor')):
    $backendNavbarBackgroundColor = Configure::read('YabCmsFf.settings.backendNavbarBackgroundColor');
endif;

$backendSidebarColor = 'dark';
if (Configure::check('YabCmsFf.settings.backendSidebarColor')):
    $backendSidebarColor = Configure::read('YabCmsFf.settings.backendSidebarColor');
endif;

$backendSidebarTextColor = 'white';
if (Configure::check('YabCmsFf.settings.backendSidebarTextColor')):
    $backendSidebarTextColor = Configure::read('YabCmsFf.settings.backendSidebarTextColor');
endif;

$backendSidebarBackgroundColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendSidebarBackgroundColor')):
    $backendSidebarBackgroundColor = Configure::read('YabCmsFf.settings.backendSidebarBackgroundColor');
endif;

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

$backendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.backendButtonColor')):
    $backendButtonColor = Configure::read('YabCmsFf.settings.backendButtonColor');
endif;

$backendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.backendBoxColor')):
    $backendBoxColor = Configure::read('YabCmsFf.settings.backendBoxColor');
endif;

?>
<aside class="control-sidebar control-sidebar-<?= h($backendSidebarColor); ?> customize-the-backend">
    <div class="p-3 control-sidebar-content">
        <h5><?= __d('yab_cms_ff', 'Customize the backend'); ?></h5>
        <hr class="mb-2">

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 3,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendNavbarColorOptions = [
                'white'     => __d('yab_cms_ff', 'White'),
                'warning'   => __d('yab_cms_ff', 'Warning'),
                'orange'    => __d('yab_cms_ff', 'Orange'),
                'dark'      => __d('yab_cms_ff', 'Dark'),
                'light'     => __d('yab_cms_ff', 'Light'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Navbar color'),
                'default'   => h($backendNavbarColor),
                'options'   => h($backendNavbarColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 4,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendNavbarTextColorOptions = [
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
                'default'   => h($backendNavbarTextColor),
                'options'   => h($backendNavbarTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type' => 'post',
            'url' => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 5,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendNavbarBackgroundColorOptions = [
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
                'default'   => h($backendNavbarBackgroundColor),
                'options'   => h($backendNavbarBackgroundColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 6,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendSidebarColorOptions = [
                'dark'      => __d('yab_cms_ff', 'Dark'),
                'light'     => __d('yab_cms_ff', 'Light'),
            ]; ?>
            <?= $this->Form->control('value', [
                'type'      => 'select',
                'label'     => __d('yab_cms_ff', 'Sidebar color'),
                'default'   => h($backendSidebarColor),
                'options'   => h($backendSidebarColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 7,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendSidebarTextColorOptions = [
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
                'default'   => h($backendSidebarTextColor),
                'options'   => h($backendSidebarTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 8,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendSidebarBackgroundColorOptions = [
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
                'default'   => h($backendSidebarBackgroundColor),
                'options'   => h($backendSidebarBackgroundColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 9,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendLinkTextColorOptions = [
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
                'default'   => h($backendLinkTextColor),
                'options'   => h($backendLinkTextColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 10,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendButtonColorOptions = [
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
                'default'   => h($backendButtonColor),
                'options'   => h($backendButtonColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, [
            'type'  => 'post',
            'url'   => [
                'controller'    => 'Settings',
                'action'        => 'edit',
                'id'            => 11,
            ],
            'class' => 'form-general'
        ]); ?>
            <?php $backendBoxColorOptions = [
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
                'default'   => h($backendBoxColor),
                'options'   => h($backendBoxColorOptions),
                'class'     => 'custom-select mb-3 border-0 text-' . h($backendButtonColor) . ' bg-' . h($backendBoxColor),
                'style'     => 'width: 100%',
                'empty'     => false,
                'required'  => true,
                'onchange'  => 'this.form.submit();',
            ]); ?>
        <?= $this->Form->end(); ?>

</aside>

<?= $this->Html->scriptBlock(
    '$(function() {
        $(\'.customize-the-backend\').hide();
    });',
    ['block' => 'scriptBottom']); ?>