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

use Cake\Core\Configure;
use Cake\Log\Log;
use Psr\Log\LogLevel;
use SoapClient as Client;

/**
 * SoapClient Override
 */
class SoapClient extends Client
{
    /**
     * Performs a SOAP request
     *
     * @param string $request The XML SOAP request.
     * @param string $location The URL to request.
     * @param string $action The SOAP action.
     * @param int $version The SOAP version.
     * @param bool $oneWay If set to true, this method returns nothing. Use this where a response is not expected.
     * @return string|null The XML SOAP response.
     */
    public function __doRequest(string $request, string $location, string $action, int $version, bool $oneWay = false): ?string
    {
        if (Configure::read('debug') === true) {
            Log::write(LogLevel::INFO, 'SOAP Request XML: {request}', ['request' => $request]);
            Log::write(LogLevel::INFO, 'SOAP Location: {location}', ['location' => $location]);
            Log::write(LogLevel::INFO, 'SOAP Action: {action}', ['action' => $action]);
            Log::write(LogLevel::INFO, 'SOAP Version: {version}', ['version' => $version]);
        }

        $result = parent::__doRequest($request, $location, $action, $version, (int)$oneWay);

        return is_string($result) ? $result : null;
    }

    /**
     * Calls a SOAP function
     *
     * @param string $functionName The name of the SOAP function to call.
     * @param array $arguments An array of the arguments to pass to the function.
     * @param array|null $options An associative array of options to pass to the client.
     * @param array|null $inputHeaders An array of headers to be sent along with the SOAP request.
     * @param array|null $outputHeaders If supplied, this array will be filled with the headers from the SOAP response.
     * @return mixed
     */
    public function __soapCall(
        string $functionName,
        array $arguments,
        ?array $options = null,
        ?array $inputHeaders = null,
        &$outputHeaders = null
    ): mixed {
        if (Configure::read('debug') === true) {
            Log::write(LogLevel::INFO, 'SOAP Function: {function}', ['function' => $functionName]);
            Log::write(LogLevel::INFO, 'SOAP Arguments context', ['arguments' => $arguments]);
            Log::write(LogLevel::INFO, 'SOAP Options context', ['options' => $options]);
            Log::write(LogLevel::INFO, 'SOAP Input Headers context', ['inputHeaders' => $inputHeaders]);
        }

        return parent::__soapCall($functionName, $arguments, $options, $inputHeaders, $outputHeaders);
    }
}