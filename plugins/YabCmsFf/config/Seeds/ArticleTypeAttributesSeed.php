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
 * Class ArticleTypeAttributesSeed
 */
class ArticleTypeAttributesSeed extends AbstractSeed
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
                'title' => 'Foreign key',
                'alias' => 'foreign_key',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Title',
                'alias' => 'title',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Subtitle',
                'alias' => 'subtitle',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Alias',
                'alias' => 'alias',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Name',
                'alias' => 'name',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Slug',
                'alias' => 'slug',
                'type' => 'string',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Excerpt',
                'alias' => 'excerpt',
                'type' => 'text',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Body',
                'alias' => 'body',
                'type' => 'text',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => true,
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
                'title' => 'Link',
                'alias' => 'link',
                'type' => 'text',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Meta description',
                'alias' => 'meta_description',
                'type' => 'text',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Meta keywords',
                'alias' => 'meta_keywords',
                'type' => 'text',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Salutation',
                'alias' => 'salutation',
                'type' => 'select',
                'description' => 'Default type option',
                'empty_value' => true,
                'wysiwyg' => false,
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
                'title' => 'Project hours',
                'alias' => 'project_hours',
                'type' => 'number',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Project hours spent',
                'alias' => 'project_hours_spent',
                'type' => 'number',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Project progress',
                'alias' => 'project_progress',
                'type' => 'number',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
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
                'title' => 'Project team members',
                'alias' => 'project_team_members',
                'type' => 'select',
                'description' => 'Default type option',
                'empty_value' => false,
                'wysiwyg' => false,
                'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created_by' => '1',
                'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'modified_by' => '1',
                'deleted' => NULL,
                'deleted_by' => NULL,
            ],
        ];

        $table = $this->table('article_type_attributes');
        $table->insert($data)->save();
    }
}
