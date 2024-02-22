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
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use YabCmsFf\Utility\YabCmsFf;

/**
 * Logs Controller
 *
 * @property \YabCmsFf\Model\Table\LogsTable $Logs
 */
class LogsController extends AppController
{

    /**
     * Locale
     *
     * @var string
     */
    private string $locale;

    /**
     * Pagination
     *
     * @var array
     */
    public array $paginate = [
        'limit' => 25,
        'maxLimit' => 50,
        'sortableFields' => [
            'id',
            'request',
            'type',
            'message',
            'ip',
            'uri',
            'data',
            'created',
            'modified',
        ],
        'order' => ['created' => 'DESC']
    ];

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     * @link https://book.cakephp.org/4/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $session = $this->getRequest()->getSession();
        $this->locale = $session->check('Locale.code')? $session->read('Locale.code'): 'en_US';
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->Logs
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->orderBy(['created' => 'desc']);

        YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeIndexRender', $this, ['Query' => $query]);

        $this->set('logs', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $log = $this->Logs->get($id);

        YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeViewRender', $this, ['Log' => $log]);

        $this->set('log', $log);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $log = $this->Logs->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $log = $this->Logs->patchEntity($log, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeAdd', $this, ['Log' => $log]);
            if ($this->Logs->save($log)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Logs.onAddSuccess', $this, ['Log' => $log]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The log has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Logs.onAddFailure', $this, ['Log' => $log]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The log could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeAddRender', $this, ['Log' => $log]);

        $this->set('log', $log);
    }

    /**
     * Edit method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function edit(int $id = null)
    {
        $log = $this->Logs->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $log = $this->Logs->patchEntity($log, $this->getRequest()->getData());
            YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeEdit', $this, ['Log' => $log]);
            if ($this->Logs->save($log)) {
                YabCmsFf::dispatchEvent('Controller.Admin.Logs.onEditSuccess', $this, ['Log' => $log]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The log has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.Logs.onEditFailure', $this, ['Log' => $log]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The log could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeEditRender', $this, ['Log' => $log]);

        $this->set('log', $log);
    }

    /**
     * Delete method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function delete(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $log = $this->Logs->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.Logs.beforeDelete', $this, ['Log' => $log]);
        if ($this->Logs->delete($log)) {
            YabCmsFf::dispatchEvent('Controller.Admin.Logs.onDeleteSuccess', $this, ['Log' => $log]);

            $connection = ConnectionManager::get('default');
            $connection->delete('logs', ['id' => $id]);

            $this->Flash->set(
                __d('yab_cms_ff', 'The log has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.Logs.onDeleteFailure', $this, ['Log' => $log]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The log could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Export method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function export()
    {
        $logs = $this->Logs->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->Logs->tableColumns;
        $extract = $this->Logs->tableColumns;

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('logs'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'logs',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
