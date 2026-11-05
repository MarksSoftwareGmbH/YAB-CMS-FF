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

use CurlHandle;

/**
 * FTP with Implicit SSL/TLS Class
 *
 * Simple wrapper for cURL functions to transfer an ASCII file over FTP with implicit SSL/TLS
 */
class FtpsConnect
{
    private string $host;
    private string $username;
    private string $password;
    private CurlHandle $handle;

    /**
     * FtpsConnect constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, string $username, string $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->handle = curl_init();
    }

    /**
     * FtpsConnect destructor.
     */
    public function __destruct()
    {
        if (isset($this->handle)) {
            @curl_close($this->handle);
        }
    }

    /**
     * Common cURL setup.
     *
     * @param string $folderPath
     * @return CurlHandle
     */
    private function common(string $folderPath): CurlHandle
    {
        curl_reset($this->handle);
        curl_setopt($this->handle, CURLOPT_URL, 'ftps://' . $this->host . $folderPath);
        curl_setopt($this->handle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->handle, CURLOPT_USE_SSL, CURLFTPSSL_TRY);
        curl_setopt($this->handle, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);
        return $this->handle;
    }

    /**
     * Download file.
     *
     * @param string $folderPath
     * @param string|null $localDirectory
     * @return string|false
     */
    public function download(string $folderPath, ?string $localDirectory = null): string|false
    {
        if ($localDirectory === null) {
            $localDirectory = tempnam(sys_get_temp_dir(), 'ftps_connect');
        }

        $fp = fopen($localDirectory, 'w');
        if ($fp === false) {
            return false;
        }

        $this->handle = $this->common($folderPath);
        curl_setopt($this->handle, CURLOPT_UPLOAD, 0);
        curl_setopt($this->handle, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($fp) {
            return fwrite($fp, $data);
        });

        curl_exec($this->handle);
        $error = curl_error($this->handle);
        fclose($fp);

        if ($error !== '') {
            return false;
        }

        return $localDirectory;
    }

    /**
     * Upload file.
     *
     * @param string|null $localDirectory
     * @param string $folderPath
     * @param string|null $file
     * @return bool
     */
    public function upload(?string $localDirectory, string $folderPath, ?string $file = null): bool
    {
        if ($localDirectory === null || $file === null) {
            return false;
        }

        $fp = fopen($localDirectory, 'r');
        if ($fp === false) {
            return false;
        }

        $this->handle = $this->common($folderPath . '/' . $file);
        curl_setopt($this->handle, CURLOPT_UPLOAD, 1);
        curl_setopt($this->handle, CURLOPT_READFUNCTION, function ($ch, $fd, $length) use ($fp) {
            return fread($fp, $length);
        });

        curl_exec($this->handle);
        $err = curl_error($this->handle);
        fclose($fp);

        return $err === '';
    }

    /**
     * List folder and files.
     *
     * @param string $folderPath
     * @return array|false
     */
    public function listFolderAndFiles(string $folderPath): array|false
    {
        if (!str_ends_with($folderPath, '/')) {
            $folderPath .= '/';
        }

        $this->handle = $this->common($folderPath);
        curl_setopt($this->handle, CURLOPT_UPLOAD, 0);
        curl_setopt($this->handle, CURLOPT_FTPLISTONLY, 1);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($this->handle);

        if (curl_error($this->handle) || !is_string($result)) {
            return false;
        }

        return array_filter(explode("\n", str_replace("\r\n", "\n", trim($result))));
    }

    /**
     * List files by date.
     *
     * @param string $folderPath
     * @return array|null
     */
    public function listByLastModified(string $folderPath): ?array
    {
        $remoteFiles = $this->listFolderAndFiles($folderPath);
        if (empty($remoteFiles)) {
            return null;
        }

        $fileAttributes = [];
        foreach ($remoteFiles as $remoteFile) {
            $this->handle = $this->common($folderPath . '/' . $remoteFile);
            curl_setopt($this->handle, CURLOPT_NOBODY, 1);
            curl_setopt($this->handle, CURLOPT_FILETIME, 1);
            curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($this->handle);

            if ($result !== false) {
                $timestamp = curl_getinfo($this->handle, CURLINFO_FILETIME);
                $fileAttributes[] = [
                    'name' => $remoteFile,
                    'last_modified' => ($timestamp !== -1 && $timestamp !== false) ? date('Y-m-d H:i:s', $timestamp) : null,
                ];
            }
        }

        usort($fileAttributes, function ($first, $second) {
            $timeFirst = $first['last_modified'] ?? '';
            $timeSecond = $second['last_modified'] ?? '';
            return $timeSecond <=> $timeFirst;
        });

        return $fileAttributes;
    }

    /**
     * Raw list of files.
     *
     * @param string $folderPath
     * @return array|false
     */
    public function rawList(string $folderPath): array|false
    {
        if (!str_ends_with($folderPath, '/')) {
            $folderPath .= '/';
        }

        $this->handle = $this->common($folderPath);
        curl_setopt($this->handle, CURLOPT_UPLOAD, 0);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($this->handle);

        if (curl_error($this->handle) || !is_string($result)) {
            return false;
        }

        return array_filter(explode("\n", str_replace("\r\n", "\n", trim($result))));
    }

    /**
     * Attribute list of files.
     *
     * @param string $folderPath
     * @return array|false
     */
    public function attributeList(string $folderPath): array|false
    {
        $remoteFiles = $this->rawList($folderPath);
        if (empty($remoteFiles)) {
            return false;
        }

        $files = [];
        foreach ($remoteFiles as $remoteFile) {
            $fileAttributes = preg_split("/\s+/", trim($remoteFile));
            if (count($fileAttributes) < 8) {
                continue;
            }

            [
                $attribute['rights'],
                $attribute['number'],
                $attribute['user'],
                $attribute['group'],
                $attribute['size'],
                $attribute['month'],
                $attribute['day'],
                $attribute['time']
            ] = $fileAttributes;

            array_splice($fileAttributes, 0, 8);
            $attribute['name'] = trim(implode(' ', $fileAttributes));
            $attribute['type'] = str_starts_with($attribute['rights'], 'd') ? 'directory' : 'file';
            $files[] = $attribute;
        }

        return $files;
    }
}