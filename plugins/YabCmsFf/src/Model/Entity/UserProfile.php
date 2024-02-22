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

/**
 * UserProfile Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $uuid_id
 * @property string|null $foreign_key
 * @property string $prefix
 * @property string $salutation
 * @property string $suffix
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $gender
 * @property \Cake\I18n\Time $birthday
 * @property string $image
 * @property string $website
 * @property string $telephone
 * @property string $mobilephone
 * @property string $fax
 * @property string $company
 * @property string $street
 * @property string $street_addition
 * @property string $postcode
 * @property string $city
 * @property int $region_id
 * @property int $country_id
 * @property string $about_me
 * @property string $tags
 * @property string $timezone
 * @property int $view_counter
 * @property bool $status
 * @property \Cake\I18n\Time $created
 * @property int $created_by
 * @property \Cake\I18n\Time $modified
 * @property int $modified_by
 * @property \Cake\I18n\Time $deleted
 * @property int $deleted_by
 *
 * @property \YabCmsFf\Model\Entity\User $user
 * @property \YabCmsFf\Model\Entity\Region $region
 * @property \YabCmsFf\Model\Entity\Country $country
 */
class UserProfile extends Entity
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
        'full_name',
    ];

    /**
     * Get full name method.
     *
     * @return string
     */
    protected function _getFullName()
    {
        if (
            (isset($this->first_name) && !empty($this->first_name)) &&
            (isset($this->middle_name) && !empty($this->middle_name)) &&
            (isset($this->last_name) && !empty($this->last_name))
        ) {
            return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        }

        return '';
    }
}
