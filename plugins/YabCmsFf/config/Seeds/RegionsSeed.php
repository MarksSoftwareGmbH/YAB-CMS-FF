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
 * Class RegionsSeed
 */
class RegionsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $dateTime = DateTime::now();

        $data = [
            [
                'id' => '1',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Baden-Württemberg',
                'slug' => 'baden-wuerttemberg',
                'code' => 'BW',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '2',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Bayern',
                'slug' => 'bayern',
                'code' => 'BY',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '3',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Berlin',
                'slug' => 'berlin',
                'code' => 'BE',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '4',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Brandenburg',
                'slug' => 'brandenburg',
                'code' => 'BB',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '5',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Bremen',
                'slug' => 'bremen',
                'code' => 'HB',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '6',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Hamburg',
                'slug' => 'hamburg',
                'code' => 'HH',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '7',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Hessen',
                'slug' => 'hessen',
                'code' => 'HE',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '8',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Mecklenburg-Vorpommern',
                'slug' => 'mecklenburg-vorpommern',
                'code' => 'MV',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '9',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Niedersachsen',
                'slug' => 'niedersachsen',
                'code' => 'NI',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '10',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Nordrhein-Westfalen',
                'slug' => 'nordrhein-westfalen',
                'code' => 'NW',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '11',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Rheinland-Pfalz',
                'slug' => 'rheinland-pfalz',
                'code' => 'RP',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '12',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Saarland',
                'slug' => 'saarland',
                'code' => 'SL',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '13',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Sachsen',
                'slug' => 'sachsen',
                'code' => 'SN',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '14',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Sachsen-Anhalt',
                'slug' => 'sachsen-anhalt',
                'code' => 'ST',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '15',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Schleswig-Holstein',
                'slug' => 'schleswig-holstein',
                'code' => 'SH',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '16',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Thüringen',
                'slug' => 'thueringen',
                'code' => 'TH',
                'info' => 'Germany',
                'locale' => 'de_DE',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => '17',
                'uuid_id' => Text::uuid(),
                'foreign_key' => NULL,
                'name' => 'Other',
                'slug' => 'other',
                'code' => 'other',
                'info' => 'International',
                'locale' => 'en_US',
                'status' => '1',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
        ];

        $table = $this->table('regions');
        $table->insert($data)->save();
    }
}
