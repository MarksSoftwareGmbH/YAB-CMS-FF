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
namespace YabCmsFf\Error;

use Authentication\Authenticator\UnauthenticatedException;
use Authorization\Exception\ForbiddenException;
use Cake\Core\Configure;
use Cake\Error\Renderer\WebExceptionRenderer;
use Cake\Http\Response;
use Cake\Routing\Router;

class ExceptionRenderer extends WebExceptionRenderer
{
    public function render(): Response
    {
        $exception = $this->error;
        $request = $this->controller->getRequest();

        $isApi = $request->getParam('prefix') === 'Api'
            || $request->is('json')
            || str_starts_with($request->getPath(), '/api/');

        if ($isApi) {
            return $this->renderApi($exception);
        }

        if ($exception instanceof ForbiddenException || $exception instanceof UnauthenticatedException) {
            return $this->renderWebAuthError($exception);
        }

        return parent::render();
    }

    protected function renderApi(\Throwable $exception): Response
    {
        $status = 500;
        $message = 'An error occurred';

        if ($exception instanceof UnauthenticatedException) {
            $status = 401;
            $message = 'Authentication is required';
        } elseif ($exception instanceof ForbiddenException) {
            $status = 403;
            $message = 'You are not authorized to access this resource';
        } elseif (method_exists($exception, 'getCode') && $exception->getCode() >= 400) {
            $status = (int)$exception->getCode();
            $message = $exception->getMessage() ?: $message;
        }

        $body = [
            'success' => false,
            'message' => $message,
            'code'    => $status,
        ];

        if (Configure::read('debug')) {
            $body['exception'] = get_class($exception);
            $body['file'] = $exception->getFile();
            $body['line'] = $exception->getLine();
        }

        $response = $this->controller->getResponse()
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $this->controller->setResponse($response);

        return $response;
    }

    protected function renderWebAuthError(\Throwable $exception): Response
    {
        $request = $this->controller->getRequest();
        $session = $request->getSession();

        $message = $exception instanceof ForbiddenException
            ? __d('yab_cms_ff', 'You are not authorized to access this location.')
            : __d('yab_cms_ff', 'You need to be logged in to access this location.');

        $session->write('Flash.flash', [[
            'message' => $message,
            'key'     => 'flash',
            'element' => 'flash/default',
            'params'  => ['class' => 'error'],
        ]]);

        $referer = $request->getEnv('HTTP_REFERER');
        $redirectUrl = $referer ?: Router::url('/');

        $response = $this->controller->getResponse()
            ->withStatus(302)
            ->withHeader('Location', $redirectUrl);

        $this->controller->setResponse($response);

        return $response;
    }
}
