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
namespace YabCmsFf\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * ArticleTypeAttribute Entity.
 *
 * @property int $id
 * @property string|null $uuid_id
 * @property string|null $foreign_key
 * @property string $title
 * @property string $alias
 * @property string $type
 * @property string $description
 * @property string|null $link_1
 * @property string|null $link_2
 * @property string|null $link_3
 * @property string|null $link_4
 * @property string|null $link_5
 * @property string|null $link_6
 * @property string|null $link_7
 * @property string|null $link_8
 * @property string|null $link_9
 * @property string|null $image_1
 * @property string|null $image_1_file
 * @property string|null $image_2
 * @property string|null $image_2_file
 * @property string|null $image_3
 * @property string|null $image_3_file
 * @property string|null $image_4
 * @property string|null $image_4_file
 * @property string|null $image_5
 * @property string|null $image_5_file
 * @property string|null $image_6
 * @property string|null $image_6_file
 * @property string|null $image_7
 * @property string|null $image_7_file
 * @property string|null $image_8
 * @property string|null $image_8_file
 * @property string|null $image_9
 * @property string|null $image_9_file
 * @property string|null $video_1
 * @property string|null $video_1_file
 * @property string|null $video_2
 * @property string|null $video_2_file
 * @property string|null $video_3
 * @property string|null $video_3_file
 * @property string|null $video_4
 * @property string|null $video_4_file
 * @property string|null $video_5
 * @property string|null $video_5_file
 * @property string|null $video_6
 * @property string|null $video_6_file
 * @property string|null $video_7
 * @property string|null $video_7_file
 * @property string|null $video_8
 * @property string|null $video_8_file
 * @property string|null $video_9
 * @property string|null $video_9_file
 * @property string|null $pdf_1
 * @property string|null $pdf_1_file
 * @property string|null $pdf_2
 * @property string|null $pdf_2_file
 * @property string|null $pdf_3
 * @property string|null $pdf_3_file
 * @property string|null $pdf_4
 * @property string|null $pdf_4_file
 * @property string|null $pdf_5
 * @property string|null $pdf_5_file
 * @property string|null $pdf_6
 * @property string|null $pdf_6_file
 * @property string|null $pdf_7
 * @property string|null $pdf_7_file
 * @property string|null $pdf_8
 * @property string|null $pdf_8_file
 * @property string|null $pdf_9
 * @property string|null $pdf_9_file
 * @property bool $empty_value
 * @property bool $wysiwyg
 * @property \Cake\I18n\Time $created
 * @property int $created_by
 * @property \Cake\I18n\Time $modified
 * @property int $modified_by
 * @property \Cake\I18n\Time $deleted
 * @property int $deleted_by
 *
 * @property \YabCmsFf\Model\Entity\ArticleArticleTypeAttributeValue[] $article_article_type_attribute_values
 * @property \YabCmsFf\Model\Entity\ArticleTypeAttributeChoice[] $article_type_attribute_choices
 * @property \YabCmsFf\Model\Entity\ArticleType[] $article_types
 */
class ArticleTypeAttribute extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * List of computed or virtual fields that **should** be included in JSON or array
     * representations of this Entity. If a field is present in both _hidden and _virtual
     * the field will **not** be in the array/JSON versions of the entity.
     *
     * @var string[]
     */
    protected array $_virtual = [
        'title_alias',
    ];

    /**
     * Returns a string with all spaces converted to dashes (by default), accented
     * characters converted to non-accented characters, and non word characters removed.
     *
     * @param string $string the string you want to slug
     * @param string $replacement will replace keys in map
     * @return string
     * @link http://book.cakephp.org/3.0/en/core-libraries/inflector.html#creating-url-safe-strings
     */
    protected function _setTitle($title)
    {
        $this->set('alias', Text::slug(strtolower($title)));
        return $title;
    }

    /**
     * Get title alias method.
     *
     * @return string
     */
    protected function _getTitleAlias()
    {
        if (
            (isset($this->title) && !empty($this->title)) &&
            (isset($this->alias) && !empty($this->alias))
        ) {
            return $this->title . ' ' . '(' . $this->alias . ')';
        }

        return '';
    }
}
