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
namespace YabCmsFf\Controller\Admin;

use YabCmsFf\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * Dashboards Controller
 *
 */
class DashboardsController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @return void
     */
    public function initialize($loadComponents = true): void
    {
        parent::initialize(true);
    }

    /**
     * Dashboard method
     *
     * @return void
     */
    public function dashboard()
    {
        $Articles = TableRegistry::getTableLocator()->get('YabCmsFf.Articles');
        $articlesCount = $Articles
            ->find()
            ->where(['status' => 1])
            ->count();

        $ArticleTypes = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypes');
        $articleTypesCount = $ArticleTypes
            ->find()
            ->count();

        $ArticleTypeAttributes = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypeAttributes');
        $articleTypeAttributesCount = $ArticleTypeAttributes
            ->find()
            ->count();

        $ArticleTypeAttributeChoices = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypeAttributeChoices');
        $articleTypeAttributeChoicesCount = $ArticleTypeAttributeChoices
            ->find()
            ->count();

        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
        $domainsCount = $Domains
            ->find()
            ->count();

        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $localesCount = $Locales
            ->find()
            ->where(['status' => 1])
            ->count();

        $Regions = TableRegistry::getTableLocator()->get('YabCmsFf.Regions');
        $regionsCount = $Regions
            ->find()
            ->where(['status' => 1])
            ->count();

        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countriesCount = $Countries
            ->find()
            ->where(['status' => 1])
            ->count();

        $Users = TableRegistry::getTableLocator()->get('YabCmsFf.Users');
        $usersCount = $Users
            ->find()
            ->where(['status' => 1])
            ->count();

        $Roles = TableRegistry::getTableLocator()->get('YabCmsFf.Roles');
        $rolesCount = $Roles
            ->find()
            ->count();

        $this->set(compact(
            'articlesCount',
            'articleTypesCount',
            'articleTypeAttributesCount',
            'articleTypeAttributeChoicesCount',
            'domainsCount',
            'localesCount',
            'regionsCount',
            'countriesCount',
            'usersCount',
            'rolesCount'
        ));
    }
}
