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
use Cake\Core\InstanceConfigTrait;
use Cake\Log\Log;
use Psr\Log\LogLevel;
use YabCmsFf\Network\SoapClient;
use SoapFault;

/**
 * SoapConnect Model
 */
class SoapConnect
{
    use InstanceConfigTrait;

    /**
     * SoapClient instance
     *
     * @var SoapClient|null
     */
    public ?SoapClient $client = null;

    /**
     * Connection status
     *
     * @var bool
     */
    public bool $connected = false;

    /**
     * Error message
     *
     * @var string|null
     */
    public ?string $error = null;

    /**
     * Default configuration
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'wsdl' => null,
        'userAgent' => 'SoapClient',
        'location' => '',
        'uri' => '',
        'login' => '',
        'password' => '',
        'authentication' => SOAP_AUTHENTICATION_BASIC,
        'trace' => false,
        'exception' => false,
    ];

    /**
     * Constructor
     *
     * @param array $config An array defining the configuration settings
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->connect();
    }

    /**
     * Setup Configuration options
     *
     * @return array|false
     */
    protected function _parseConfig(): array|false
    {
        if (!class_exists('SoapClient')) {
            $this->error = __d('yab_cms_ff', 'Class SoapClient not found, please enable Soap extensions.');
            $this->showError();

            return false;
        }

        $opts = ['http' => ['user_agent' => $this->getConfig('userAgent')]];
        $context = stream_context_create($opts);
        $options = [
            'trace' => Configure::read('debug'),
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ];

        if (!empty($this->getConfig('location'))) {
            $options['location'] = $this->getConfig('location');
        }
        if (!empty($this->getConfig('uri'))) {
            $options['uri'] = $this->getConfig('uri');
        }
        if (!empty($this->getConfig('login'))) {
            $options['login'] = $this->getConfig('login');
            $options['password'] = $this->getConfig('password');
            $options['authentication'] = $this->getConfig('authentication');
        }

        return $options;
    }

    /**
     * Connects to the SOAP server using the WSDL in the configuration
     *
     * @return bool True on success, false on failure
     */
    public function connect(): bool
    {
        $options = $this->_parseConfig();
        if ($options === false) {
            $this->connected = false;
            return false;
        }

        try {
            $this->client = new SoapClient($this->getConfig('wsdl'), $options);
            $this->connected = true;
        } catch (SoapFault $fault) {
            $this->error = $fault->faultstring;
            $this->showError();
            $this->connected = false;
        }

        return $this->connected;
    }

    /**
     * Sets the SoapClient instance to null
     *
     * @return bool True
     */
    public function close(): bool
    {
        $this->client = null;
        $this->connected = false;

        return true;
    }

    /**
     * Returns the available SOAP methods
     *
     * @return array|null List of SOAP methods
     */
    public function listSources(): ?array
    {
        if ($this->client === null) {
            return null;
        }

        return $this->client->__getFunctions();
    }

    /**
     * Query the SOAP server with the given method and parameters
     *
     * @param string $action The WSDL Action
     * @param array $data The data array
     * @return mixed Returns the result on success, false on failure
     */
    public function sendRequest(string $action, array $data = []): mixed
    {
        $this->error = null;
        if (!$this->connected || $this->client === null) {
            $this->connect();
        }

        if ($this->client === null) {
            return false;
        }

        try {
            $result = $this->client->__soapCall($action, $data);
        } catch (SoapFault $fault) {
            $this->error = $fault->faultstring;
            $this->showError();

            return false;
        }

        return $result;
    }

    /**
     * Returns the last SOAP response
     *
     * @return string|null The last SOAP response
     */
    public function getResponse(): ?string
    {
        if ($this->client === null) {
            return null;
        }

        return $this->client->__getLastResponse();
    }

    /**
     * Returns the last SOAP request
     *
     * @return string|null The last SOAP request
     */
    public function getRequest(): ?string
    {
        if ($this->client === null) {
            return null;
        }

        return $this->client->__getLastRequest();
    }

    /**
     * Shows an error message and outputs the SOAP result if passed
     *
     * @param string|null $result A SOAP result
     * @return void
     */
    public function showError(?string $result = null): void
    {
        if ($this->client !== null) {
            Log::write(LogLevel::ERROR, (string)$this->client->__getLastRequest());
        }

        if (Configure::read('debug') === true) {
            if ($this->error !== null) {
                trigger_error(
                    '<span style="color: #ff0; text-align: left;"><b>SOAP Error:</b> ' . $this->error . '</span>',
                    E_USER_WARNING
                );
            }
            if (!empty($result)) {
                echo sprintf("<p><b>Result:</b> %s </p>", $result);
            }
        }
    }
}