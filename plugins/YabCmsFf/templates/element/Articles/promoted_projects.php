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
use Cake\Utility\Text;

// Get session object
$session = $this->getRequest()->getSession();
?>

<?php if (!empty($projects)): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= __d('yab_cms_ff', 'Recent middleware projects'); ?></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="<?= __d('yab_cms_ff', 'Collapse'); ?>">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 60%">
                        <?= __d('yab_cms_ff', 'Project'); ?>
                    </th>
                    <th style="width: 20%">
                        <?= __d('yab_cms_ff', 'Progress'); ?>
                    </th>
                    <th style="width: 20%">
                        <?= __d('yab_cms_ff', 'Members'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($projects as $project): ?>
                    <tr>
                        <td class="align-middle">
                            <strong><?= $this->Html->link($project->global_title, '/' . h($project->article_type->alias) . '/' . h($project->slug)); ?></strong>
                            <br />
                            <?= $project->subtitle; ?><br />
                            <small>
                                <?= __d('yab_cms_ff', 'Last updated {date}', ['date' => $project->modified->nice()]); ?>
                            </small>
                        </td>
                        <td class="project_progress align-middle">
                            <?php if (empty($project->project_progress)): ?>
                                <?php $project->project_progress = 0; ?>
                            <?php endif; ?>
                            <div class="progress progress-sm border border-secondary">
                                <div
                                    class="progress-bar bg-green"
                                    role="progressbar"
                                    aria-volumenow="<?= h($project->project_progress); ?>"
                                    aria-volumemin="0"
                                    aria-volumemax="100"
                                    style="width: <?= h($project->project_progress); ?>%"
                                ></div>
                            </div>
                            <?= __d('yab_cms_ff', '{percent}% complete', ['percent' => h($project->project_progress)]) ?>
                        </td>
                        <td class="align-middle">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <?= $this->Html->image(
                                        '/yab_cms_ff/img/avatars/' . Text::slug(strtolower($project->project_team_members), ['replacement' => '_']) . '.' . 'jpg',
                                        [
                                            'alt'   => $project->project_team_members,
                                            'title' => $project->project_team_members,
                                            'class' => 'img-size-50 user-image img-circle elevation-2',
                                        ]); ?>
                                </li>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
