<?php
declare(strict_types=1);

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
namespace YabCmsFf\Config;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use YabCmsFf\Event\EventManager;
use YabCmsFf\Menu\MenuManager;
use YabCmsFf\Utility\YabCmsFf;

YabCmsFf::hookComponent('*', 'YabCmsFf.Global');
YabCmsFf::hookComponent('*', 'YabCmsFf.User');
YabCmsFf::hookComponent('*', 'YabCmsFf.Locale');
YabCmsFf::hookComponent('*', 'YabCmsFf.Menu');
YabCmsFf::hookComponent('*', 'YabCmsFf.Setting');
YabCmsFf::hookComponent('*', 'YabCmsFf.Page');

// @codingStandardsIgnoreStart
// Make sure that the YabCmsFf event manager is the global one
EventManager::instance();
MenuManager::instance();

/**
 * Failed login attempts
 *
 * Default is 3 failed login attempts in every 5 minutes
 */
$cacheConfig = array_merge(
    Cache::getConfig('default'),
    ['groups' => ['users']]
);
$failedLoginDuration = 300;
Configure::write('YabCmsFf.failed_login_limit', 3);
Configure::write('YabCmsFf.failed_login_duration', $failedLoginDuration);
Cache::setConfig('yab_cms_ff_users_login',
    array_merge(
        $cacheConfig,
        [
            'duration' => '+' . $failedLoginDuration . ' seconds',
            'groups' => ['users'],
        ]
    )
);

// Load all EventHandlers defined in YabCmsFf\Config\events and later other activated Plugins
EventManager::loadListeners();
MenuManager::loadListeners();
// @codingStandardsIgnoreEnd
