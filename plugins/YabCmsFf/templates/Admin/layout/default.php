<!DOCTYPE html>
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
// Get browser language first
$language = 'en';
if (!empty(getEnv('HTTP_ACCEPT_LANGUAGE'))):
    $language = substr(getEnv('HTTP_ACCEPT_LANGUAGE'), 0, 2);
endif;
// If session locale set use it as language
if ($session->check('Locale.code')):
    $language = substr($session->read('Locale.code'), 0, 2);
endif;

// Set active controller
$activeController = $this->getRequest()->getParam('controller');

// Set active action
$activeAction = $this->getRequest()->getParam('action');

// Define global settings
$settings = [];
if (!empty($settings_for_layout)):
    $settings = $settings_for_layout['settings'];
endif;
// Site title
$siteTitle = isset($settings['siteTitle'])? $settings['siteTitle']: '';
if (isset($settings['siteTitlePrefix']) && !empty($settings['siteTitlePrefix'])):
    $siteTitle = h($settings['siteTitlePrefix']) . ' | ' . $siteTitle;
endif;
if (isset($settings['siteTitleSuffix']) && !empty($settings['siteTitleSuffix'])):
    $siteTitle = $siteTitle . ' | ' . h($settings['siteTitleSuffix']);
endif;
// Define global locales
$locales = [];
if (!empty($locales_for_layout)):
    $locales = $locales_for_layout['locales'];
endif;
?>
<html lang="<?= $language; ?>">
<head>
    <title><?= $siteTitle; ?> | <?= h($this->fetch('title')); ?></title>
    <?= $this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <?= $this->Html->meta('favicon.ico', DS . 'YabCmsFf' . DS . 'img' . DS . 'admin' . DS . 'favicons' . DS . 'favicon.ico', ['type' => 'icon']); ?>
    <?= $this->fetch('meta'); ?>
    <link rel="canonical" href="<?= $this->Url->build(); ?>" />
    <?= $this->Html->css([
        // 1. Original-Dateien dynamisch aus vendor/almasaeed2010/adminlte/
        'YabCmsFf./adminlte/plugins/fontawesome-free/css/all.min',
        'YabCmsFf./adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min',
        'YabCmsFf./adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min',
        'YabCmsFf./adminlte/plugins/jqvmap/jqvmap.min',
        'YabCmsFf./adminlte/dist/css/adminlte.min',
        'YabCmsFf./adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min',
        'YabCmsFf./adminlte/plugins/daterangepicker/daterangepicker',
        'YabCmsFf./adminlte/plugins/summernote/summernote-bs4.min',
        // 2. Overrides in plugins/YabCmsFf/webroot/css/admin/
        'YabCmsFf.admin/fonts',
    ]); ?>

    <?php if ($session->check('Auth.User.id')): ?>
        <?= $this->Html->css([
            'YabCmsFf.admin/yab_cms_ff.admin',
            'YabCmsFf.admin/yab_cms_ff.default',
            'YabCmsFf.admin/yab_cms_ff.select2',
        ]); ?>
    <?php endif; ?>

    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>
</head>

<?php if ($session->check('Auth.User.id')): ?>
    <?= $this->element('site'); ?>
<?php else: ?>
    <?php
        switch ($activeAction):

            case 'login':
                echo $this->element('Users/login');
                break;
            default:
                echo $this->element('site');

        endswitch;
    ?>
<?php endif; ?>

</html>
