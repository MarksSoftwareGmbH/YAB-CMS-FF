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
use Migrations\AbstractSeed;

/**
 * ArticleArticleTypeAttributeValues seed.
 */
class ArticleArticleTypeAttributeValuesSeed extends AbstractSeed
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
                'article_id' => 1,
                'article_type_attribute_id' => 1,
                'value' => '',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 2,
                'article_id' => 1,
                'article_type_attribute_id' => 5,
                'value' => 'Yet another boring CMS for FREE v1.0 beta',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 3,
                'article_id' => 1,
                'article_type_attribute_id' => 4,
                'value' => 'YAB-CMS-FF',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 4,
                'article_id' => 1,
                'article_type_attribute_id' => 6,
                'value' => 'yet-another-boring-cms-for-free',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 5,
                'article_id' => 1,
                'article_type_attribute_id' => 7,
                'value' => 'Since the Croogo CMS project has come to a standstill and we want to use a lean CakePHP 5 Bootstrap CMS, we have extracted it ourselves from the EnterPULSE - CONNECTED. BUSINESS. PERFORMANCE.® middleware. The v1.0 beta looks very promising.',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 6,
                'article_id' => 1,
                'article_type_attribute_id' => 8,
                'value' => '<p>Since the <a href="https://twitter.com/croogo" target="_blank">@croogo</a> CMS project has come to a standstill and we want to use a lean <a href="https://cakephp.org/" target="_blank">CakePHP 5</a> Bootstrap CMS, we have extracted it ourselves from the <a href="https://enterpulse.io/" target="_blank">EnterPULSE - CONNECTED. BUSINESS. PERFORMANCE.®</a> middleware. The v1.0 beta looks very promising.
/cc <a href="https://twitter.com/rchavik" target="_blank">@rchavik</a> <a href="https://twitter.com/fahad19" target="_blank">@fahad19</a></p>',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 7,
                'article_id' => 1,
                'article_type_attribute_id' => 10,
                'value' => 'Yet another boring CMS for FREE - A CakePHP 5 Bootstrap CMS',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 8,
                'article_id' => 1,
                'article_type_attribute_id' => 11,
                'value' => 'YAB CMS, CMS, Bootstrap, CakePHP, CakePHP 5, PHP, PHP8',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 9,
                'article_id' => 1,
                'article_type_attribute_id' => 13,
                'value' => '120',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 10,
                'article_id' => 1,
                'article_type_attribute_id' => 14,
                'value' => '120',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 11,
                'article_id' => 1,
                'article_type_attribute_id' => 15,
                'value' => '100',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
            [
                'id' => 12,
                'article_id' => 1,
                'article_type_attribute_id' => 16,
                'value' => 'Lukas Marks',
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
        ];

        $table = $this->table('article_article_type_attribute_values');
        $table->insert($data)->save();
    }
}
