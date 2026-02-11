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

$backendLinkTextColor = 'navy';
if (Configure::check('YabCmsFf.settings.backendLinkTextColor')):
    $backendLinkTextColor = Configure::read('YabCmsFf.settings.backendLinkTextColor');
endif;

// Title
$this->assign('title', $this->YabCmsFf->readCamel($this->getRequest()->getParam('controller'))
    . ' :: '
    . ucfirst($this->YabCmsFf->readCamel($this->getRequest()->getParam('action')))
    . ' :: '
    . h($log->message) . ' ' . '(' . h($log->created) . ')'
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
            'controller'    => 'Logs',
            'action'        => 'index',
        ]
    ],
    ['title' => __d('yab_cms_ff', 'View')],
    ['title' => h($log->message) . ' ' . '(' . h($log->created) . ')']
], ['class' => 'breadcrumb-item']); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= h($log->message); ?> (<?= h($log->created); ?>) - <?= __d('yab_cms_ff', 'View'); ?>
                </h3>
                <div class="card-tools">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'plugin'        => 'YabCmsFf',
                            'controller'    => 'Logs',
                            'action'        => 'index',
                        ],
                    ]); ?>
                    <?= $this->Form->control('search', [
                        'type'          => 'text',
                        'label'         => false,
                        'placeholder'   => __d('yab_cms_ff', 'Search') . '...',
                        'style'         => 'width: 150px;',
                        'append'        => $this->Form->button(
                                __d('yab_cms_ff', 'Filter'),
                                ['class' => 'btn btn-' . h($backendButtonColor)]
                            )
                            . ' '
                            . $this->Html->link(
                                __d('yab_cms_ff', 'Reset'),
                                [
                                    'plugin'        => 'YabCmsFf',
                                    'controller'    => 'Logs',
                                    'action'        => 'index',
                                ],
                                [
                                    'class'         => 'btn btn-' . h($backendButtonColor),
                                    'escapeTitle'   => false,
                                ]
                            ),
                    ]); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Id'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->id); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Request'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->request); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Type'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->type); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Message'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->message); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'IP'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->ip); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'URI'); ?></dt>
                    <dd class="col-sm-9"><?= h($log->uri); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created'); ?></dt>
                    <dd class="col-sm-9"><?= empty($log->created)? '-': h($log->created->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Created by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($log->created_by)]: h($log->created_by); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified'); ?></dt>
                    <dd class="col-sm-9"><?= empty($log->modified)? '-': h($log->modified->format('d.m.Y H:i:s')); ?></dd>
                    <dt class="col-sm-3"><?= __d('yab_cms_ff', 'Modified by'); ?></dt>
                    <dd class="col-sm-9"><?= !empty($users)? $users[h($log->modified_by)]: h($log->modified_by); ?></dd>
                </dl>
                <hr/>
                <dl>
                    <dt>
                        <?= __d('yab_cms_ff', 'Data') . ' ' . '(' . __d('yab_cms_ff', 'JSON encoded version') . ')'; ?>
                    </dt>
                    <dd>
                        <pre>
                            <code>
                                <?= $this->Text->autoParagraph(h($log->data)); ?>
                            </code>
                        </pre>
                    </dd>
                </dl>
                <hr/>
                <dl>
                    <dt>
                        <?= __d('yab_cms_ff', 'Data') . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                    </dt>
                    <dd>
                        <pre>
                            <code class="language-php">
                                <?= print_r(json_decode($log->data), true); ?>
                            </code>
                        </pre>
                    </dd>
                </dl>
                <hr/>
                <dl>
                    <dd>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <div class="card-header" id="heading">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <?= __d('yab_cms_ff', 'REST API request') . ':' . ' ' . '/api/logs/' . h($log->id) . ' ' . '(' . __d('yab_cms_ff', 'JSON decoded version') . ')'; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
                                        <pre>
                                            <code class="language-php">
                                                <?php $json = json_encode(['success' => true, 'data' => $log]); ?>
                                                <?= print_r(json_decode($json), true); ?>
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </dd>
                </dl>
                <hr/>
                <?= $this->Html->link(
                    $this->Html->icon('list') . ' ' . __d('yab_cms_ff', 'Index'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Logs',
                        'action'        => 'index',
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Html->link(
                    $this->Html->icon('edit') . ' ' . __d('yab_cms_ff', 'Edit'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Logs',
                        'action'        => 'edit',
                        'id'            => h($log->id),
                    ],
                    [
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
                <?= $this->Form->postLink(
                    $this->Html->icon('trash') . ' ' . __d('yab_cms_ff', 'Delete'),
                    [
                        'plugin'        => 'YabCmsFf',
                        'controller'    => 'Logs',
                        'action'        => 'delete',
                        'id'            => h($log->id),
                    ],
                    [
                        'confirm' => __d(
                            'yab_cms_ff',
                            'Are you sure you want to delete "{request}"?',
                            ['request' => h($log->request)]
                        ),
                        'class'         => 'btn btn-app',
                        'escapeTitle'   => false,
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->css('YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'prism' . DS . 'prism.min'); ?>
<?= $this->Html->script(
    'YabCmsFf' . '.' . 'admin' . DS . 'vendor' . DS . 'prism' . DS . 'prism.min',
    ['block' => 'scriptBottom']); ?>
