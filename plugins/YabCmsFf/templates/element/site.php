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

$frontendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.frontendLinkTextColor')):
    $frontendLinkTextColor = Configure::read('YabCmsFf.settings.frontendLinkTextColor');
endif;

$frontendControlSidebar = '0';
if (Configure::check('YabCmsFf.settings.frontendControlSidebar')):
    $frontendControlSidebar = Configure::read('YabCmsFf.settings.frontendControlSidebar');
endif;
?>
<body class="hold-transition sidebar-mini layout-fixed accent-<?= h($frontendLinkTextColor); ?> control-sidebar-slide-open text-sm">
    <div class="wrapper">
        <?= $this->element('flash'); ?>
        <?= $this->element('header'); ?>
        <?= $this->element('sidebar'); ?>
        <?= $this->element('content'); ?>
        <?= $this->element('footer'); ?>
        <?php if ($session->check('Auth.User.id') && ($session->read('Auth.User.role.alias') === 'admin')): ?>
            <?php if ($frontendControlSidebar === '1'): ?>
                <?= $this->element('control_sidebar'); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?= $this->Html->script([
        'YabCmsFf./adminlte/plugins/jquery/jquery.min',
        'YabCmsFf./adminlte/plugins/jquery-ui/jquery-ui.min'
    ], ['block' => 'scripts']); ?>
    <?= $this->Html->scriptBlock("$.widget.bridge('uibutton', $.ui.button)", ['block' => 'scripts']); ?>
    <?= $this->Html->script([
        'YabCmsFf./adminlte/plugins/bootstrap/js/bootstrap.bundle.min',
        'YabCmsFf./adminlte/plugins/jquery-validation/jquery.validate.min',
        'YabCmsFf./adminlte/plugins/jquery-validation/additional-methods.min',
        'YabCmsFf./adminlte/plugins/jqvmap/jquery.vmap.min',
        'YabCmsFf./adminlte/plugins/jqvmap/maps/jquery.vmap.world',
        'YabCmsFf./adminlte/plugins/jquery-knob/jquery.knob.min',
        'YabCmsFf./adminlte/plugins/moment/moment.min',
        'YabCmsFf./adminlte/plugins/daterangepicker/daterangepicker',
        'YabCmsFf./adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min',
        'YabCmsFf./adminlte/plugins/summernote/summernote-bs4.min',
        'YabCmsFf./adminlte/plugins/select2/js/select2.full.min',
        'YabCmsFf./adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min',
        'YabCmsFf./adminlte/dist/js/adminlte.min',
    ], ['block' => 'scripts']); ?>
    <?= $this->fetch('scripts'); ?>
    <?= $this->fetch('scriptBottom'); ?>
</body>
