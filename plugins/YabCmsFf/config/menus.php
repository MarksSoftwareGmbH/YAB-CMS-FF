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

return [
    'MenuHandlers' => [
        'YabCmsFf.CmsModules' => [
            'controller' => [
                'Articles',
                'ArticleTypes',
                'ArticleTypeAttributes',
                'ArticleTypeAttributeChoices',
                'ArticleArticleTypeAttributeValues',
            ],
            'title' => 'CMS Module',
            'icon' => 'file',
            'branch' => [
                'YabCmsFf.Articles' => [
                    'controller' => 'Articles',
                    'title' => 'Articles',
                    'icon' => 'file',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Articles',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 10,
                ],
                'YabCmsFf.ArticleTypes' => [
                    'controller' => 'ArticleTypes',
                    'title' => 'Types',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'ArticleTypes',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 20,
                ],
                'YabCmsFf.ArticleTypeAttributes' => [
                    'controller' => 'ArticleTypeAttributes',
                    'title' => 'Type Attributes',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'ArticleTypeAttributes',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 30,
                ],
                'YabCmsFf.ArticleTypeAttributeChoices' => [
                    'controller' => 'ArticleTypeAttributeChoices',
                    'title' => 'Type Attribute Choices',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'ArticleTypeAttributeChoices',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 40,
                ],
                'YabCmsFf.ArticleArticleTypeAttributeValues' => [
                    'controller' => 'ArticleArticleTypeAttributeValues',
                    'title' => 'Type Attribute Values',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'ArticleArticleTypeAttributeValues',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 50,
                ],
            ],
            'options' => ['escape' => false],
            'position' => 10,
        ],
        'YabCmsFf.NavigationModules' => [
            'controller' => [
                'Categories',
                'Menus',
                'MenuItems',
            ],
            'title' => 'Navigation Module',
            'icon' => 'list',
            'branch' => [
                'YabCmsFf.Categories' => [
                    'controller' => 'Categories',
                    'title' => 'Categories',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Categories',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 10,
                ],
                'YabCmsFf.Menus' => [
                    'controller' => 'Menus',
                    'title' => 'Menus',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Menus',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 20,
                ],
                'YabCmsFf.MenuItems' => [
                    'controller' => 'MenuItems',
                    'title' => 'Menu Items',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'MenuItems',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 30,
                ],
            ],
            'options' => ['escape' => false],
            'position' => 20,
        ],
        'YabCmsFf.RolesUsersModules' => [
            'controller' => [
                'Users',
                'UserProfiles',
                'Roles',
                'Registrations',
                'RegistrationTypes',
            ],
            'title' => 'Roles & Users',
            'icon' => 'user',
            'branch' => [
                'YabCmsFf.Users' => [
                    'controller' => 'Users',
                    'title' => 'Users',
                    'icon' => 'user',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Users',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 1,
                ],
                'YabCmsFf.UserProfiles' => [
                    'controller' => 'UserProfiles',
                    'title' => 'User Profiles',
                    'icon' => 'users',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'UserProfiles',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 2,
                ],
                'YabCmsFf.Roles' => [
                    'controller' => 'Roles',
                    'title' => 'Roles',
                    'icon' => 'user',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Roles',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 3,
                ],
                'YabCmsFf.Registrations' => [
                    'controller' => 'Registrations',
                    'title' => 'Registrations',
                    'icon' => 'user',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Registrations',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 4,
                ],
                'YabCmsFf.RegistrationTypes' => [
                    'controller' => 'RegistrationTypes',
                    'title' => 'Registration Types',
                    'icon' => 'list',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'RegistrationTypes',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 5,
                ],
            ],
            'options' => ['escape' => false],
            'position' => 30,
        ],
        'YabCmsFf.Domains' => [
            'controller' => 'Domains',
            'title' => 'Domains',
            'icon' => 'globe',
            'link' => [
                'prefix' => 'Admin',
                'plugin' => 'YabCmsFf',
                'controller' => 'Domains',
                'action' => 'index',
            ],
            'options' => ['escape' => false],
            'position' => 40,
        ],
        'YabCmsFf.LocalesRegionsCountries' => [
            'controller' => [
                'Locales',
                'Regions',
                'Countries',
            ],
            'title' => 'Locales',
            'icon' => 'globe',
            'branch' => [
                'YabCmsFf.Locales' => [
                    'controller' => 'Locales',
                    'title' => 'Locales',
                    'icon' => 'globe',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Locales',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 1,
                ],
                'YabCmsFf.Regions' => [
                    'controller' => 'Regions',
                    'title' => 'Regions',
                    'icon' => 'globe',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Regions',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 2,
                ],
                'YabCmsFf.Countries' => [
                    'controller' => 'Countries',
                    'title' => 'Countries',
                    'icon' => 'globe',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'Countries',
                        'action' => 'index',
                    ],
                    'options' => ['escape' => false],
                    'position' => 3,
                ],
            ],
            'options' => ['escape' => false],
            'position' => 50,
        ],
        'YabCmsFf.Logs' => [
            'controller' => 'Logs',
            'title' => 'Logs',
            'icon' => 'file',
            'link' => [
                'prefix' => 'Admin',
                'plugin' => 'YabCmsFf',
                'controller' => 'Logs',
                'action' => 'index',
            ],
            'options' => ['escape' => false],
            'position' => 60,
        ],
        'YabCmsFf.CachesLogsSessions' => [
            'controller' => [
                'AppCaches',
                'AppLogs',
                'AppSessions',
            ],
            'title' => 'App Data',
            'icon' => 'trash',
            'branch' => [
                'YabCmsFf.AppCaches' => [
                    'controller' => 'AppCaches',
                    'title' => 'Clear Caches',
                    'icon' => 'trash',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'AppCaches',
                        'action' => 'clearAppCaches',
                    ],
                    'options' => ['escape' => false],
                    'position' => 1,
                ],
                'YabCmsFf.AppLogs' => [
                    'controller' => 'AppLogs',
                    'title' => 'Clear Logs',
                    'icon' => 'trash',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'AppLogs',
                        'action' => 'clearAppLogs',
                    ],
                    'options' => ['escape' => false],
                    'position' => 2,
                ],
                'YabCmsFf.AppSessions' => [
                    'controller' => 'AppSessions',
                    'title' => 'Clear Sessions',
                    'icon' => 'trash',
                    'link' => [
                        'prefix' => 'Admin',
                        'plugin' => 'YabCmsFf',
                        'controller' => 'AppSessions',
                        'action' => 'clearAppSessions',
                    ],
                    'options' => ['escape' => false],
                    'position' => 3,
                ],
            ],
            'options' => ['escape' => false],
            'position' => 70,
        ],
        'YabCmsFf.Settings' => [
            'controller' => 'Settings',
            'title' => 'Settings',
            'icon' => 'cog',
            'link' => [
                'prefix' => 'Admin',
                'plugin' => 'YabCmsFf',
                'controller' => 'Settings',
                'action' => 'index',
            ],
            'options' => ['escape' => false],
            'position' => 80,
        ],
    ],
];
