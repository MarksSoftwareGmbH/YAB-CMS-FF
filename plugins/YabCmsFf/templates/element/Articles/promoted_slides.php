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

// Get session object
$session = $this->getRequest()->getSession();
?>

<?php if (!empty($slides)): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= __d('yab_cms_ff', 'New internet technologies that we connect or integrate'); ?></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="<?= __d('yab_cms_ff', 'Collapse'); ?>">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="slides" class="carousel slide" data-ride="carousel">

                <ol class="carousel-indicators">
                    <?php $active = ''; ?>
                    <?php foreach($slides as $key => $slide): ?>
                        <?php if ($key == 0) $active = 'active'; ?>
                        <li data-target="#slides" data-slide-to="<?= h($key); ?>" class="<?= h($active); ?>"></li>
                        <?php $active = ''; ?>
                    <?php endforeach; ?>
                </ol>
                <div class="carousel-inner">
                    <?php $active = ''; ?>
                    <?php foreach($slides as $key => $slide): ?>
                        <?php if ($key == 0) $active = ' ' . 'active'; ?>
                        <div class="carousel-item<?= h($active); ?>">
                            <?php if (!empty($slide->link)): ?>
                                <a href="<?= h($slide->link); ?>" target="_blank">
                                    <img class="d-block w-100" alt="<?= h($slide->name); ?>" src="<?= h($slide->image); ?>">
                                </a>
                            <?php else: ?>
                                <img class="d-block w-100" alt="<?= h($slide->name); ?>" src="<?= h($slide->image); ?>">
                            <?php endif; ?>
                        </div>
                        <?php $active = ''; ?>
                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#slides" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?= __d('yab_cms_ff', 'Previous'); ?></span>
                </a>
                <a class="carousel-control-next" href="#slides" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?= __d('yab_cms_ff', 'Next'); ?></span>
                </a>

            </div>
        </div>
    </div>
<?php endif; ?>
