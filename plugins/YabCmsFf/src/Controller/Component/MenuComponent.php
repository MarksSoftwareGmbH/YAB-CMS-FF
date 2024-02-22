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
namespace YabCmsFf\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Menu component
 *
 * Class MenuComponent
 * @package YabCmsFf\Controller\Component
 */
class MenuComponent extends Component
{
    /**
     * Menus for layout
     *
     * @var array
     */
    public array $menusForLayout = [];

    /**
     * Default config
     *
     * These are merged with user-provided config when the component is used.
     *
     * @var array
     */
    protected array $defaultConfig = [];

    /**
     * Http host
     *
     * @var string
     */
    private string $httpHost;

    /**
     * Domain id
     *
     * @var string
     */
    private int $domainId;

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this component.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * Callback for Controller.startup event.
     *
     * @param \Cake\Event\Event $event Event instance.
     */
    public function startup(Event $event)
    {
        // Set controller
        $Controller = $event->getSubject();

        // Set http host by environment
        if (filter_var(env('HTTP_HOST'), FILTER_VALIDATE_IP) !== false) {
            $this->httpHost = env('HTTP_X_FORWARDED_HOST');
        } else {
            $this->httpHost = env('HTTP_HOST');
        }

        // Set domain id by http host
        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
        $domain = $Domains
            ->find()
            ->where(['url' => $this->httpHost])
            ->first();
        if (!empty($domain)) {
            $this->domainId = $domain->id;
        }

        if ($Controller->getRequest()->getParam('prefix') !== 'admin') {
            $this->menus($Controller);
        }
    }

    /**
     * Called after the controller's beforeFilter method, and before the controller action is called.
     *
     * @param Event $event
     */
    public function beforeRender(Event $event)
    {
        // Set controller
        $Controller = $event->getSubject();

        if ($Controller->getRequest()->getParam('prefix') !== 'admin') {
            $Controller->set('menus_for_layout', $this->menusForLayout);
        }
    }

    /**
     * Menus method.
     *
     * @param object|null $controller
     * Menus will be available in this variable in views: $menus_for_layout
     * @return void
     */
    public function menus(object $controller = null)
    {
        $code = 'en_US';
        // Check if locale code is already in session
        if ($controller->getRequest()->getSession()->check('Locale.code')) {
            $code = $controller->getRequest()->getSession()->read('Locale.code');
        }

        $Menus = TableRegistry::getTableLocator()->get('YabCmsFf.Menus');
        $menus = $Menus
            ->find('all')
            ->where([
                'Menus.domain_id' => $this->domainId,
                'Menus.locale' => $code,
                'Menus.status' => 1,
            ])
            ->orderBy(['Menus.created' => 'ASC']);

        foreach ($menus as $menu) {
            $MenuItems = TableRegistry::getTableLocator()->get('YabCmsFf.MenuItems');
            $menuItems = $MenuItems
                ->find('threaded')
                ->where([
                    'MenuItems.menu_id' => $menu->id,
                    'MenuItems.domain_id' => $this->domainId,
                    'MenuItems.locale' => $code,
                    'MenuItems.status' => 1,
                ])
                ->orderBy(['MenuItems.lft' => 'ASC'])
                ->toArray();

            $this->menusForLayout = Hash::insert($this->menusForLayout, $menu->alias, $menuItems);
        }
    }
}
