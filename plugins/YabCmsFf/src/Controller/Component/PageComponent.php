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
 * Page component
 *
 * Class PageComponent
 * @package YabCmsFf\Controller\Component
 */
class PageComponent extends Component
{
    /**
     * Default config
     *
     * These are merged with user-provided config when the component is used.
     *
     * @var array
     */
    protected array $_defaultConfig = [];

    /**
     * Locales for layout
     *
     * @var array
     * @access public
     */
    public array $pagesForLayout = [];

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
     * Is called before the controller’s beforeFilter method, but after the
     * controller’s initialize() method.
     *
     * @param \Cake\Event\Event $event Event instance.
     */
    public function beforeFilter(Event $event)
    {
        $Controller = $event->getSubject();

        // Set the locales for layout in the $localesForLayout array
        if ($Controller->getRequest()->getParam('prefix') !== 'admin') {
            $this->setPagesForLayout($Controller);
        }

        // Set the locales for layout as variable for the view
        if ($Controller->getRequest()->getParam('prefix') !== 'admin') {
            $Controller->set('pages_for_layout', $this->pagesForLayout);
        }
    }

    /**
     * Set pages for layout
     *
     * @param object|null $controller
     * Pages will be available in this variable in views: $pages_for_layout
     *
     * @return void
     */
    public function setPagesForLayout(object $controller = null)
    {
        $code = 'en_US';
        // Check if locale code is already in session
        if ($controller->getRequest()->getSession()->check('Locale.code')) {
            $code = $controller->getRequest()->getSession()->read('Locale.code');
        }

        $Articles = TableRegistry::getTableLocator()->get('YabCmsFf.Articles');
        $pages = $Articles
            ->find('byArticleType', options: [
                'article_type'      => 'page',
                'articles_order'    => 'DESC',
                'locale'            => $code,
            ]);

        foreach ($pages as $page) {
            $this->pagesForLayout = Hash::insert($this->pagesForLayout, $page->slug, $page);
        }
    }
}
