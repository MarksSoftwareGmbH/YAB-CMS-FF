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
namespace YabCmsFf\Network;

use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Network trait
 */
trait NetworkTrait
{

    /**
     * Convenience method to write a request log into database
     *
     * @param object|null $controller
     * @param string|null $type
     * @param string|null $message
     * @param array $data
     */
    public function requestLog($controller = null, $type = null, $message = null, $data = [])
    {
        try {
            $Logs = TableRegistry::getTableLocator()->get('YabCmsFf.Logs');
            $Logs->createLog(
                $controller->getRequest()->getMethod(),
                $type,
                $message,
                $controller->getRequest()->clientIp(),
                $controller->getRequest()->getRequestTarget(),
                $data
            );
        } catch (\Exception $ex) {
            Log::write('error', (string)$ex);
        }
    }
}
