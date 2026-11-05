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

use Cake\Controller\Controller;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Psr\Log\LogLevel;
use Exception;

/**
 * Network trait
 */
trait NetworkTrait
{
    /**
     * Convenience method to write a request log into database
     *
     * @param Controller|null $controller
     * @param string|null $type
     * @param string|null $message
     * @param array $data
     * @return void
     */
    public function requestLog(?Controller $controller = null, ?string $type = null, ?string $message = null, array $data = []): void
    {
        try {
            $request = $controller?->getRequest();
            
            $method = $request?->getMethod() ?? 'CLI';
            $clientIp = $request?->clientIp() ?? '127.0.0.1';
            $requestTarget = $request?->getRequestTarget() ?? '';

            $logsTable = TableRegistry::getTableLocator()->get('YabCmsFf.Logs');
            $logsTable->createLog(
                $method,
                $type,
                $message,
                $clientIp,
                $requestTarget,
                $data
            );
        } catch (Exception $ex) {
            Log::write(LogLevel::ERROR, (string)$ex);
        }
    }
}