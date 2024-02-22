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
use Cake\ORM\TableRegistry;

/**
 * Article Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \YabCmsFf\Model\Entity\ParentArticle $parent_article
 * @property int $article_type_id
 * @property \YabCmsFf\Model\Entity\ArticleType $article_type
 * @property int $user_id
 * @property \YabCmsFf\Model\Entity\User $user
 * @property int $domain_id
 * @property \YabCmsFf\Model\Entity\Domain $domain
 * @property string|null $uuid_id
 * @property int $lft
 * @property int $rght
 * @property string $locale
 * @property \Cake\I18n\Time $promote_start
 * @property \Cake\I18n\Time $promote_end
 * @property bool $promote
 * @property bool $status
 * @property \Cake\I18n\Time $created
 * @property int $created_by
 * @property \Cake\I18n\Time $modified
 * @property int $modified_by
 * @property \Cake\I18n\Time $deleted
 * @property int $deleted_by
 * @property \YabCmsFf\Model\Entity\ArticleArticleTypeAttributeValue[] $article_article_type_attribute_values
 * @property \YabCmsFf\Model\Entity\ChildArticle[] $child_articles
 */
class Article extends Entity
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
        'categories' => true,
    ];

    /**
     * List of computed or virtual fields that **should** be included in JSON or array
     * representations of this Entity. If a field is present in both _hidden and _virtual
     * the field will **not** be in the array/JSON versions of the entity.
     *
     * @var string[]
     */
    protected array $_virtual = [
        'global_title',
        'global_slug',
    ];

    /**
     * Use entity constructor to set the article_article_type_attribute_values as properties of the article entity
     *
     * @param array $properties
     * @param array $options
     */
    public function __construct(array $properties = [], array $options = [])
    {
        if (
            !array_key_exists('article_type', $properties) &&
            array_key_exists('article_type_id', $properties)
        ) {
            $properties['article_type'] = $this->getArticleTypeAttributes($properties['article_type_id']);
        }

        // Set the article_type_attributes as keys for the article and article_article_type_attribute_values
        if (array_key_exists('_type', $properties)) {
            foreach ($properties['article_type']->article_type_attributes as $article_type_attribute) {
                $properties[$article_type_attribute->alias] = null;
            }
        }
        // unset($properties['article_type']->articleTypeAttributes);

        // Set the article_article_type_attribute_values for the article_type_attributes by article_type_attribute title
        if (array_key_exists('article_article_type_attribute_values', $properties)) {
            foreach ($properties['article_article_type_attribute_values'] as $article_article_type_attribute_value) {
                $properties[$article_article_type_attribute_value->article_type_attribute->alias] =
                    $article_article_type_attribute_value->value;
            }
        }
        // unset($properties['article_article_type_attribute_values']);
        // unset($properties['article_type']);
        // unset($properties['_matchingData']);

        parent::__construct($properties, $options);
    }

    /**
     * Get article type attributes method.
     *
     * @param int $articleTypeId
     * @return \Cake\Datasource\EntityInterface|mixed
     */
    private function getArticleTypeAttributes(int $articleTypeId)
    {
        $table = TableRegistry::getTableLocator()->get('YabCmsFf.ArticleTypes');

        return $table->get($articleTypeId, contain: ['ArticleTypeAttributes']);
    }

    /**
     * Get global title method.
     *
     * @return string
     */
    protected function _getGlobalTitle()
    {
        // Check for title and subtitle
        if (!empty($this->title) && !empty($this->subtitle)) {
            return $this->title . ' ' . '(' . $this->subtitle . ')';
        // Check for title
        } elseif (!empty($this->title)) {
            return $this->title;
        // Check for subtitle
        } elseif (!empty($this->subtitle)) {
            return $this->subtitle;
        // Check for name
        } elseif (!empty($this->name)) {
            return $this->name;
        // Check for slug
        } elseif (!empty($this->slug)) {
            return ucwords(str_replace('-', ' ', $this->slug));
        }

        return '';
    }

    /**
     * Get global slug method.
     *
     * @return string
     */
    protected function _getGlobalSlug()
    {
        // Check for slug and alias
        if (!empty($this->slug)) {
            return $this->slug;
        } elseif (!empty($this->alias)) {
            return $this->alias;
        }

        return '';
    }
}
