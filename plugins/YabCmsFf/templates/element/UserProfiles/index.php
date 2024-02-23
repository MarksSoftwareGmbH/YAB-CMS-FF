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

$frontendButtonColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendButtonColor')):
    $frontendButtonColor = Configure::read('YabCmsFf.settings.frontendButtonColor');
endif;

$frontendBoxColor = 'secondary';
if (Configure::check('YabCmsFf.settings.frontendBoxColor')):
    $frontendBoxColor = Configure::read('YabCmsFf.settings.frontendBoxColor');
endif;

$locale = $session->check('Locale.code')? $session->read('Locale.code'): 'en_US';

// Define global settings
$settings = [];
if (!empty($settings_for_layout)):
    $settings = $settings_for_layout['settings'];
endif;
// Site title
$siteTitle = isset($settings['siteTitle'])? $settings['siteTitle']: '';
if (isset($settings['siteTitlePrefix']) & !empty($settings['siteTitlePrefix'])):
    $siteTitle = h($settings['siteTitlePrefix']) . ' - ' . $siteTitle;
endif;
if (isset($settings['siteTitleSuffix']) & !empty($settings['siteTitleSuffix'])):
    $siteTitle = $siteTitle . ' - ' . h($settings['siteTitleSuffix']);
endif;

// Title
$this->assign('title', __d('yab_cms_ff', 'Profiles'));

$this->Html->meta('robots', 'index, follow', ['block' => true]);
$this->Html->meta('author', $siteTitle, ['block' => true]);
$this->Html->meta('description', __d('yab_cms_ff', 'Profiles'), ['block' => true]);

$this->Html->meta([
    'property'  => 'og:title',
    'content'   => __d('yab_cms_ff', 'Profiles'),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:description',
    'content'   => __d('yab_cms_ff', 'Profiles'),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:url',
    'content'   => $this->Url->build([
        'plugin'        => 'YabCmsFf',
        'controller'    => 'UserProfiles',
        'action'        => 'index',
    ], ['fullBase' => true]),
    'block' => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:locale',
    'content'   => $session->read('Locale.code'),
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:type',
    'content'   => 'profile',
    'block'     => 'meta',
]);
$this->Html->meta([
    'property'  => 'og:site_name',
    'content'   => 'Yet another boring CMS for FREE',
    'block'     => 'meta',
]);

// Breadcrumb
$this->Breadcrumbs->add([
    [
        'title' => __d('yab_cms_ff', 'Yet another boring CMS for FREE'),
        'url' => [
            'plugin'        => 'YabCmsFf',
            'controller'    => 'Articles',
            'action'        => 'promoted',
        ],
    ],
    ['title' => __d('yab_cms_ff', 'Profiles')]
]);
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <?= __d('yab_cms_ff', 'Profiles'); ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <?= $this->element('breadcrumb'); ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($userProfiles)): ?>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'UserProfiles',
                            'action'        => 'index',
                        ],
                    ]); ?>
                    <?= $this->Form->control('search', [
                        'type'          => 'text',
                        'value'         => $this->getRequest()->getQuery('search'),
                        'label'         => false,
                        'placeholder'   => __d('yab_cms_ff', 'Search profiles') . '...',
                        'append' => $this->Form->button(
                                __d('yab_cms_ff', 'Search'),
                                ['class' => 'btn btn-' . h($frontendButtonColor)]
                            )
                            . ' '
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Reset'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'UserProfiles',
                                    'action'        => 'index',
                                ],
                                [
                                    'class'         => 'btn btn-' . h($frontendButtonColor),
                                    'escapeTitle'   => false,
                                ]
                            ),
                    ]); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>

            <div class="row">
                <?php foreach($userProfiles as $userProfile): ?>
                    <div class="col-md-4">
                        <div class="card card-widget widget-user">
                            <div class="widget-user-header bg-<?= h($frontendBoxColor); ?>">
                                <h3 class="widget-user-username">
                                    <?= !empty($userProfile->first_name)? $this->Html->link(htmlspecialchars_decode($userProfile->first_name), ['controller' => 'UserProfiles', 'action' => 'view', 'foreignKey' => h($userProfile->foreign_key)], ['target' => '_self', 'class' => 'text-light']): ''; ?>
                                    <?= !empty($userProfile->middle_name)? $this->Html->link(htmlspecialchars_decode($userProfile->middle_name), ['controller' => 'UserProfiles', 'action' => 'view', 'foreignKey' => h($userProfile->foreign_key)], ['target' => '_self', 'class' => 'text-light']): ''; ?>
                                    <?= !empty($userProfile->last_name)? $this->Html->link(htmlspecialchars_decode($userProfile->last_name), ['controller' => 'UserProfiles', 'action' => 'view', 'foreignKey' => h($userProfile->foreign_key)], ['target' => '_self', 'class' => 'text-light']): ''; ?>
                                </h3>
                                <?= !empty($userProfile->website)? $this->Html->tag('h5', $this->Html->link(htmlspecialchars_decode($userProfile->website), htmlspecialchars_decode($userProfile->website), ['target' => '_blank', 'class' => 'text-light'])): ''; ?>
                            </div>
                            <?= !empty($userProfile->image)? $this->Html->link(
                                    $this->Html->tag('div', $this->Html->image(h($userProfile->image), ['alt' => __d('yab_cms_ff', 'Avatar'), 'class' => 'img-circle elevation-2']), ['class' => 'widget-user-image']),
                                    ['controller' => 'UserProfiles', 'action' => 'view', 'foreignKey' => htmlspecialchars_decode($userProfile->foreign_key)],
                                    ['target' => '_self', 'escapeTitle' => false]): ''; ?>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-6 border-right">
                                        <div class="description-block">
                                            <?= $this->Html->tag('h5', 0, ['class' => 'description-header diary-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]); ?>
                                            <?= $this->Html->scriptBlock(
                                                '$(function() {
                                                    $(document).ready(function() {
                                                        $.ajax({
                                                            beforeSend: function(xhr) {
                                                                xhr.setRequestHeader(\'X-CSRF-Token\', \'' . $this->getRequest()->getAttribute('csrfToken') . '\');
                                                            },
                                                            url: \'' . $this->Url->build([
                                                                'plugin'        => 'YabCmsFf',
                                                                'controller'    => 'UserProfiles',
                                                                'action'        => 'countDiaryEntries',
                                                            ]) . '\',
                                                            data: {foreign_key: \'' . htmlspecialchars_decode($userProfile->foreign_key) . '\'},
                                                            type: \'JSON\',
                                                            method: \'POST\',
                                                            success: function(data) {
                                                                $(\'.diary-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').html(data.diaryEntriesCounter);
                                                            }
                                                        });
                                                    });
                                                });',
                                                ['block' => 'scriptBottom']); ?>
                                            <span class="description-text">
                                                <?= $this->Html->link(__d('yab_cms_ff', 'Diary entries'), [
                                                    'controller'    => 'UserProfileDiaryEntries',
                                                    'action'        => 'index',
                                                    'foreignKey'    => h($userProfile->foreign_key)
                                                ]); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 border-right">
                                        <div class="description-block">
                                            <?= $this->Html->tag('h5', 0, ['class' => 'description-header timeline-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key)]); ?>
                                            <?= $this->Html->scriptBlock(
                                                '$(function() {
                                                    $(document).ready(function() {
                                                        $.ajax({
                                                            beforeSend: function(xhr) {
                                                                xhr.setRequestHeader(\'X-CSRF-Token\', \'' . $this->getRequest()->getAttribute('csrfToken') . '\');
                                                            },
                                                            url: \'' . $this->Url->build([
                                                                'plugin'        => 'YabCmsFf',
                                                                'controller'    => 'UserProfiles',
                                                                'action'        => 'countTimelineEntries',
                                                            ]) . '\',
                                                            data: {foreign_key: \'' . htmlspecialchars_decode($userProfile->foreign_key) . '\'},
                                                            type: \'JSON\',
                                                            method: \'POST\',
                                                            success: function(data) {
                                                                $(\'.timeline-entries-counter-' . htmlspecialchars_decode($userProfile->foreign_key) . '\').html(data.timelineEntriesCounter);
                                                            }
                                                        });
                                                    });
                                                });',
                                                ['block' => 'scriptBottom']); ?>
                                            <span class="description-text">
                                                <?= $this->Html->link(__d('yab_cms_ff', 'Timeline entries'), [
                                                    'controller'    => 'UserProfileTimelineEntries',
                                                    'action'        => 'index',
                                                    'foreignKey'    => h($userProfile->foreign_key)
                                                ]); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

