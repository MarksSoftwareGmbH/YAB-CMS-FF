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
namespace YabCmsFf\Controller\Api;

use YabCmsFf\Controller\AppController as BaseController;

/**
 * App Controller
 *
 * Class AppController
 * @package YabCmsFf\Controller\Api
 */
class AppController extends BaseController
{
    use \Crud\Controller\ControllerTrait;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize($loadComponents = false): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete',
                'category' => ['className' => '\YabCmsFf\Crud\Action\CategoryAction'],
                'categoryKey' => ['className' => '\YabCmsFf\Crud\Action\CategoryKeyAction'],
                'email' => ['className' => '\YabCmsFf\Crud\Action\EmailAction'],
                'foreignKey' => ['className' => '\YabCmsFf\Crud\Action\ForeignKeyAction'],
                'managerKey' => ['className' => '\YabCmsFf\Crud\Action\ManagerKeyAction'],
                'name' => ['className' => '\YabCmsFf\Crud\Action\NameAction'],
                'nameAll' => ['className' => '\YabCmsFf\Crud\Action\NameAllAction'],
                'number' => ['className' => '\YabCmsFf\Crud\Action\NumberAction'],
                'numberAll' => ['className' => '\YabCmsFf\Crud\Action\NumberAllAction'],
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.Search',
            ],
        ]);
    }
}
