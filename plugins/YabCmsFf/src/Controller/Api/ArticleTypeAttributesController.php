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
namespace YabCmsFf\Controller\Api;

use Cake\Event\Event;

/**
 * Class ArticleTypeAttributesController
 *
 * @package Api\Controller
 */
class ArticleTypeAttributesController extends AppController
{

    /**
     * Pagination
     *
     * @var array
     */
    public array $paginate = [
        'limit' => 25,
        'maxLimit' => 50,
        'sortableFields' => [
            'id',
            'uuid_id',
            'foreign_key',
            'title',
            'alias',
            'type',
            'description',
            'link_1',
            'link_2',
            'link_3',
            'link_4',
            'link_5',
            'link_6',
            'link_7',
            'link_8',
            'link_9',
            'image_1',
            'image_1_file',
            'image_2',
            'image_2_file',
            'image_3',
            'image_3_file',
            'image_4',
            'image_4_file',
            'image_5',
            'image_5_file',
            'image_6',
            'image_6_file',
            'image_7',
            'image_7_file',
            'image_8',
            'image_8_file',
            'image_9',
            'image_9_file',
            'video_1',
            'video_1_file',
            'video_2',
            'video_2_file',
            'video_3',
            'video_3_file',
            'video_4',
            'video_4_file',
            'video_5',
            'video_5_file',
            'video_6',
            'video_6_file',
            'video_7',
            'video_7_file',
            'video_8',
            'video_8_file',
            'video_9',
            'video_9_file',
            'pdf_1',
            'pdf_1_file',
            'pdf_2',
            'pdf_2_file',
            'pdf_3',
            'pdf_3_file',
            'pdf_4',
            'pdf_4_file',
            'pdf_5',
            'pdf_5_file',
            'pdf_6',
            'pdf_6_file',
            'pdf_7',
            'pdf_7_file',
            'pdf_8',
            'pdf_8_file',
            'pdf_9',
            'pdf_9_file',
            'empty_value',
            'wysiwyg',
            'created',
            'modified',
        ],
        'order' => ['created' => 'DESC']
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->Crud->on('beforePaginate', function (Event $event) {
            if (
                ($this->getRequest()->getQuery('fields') !== null) &&
                !empty($this->getRequest()->getQuery('fields'))
            ) {
                $select = explode(',', $this->getRequest()->getQuery('fields'));
                $this->paginate($event->getSubject()->query->select($select));
            }
            if (
                ($this->getRequest()->getQuery('contain') !== null) &&
                ($this->getRequest()->getQuery('contain') == 1)
            ) {
                $event->getSubject()->query->contain([
                    'ArticleTypeAttributeChoices' => function ($q) {
                        return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                    }
                ]);
            }
        });

        return $this->Crud->execute();
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return mixed
     */
    public function view(int $id = null)
    {
        $this->Crud->on('beforeFind', function (Event $event) {
            if (
                ($this->getRequest()->getQuery('fields') !== null) &&
                !empty($this->getRequest()->getQuery('fields'))
            ) {
                $select = explode(',', $this->getRequest()->getQuery('fields'));
                $event->getSubject()->query->select($select);
            }
            if (
                ($this->getRequest()->getQuery('contain') !== null) &&
                ($this->getRequest()->getQuery('contain') == 1)
            ) {
                $event->getSubject()->query->contain([
                    'ArticleTypeAttributeChoices' => function ($q) {
                        return $q->orderBy(['ArticleTypeAttributeChoices.value' => 'ASC']);
                    },
                    'ArticleTypes' => function ($q) {
                        return $q->orderBy(['ArticleTypes.title' => 'ASC']);
                    },
                    'ArticleArticleTypeAttributeValues',
                ]);
            }
        });

        return $this->Crud->execute();
    }
}
