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
use Migrations\AbstractMigration;

/**
 * Class InitialArticleTypeAttributes
 */
class InitialArticleTypeAttributes extends AbstractMigration
{
    /**
     * You can specify a autoId property in the Migration class and set it to false,
     * which will turn off the automatic id column creation.
     *
     * @var bool
     * public $autoId = false;
     */

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    // @codingStandardsIgnoreStart
    public function change()
    {
        // Table article_type_attributes
        $tableName = 'article_type_attributes';

        // Check if table exists
        $exists = $this->hasTable($tableName);
        if ($exists) {
            // Drop table
            $this->table($tableName)->drop()->save();
        }

        // Create table
        $table = $this->table($tableName, ['id' => false, 'primary_key' => ['id']]);
        $table
            ->addColumn('id', 'integer', ['signed' => false, 'identity' => true])
            ->addColumn('uuid_id', 'uuid', ['null' => true])
            ->addColumn('foreign_key', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('alias', 'string', ['limit' => 255])
            ->addColumn('type', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('link_1', 'text', ['null' => true])
            ->addColumn('link_2', 'text', ['null' => true])
            ->addColumn('link_3', 'text', ['null' => true])
            ->addColumn('link_4', 'text', ['null' => true])
            ->addColumn('link_5', 'text', ['null' => true])
            ->addColumn('link_6', 'text', ['null' => true])
            ->addColumn('link_7', 'text', ['null' => true])
            ->addColumn('link_8', 'text', ['null' => true])
            ->addColumn('link_9', 'text', ['null' => true])
            ->addColumn('image_1', 'text', ['null' => true])
            ->addColumn('image_1_file', 'text', ['null' => true])
            ->addColumn('image_2', 'text', ['null' => true])
            ->addColumn('image_2_file', 'text', ['null' => true])
            ->addColumn('image_3', 'text', ['null' => true])
            ->addColumn('image_3_file', 'text', ['null' => true])
            ->addColumn('image_4', 'text', ['null' => true])
            ->addColumn('image_4_file', 'text', ['null' => true])
            ->addColumn('image_5', 'text', ['null' => true])
            ->addColumn('image_5_file', 'text', ['null' => true])
            ->addColumn('image_6', 'text', ['null' => true])
            ->addColumn('image_6_file', 'text', ['null' => true])
            ->addColumn('image_7', 'text', ['null' => true])
            ->addColumn('image_7_file', 'text', ['null' => true])
            ->addColumn('image_8', 'text', ['null' => true])
            ->addColumn('image_8_file', 'text', ['null' => true])
            ->addColumn('image_9', 'text', ['null' => true])
            ->addColumn('image_9_file', 'text', ['null' => true])
            ->addColumn('video_1', 'text', ['null' => true])
            ->addColumn('video_1_file', 'text', ['null' => true])
            ->addColumn('video_2', 'text', ['null' => true])
            ->addColumn('video_2_file', 'text', ['null' => true])
            ->addColumn('video_3', 'text', ['null' => true])
            ->addColumn('video_3_file', 'text', ['null' => true])
            ->addColumn('video_4', 'text', ['null' => true])
            ->addColumn('video_4_file', 'text', ['null' => true])
            ->addColumn('video_5', 'text', ['null' => true])
            ->addColumn('video_5_file', 'text', ['null' => true])
            ->addColumn('video_6', 'text', ['null' => true])
            ->addColumn('video_6_file', 'text', ['null' => true])
            ->addColumn('video_7', 'text', ['null' => true])
            ->addColumn('video_7_file', 'text', ['null' => true])
            ->addColumn('video_8', 'text', ['null' => true])
            ->addColumn('video_8_file', 'text', ['null' => true])
            ->addColumn('video_9', 'text', ['null' => true])
            ->addColumn('video_9_file', 'text', ['null' => true])
            ->addColumn('pdf_1', 'text', ['null' => true])
            ->addColumn('pdf_1_file', 'text', ['null' => true])
            ->addColumn('pdf_2', 'text', ['null' => true])
            ->addColumn('pdf_2_file', 'text', ['null' => true])
            ->addColumn('pdf_3', 'text', ['null' => true])
            ->addColumn('pdf_3_file', 'text', ['null' => true])
            ->addColumn('pdf_4', 'text', ['null' => true])
            ->addColumn('pdf_4_file', 'text', ['null' => true])
            ->addColumn('pdf_5', 'text', ['null' => true])
            ->addColumn('pdf_5_file', 'text', ['null' => true])
            ->addColumn('pdf_6', 'text', ['null' => true])
            ->addColumn('pdf_6_file', 'text', ['null' => true])
            ->addColumn('pdf_7', 'text', ['null' => true])
            ->addColumn('pdf_7_file', 'text', ['null' => true])
            ->addColumn('pdf_8', 'text', ['null' => true])
            ->addColumn('pdf_8_file', 'text', ['null' => true])
            ->addColumn('pdf_9', 'text', ['null' => true])
            ->addColumn('pdf_9_file', 'text', ['null' => true])
            ->addColumn('empty_value', 'boolean', ['default' => 0, 'signed' => false])
            ->addColumn('wysiwyg', 'boolean', ['default' => 0, 'signed' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('created_by', 'integer', ['default' => null, 'limit' => 11, 'null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addColumn('modified_by', 'integer', ['default' => null, 'limit' => 11, 'null' => true])
            ->addColumn('deleted', 'datetime', ['null' => true])
            ->addColumn('deleted_by', 'integer', ['default' => null, 'limit' => 11, 'null' => true])
            ->addIndex(['title', 'alias'], ['unique' => true])
            ->addIndex(['title'])
            ->addIndex(['alias'])
            ->create();
    }
    // @codingStandardsIgnoreEnd
}
