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
     *
     * @return string
     */
    public function readCamel(string $camelcase)
    {
        $regex = '/
              (?<=[a-z])
              (?=[A-Z])
            | (?<=[A-Z])
              (?=[A-Z][a-z])
            /x';
        $split = preg_split($regex, $camelcase);
        $readableCamelcase = implode(' ', $split);
        return $readableCamelcase;
    }

    /**
     * Status method
     *
     * @param $status
     * @return mixed
     */
    public function status($status)
    {
        $output = $this->Html->tag('span', __d('yab_cms_ff', 'Inactive'), [
            'class'     => 'badge badge-danger',
            'escape'    => false,
        ]);
        if ($status == 1 || $status == true || $status === 'true') {
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
     * @param $check
     * @return mixed
     */
    public function check($check)
    {
        if ($check == 1 || $check == true || $check === 'true') {
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
     * @return array|mixed
     */
    public function prefixes(string $key = null)
    {
        $prefixes = [
            'Dr.'           => __d('yab_cms_ff', 'Dr.'),
            'Prof.'         => __d('yab_cms_ff', 'Prof.'),
            'Prof. & Dr.'   => __d('yab_cms_ff', 'Prof. & Dr.'),
        ];
        if ($key) {
            if (isset($prefixes[$key])) {
                return $prefixes[$key];
            }
        }

        return $prefixes;
    }

    /**
     * Saluations method
     *
     * @param string|null $key
     * @return array|mixed
     */
    public function salutations(string $key = null)
    {
        $salutations = [
            'Mr.'           => __d('yab_cms_ff', 'Mr.'),
            'Mr. & Dr.'     => __d('yab_cms_ff', 'Mr. & Dr.'),
            'Mr. & Mrs.'    => __d('yab_cms_ff', 'Mr. & Mrs.'),
            'Mrs.'          => __d('yab_cms_ff', 'Mrs.'),
            'Mrs. & Dr.'    => __d('yab_cms_ff', 'Mrs. & Dr.'),
            'Ms.'           => __d('yab_cms_ff', 'Ms.'),
        ];
        if ($key) {
            if (isset($salutations[$key])) {
                return $salutations[$key];
            }
        }

        return $salutations;
    }

    /**
     * Timezone method
     *
     * @param string|null $timezone
     * @return array
     */
    public function timezone(string $timezone = null)
    {
        $timezones = DateTime::listTimezones(null, null, ['group' => false]);
        if ($timezone) {
            if (isset($timezones[$timezone])) {
                return $timezones[$timezone];
            }
        }

        return $timezones;
    }

    /**
     * Month method
     *
     * @param string|null $month
     * @return mixed|null
     */
    public function month(string $month = null)
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
        if ($month) {
            if (isset($months[$month])) {
                return $months[$month];
            }
        }

        return $month;
    }

    /**
     * Input type method
     *
     * @param string|null $type
     * @return mixed|null
     */
    public function inputType(string $type = null)
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
        if ($type) {
            if (isset($types[$type])) {
                return $types[$type];
            } else {
                return $type;
            }
        } else {
            return $type;
        }
    }

    /**
     * Input types list method
     *
     * @return array
     */
    public function inputTypesList()
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
     * @param array|null $array
     * @return array
     */
    public function inputOptions(array $array = null)
    {
        $options = [];
        if (!empty($array)) {
            foreach ($array as $option) {
                $options[$option->value] = $option->value;
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
    public function pageTitle(object $article, $title = 'default')
    {
        // Check for title
        if (isset($article->title) && !empty($article->title)) {
            return $article->title;
            // Check for subtitle
        } elseif (isset($article->subtitle) && !empty($article->subtitle)) {
            return $article->subtitle;
            // Check for name
        } elseif (isset($article->name) && !empty($article->name)) {
            return $article->name;
            // Check for slug
        } elseif (isset($article->slug) && !empty($article->slug)) {
            return ucwords(str_replace('-', ' ', $article->slug));
        }

        return $title;
    }

    /**
     * Users method
     *
     * @return object
     */
    public function users()
    {
        $Users = TableRegistry::getTableLocator()->get('YabCmsFf.Users');
        return $Users
            ->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name_username');
    }

    /**
     * Domains method
     *
     * @return object
     */
    public function domains()
    {
        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
        return $Domains
            ->find('list', order: ['Domains.name' => 'ASC'], keyField: 'id', valueField: 'name');
    }

    /**
     * ArticleTypes method
     *
     * @return object
     */
    public function articleTypes()
    {
        $ArticleTypes = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypes');
        return $ArticleTypes
            ->find('list', order: ['ArticleTypes.alias' => 'ASC'], keyField: 'id', valueField: 'title');
    }

    /**
     * Categories method
     *
     * @return object
     */
    public function categories()
    {
        $Categories = TableRegistry::getTableLocator()->get('YabCmsFf.Categories');
        $Domains = TableRegistry::getTableLocator()->get('YabCmsFf.Domains');
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
     *
     * @return array|mixed
     */
    public function countries(string $key = null)
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.name' => 'ASC'], keyField: 'name', valueField: 'name_code')
            ->where([
                'locale' => 'en_US',
                'status' => 1,
            ])
            ->toArray();

        if ($key) {
            if (array_key_exists($key, $countries)) {
                return $countries[$key];
            } else {
                return $key;
            }
        }

        return $countries;
    }

    /**
     * Countries by locale method
     *
     * @param string $locale
     * @param string|null $key
     *
     * @return array|mixed
     */
    public function countriesByLocale(string $locale, string $key = null)
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.locale_translation' => 'ASC'], keyField: 'locale_translation', valueField: 'locale_translation_code')
            ->where([
                'locale' => $locale,
                'status' => 1,
            ])
            ->toArray();

        if ($key) {
            if (array_key_exists($key, $countries)) {
                return $countries[$key];
            } else {
                return $key;
            }
        }

        return $countries;
    }

    /**
     * Countries list method
     *
     * @param int|null $id
     *
     * @return array|mixed
     */
    public function countriesList(int $id = null)
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.name' => 'ASC'], keyField: 'id', valueField: 'name_code')
            ->where([
                'locale' => 'en_US',
                'status' => 1,
            ])
            ->toArray();

        if ($id) {
            if (array_key_exists($id, $countries)) {
                return $countries[$id];
            } else {
                return $id;
            }
        }

        return $countries;
    }

    /**
     * Countries list by locale method
     *
     * @param string $locale
     * @param int|null $id
     *
     * @return array|mixed
     */
    public function countriesListByLocale(string $locale, int $id = null)
    {
        $Countries = TableRegistry::getTableLocator()->get('YabCmsFf.Countries');
        $countries = $Countries
            ->find('list', order: ['Countries.locale_translation' => 'ASC'], keyField: 'id', valueField: 'locale_translation_code')
            ->where([
                'locale' => $locale,
                'status' => 1,
            ])
            ->toArray();

        if ($id) {
            if (array_key_exists($id, $countries)) {
                return $countries[$id];
            } else {
                return $id;
            }
        }

        return $countries;
    }

    /**
     * Registration types method
     *
     * @param int|null $key
     *
     * @return array|mixed
     */
    public function registrationTypes(int $key = null)
    {
        $RegistrationTypes = TableRegistry::getTableLocator()->get('YabCmsFf.RegistrationTypes');
        $registrationTypes = $RegistrationTypes
            ->find('list', order: ['RegistrationTypes.title' => 'ASC'], keyField: 'id', valueField: 'title')
            ->toArray();

        if ($key) {
            if (array_key_exists($key, $registrationTypes)) {
                return $registrationTypes[$key];
            } else {
                return $key;
            }
        }

        return $registrationTypes;
    }

    /**
     * Locales list method
     *
     * @param string|null $code
     *
     * @return array|mixed
     */
    public function localesList(string $code = null)
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code) {
            if (array_key_exists($code, $locales)) {
                return $locales[$code];
            } else {
                return $code;
            }
        }

        return $locales;
    }

    /**
     * Locale codes method
     *
     * @param string|null $code
     *
     * @return array|mixed
     */
    public function localeCodes(string $code = null)
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'code', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code) {
            if (array_key_exists($code, $locales)) {
                return $locales[$code];
            } else {
                return $code;
            }
        }

        return $locales;
    }

    /**
     * Locale code method
     *
     * @param string|null $code
     * @return string|null
     */
    public function localeCode(string $code = null)
    {
        $Locales = TableRegistry::getTableLocator()->get('YabCmsFf.Locales');
        $locales = $Locales
            ->find('list', keyField: 'code', valueField: 'name')
            ->where(['Locales.status' => 1])
            ->orderBy(['Locales.weight' => 'ASC'])
            ->toArray();

        if ($code) {
            if (array_key_exists($code, $locales)) {
                return $locales[$code];
            }
        }

        return $code;
    }

    /**
     * Http scheme types method
     *
     * @param string|null $key
     *
     * @return array|mixed
     */
    public function httpSchemeTypes(string $key = null)
    {
        $types = [
            'http'  => 'http',
            'https' => 'https',
            'ftp'   => 'ftp',
            'ftps'  => 'ftps',
        ];
        if ($key) {
            if (array_key_exists($key, $types)) {
                return $types[$key];
            } else {
                return $key;
            }
        }

        return $types;
    }

    /**
     * Http connect types method
     *
     * @param string|null $key
     *
     * @return array|mixed
     */
    public function httpConnectTypes(string $key = null)
    {
        $types = [
            'basic'         => 'Basic',
            'digest'        => 'Digest',
            'oauth'         => 'OAuth 1.0',
            'oauth2'        => 'OAuth 2.0',
            'oauth2bearer'  => 'OAuth 2.0 Bearer',
            'proxy'         => 'Proxy',
        ];
        if ($key) {
            if (array_key_exists($key, $types)) {
                return $types[$key];
            } else {
                return $key;
            }
        }

        return $types;
    }

    /**
     * Dynamics connect types method
     *
     * @param string|null $key
     *
     * @return array|mixed
     */
    public function dynamicsConnectTypes(string $key = null)
    {
        $types = [
            'basic'  => 'Basic',
            'custom' => 'Custom',
            'proxy'  => 'Proxy',
        ];
        if ($key) {
            if (isset($types[$key])) {
                return $types[$key];
            } else {
                return $key;
            }
        }

        return $types;
    }

    /**
     * Generates a strong password of N length containing at least one lower case letter,
     * one uppercase letter, one digit, and one special character. The remaining characters
     * in the password are chosen at random from those four sets.
     *
     * The available characters in each set are user friendly - there are no ambiguous
     * characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
     * makes it much easier for users to manually type or speak their passwords.
     *
     * Note: the $add_dashes option will increase the length of the password by floor(sqrt(N)) characters.
     *
     * @param int $length
     * @param false $add_dashes
     * @param string $available_sets
     *
     * @return string
     */
    public function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if(!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, (int)$dash_len) . '-';
            $password = substr($password, (int)$dash_len);
        }
        $dash_str .= $password;

        return $dash_str;
    }

    /**
     * Build slug method
     *
     * @param string $string
     *
     * @return string
     */
    public function buildSlug(string $string)
    {
        return Text::slug(
            strtolower($string),
            ['transliteratorId' => 'de-ASCII; Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove']
        );
    }
}
