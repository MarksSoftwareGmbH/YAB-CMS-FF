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
use Cake\I18n\DateTime;
use Cake\Utility\Text;
use Migrations\AbstractSeed;

/**
 * UserProfiles seed.
 */
class UserProfilesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $dateTime = DateTime::now();

        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'uuid_id' => Text::uuid(),
                'foreign_key' => Text::uuid(),
                'prefix' => '',
                'salutation' => 'Mr.',
                'suffix' => NULL,
                'first_name' => 'Marks',
                'middle_name' => '',
                'last_name' => 'Software',
                'gender' => 'Male',
                'birthday' => NULL,
                'website' => 'https://www.marks-software.de/',
                'telephone' => '',
                'mobilephone' => '',
                'fax' => '',
                'company' => 'Marks Software GmbH',
                'street' => 'Holunderweg',
                'street_addition' => '20',
                'postcode' => '29664',
                'city' => 'Walsrode',
                'region_id' => NULL,
                'country_id' => 98,
                'about_me' => '&lt;p&gt;Coder in Chef&lt;br&gt;&lt;/p&gt;',
                'tags' => '[{&quot;value&quot;:&quot;YAB&quot;,&quot;color&quot;:&quot;hsl(198,62%,71%)&quot;,&quot;style&quot;:&quot;--tag-bg:hsl(198,62%,71%)&quot;},{&quot;value&quot;:&quot;CMS&quot;,&quot;color&quot;:&quot;hsl(96,42%,71%)&quot;,&quot;style&quot;:&quot;--tag-bg:hsl(96,42%,71%)&quot;},{&quot;value&quot;:&quot;CakePHP&quot;,&quot;color&quot;:&quot;hsl(73,62%,71%)&quot;,&quot;style&quot;:&quot;--tag-bg:hsl(73,62%,71%)&quot;},{&quot;value&quot;:&quot;PHP8&quot;,&quot;color&quot;:&quot;hsl(244,68%,65%)&quot;,&quot;style&quot;:&quot;--tag-bg:hsl(244,68%,65%)&quot;}]',
                'timezone' => 'Europe/Berlin',
                'image' => '/yab_cms_ff/img/avatars/profile_avatar_7051295d-e7e2-42b8-8b97-5a8fa9c22152.jpg',
                'view_counter' => 0,
                'status' => 1,
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
        ];

        $table = $this->table('user_profiles');
        $table->insert($data)->save();
    }
}
