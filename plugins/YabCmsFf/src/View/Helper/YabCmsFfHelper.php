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
namespace YabCmsFf\View\Helper;

use Cake\I18n\DateTime;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;

/**
 * Class YabCmsFfHelper
 *
 * @package YabCmsFf\View\Helper
 */
class YabCmsFfHelper extends Helper
{
    /**
     * Helpers array
     *
     * @var array
     */
    public array $helpers = ['Html', 'Form', 'Time'];

    /**
     * YabCmsFf upload link
     *
     * @var string
     */
    public string $yabCmsFfUploadLink = '/yab_cms_ff/uploads/';

    /**
     * YabCmsFf upload directory
     *
     * @var string
     */
    public string $yabCmsFfUploadDir = '/plugins/YabCmsFf/webroot/uploads/';

    /**
     * Read camel method
     *
     * @param string $camelcase
     * @return string
     */
    public function readCamel(string $camelcase): string
    {
        $regex = '/
              (?<=[a-z])
              (?=[A-Z])
            | (?<=[A-Z])
              (?=[A-Z][a-z])
            /x';
        $split = preg_split($regex, $camelcase);
        if ($split === false) {
            return $camelcase;
        }

        return implode(' ', $split);
    }

    /**
     * Status method
     *
     * @param mixed $status
     * @return string
     */
    public function status(mixed $status): string
    {
        $output = $this->Html->tag('span', __d('yab_cms_ff', 'Inactive'), [
            'class'     => 'badge badge-danger',
            'escape'    => false,
        ]);
        if ($status == 1 || $status === true || $status === 'true') {
            $output = $this->Html->tag('span', __d('yab_cms_ff', 'Active'), [
                'class'     => 'badge badge-success',
                'escape'    => false,
            ]);
        }

        return $output;
    }

    /**
     * Check method
     *
     * @param mixed $check
     * @return string
     */
    public function check(mixed $check): string
    {
        if ($check == 1 || $check === true || $check === 'true') {
            $icon = $this->Html->tag('i', '', [
                'class'     => 'fa fa-check',
                'escape'    => false,
            ]);
            $class = 'text-success';
        } else {
            $icon = $this->Html->tag('i', '', [
                'class'     => 'fa fa-remove',
                'escape'    => false,
            ]);
            $class = 'text-danger';
        }

        return $this->Html->tag('span', $icon, [
            'class'     => $class,
            'escape'    => false,
        ]);
    }

    /**
     * Prefixes method
     *
     * @param string|null $key
     * @return array|string
     */
    public function prefixes(?string $key = null): array|string
    {
        $prefixes = [
            'Dr.'           => __d('yab_cms_ff', 'Dr.'),
            'Prof.'         => __d('yab_cms_ff', 'Prof.'),
            'Prof. & Dr.'   => __d('yab_cms_ff', 'Prof. & Dr.'),
        ];
        if ($key !== null && isset($prefixes[$key])) {
            return $prefixes[$key];
        }

        return $prefixes;
    }

    /**
     * Salutations method
     *
     * @param string|null $key
     * @return array|string
     */
    public function salutations(?string $key = null): array|string
    {
        $salutations = [
            'Mr.'           => __d('yab_cms_ff', 'Mr.'),
            'Mr. & Dr.'     => __d('yab_cms_ff', 'Mr. & Dr.'),
            'Mr. & Mrs.'    => __d('yab_cms_ff', 'Mr. & Mrs.'),
            'Mrs.'          => __d('yab_cms_ff', 'Mrs.'),
            'Mrs. & Dr.'    => __d('yab_cms_ff', 'Mrs. & Dr.'),
            'Ms.'           => __d('yab_cms_ff', 'Ms.'),
        ];
        if ($key !== null && isset($salutations[$key])) {
            return $salutations[$key];
        }

        return $salutations;
    }

    /**
     * Timezone method
     *
     * @param string|null $timezone
     * @return array|string
     */
    public function timezone(?string $timezone = null): array|string
    {
        $timezones = DateTime::listTimezones(null, null, ['group' => false]);
        if ($timezone !== null && isset($timezones[$timezone])) {
            return $timezones[$timezone];
        }

        return $timezones;
    }

    /**
     * Month method
     *
     * @param string|null $month
     * @return array|string|null
     */
    public function month(?string $month = null): array|string|null
    {
        $months = [
            'January'   => __d('yab_cms_ff', 'January'),
            'February'  => __d('yab_cms_ff', 'February'),
            'March'     => __d('yab_cms_ff', 'March'),
            'April'     => __d('yab_cms_ff', 'April'),
            'May'       => __d('yab_cms_ff', 'May'),
            'June'      => __d('yab_cms_ff', 'June'),
            'July'      => __d('yab_cms_ff', 'July'),
            'August'    => __d('yab_cms_ff', 'August'),
            'September' => __d('yab_cms_ff', 'September'),
            'October'   => __d('yab_cms_ff', 'October'),
            'November'  => __d('yab_cms_ff', 'November'),
            'December'  => __d('yab_cms_ff', 'December'),
        ];
        if ($month !== null) {
            return $months[$month] ?? $month;
        }

        return $months;
    }

    /**
     * Input type method
     *
     * @param string|null $type
     * @return string|null
     */
    public function inputType(?string $type = null): ?string
    {
        $types = [
            'string'    => 'text',
            'text'      => 'textarea',
            'number'    => 'number',
            'password'  => 'password',
            'email'     => 'email',
            'select'    => 'select',
            'checkbox'  => 'checkbox',
            'radio'     => 'radio',
        ];
        if ($type !== null) {
            return $types[$type] ?? $type;
        }

        return $type;
    }

    /**
     * Input types list method
     *
     * @return array
     */
    public function inputTypesList(): array
    {
        return [
            'string'    => 'String (Varchar / Decimal / Float)',
            'text'      => 'Text',
            'number'    => 'Number',
            'password'  => 'Password (Varchar)',
            'email'     => 'Email (Varchar)',
            'select'    => 'Select',
            'checkbox'  => 'Checkbox (Boolean)',
            'radio'     => 'Radio (Boolean)',
        ];
    }

    /**
     * Input options method
     *
     * @param iterable|null $array
     * @return array
     */
    public function inputOptions(?iterable $array = null): array
    {
        $options = [];
        if (!empty($array)) {
            foreach ($array as $option) {
                if (isset($option->value)) {
                    $options[$option->value] = $option->value;
                }
            }
        }

        return $options;
    }

    /**
     * Page title method
     *
     * @param object $article
     * @param string $title
     * @return string
     */
    public function pageTitle(object $article, string $title = 'default'): string
    {
        if (isset($article->title) && $article->title !== '') {
            return (string)$article->title;
        } elseif (isset($article->subtitle) && $article->subtitle !== '') {
            return (string)$article->subtitle;
        } elseif (isset($article->name) && $article->name !== '') {
            return (string)$article->name;
        } elseif (isset($article->slug) && $article->slug !== '') {
            return ucwords(str_replace('-', ' ', (string)$article->slug));
        }

        return $title;
    }

    /**
     * Users method
     *
     * @return mixed
     */
    public function users(): mixed
    {
        $Users = TableRegistry::getTableLocator()->get('YabCmsFf.Users');
        return $Users
            ->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name_username');
    }

    /**
     * Domains method
     *
     * @return mixed
     */
    public function domains(): mixed
    {
        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
        return $Domains
            ->find('list', order: ['Domains.name' => 'ASC'], keyField: 'id', valueField: 'name');
    }

    /**
     * ArticleTypes method
     *
     * @return mixed
     */
    public function articleTypes(): mixed
    {
        $ArticleTypes = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypes');
        return $ArticleTypes
            ->find('list', order: ['ArticleTypes.alias' => 'ASC'], keyField: 'id', valueField: 'title');
    }

    /**
     * Categories method
     *
     * @return mixed
     */
    public function categories(): mixed
    {
        $Categories = TableRegistry::getTableLocator()->get('YabCmsFf.Categories');
        return $Categories
            ->find('treeList',
                keyPath: 'id',                
                valuePath: function($entity) {
                    $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
                    return $entity->name_locale . ' ' . '(' . $Domains->getNameById($entity->domain_id) . ')';
                },
                spacer: '-> '
            )
            ->where(['Categories.status' => 1]);
    }

    /**
     * Countries method
     *
     * @param string|null $key
     * @return array|string
     */
    public function countries(?string $key = null): array|string
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.name' => 'ASC'], keyField: 'name', valueField: 'name_code')
            ->where([
                'locale' => 'en_US',
                'status' => 1,
            ])
            ->toArray();

        if ($key !== null) {
            return $countries[$key] ?? $key;
        }

        return $countries;
    }

    /**
     * Countries by locale method
     *
     * @param string $locale
     * @param string|null $key
     * @return array|string
     */
    public function countriesByLocale(string $locale, ?string $key = null): array|string
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.locale_translation' => 'ASC'], keyField: 'locale_translation', valueField: 'locale_translation_code')
            ->where([
                'locale' => $locale,
                'status' => 1,
            ])
            ->toArray();

        if ($key !== null) {
            return $countries[$key] ?? $key;
        }

        return $countries;
    }

    /**
     * Countries list method
     *
     * @param int|null $id
     * @return array|int
     */
    public function countriesList(?int $id = null): array|int
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.name' => 'ASC'], keyField: 'id', valueField: 'name_code')
            ->where([
                'locale' => 'en_US',
                'status' => 1,
            ])
            ->toArray();

        if ($id !== null) {
            return $countries[$id] ?? $id;
        }

        return $countries;
    }

    /**
     * Countries list by locale method
     *
     * @param string $locale
     * @param int|null $id
     * @return array|int
     */
    public function countriesListByLocale(string $locale, ?int $id = null): array|int
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.locale_translation' => 'ASC'], keyField: 'id', valueField: 'locale_translation_code')
            ->where([
                'locale' => $locale,
                'status' => 1,
            ])
            ->toArray();

        if ($id !== null) {
            return $countries[$id] ?? $id;
        }

        return $countries;
    }

    /**
     * Registration types method
     *
     * @param int|null $key
     * @return array|int
     */
    public function registrationTypes(?int $key = null): array|int
    {
        $RegistrationTypes = TableRegistry::getTableLocator()->get('YabCmsFf.RegistrationTypes');
        $registrationTypes = $RegistrationTypes
            ->find('list', order: ['RegistrationTypes.title' => 'ASC'], keyField: 'id', valueField: 'title')
            ->toArray();

        if ($key !== null) {
            return $registrationTypes[$key] ?? $key;
        }

        return $registrationTypes;
    }

    /**
     * Locales list method
     *
     * @param string|null $code
     * @return array|string
     */
    public function localesList(?string $code = null): array|string
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code !== null) {
            return $locales[$code] ?? $code;
        }

        return $locales;
    }

    /**
     * Locale codes method
     *
     * @param string|null $code
     * @return array|string
     */
    public function localeCodes(?string $code = null): array|string
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'code', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code !== null) {
            return $locales[$code] ?? $code;
        }

        return $locales;
    }

    /**
     * Locale code method
     *
     * @param string|null $code
     * @return string|null
     */
    public function localeCode(?string $code = null): ?string
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'code', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code !== null && isset($locales[$code])) {
            return $locales[$code];
        }

        return $code;
    }

    /**
     * Http scheme types method
     *
     * @param string|null $key
     * @return array|string
     */
    public function httpSchemeTypes(?string $key = null): array|string
    {
        $types = [
            'http'  => 'http',
            'https' => 'https',
            'ftp'   => 'ftp',
            'ftps'  => 'ftps',
        ];
        if ($key !== null) {
            return $types[$key] ?? $key;
        }

        return $types;
    }

    /**
     * Http connect types method
     *
     * @param string|null $key
     * @return array|string
     */
    public function httpConnectTypes(?string $key = null): array|string
    {
        $types = [
            'basic'         => 'Basic',
            'digest'        => 'Digest',
            'oauth'         => 'OAuth 1.0',
            'oauth2'        => 'OAuth 2.0',
            'oauth2bearer'  => 'OAuth 2.0 Bearer',
            'proxy'         => 'Proxy',
        ];
        if ($key !== null) {
            return $types[$key] ?? $key;
        }

        return $types;
    }

    /**
     * Generates a strong password of N length containing at least one lower case letter,
     * one uppercase letter, one digit, and one special character.
     *
     * @param int $length
     * @param bool $addDashes
     * @param string $availableSets
     * @return string
     */
    public function generateStrongPassword(int $length = 9, bool $addDashes = false, string $availableSets = 'luds'): string
    {
        $sets = [];
        if (str_contains($availableSets, 'l')) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (str_contains($availableSets, 'u')) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (str_contains($availableSets, 'd')) {
            $sets[] = '23456789';
        }
        if (str_contains($availableSets, 's')) {
            $sets[] = '!@#$%&*?';
        }

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $chars = str_split($set);
            $password .= $chars[array_rand($chars)];
            $all .= $set;
        }

        $allChars = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $allChars[array_rand($allChars)];
        }

        $password = str_shuffle($password);

        if (!$addDashes) {
            return $password;
        }

        $dashLen = (int)floor(sqrt($length));
        $dashStr = '';
        while (strlen($password) > $dashLen) {
            $dashStr .= substr($password, 0, $dashLen) . '-';
            $password = substr($password, $dashLen);
        }
        $dashStr .= $password;

        return $dashStr;
    }

    /**
     * Build slug method
     *
     * @param string $string
     * @return string
     */
    public function buildSlug(string $string): string
    {
        return Text::slug(
            strtolower($string),
            ['transliteratorId' => 'de-ASCII; Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove']
        );
    }
}