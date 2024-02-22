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
 * Class ArticleTypeAttributesSeed
 */
class ArticleTypesArticleTypeAttributesSeed extends AbstractSeed
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
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '1',     // Foreign key
                'position' => '1',
            ],
            [
                'id' => '2',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '2',     // Title
                'position' => '2',
            ],
            [
                'id' => '3',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '3',     // Subtitle
                'position' => '3',
            ],
            [
                'id' => '4',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '6',     // Slug
                'position' => '4',
            ],
            [
                'id' => '5',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '7',     // Excerpt
                'position' => '5',
            ],
            [
                'id' => '6',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '8',     // Body
                'position' => '6',
            ],
            [
                'id' => '7',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '10',    // Meta description
                'position' => '7',
            ],
            [
                'id' => '8',
                'article_type_id' => '1',               // Article
                'article_type_attribute_id' => '11',    // Meta keywords
                'position' => '8',
            ],
            [
                'id' => '9',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '1',     // Foreign key
                'position' => '1',
            ],
            [
                'id' => '10',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '2',     // Title
                'position' => '2',
            ],
            [
                'id' => '11',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '3',     // Subtitle
                'position' => '3',
            ],
            [
                'id' => '12',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '6',     // Slug
                'position' => '4',
            ],
            [
                'id' => '13',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '7',     // Excerpt
                'position' => '5',
            ],
            [
                'id' => '14',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '8',     // Body
                'position' => '6',
            ],
            [
                'id' => '15',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '10',    // Meta description
                'position' => '7',
            ],
            [
                'id' => '16',
                'article_type_id' => '2',               // Page
                'article_type_attribute_id' => '11',    // Meta keywords
                'position' => '8',
            ],
            [
                'id' => '17',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '1',     // Foreign key
                'position' => '1',
            ],
            [
                'id' => '18',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '2',     // Title
                'position' => '2',
            ],
            [
                'id' => '19',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '3',     // Subtitle
                'position' => '3',
            ],
            [
                'id' => '20',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '6',     // Slug
                'position' => '4',
            ],
            [
                'id' => '21',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '7',     // Excerpt
                'position' => '5',
            ],
            [
                'id' => '22',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '8',     // Body
                'position' => '6',
            ],
            [
                'id' => '23',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '10',    // Meta description
                'position' => '7',
            ],
            [
                'id' => '24',
                'article_type_id' => '3',               // Blog
                'article_type_attribute_id' => '11',    // Meta keywords
                'position' => '8',
            ],
            [
                'id' => '25',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '1',     // Foreign key
                'position' => '1',
            ],
            [
                'id' => '26',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '5',     // Name
                'position' => '2',
            ],
            [
                'id' => '27',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '4',     // Alias
                'position' => '3',
            ],
            [
                'id' => '28',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '6',     // Slug
                'position' => '4',
            ],
            [
                'id' => '29',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '7',     // Excerpt
                'position' => '5',
            ],
            [
                'id' => '30',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '8',     // Body
                'position' => '6',
            ],
            [
                'id' => '31',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '10',    // Meta description
                'position' => '7',
            ],
            [
                'id' => '32',
                'article_type_id' => '4',               // Product
                'article_type_attribute_id' => '11',    // Meta keywords
                'position' => '8',
            ],
            [
                'id' => '33',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '1',     // Foreign key
                'position' => '1',
            ],
            [
                'id' => '34',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '5',     // Name
                'position' => '2',
            ],
            [
                'id' => '35',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '4',     // Alias
                'position' => '3',
            ],
            [
                'id' => '36',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '6',     // Slug
                'position' => '4',
            ],
            [
                'id' => '37',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '7',     // Excerpt
                'position' => '5',
            ],
            [
                'id' => '38',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '8',     // Body
                'position' => '6',
            ],
            [
                'id' => '39',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '10',    // Meta description
                'position' => '7',
            ],
            [
                'id' => '40',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '11',    // Meta keywords
                'position' => '8',
            ],
            [
                'id' => '41',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '13',    // Project hours
                'position' => '9',
            ],
            [
                'id' => '42',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '14',    // Project hours spent
                'position' => '10',
            ],
            [
                'id' => '43',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '15',    // Project progress
                'position' => '11',
            ],
            [
                'id' => '44',
                'article_type_id' => '5',               // Project
                'article_type_attribute_id' => '16',    // Project team members
                'position' => '12',
            ],
        ];

        $table = $this->table('article_types_article_type_attributes');
        $table->insert($data)->save();
    }
}
