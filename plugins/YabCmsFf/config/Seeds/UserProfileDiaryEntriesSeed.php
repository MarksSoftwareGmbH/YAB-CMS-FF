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
 * UserProfileDiaryEntries seed.
 */
class UserProfileDiaryEntriesSeed extends AbstractSeed
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
                'entry_title' => 'YAB CMS FF v1.0 beta release',
                'entry_body' => '&lt;p&gt;Looking good.&lt;br&gt;&lt;/p&gt;',
                'entry_avatar' => '/yab_cms_ff/img/content/diary_entry_avatar_128908a2-92b1-4c74-952f-b6df7769b476.gif',
                'entry_star_counter' => 0,
                'view_counter' => 0,
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'uuid_id' => Text::uuid(),
                'foreign_key' => Text::uuid(),
                'entry_title' => 'YAB CMS FF v1.0.1 beta update',
                'entry_body' => '&lt;p&gt;Looking better.&lt;/p&gt;',
                'entry_avatar' => '/yab_cms_ff/img/content/diary_entry_avatar_b2f68372-8e4f-44d2-b60a-6491d1e9b44c.gif',
                'entry_star_counter' => 0,
                'view_counter' => 0,
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
        ];

        $table = $this->table('user_profile_diary_entries');
        $table->insert($data)->save();
    }
}
