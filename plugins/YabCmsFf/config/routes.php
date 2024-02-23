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
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setExtensions(['json', 'xml', 'csv', 'txt']);

$routes->plugin('YabCmsFf', ['path' => '/'], function (RouteBuilder $routes) {

        // Switch locale
        $routes
            ->connect('/switch-locale/{code}', ['controller' => 'Locales', 'action' => 'switchLocale'])
            ->setPass(['code']);

        // Article promoted
        $routes
            ->connect('/{locale}/', ['controller' => 'Articles', 'action' => 'promoted'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/', ['controller' => 'Articles', 'action' => 'promoted']);

        // Article view by slug
        $routes
            ->connect('/{locale}/{slug}', ['controller' => 'Articles', 'action' => 'view'])
            ->setPass(['slug'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/{slug}', ['controller' => 'Articles', 'action' => 'view'])
            ->setPass(['slug']);

        // Article index by type
        $routes
            ->connect('/{locale}/type/{articleType}', ['controller' => 'Articles', 'action' => 'index'])
            ->setPass(['articleType'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/type/{articleType}', ['controller' => 'Articles', 'action' => 'index'])
            ->setPass(['articleType']);

        // Article view by type and slug
        $routes
            ->connect('/{locale}/{articleType}/{slug}', ['controller' => 'Articles', 'action' => 'view'])
            ->setPass(['articleType', 'slug'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/{articleType}/{slug}', ['controller' => 'Articles', 'action' => 'view'])
            ->setPass(['articleType', 'slug']);

        // Page view by slug and *
        $routes
            ->connect('/{locale}/page/{slug}', ['controller' => 'Articles', 'action' => 'pageView'])
            ->setPass(['slug'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/page/{slug}', ['controller' => 'Articles', 'action' => 'pageView'])
            ->setPass(['slug']);

        // Article search
        $routes
            ->connect('/{locale}/search', ['controller' => 'Articles', 'action' => 'search'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/search', ['controller' => 'Articles', 'action' => 'search']);

        // User register
        $routes
            ->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        // User login
        $routes
            ->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        // User logout
        $routes
            ->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        // User forgot
        $routes
            ->connect('/forgot', ['controller' => 'Users', 'action' => 'forgot']);
        // User reset
        $routes
            ->connect('/reset/{username}/{token}', ['controller' => 'Users', 'action' => 'reset'])
            ->setPass(['username', 'token'])
            ->setPatterns(['token' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);

        // Registration
        $routes
            ->connect('/registration', ['controller' => 'Registrations', 'action' => 'add']);

        // Sitemap
        $routes
            ->connect('/{locale}/sitemap', ['controller' => 'Articles', 'action' => 'sitemap'])
            ->setPatterns(['locale' => 'de|en|nl|fr|it|es|pt|ru|zh|ar|he|pl']);
        $routes
            ->connect('/sitemap', ['controller' => 'Articles', 'action' => 'sitemap']);

        // Dashboards Controller
        $routes
            ->connect('/dashboard', ['controller' => 'Dashboards', 'action' => 'dashboard']);

        $routes
            ->connect('/u/profiles', ['controller' => 'UserProfiles', 'action' => 'index']);
        $routes
            ->connect('/u/profile/{foreignKey}', ['controller' => 'UserProfiles', 'action' => 'view'])
            ->setPass(['foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/profile/diary-entries/{foreignKey}', ['controller' => 'UserProfileDiaryEntries', 'action' => 'index'])
            ->setPass(['foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/profile/diary-entry/{foreignKey}', ['controller' => 'UserProfileDiaryEntries', 'action' => 'view'])
            ->setPass(['foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/p/diary-entries/count', ['controller' => 'UserProfiles', 'action' => 'countDiaryEntries']);
        $routes
            ->connect('/u/p/diary-entry/count-up', ['controller' => 'UserProfileDiaryEntries', 'action' => 'countUp']);
        $routes
            ->connect('/u/profile/timeline-entries/{foreignKey}', ['controller' => 'UserProfileTimelineEntries', 'action' => 'index'])
            ->setPass(['foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/profile/timeline-entry/{foreignKey}', ['controller' => 'UserProfileTimelineEntries', 'action' => 'view'])
            ->setPass(['foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/profile/timeline-entry/s/{slug}_{foreignKey}', ['controller' => 'UserProfileTimelineEntries', 'action' => 'viewBySlug'])
            ->setPass(['slug', 'foreignKey'])
            ->setPatterns(['foreignKey' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
        $routes
            ->connect('/u/p/timeline-entries/count', ['controller' => 'UserProfiles', 'action' => 'countTimelineEntries']);

        // User account
        $routes
            ->connect('/user/account/welcome', ['controller' => 'Users', 'action' => 'welcome']);
        $routes
            ->connect('/user/account/edit', ['controller' => 'Users', 'action' => 'edit']);
        $routes
            ->connect('/user/account/deactivate', ['controller' => 'Users', 'action' => 'deactivate']);

        // User profile
        $routes
            ->connect('/user/profile', ['controller' => 'Users', 'action' => 'profile']);
        $routes
            ->connect('/user/profile/add', ['controller' => 'UserProfiles', 'action' => 'add']);
        $routes
            ->connect('/user/profile/edit', ['controller' => 'UserProfiles', 'action' => 'edit']);

        // User profile diary entries
        $routes
            ->connect('/user/profile-diary-entry/add', ['controller' => 'UserProfileDiaryEntries', 'action' => 'add']);
        $routes
            ->connect('/user/profile-diary-entry/edit', ['controller' => 'UserProfileDiaryEntries', 'action' => 'edit']);
        $routes
            ->connect('/user/profile-diary-entry/delete', ['controller' => 'UserProfileDiaryEntries', 'action' => 'delete']);

        // User profile timeline entries
        $routes
            ->connect('/user/profile-timeline-entry/add', ['controller' => 'UserProfileTimelineEntries', 'action' => 'add']);
        $routes
            ->connect('/user/profile-timeline-entry/edit', ['controller' => 'UserProfileTimelineEntries', 'action' => 'edit']);
        $routes
            ->connect('/user/profile-timeline-entry/delete', ['controller' => 'UserProfileTimelineEntries', 'action' => 'delete']);

        /*
         * Admin Prefix Routing
         */
        $routes->prefix('Admin', ['_namePrefix' => 'admin:'], function (RouteBuilder $routes) {
            $routes
                ->setExtensions(['ajax', 'json', 'xml', 'csv', 'txt']);

            $routes
                ->connect('/app/clear-cache', ['controller' => 'AppCaches', 'action' => 'clearAppCaches']);
            $routes
                ->connect('/app/clear-log', ['controller' => 'AppLogs', 'action' => 'clearAppLogs']);
            $routes
                ->connect('/app/clear-session', ['controller' => 'AppSessions', 'action' => 'clearAppSessions']);

            /*
             * Categories Controller
             */
            $routes
                ->connect('/categories', ['controller' => 'Categories', 'action' => 'index']);
            $routes
                ->connect('/category/ajax-move', ['controller' => 'Categories', 'action' => 'ajaxMove']);
            $routes
                ->connect('/category/move-up/{id}', ['controller' => 'Categories', 'action' => 'moveUp'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/category/move-down/{id}', ['controller' => 'Categories', 'action' => 'moveDown'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/category/{id}', ['controller' => 'Categories', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/category/add', ['controller' => 'Categories', 'action' => 'add']);
            $routes
                ->connect('/category/edit/{id}', ['controller' => 'Categories', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/category/delete/{id}', ['controller' => 'Categories', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/categories/import', ['controller' => 'Categories', 'action' => 'import']);
            $routes
                ->connect('/categories/export', ['controller' => 'Categories', 'action' => 'export']);

            /*
             * Countries Controller
             */
            $routes
                ->connect('/countries', ['controller' => 'Countries', 'action' => 'index']);
            $routes
                ->connect('/country/{id}', ['controller' => 'Countries', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/country/add', ['controller' => 'Countries', 'action' => 'add']);
            $routes
                ->connect('/country/edit/{id}', ['controller' => 'Countries', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/country/delete/{id}', ['controller' => 'Countries', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/countries/import', ['controller' => 'Countries', 'action' => 'import']);
            $routes
                ->connect('/countries/export', ['controller' => 'Countries', 'action' => 'export']);

            /*
             * Dashboards Controller
             */
            $routes
                ->connect('/dashboard', ['controller' => 'Dashboards', 'action' => 'dashboard']);

            /*
             * Domains Controller
             */
            $routes
                ->connect('/domains', ['controller' => 'Domains', 'action' => 'index']);
            $routes
                ->connect('/domain/{id}', ['controller' => 'Domains', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/domain/add', ['controller' => 'Domains', 'action' => 'add']);
            $routes
                ->connect('/domain/edit/{id}', ['controller' => 'Domains', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/domain/delete/{id}', ['controller' => 'Domains', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/domains/import', ['controller' => 'Domains', 'action' => 'import']);
            $routes
                ->connect('/domains/export', ['controller' => 'Domains', 'action' => 'export']);

            /*
             * Locales Controller
             */
            $routes
                ->connect('/locales', ['controller' => 'Locales', 'action' => 'index']);
            $routes
                ->connect('/locale/{id}', ['controller' => 'Locales', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/locale/add', ['controller' => 'Locales', 'action' => 'add']);
            $routes
                ->connect('/locale/edit/{id}', ['controller' => 'Locales', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes->connect('/locale/delete/{id}', ['controller' => 'Locales', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/locales/import', ['controller' => 'Locales', 'action' => 'import']);
            $routes
                ->connect('/locales/export', ['controller' => 'Locales', 'action' => 'export']);

            /*
             * Logs Controller
             */
            $routes
                ->connect('/logs', ['controller' => 'Logs', 'action' => 'index']);
            $routes
                ->connect('/log/{id}', ['controller' => 'Logs', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/log/add', ['controller' => 'Logs', 'action' => 'add']);
            $routes
                ->connect('/log/edit/{id}', ['controller' => 'Logs', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/log/delete/{id}', ['controller' => 'Logs', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/logs/export', ['controller' => 'Logs', 'action' => 'export']);

            /*
             * Menus Controller
             */
            $routes
                ->connect('/menus', ['controller' => 'Menus', 'action' => 'index']);
            $routes
                ->connect('/menu/{id}', ['controller' => 'Menus', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu/add', ['controller' => 'Menus', 'action' => 'add']);
            $routes
                ->connect('/menu/edit/{id}', ['controller' => 'Menus', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu/delete/{id}', ['controller' => 'Menus', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menus/import', ['controller' => 'Menus', 'action' => 'import']);
            $routes
                ->connect('/menus/export', ['controller' => 'Menus', 'action' => 'export']);

            /*
             * Menu Items Controller
             */
            $routes
                ->connect('/menu-items', ['controller' => 'MenuItems', 'action' => 'index']);
            $routes
                ->connect('/menu-item/ajax-move', ['controller' => 'MenuItems', 'action' => 'ajaxMove']);
            $routes
                ->connect('/menu-item/move-up/{id}', ['controller' => 'MenuItems', 'action' => 'moveUp'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu-item/move-down/{id}', ['controller' => 'MenuItems', 'action' => 'moveDown'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu-item/{id}', ['controller' => 'MenuItems', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu-item/add', ['controller' => 'MenuItems', 'action' => 'add']);
            $routes
                ->connect('/menu-item/edit/{id}', ['controller' => 'MenuItems', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu-item/delete/{id}', ['controller' => 'MenuItems', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/menu-items/import', ['controller' => 'MenuItems', 'action' => 'import']);
            $routes
                ->connect('/menu-items/export', ['controller' => 'MenuItems', 'action' => 'export']);

            /*
             * Articles Controller
             */
            $routes
                ->connect('/articles', ['controller' => 'Articles', 'action' => 'index']);
            $routes
                ->connect('/article/ajax-move', ['controller' => 'Articles', 'action' => 'ajaxMove']);
            $routes
                ->connect('/article/move-up/{id}', ['controller' => 'Articles', 'action' => 'moveUp'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article/move-down/{id}', ['controller' => 'Articles', 'action' => 'moveDown'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article/{id}', ['controller' => 'Articles', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article/add/{articleTypeAlias}', ['controller' => 'Articles', 'action' => 'add'])
                ->setPass(['articleTypeAlias']);
            $routes
                ->connect('/article/edit/{id}', ['controller' => 'Articles', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article/copy/{id}', ['controller' => 'Articles', 'action' => 'copy'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article/delete/{id}', ['controller' => 'Articles', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/articles/file/upload', ['controller' => 'Articles', 'action' => 'fileUpload']);

            /*
             * ArticleTypes Controller
             */
            $routes
                ->connect('/article-types', ['controller' => 'ArticleTypes', 'action' => 'index']);
            $routes
                ->connect('/article-type/{id}', ['controller' => 'ArticleTypes', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type/add', ['controller' => 'ArticleTypes', 'action' => 'add']);
            $routes
                ->connect('/article-type/edit/{id}', ['controller' => 'ArticleTypes', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type/delete/{id}', ['controller' => 'ArticleTypes', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-types/import', ['controller' => 'ArticleTypes', 'action' => 'import']);
            $routes
                ->connect('/article-types/export', ['controller' => 'ArticleTypes', 'action' => 'export']);

            /*
             * ArticleTypeAttributes Controller
             */
            $routes
                ->connect('/article-type-attributes', ['controller' => 'ArticleTypeAttributes', 'action' => 'index']);
            $routes
                ->connect('/article-type-attribute/{id}', ['controller' => 'ArticleTypeAttributes', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attribute/add', ['controller' => 'ArticleTypeAttributes', 'action' => 'add']);
            $routes
                ->connect('/article-type-attribute/edit/{id}', ['controller' => 'ArticleTypeAttributes', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attribute/delete/{id}', ['controller' => 'ArticleTypeAttributes', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attributes/import', ['controller' => 'ArticleTypeAttributes', 'action' => 'import']);
            $routes
                ->connect('/article-type-attributes/export', ['controller' => 'ArticleTypeAttributes', 'action' => 'export']);

            /*
             * ArticleTypeAttributeChoices Controller
             */
            $routes
                ->connect('/article-type-attribute-choices', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'index']);
            $routes
                ->connect('/article-type-attribute-choice/{id}', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attribute-choice/add', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'add']);
            $routes
                ->connect('/article-type-attribute-choice/edit/{id}', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attribute-choice/delete/{id}', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-type-attribute-choices/import', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'import']);
            $routes
                ->connect('/article-type-attribute-choices/export', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'export']);

            /*
             * ArticleArticleTypeAttributeValues Controller
             */
            $routes
                ->connect('/article-article-type-attribute-values', ['controller' => 'ArticleArticleTypeAttributeValues', 'action' => 'index']);
            $routes
                ->connect('/article-article-type-attribute-value/{id}', ['controller' => 'ArticleArticleTypeAttributeValues', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-article-type-attribute-value/add', ['controller' => 'ArticleArticleTypeAttributeValues', 'action' => 'add']);
            $routes
                ->connect('/article-article-type-attribute-value/edit/{id}', ['controller' => 'ArticleArticleTypeAttributeValues', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/article-article-type-attribute-value/delete/{id}', ['controller' => 'ArticleArticleTypeAttributeValues', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Regions Controller
             */
            $routes
                ->connect('/regions', ['controller' => 'Regions', 'action' => 'index']);
            $routes
                ->connect('/region/{id}', ['controller' => 'Regions', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/region/add', ['controller' => 'Regions', 'action' => 'add']);
            $routes
                ->connect('/region/edit/{id}', ['controller' => 'Regions', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/region/delete/{id}', ['controller' => 'Regions', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/regions/import', ['controller' => 'Regions', 'action' => 'import']);
            $routes
                ->connect('/regions/export', ['controller' => 'Regions', 'action' => 'export']);

            /*
             * Roles Controller
             */
            $routes
                ->connect('/roles', ['controller' => 'Roles', 'action' => 'index']);
            $routes
                ->connect('/role/{id}', ['controller' => 'Roles', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/role/add', ['controller' => 'Roles', 'action' => 'add']);
            $routes
                ->connect('/role/edit/{id}', ['controller' => 'Roles', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/role/delete/{id}', ['controller' => 'Roles', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Settings Controller
             */
            $routes
                ->connect('/settings', ['controller' => 'Settings', 'action' => 'index']);
            $routes
                ->connect('/setting/{id}', ['controller' => 'Settings', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/setting/add', ['controller' => 'Settings', 'action' => 'add']);
            $routes
                ->connect('/setting/edit/{id}', ['controller' => 'Settings', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/setting/delete/{id}', ['controller' => 'Settings', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/settings/import', ['controller' => 'Settings', 'action' => 'import']);
            $routes
                ->connect('/settings/export', ['controller' => 'Settings', 'action' => 'export']);

            /*
             * Users Controller
             */
            $routes
                ->connect('/user/login', ['controller' => 'Users', 'action' => 'login']);
            $routes
                ->connect('/user/logout', ['controller' => 'Users', 'action' => 'logout']);
            $routes
                ->connect('/user/forgot', ['controller' => 'Users', 'action' => 'forgot']);
            $routes
                ->connect('/user/reset/{username}/{token}', ['controller' => 'Users', 'action' => 'reset'])
                ->setPass(['username', 'token'])
                ->setPatterns(['token' => '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}']);
            $routes
                ->connect('/users', ['controller' => 'Users', 'action' => 'index']);
            $routes
                ->connect('/user/{id}', ['controller' => 'Users', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/profile/{id}', ['controller' => 'Users', 'action' => 'profile'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/user/add', ['controller' => 'Users', 'action' => 'add']);
            $routes
                ->connect('/user/edit/{id}', ['controller' => 'Users', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/user/reset-password/{id}', ['controller' => 'Users', 'action' => 'resetPassword'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/user/delete/{id}', ['controller' => 'Users', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * UserProfiles Controller
             */
            $routes
                ->connect('/user-profiles', ['controller' => 'UserProfiles', 'action' => 'index']);
            $routes
                ->connect('/user-profile/{id}', ['controller' => 'UserProfiles', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/user-profile/add', ['controller' => 'UserProfiles', 'action' => 'add']);
            $routes
                ->connect('/user-profile/edit/{id}', ['controller' => 'UserProfiles', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/user-profile/delete/{id}', ['controller' => 'UserProfiles', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Registrations Controller
             */
            $routes
                ->connect('/registrations', ['controller' => 'Registrations', 'action' => 'index']);
            $routes
                ->connect('/registration/{id}', ['controller' => 'Registrations', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration/add', ['controller' => 'Registrations', 'action' => 'add']);
            $routes
                ->connect('/registration/edit/{id}', ['controller' => 'Registrations', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration/delete/{id}', ['controller' => 'Registrations', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registrations/import', ['controller' => 'Registrations', 'action' => 'import']);
            $routes
                ->connect('/registrations/export', ['controller' => 'Registrations', 'action' => 'export']);

            /*
             * Registration Types Controller
             */
            $routes
                ->connect('/registration-types', ['controller' => 'RegistrationTypes', 'action' => 'index']);
            $routes
                ->connect('/registration-type/{id}', ['controller' => 'RegistrationTypes', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration-type/add', ['controller' => 'RegistrationTypes', 'action' => 'add']);
            $routes
                ->connect('/registration-type/edit/{id}', ['controller' => 'RegistrationTypes', 'action' => 'edit'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration-type/copy/{id}', ['controller' => 'RegistrationTypes', 'action' => 'copy'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration-type/delete/{id}', ['controller' => 'RegistrationTypes', 'action' => 'delete'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
            $routes
                ->connect('/registration-types/import', ['controller' => 'RegistrationTypes', 'action' => 'import']);
            $routes
                ->connect('/registration-types/export', ['controller' => 'RegistrationTypes', 'action' => 'export']);

        });

        /*
        * Api Prefix Routing
        */
        $routes->prefix('Api', ['_namePrefix' => 'api:'], function (RouteBuilder $builder) {

            $builder->registerMiddleware('auth', new \Authentication\Middleware\AuthenticationMiddleware($this));
            $builder->applyMiddleware('auth');

            // Parse specified extensions from URLs
            $builder->setExtensions(['json', 'xml']);

            /*
             * Articles Controller
             */
            $builder
                ->connect('/articles', ['controller' => 'Articles', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/articles/{id}', ['controller' => 'Articles', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * ArticleTypes Controller
             */
            $builder
                ->connect('/article-types', ['controller' => 'ArticleTypes', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/article-types/{id}', ['controller' => 'ArticleTypes', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * ArticleTypeAttributes Controller
             */
            $builder
                ->connect('/article-type-attributes', ['controller' => 'ArticleTypeAttributes', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/article-type-attributes/{id}', ['controller' => 'ArticleTypeAttributes', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * ArticleTypeAttributeChoices Controller
             */
            $builder
                ->connect('/article-type-attribute-choices', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/article-type-attribute-choices/{id}', ['controller' => 'ArticleTypeAttributeChoices', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Categories Controller
             */
            $builder
                ->connect('/categories', ['controller' => 'Categories', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/categories/{id}', ['controller' => 'Categories', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Countries Controller
             */
            $builder
                ->connect('/countries', ['controller' => 'Countries', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/countries/{id}', ['controller' => 'Countries', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Domains Controller
             */
            $builder
                ->connect('/domains', ['controller' => 'Domains', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/domains/{id}', ['controller' => 'Domains', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Locales Controller
             */
            $builder
                ->connect('/locales', ['controller' => 'Locales', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/locales/{id}', ['controller' => 'Locales', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Logs Controller
             */
            $builder
                ->connect('/logs', ['controller' => 'Logs', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/logs/{id}', ['controller' => 'Logs', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * MenuItems Controller
             */
            $builder
                ->connect('/menu-items', ['controller' => 'MenuItems', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/menu-items/{id}', ['controller' => 'MenuItems', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Menus Controller
             */
            $builder
                ->connect('/menus', ['controller' => 'Menus', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/menus/{id}', ['controller' => 'Menus', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Regions Controller
             */
            $builder
                ->connect('/regions', ['controller' => 'Regions', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/regions/{id}', ['controller' => 'Regions', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Registrations Controller
             */
            $builder
                ->connect('/registrations', ['controller' => 'Registrations', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/registrations/{id}', ['controller' => 'Registrations', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * RegistrationTypes Controller
             */
            $builder
                ->connect('/registration-types', ['controller' => 'RegistrationTypes', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/registration-types/{id}', ['controller' => 'RegistrationTypes', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Roles Controller
             */
            $builder
                ->connect('/roles', ['controller' => 'Roles', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/roles/{id}', ['controller' => 'Roles', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Settings Controller
             */
            $builder
                ->connect('/settings', ['controller' => 'Settings', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/settings/{id}', ['controller' => 'Settings', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
            * UserProfiles Controller
            */
            $builder
                ->connect('/user-profiles', ['controller' => 'UserProfiles', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/user-profiles/{id}', ['controller' => 'UserProfiles', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

            /*
             * Users Controller
             */
            $builder
                ->connect('/oauth/token', ['controller' => 'Users', 'action' => 'token', '_ext' => 'json', '_method' => 'POST']);
            $builder
                ->connect('/users', ['controller' => 'Users', 'action' => 'index', '_ext' => 'json', '_method' => 'GET']);
            $builder
                ->connect('/users/{id}', ['controller' => 'Users', 'action' => 'view', '_ext' => 'json', '_method' => 'GET'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);

        });
    }
);
