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
namespace YabCmsFf\Controller\Admin;

use YabCmsFf\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use YabCmsFf\Utility\YabCmsFf;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Intervention\Image\ImageManager;
use Imagick;

/**
 * ArticleTypeAttributeChoices Controller
 *
 * @property \YabCmsFf\Model\Table\ArticleTypeAttributeChoicesTable $ArticleTypeAttributeChoices
 */
class ArticleTypeAttributeChoicesController extends AppController
{

    /**
     * Locale
     *
     * @var string
     */
    private string $locale;

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
            'article_type_attribute_id',
            'uuid_id',
            'foreign_key',
            'value',
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
            'created',
            'modified',
            'ArticleTypeAttributes.alias',
        ],
        'order' => ['article_type_attribute_id' => 'ASC']
    ];

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     * @link https://book.cakephp.org/4/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $session = $this->getRequest()->getSession();
        $this->locale = $session->check('Locale.code')? $session->read('Locale.code'): 'en_US';
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->ArticleTypeAttributeChoices
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain([
                'ArticleTypeAttributes' => function ($q) {
                    $q->where(['ArticleTypeAttributes.type' => 'select']);
                    return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
                },
            ]);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('articleTypeAttributeChoices', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->get($id, contain: [
            'ArticleTypeAttributes' => function ($q) {
                $q->where(['ArticleTypeAttributes.type' => 'select']);
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            }
        ]);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeViewRender', $this, [
            'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
        ]);

        $this->set('articleTypeAttributeChoice', $articleTypeAttributeChoice);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->newEmptyEntity();
        if ($this->getRequest()->is('post')) {

            ini_set('post_max_size', '512M');
            ini_set('upload_max_filesize', '512M');

            $uuidId = Text::uuid();

            $postData = $this->getRequest()->getData();

            if (empty($postData['link_1'])) { unset($postData['link_1']); }
            if (empty($postData['link_2'])) { unset($postData['link_2']); }
            if (empty($postData['link_3'])) { unset($postData['link_3']); }
            if (empty($postData['link_4'])) { unset($postData['link_4']); }
            if (empty($postData['link_5'])) { unset($postData['link_5']); }
            if (empty($postData['link_6'])) { unset($postData['link_6']); }
            if (empty($postData['link_7'])) { unset($postData['link_7']); }
            if (empty($postData['link_8'])) { unset($postData['link_8']); }
            if (empty($postData['link_9'])) { unset($postData['link_9']); }

            if (
                !empty($postData['image_1']) ||
                !empty($postData['image_2']) ||
                !empty($postData['image_3']) ||
                !empty($postData['image_4']) ||
                !empty($postData['image_5']) ||
                !empty($postData['image_6']) ||
                !empty($postData['image_7']) ||
                !empty($postData['image_8']) ||
                !empty($postData['image_9'])
            ) {
                // Image 1
                if (!empty($postData['image_1'])) {
                    if (
                        !empty($postData['image_1']->getClientFileName()) &&
                        !empty($postData['image_1']->getClientMediaType()) &&
                        !empty($postData['image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_1']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_1_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_1' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_1']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_1'] = $postData['image_1']->getClientFileName();
                            $postData['image_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_1' . '.' . 'jpg';
                        } else {
                            unset($postData['image_1']);
                        }
                    } elseif (
                        !empty($postData['image_1']->getClientFileName()) &&
                        !empty($postData['image_1']->getClientMediaType()) &&
                        !empty($postData['image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_1']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_1' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_1']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_1'] = $postData['image_1']->getClientFileName();
                            $postData['image_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_1' . '.' . 'gif';
                        } else {
                            unset($postData['image_1']);
                        }
                    } else {
                        unset($postData['image_1']);
                    }
                } else {
                    unset($postData['image_1']);
                }

                // Image 2
                if (!empty($postData['image_2'])) {
                    if (
                        !empty($postData['image_2']->getClientFileName()) &&
                        !empty($postData['image_2']->getClientMediaType()) &&
                        !empty($postData['image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_2']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_2_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_2' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_2']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_2'] = $postData['image_2']->getClientFileName();
                            $postData['image_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_2' . '.' . 'jpg';
                        } else {
                            unset($postData['image_2']);
                        }
                    } elseif (
                        !empty($postData['image_2']->getClientFileName()) &&
                        !empty($postData['image_2']->getClientMediaType()) &&
                        !empty($postData['image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_2']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_2' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_2']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_2'] = $postData['image_2']->getClientFileName();
                            $postData['image_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_2' . '.' . 'gif';
                        } else {
                            unset($postData['image_2']);
                        }
                    } else {
                        unset($postData['image_2']);
                    }
                } else {
                    unset($postData['image_2']);
                }

                // Image 3
                if (!empty($postData['image_3'])) {
                    if (
                        !empty($postData['image_3']->getClientFileName()) &&
                        !empty($postData['image_3']->getClientMediaType()) &&
                        !empty($postData['image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_3']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_3_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_3' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_3']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_3'] = $postData['image_3']->getClientFileName();
                            $postData['image_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_3' . '.' . 'jpg';
                        } else {
                            unset($postData['image_3']);
                        }
                    } elseif (
                        !empty($postData['image_3']->getClientFileName()) &&
                        !empty($postData['image_3']->getClientMediaType()) &&
                        !empty($postData['image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_3']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_3' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_3']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_3'] = $postData['image_3']->getClientFileName();
                            $postData['image_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_3' . '.' . 'gif';
                        } else {
                            unset($postData['image_3']);
                        }
                    } else {
                        unset($postData['image_3']);
                    }
                } else {
                    unset($postData['image_3']);
                }

                // Image 4
                if (!empty($postData['image_4'])) {
                    if (
                        !empty($postData['image_4']->getClientFileName()) &&
                        !empty($postData['image_4']->getClientMediaType()) &&
                        !empty($postData['image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_4']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_4_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_4' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_4']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_4'] = $postData['image_4']->getClientFileName();
                            $postData['image_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_4' . '.' . 'jpg';
                        } else {
                            unset($postData['image_4']);
                        }
                    } elseif (
                        !empty($postData['image_4']->getClientFileName()) &&
                        !empty($postData['image_4']->getClientMediaType()) &&
                        !empty($postData['image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_4']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_4' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_4']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_4'] = $postData['image_3']->getClientFileName();
                            $postData['image_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_4' . '.' . 'gif';
                        } else {
                            unset($postData['image_4']);
                        }
                    } else {
                        unset($postData['image_4']);
                    }
                } else {
                    unset($postData['image_4']);
                }

                // Image 5
                if (!empty($postData['image_5'])) {
                    if (
                        !empty($postData['image_5']->getClientFileName()) &&
                        !empty($postData['image_5']->getClientMediaType()) &&
                        !empty($postData['image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_5']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_5_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_5' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_5']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_5'] = $postData['image_5']->getClientFileName();
                            $postData['image_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_5' . '.' . 'jpg';
                        } else {
                            unset($postData['image_5']);
                        }
                    } elseif (
                        !empty($postData['image_5']->getClientFileName()) &&
                        !empty($postData['image_5']->getClientMediaType()) &&
                        !empty($postData['image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_5']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_5' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_5']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_5'] = $postData['image_5']->getClientFileName();
                            $postData['image_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_5' . '.' . 'gif';
                        } else {
                            unset($postData['image_5']);
                        }
                    } else {
                        unset($postData['image_5']);
                    }
                } else {
                    unset($postData['image_5']);
                }

                // Image 6
                if (!empty($postData['image_6'])) {
                    if (
                        !empty($postData['image_6']->getClientFileName()) &&
                        !empty($postData['image_6']->getClientMediaType()) &&
                        !empty($postData['image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_6']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_6_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_6' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_6']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_6'] = $postData['image_6']->getClientFileName();
                            $postData['image_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_6' . '.' . 'jpg';
                        } else {
                            unset($postData['image_6']);
                        }
                    } elseif (
                        !empty($postData['image_6']->getClientFileName()) &&
                        !empty($postData['image_6']->getClientMediaType()) &&
                        !empty($postData['image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_6']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_6' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_6']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_6'] = $postData['image_6']->getClientFileName();
                            $postData['image_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_6' . '.' . 'gif';
                        } else {
                            unset($postData['image_6']);
                        }
                    } else {
                        unset($postData['image_6']);
                    }
                } else {
                    unset($postData['image_6']);
                }

                // Image 7
                if (!empty($postData['image_7'])) {
                    if (
                        !empty($postData['image_7']->getClientFileName()) &&
                        !empty($postData['image_7']->getClientMediaType()) &&
                        !empty($postData['image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_7']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_7_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_7' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_7']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_7'] = $postData['image_7']->getClientFileName();
                            $postData['image_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_7' . '.' . 'jpg';
                        } else {
                            unset($postData['image_7']);
                        }
                    } elseif (
                        !empty($postData['image_7']->getClientFileName()) &&
                        !empty($postData['image_7']->getClientMediaType()) &&
                        !empty($postData['image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_7']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_7' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_7']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_7'] = $postData['image_6']->getClientFileName();
                            $postData['image_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_7' . '.' . 'gif';
                        } else {
                            unset($postData['image_7']);
                        }
                    } else {
                        unset($postData['image_7']);
                    }
                } else {
                    unset($postData['image_7']);
                }

                // Image 8
                if (!empty($postData['image_8'])) {
                    if (
                        !empty($postData['image_8']->getClientFileName()) &&
                        !empty($postData['image_8']->getClientMediaType()) &&
                        !empty($postData['image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_8']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_8_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_8' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_8']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_8'] = $postData['image_8']->getClientFileName();
                            $postData['image_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_8' . '.' . 'jpg';
                        } else {
                            unset($postData['image_8']);
                        }
                    } elseif (
                        !empty($postData['image_8']->getClientFileName()) &&
                        !empty($postData['image_8']->getClientMediaType()) &&
                        !empty($postData['image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_8']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_8' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_8']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_8'] = $postData['image_6']->getClientFileName();
                            $postData['image_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_8' . '.' . 'gif';
                        } else {
                            unset($postData['image_8']);
                        }
                    } else {
                        unset($postData['image_8']);
                    }
                } else {
                    unset($postData['image_8']);
                }

                // Image 9
                if (!empty($postData['image_9'])) {
                    if (
                        !empty($postData['image_9']->getClientFileName()) &&
                        !empty($postData['image_9']->getClientMediaType()) &&
                        !empty($postData['image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_9']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_9_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_9' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_9']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_9'] = $postData['image_9']->getClientFileName();
                            $postData['image_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_9' . '.' . 'jpg';
                        } else {
                            unset($postData['image_9']);
                        }
                    } elseif (
                        !empty($postData['image_9']->getClientFileName()) &&
                        !empty($postData['image_9']->getClientMediaType()) &&
                        !empty($postData['image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_9']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_9' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_9']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_9'] = $postData['image_6']->getClientFileName();
                            $postData['image_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_9' . '.' . 'gif';
                        } else {
                            unset($postData['image_9']);
                        }
                    } else {
                        unset($postData['image_9']);
                    }
                } else {
                    unset($postData['image_9']);
                }
            }

            if (
                !empty($postData['video_1']) ||
                !empty($postData['video_2']) ||
                !empty($postData['video_3']) ||
                !empty($postData['video_4']) ||
                !empty($postData['video_5']) ||
                !empty($postData['video_6']) ||
                !empty($postData['video_7']) ||
                !empty($postData['video_8']) ||
                !empty($postData['video_9'])
            ) {
                // Video 1
                if (!empty($postData['video_1'])) {
                    if (
                        !empty($postData['video_1']->getClientFileName()) &&
                        !empty($postData['video_1']->getClientMediaType()) &&
                        !empty($postData['video_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_1']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_1' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_1']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_1'] = $postData['video_1']->getClientFileName();
                            $postData['video_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_1' . '.' . 'mp4';
                        } else {
                            unset($postData['video_1']);
                        }
                    } else {
                        unset($postData['video_1']);
                    }
                } else {
                    unset($postData['video_1']);
                }

                // Video 2
                if (!empty($postData['video_2'])) {
                    if (
                        !empty($postData['video_2']->getClientFileName()) &&
                        !empty($postData['video_2']->getClientMediaType()) &&
                        !empty($postData['video_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_2']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_2' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_2']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_2'] = $postData['video_2']->getClientFileName();
                            $postData['video_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_2' . '.' . 'mp4';
                        } else {
                            unset($postData['video_2']);
                        }
                    } else {
                        unset($postData['video_2']);
                    }
                } else {
                    unset($postData['video_2']);
                }

                // Video 3
                if (!empty($postData['video_3'])) {
                    if (
                        !empty($postData['video_3']->getClientFileName()) &&
                        !empty($postData['video_3']->getClientMediaType()) &&
                        !empty($postData['video_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_3']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_3' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_3']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_3'] = $postData['video_3']->getClientFileName();
                            $postData['video_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_3' . '.' . 'mp4';
                        } else {
                            unset($postData['video_3']);
                        }
                    } else {
                        unset($postData['video_3']);
                    }
                } else {
                    unset($postData['video_3']);
                }

                // Video 4
                if (!empty($postData['video_4'])) {
                    if (
                        !empty($postData['video_4']->getClientFileName()) &&
                        !empty($postData['video_4']->getClientMediaType()) &&
                        !empty($postData['video_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_4']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_4' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_4']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_4'] = $postData['video_4']->getClientFileName();
                            $postData['video_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_4' . '.' . 'mp4';
                        } else {
                            unset($postData['video_4']);
                        }
                    } else {
                        unset($postData['video_4']);
                    }
                } else {
                    unset($postData['video_4']);
                }
                
                // Video 5
                if (!empty($postData['video_5'])) {
                    if (
                        !empty($postData['video_5']->getClientFileName()) &&
                        !empty($postData['video_5']->getClientMediaType()) &&
                        !empty($postData['video_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_5']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_5' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_5']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_5'] = $postData['video_5']->getClientFileName();
                            $postData['video_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_5' . '.' . 'mp4';
                        } else {
                            unset($postData['video_5']);
                        }
                    } else {
                        unset($postData['video_5']);
                    }
                } else {
                    unset($postData['video_5']);
                }

                // Video 6
                if (!empty($postData['video_6'])) {
                    if (
                        !empty($postData['video_6']->getClientFileName()) &&
                        !empty($postData['video_6']->getClientMediaType()) &&
                        !empty($postData['video_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_6']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_6' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_6']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_6'] = $postData['video_6']->getClientFileName();
                            $postData['video_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_6' . '.' . 'mp4';
                        } else {
                            unset($postData['video_6']);
                        }
                    } else {
                        unset($postData['video_6']);
                    }
                } else {
                    unset($postData['video_6']);
                }
                
                // Video 7
                if (!empty($postData['video_7'])) {
                    if (
                        !empty($postData['video_7']->getClientFileName()) &&
                        !empty($postData['video_7']->getClientMediaType()) &&
                        !empty($postData['video_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_7']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_7' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_7']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_7'] = $postData['video_7']->getClientFileName();
                            $postData['video_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_7' . '.' . 'mp4';
                        } else {
                            unset($postData['video_7']);
                        }
                    } else {
                        unset($postData['video_7']);
                    }
                } else {
                    unset($postData['video_7']);
                }   

                // Video 8
                if (!empty($postData['video_8'])) {
                    if (
                        !empty($postData['video_8']->getClientFileName()) &&
                        !empty($postData['video_8']->getClientMediaType()) &&
                        !empty($postData['video_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_8']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_8' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_8']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_8'] = $postData['video_8']->getClientFileName();
                            $postData['video_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_8' . '.' . 'mp4';
                        } else {
                            unset($postData['video_8']);
                        }
                    } else {
                        unset($postData['video_8']);
                    }
                } else {
                    unset($postData['video_8']);
                }

                // Video 9
                if (!empty($postData['video_9'])) {
                    if (
                        !empty($postData['video_9']->getClientFileName()) &&
                        !empty($postData['video_9']->getClientMediaType()) &&
                        !empty($postData['video_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_9']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_9' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_9']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_9'] = $postData['video_9']->getClientFileName();
                            $postData['video_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_9' . '.' . 'mp4';
                        } else {
                            unset($postData['video_9']);
                        }
                    } else {
                        unset($postData['video_9']);
                    }
                } else {
                    unset($postData['video_9']);
                }  
            }

            if (
                !empty($postData['pdf_1']) ||
                !empty($postData['pdf_2']) ||
                !empty($postData['pdf_3']) ||
                !empty($postData['pdf_4']) ||
                !empty($postData['pdf_5']) ||
                !empty($postData['pdf_6']) ||
                !empty($postData['pdf_7']) ||
                !empty($postData['pdf_8']) ||
                !empty($postData['pdf_9'])
            ) {
                // Pdf 1
                if (!empty($postData['pdf_1'])) {
                    if (
                        !empty($postData['pdf_1']->getClientFileName()) &&
                        !empty($postData['pdf_1']->getClientMediaType()) &&
                        !empty($postData['pdf_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_1']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_1' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_1']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_1'] = $postData['pdf_1']->getClientFileName();
                            $postData['pdf_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_1' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_1']);
                        }
                    } else {
                        unset($postData['pdf_1']);
                    }
                } else {
                    unset($postData['pdf_1']);
                }

                // Pdf 2
                if (!empty($postData['pdf_2'])) {
                    if (
                        !empty($postData['pdf_2']->getClientFileName()) &&
                        !empty($postData['pdf_2']->getClientMediaType()) &&
                        !empty($postData['pdf_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_2']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_2' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_2']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_2'] = $postData['pdf_2']->getClientFileName();
                            $postData['pdf_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_2' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_2']);
                        }
                    } else {
                        unset($postData['pdf_2']);
                    }
                } else {
                    unset($postData['pdf_2']);
                }

                // Pdf 3
                if (!empty($postData['pdf_3'])) {
                    if (
                        !empty($postData['pdf_3']->getClientFileName()) &&
                        !empty($postData['pdf_3']->getClientMediaType()) &&
                        !empty($postData['pdf_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_3']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_3' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_3']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_3'] = $postData['pdf_3']->getClientFileName();
                            $postData['pdf_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_3' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_3']);
                        }
                    } else {
                        unset($postData['pdf_3']);
                    }
                } else {
                    unset($postData['pdf_3']);
                }

                // Pdf 4
                if (!empty($postData['pdf_4'])) {
                    if (
                        !empty($postData['pdf_4']->getClientFileName()) &&
                        !empty($postData['pdf_4']->getClientMediaType()) &&
                        !empty($postData['pdf_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_4']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_4' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_4']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_4'] = $postData['pdf_4']->getClientFileName();
                            $postData['pdf_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_4' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_4']);
                        }
                    } else {
                        unset($postData['pdf_4']);
                    }
                } else {
                    unset($postData['pdf_4']);
                }

                // Pdf 5
                if (!empty($postData['pdf_5'])) {
                    if (
                        !empty($postData['pdf_5']->getClientFileName()) &&
                        !empty($postData['pdf_5']->getClientMediaType()) &&
                        !empty($postData['pdf_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_5']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_5' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_5']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_5'] = $postData['pdf_5']->getClientFileName();
                            $postData['pdf_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_5' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_5']);
                        }
                    } else {
                        unset($postData['pdf_5']);
                    }
                } else {
                    unset($postData['pdf_5']);
                }

                // Pdf 6
                if (!empty($postData['pdf_6'])) {
                    if (
                        !empty($postData['pdf_6']->getClientFileName()) &&
                        !empty($postData['pdf_6']->getClientMediaType()) &&
                        !empty($postData['pdf_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_6']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_6' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_6']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_6'] = $postData['pdf_6']->getClientFileName();
                            $postData['pdf_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_6' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_6']);
                        }
                    } else {
                        unset($postData['pdf_6']);
                    }
                } else {
                    unset($postData['pdf_6']);
                }

                // Pdf 7
                if (!empty($postData['pdf_7'])) {
                    if (
                        !empty($postData['pdf_7']->getClientFileName()) &&
                        !empty($postData['pdf_7']->getClientMediaType()) &&
                        !empty($postData['pdf_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_7']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_7' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_7']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_7'] = $postData['pdf_7']->getClientFileName();
                            $postData['pdf_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_7' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_7']);
                        }
                    } else {
                        unset($postData['pdf_7']);
                    }
                } else {
                    unset($postData['pdf_7']);
                }
                
                // Pdf 8
                if (!empty($postData['pdf_8'])) {
                    if (
                        !empty($postData['pdf_8']->getClientFileName()) &&
                        !empty($postData['pdf_8']->getClientMediaType()) &&
                        !empty($postData['pdf_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_8']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_8' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_8']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_8'] = $postData['pdf_8']->getClientFileName();
                            $postData['pdf_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_8' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_8']);
                        }
                    } else {
                        unset($postData['pdf_8']);
                    }
                } else {
                    unset($postData['pdf_8']);
                }

                // Pdf 9
                if (!empty($postData['pdf_9'])) {
                    if (
                        !empty($postData['pdf_9']->getClientFileName()) &&
                        !empty($postData['pdf_9']->getClientMediaType()) &&
                        !empty($postData['pdf_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_9']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_9' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_9']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_9'] = $postData['pdf_9']->getClientFileName();
                            $postData['pdf_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_9' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_9']);
                        }
                    } else {
                        unset($postData['pdf_9']);
                    }
                } else {
                    unset($postData['pdf_9']);
                }
            }

            $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->patchEntity(
                $articleTypeAttributeChoice,
                Hash::merge($postData, ['uuid_id' => $uuidId])
            );
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeAdd', $this, [
                'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            ]);
            if ($this->ArticleTypeAttributeChoices->save($articleTypeAttributeChoice)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onAddSuccess', $this, [
                    'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute choice has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onAddFailure', $this, [
                    'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute choice could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleTypeAttributeChoices->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            )
            ->where(['ArticleTypeAttributes.type' => 'select']);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeAddRender', $this, [
            'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            'ArticleTypeAttributes' => $articleTypeAttributes,
        ]);

        $this->set(compact('articleTypeAttributeChoice', 'articleTypeAttributes'));
    }

    /**
     * Edit method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function edit(int $id = null)
    {
        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->get($id, contain: [
            'ArticleTypeAttributes' => function ($q) {
                $q->where(['ArticleTypeAttributes.type' => 'select']);
                return $q->orderBy(['ArticleTypeAttributes.alias' => 'ASC']);
            }
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            ini_set('post_max_size', '512M');
            ini_set('upload_max_filesize', '512M');

            $uuidId = Text::uuid();

            $postData = $this->getRequest()->getData();

            if (empty($postData['uuid_id'])) {
                $postData['uuid_id'] = $uuidId;
            } else {
                $uuidId = $postData['uuid_id'];
            }

            if (
                !empty($postData['image_1']) ||
                !empty($postData['image_2']) ||
                !empty($postData['image_3']) ||
                !empty($postData['image_4']) ||
                !empty($postData['image_5']) ||
                !empty($postData['image_6']) ||
                !empty($postData['image_7']) ||
                !empty($postData['image_8']) ||
                !empty($postData['image_9'])
            ) {
                // Image 1
                if (!empty($postData['image_1'])) {
                    if (
                        !empty($postData['image_1']->getClientFileName()) &&
                        !empty($postData['image_1']->getClientMediaType()) &&
                        !empty($postData['image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_1']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_1_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_1' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_1']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_1'] = $postData['image_1']->getClientFileName();
                            $postData['image_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_1' . '.' . 'jpg';
                        } else {
                            unset($postData['image_1']);
                        }
                    } elseif (
                        !empty($postData['image_1']->getClientFileName()) &&
                        !empty($postData['image_1']->getClientMediaType()) &&
                        !empty($postData['image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_1']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_1' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_1']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_1'] = $postData['image_1']->getClientFileName();
                            $postData['image_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_1' . '.' . 'gif';
                        } else {
                            unset($postData['image_1']);
                        }
                    } else {
                        unset($postData['image_1']);
                    }
                } else {
                    unset($postData['image_1']);
                }

                // Image 2
                if (!empty($postData['image_2'])) {
                    if (
                        !empty($postData['image_2']->getClientFileName()) &&
                        !empty($postData['image_2']->getClientMediaType()) &&
                        !empty($postData['image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_2']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_2_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_2' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_2']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_2'] = $postData['image_2']->getClientFileName();
                            $postData['image_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_2' . '.' . 'jpg';
                        } else {
                            unset($postData['image_2']);
                        }
                    } elseif (
                        !empty($postData['image_2']->getClientFileName()) &&
                        !empty($postData['image_2']->getClientMediaType()) &&
                        !empty($postData['image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_2']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_2' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_2']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_2'] = $postData['image_2']->getClientFileName();
                            $postData['image_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_2' . '.' . 'gif';
                        } else {
                            unset($postData['image_2']);
                        }
                    } else {
                        unset($postData['image_2']);
                    }
                } else {
                    unset($postData['image_2']);
                }

                // Image 3
                if (!empty($postData['image_3'])) {
                    if (
                        !empty($postData['image_3']->getClientFileName()) &&
                        !empty($postData['image_3']->getClientMediaType()) &&
                        !empty($postData['image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_3']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_3_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_3' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_3']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_3'] = $postData['image_3']->getClientFileName();
                            $postData['image_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_3' . '.' . 'jpg';
                        } else {
                            unset($postData['image_3']);
                        }
                    } elseif (
                        !empty($postData['image_3']->getClientFileName()) &&
                        !empty($postData['image_3']->getClientMediaType()) &&
                        !empty($postData['image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_3']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_3' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_3']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_3'] = $postData['image_3']->getClientFileName();
                            $postData['image_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_3' . '.' . 'gif';
                        } else {
                            unset($postData['image_3']);
                        }
                    } else {
                        unset($postData['image_3']);
                    }
                } else {
                    unset($postData['image_3']);
                }

                // Image 4
                if (!empty($postData['image_4'])) {
                    if (
                        !empty($postData['image_4']->getClientFileName()) &&
                        !empty($postData['image_4']->getClientMediaType()) &&
                        !empty($postData['image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_4']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_4_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_4' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_4']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_4'] = $postData['image_4']->getClientFileName();
                            $postData['image_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_4' . '.' . 'jpg';
                        } else {
                            unset($postData['image_4']);
                        }
                    } elseif (
                        !empty($postData['image_4']->getClientFileName()) &&
                        !empty($postData['image_4']->getClientMediaType()) &&
                        !empty($postData['image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_4']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_4' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_4']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_4'] = $postData['image_3']->getClientFileName();
                            $postData['image_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_4' . '.' . 'gif';
                        } else {
                            unset($postData['image_4']);
                        }
                    } else {
                        unset($postData['image_4']);
                    }
                } else {
                    unset($postData['image_4']);
                }

                // Image 5
                if (!empty($postData['image_5'])) {
                    if (
                        !empty($postData['image_5']->getClientFileName()) &&
                        !empty($postData['image_5']->getClientMediaType()) &&
                        !empty($postData['image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_5']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_5_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_5' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_5']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_5'] = $postData['image_5']->getClientFileName();
                            $postData['image_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_5' . '.' . 'jpg';
                        } else {
                            unset($postData['image_5']);
                        }
                    } elseif (
                        !empty($postData['image_5']->getClientFileName()) &&
                        !empty($postData['image_5']->getClientMediaType()) &&
                        !empty($postData['image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_5']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_5' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_5']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_5'] = $postData['image_5']->getClientFileName();
                            $postData['image_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_5' . '.' . 'gif';
                        } else {
                            unset($postData['image_5']);
                        }
                    } else {
                        unset($postData['image_5']);
                    }
                } else {
                    unset($postData['image_5']);
                }

                // Image 6
                if (!empty($postData['image_6'])) {
                    if (
                        !empty($postData['image_6']->getClientFileName()) &&
                        !empty($postData['image_6']->getClientMediaType()) &&
                        !empty($postData['image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_6']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_6_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_6' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_6']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_6'] = $postData['image_6']->getClientFileName();
                            $postData['image_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_6' . '.' . 'jpg';
                        } else {
                            unset($postData['image_6']);
                        }
                    } elseif (
                        !empty($postData['image_6']->getClientFileName()) &&
                        !empty($postData['image_6']->getClientMediaType()) &&
                        !empty($postData['image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_6']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_6' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_6']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_6'] = $postData['image_6']->getClientFileName();
                            $postData['image_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_6' . '.' . 'gif';
                        } else {
                            unset($postData['image_6']);
                        }
                    } else {
                        unset($postData['image_6']);
                    }
                } else {
                    unset($postData['image_6']);
                }

                // Image 7
                if (!empty($postData['image_7'])) {
                    if (
                        !empty($postData['image_7']->getClientFileName()) &&
                        !empty($postData['image_7']->getClientMediaType()) &&
                        !empty($postData['image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_7']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_7_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_7' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_7']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_7'] = $postData['image_7']->getClientFileName();
                            $postData['image_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_7' . '.' . 'jpg';
                        } else {
                            unset($postData['image_7']);
                        }
                    } elseif (
                        !empty($postData['image_7']->getClientFileName()) &&
                        !empty($postData['image_7']->getClientMediaType()) &&
                        !empty($postData['image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_7']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_7' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_7']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_7'] = $postData['image_6']->getClientFileName();
                            $postData['image_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_7' . '.' . 'gif';
                        } else {
                            unset($postData['image_7']);
                        }
                    } else {
                        unset($postData['image_7']);
                    }
                } else {
                    unset($postData['image_7']);
                }

                // Image 8
                if (!empty($postData['image_8'])) {
                    if (
                        !empty($postData['image_8']->getClientFileName()) &&
                        !empty($postData['image_8']->getClientMediaType()) &&
                        !empty($postData['image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_8']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_8_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_8' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_8']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_8'] = $postData['image_8']->getClientFileName();
                            $postData['image_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_8' . '.' . 'jpg';
                        } else {
                            unset($postData['image_8']);
                        }
                    } elseif (
                        !empty($postData['image_8']->getClientFileName()) &&
                        !empty($postData['image_8']->getClientMediaType()) &&
                        !empty($postData['image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_8']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_8' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_8']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_8'] = $postData['image_6']->getClientFileName();
                            $postData['image_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_8' . '.' . 'gif';
                        } else {
                            unset($postData['image_8']);
                        }
                    } else {
                        unset($postData['image_8']);
                    }
                } else {
                    unset($postData['image_8']);
                }

                // Image 9
                if (!empty($postData['image_9'])) {
                    if (
                        !empty($postData['image_9']->getClientFileName()) &&
                        !empty($postData['image_9']->getClientMediaType()) &&
                        !empty($postData['image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_9']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $rootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_9_' . '.' . 'jpg';
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_img_' . h($uuidId) . '_9' . '.' . 'jpg';
                        $imageContents = file_get_contents($postData['image_9']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($rootImageUri, $imageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($rootImageUri);
                            $ratio = $size[0] / $size[1];
                            $dst_y = 0;
                            $dst_x = 0;
                            if ($ratio > 1) {
                                $width = 800;
                                $height = 800 / $ratio;
                                $dst_y = (800 - $height) / 2;
                            } else {
                                $width = 800 * $ratio;
                                $height = 800;
                                $dst_x = (800 - $width) / 2;
                            }
                            $src = imagecreatefromstring(file_get_contents($rootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $imageUri)
                            ) {
                                unlink($rootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $image = $imageManager->read($imageUri);
                            $image->resize(800, 800);
                            $image->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $image->save($imageUri);

                            $postData['image_9'] = $postData['image_9']->getClientFileName();
                            $postData['image_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_img_' . h($uuidId) . '_9' . '.' . 'jpg';
                        } else {
                            unset($postData['image_9']);
                        }
                    } elseif (
                        !empty($postData['image_9']->getClientFileName()) &&
                        !empty($postData['image_9']->getClientMediaType()) &&
                        !empty($postData['image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['image_9']->getClientMediaType(), ['image/gif'])
                    ) {
                        $imageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_gif_' . h($uuidId) . '_9' . '.' . 'gif';
                        $imageContents = file_get_contents($postData['image_9']->getStream()->getMetadata('uri'));
                        if ($imageContents) {
                            file_put_contents($imageUri, $imageContents);

                            $imageGif = new Imagick($imageUri);
                            if ($imageGif->getImageFormat() == 'GIF') {
                                $imageGif = $imageGif->coalesceImages();
                                do {
                                    $imageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($imageGif->nextImage());

                                $imageGif = $imageGif->deconstructImages();
                            } else {
                                $imageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $imageGif->writeImages($imageUri, true);

                            $postData['image_9'] = $postData['image_6']->getClientFileName();
                            $postData['image_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_gif_' . h($uuidId) . '_9' . '.' . 'gif';
                        } else {
                            unset($postData['image_9']);
                        }
                    } else {
                        unset($postData['image_9']);
                    }
                } else {
                    unset($postData['image_9']);
                }
            }

            if (
                !empty($postData['video_1']) ||
                !empty($postData['video_2']) ||
                !empty($postData['video_3']) ||
                !empty($postData['video_4']) ||
                !empty($postData['video_5']) ||
                !empty($postData['video_6']) ||
                !empty($postData['video_7']) ||
                !empty($postData['video_8']) ||
                !empty($postData['video_9'])
            ) {
                // Video 1
                if (!empty($postData['video_1'])) {
                    if (
                        !empty($postData['video_1']->getClientFileName()) &&
                        !empty($postData['video_1']->getClientMediaType()) &&
                        !empty($postData['video_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_1']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_1' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_1']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_1'] = $postData['video_1']->getClientFileName();
                            $postData['video_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_1' . '.' . 'mp4';
                        } else {
                            unset($postData['video_1']);
                        }
                    } else {
                        unset($postData['video_1']);
                    }
                } else {
                    unset($postData['video_1']);
                }

                // Video 2
                if (!empty($postData['video_2'])) {
                    if (
                        !empty($postData['video_2']->getClientFileName()) &&
                        !empty($postData['video_2']->getClientMediaType()) &&
                        !empty($postData['video_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_2']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_2' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_2']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_2'] = $postData['video_2']->getClientFileName();
                            $postData['video_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_2' . '.' . 'mp4';
                        } else {
                            unset($postData['video_2']);
                        }
                    } else {
                        unset($postData['video_2']);
                    }
                } else {
                    unset($postData['video_2']);
                }

                // Video 3
                if (!empty($postData['video_3'])) {
                    if (
                        !empty($postData['video_3']->getClientFileName()) &&
                        !empty($postData['video_3']->getClientMediaType()) &&
                        !empty($postData['video_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_3']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_3' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_3']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_3'] = $postData['video_3']->getClientFileName();
                            $postData['video_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_3' . '.' . 'mp4';
                        } else {
                            unset($postData['video_3']);
                        }
                    } else {
                        unset($postData['video_3']);
                    }
                } else {
                    unset($postData['video_3']);
                }

                // Video 4
                if (!empty($postData['video_4'])) {
                    if (
                        !empty($postData['video_4']->getClientFileName()) &&
                        !empty($postData['video_4']->getClientMediaType()) &&
                        !empty($postData['video_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_4']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_4' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_4']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_4'] = $postData['video_4']->getClientFileName();
                            $postData['video_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_4' . '.' . 'mp4';
                        } else {
                            unset($postData['video_4']);
                        }
                    } else {
                        unset($postData['video_4']);
                    }
                } else {
                    unset($postData['video_4']);
                }
                
                // Video 5
                if (!empty($postData['video_5'])) {
                    if (
                        !empty($postData['video_5']->getClientFileName()) &&
                        !empty($postData['video_5']->getClientMediaType()) &&
                        !empty($postData['video_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_5']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_5' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_5']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_5'] = $postData['video_5']->getClientFileName();
                            $postData['video_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_5' . '.' . 'mp4';
                        } else {
                            unset($postData['video_5']);
                        }
                    } else {
                        unset($postData['video_5']);
                    }
                } else {
                    unset($postData['video_5']);
                }

                // Video 6
                if (!empty($postData['video_6'])) {
                    if (
                        !empty($postData['video_6']->getClientFileName()) &&
                        !empty($postData['video_6']->getClientMediaType()) &&
                        !empty($postData['video_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_6']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_6' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_6']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_6'] = $postData['video_6']->getClientFileName();
                            $postData['video_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_6' . '.' . 'mp4';
                        } else {
                            unset($postData['video_6']);
                        }
                    } else {
                        unset($postData['video_6']);
                    }
                } else {
                    unset($postData['video_6']);
                }
                
                // Video 7
                if (!empty($postData['video_7'])) {
                    if (
                        !empty($postData['video_7']->getClientFileName()) &&
                        !empty($postData['video_7']->getClientMediaType()) &&
                        !empty($postData['video_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_7']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_7' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_7']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_7'] = $postData['video_7']->getClientFileName();
                            $postData['video_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_7' . '.' . 'mp4';
                        } else {
                            unset($postData['video_7']);
                        }
                    } else {
                        unset($postData['video_7']);
                    }
                } else {
                    unset($postData['video_7']);
                }   

                // Video 8
                if (!empty($postData['video_8'])) {
                    if (
                        !empty($postData['video_8']->getClientFileName()) &&
                        !empty($postData['video_8']->getClientMediaType()) &&
                        !empty($postData['video_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_8']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_8' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_8']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_8'] = $postData['video_8']->getClientFileName();
                            $postData['video_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_8' . '.' . 'mp4';
                        } else {
                            unset($postData['video_8']);
                        }
                    } else {
                        unset($postData['video_8']);
                    }
                } else {
                    unset($postData['video_8']);
                }

                // Video 9
                if (!empty($postData['video_9'])) {
                    if (
                        !empty($postData['video_9']->getClientFileName()) &&
                        !empty($postData['video_9']->getClientMediaType()) &&
                        !empty($postData['video_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['video_9']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $videoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_vid_' . h($uuidId) . '_9' . '.' . 'mp4';
                        $videoContents = file_get_contents($postData['video_9']->getStream()->getMetadata('uri'));
                        if ($videoContents) {
                            file_put_contents($videoUri, $videoContents);

                            $postData['video_9'] = $postData['video_9']->getClientFileName();
                            $postData['video_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_vid_' . h($uuidId) . '_9' . '.' . 'mp4';
                        } else {
                            unset($postData['video_9']);
                        }
                    } else {
                        unset($postData['video_9']);
                    }
                } else {
                    unset($postData['video_9']);
                }  
            }

            if (
                !empty($postData['pdf_1']) ||
                !empty($postData['pdf_2']) ||
                !empty($postData['pdf_3']) ||
                !empty($postData['pdf_4']) ||
                !empty($postData['pdf_5']) ||
                !empty($postData['pdf_6']) ||
                !empty($postData['pdf_7']) ||
                !empty($postData['pdf_8']) ||
                !empty($postData['pdf_9'])
            ) {
                // Pdf 1
                if (!empty($postData['pdf_1'])) {
                    if (
                        !empty($postData['pdf_1']->getClientFileName()) &&
                        !empty($postData['pdf_1']->getClientMediaType()) &&
                        !empty($postData['pdf_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_1']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_1' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_1']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_1'] = $postData['pdf_1']->getClientFileName();
                            $postData['pdf_1_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_1' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_1']);
                        }
                    } else {
                        unset($postData['pdf_1']);
                    }
                } else {
                    unset($postData['pdf_1']);
                }

                // Pdf 2
                if (!empty($postData['pdf_2'])) {
                    if (
                        !empty($postData['pdf_2']->getClientFileName()) &&
                        !empty($postData['pdf_2']->getClientMediaType()) &&
                        !empty($postData['pdf_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_2']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_2' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_2']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_2'] = $postData['pdf_2']->getClientFileName();
                            $postData['pdf_2_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_2' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_2']);
                        }
                    } else {
                        unset($postData['pdf_2']);
                    }
                } else {
                    unset($postData['pdf_2']);
                }

                // Pdf 3
                if (!empty($postData['pdf_3'])) {
                    if (
                        !empty($postData['pdf_3']->getClientFileName()) &&
                        !empty($postData['pdf_3']->getClientMediaType()) &&
                        !empty($postData['pdf_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_3']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_3' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_3']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_3'] = $postData['pdf_3']->getClientFileName();
                            $postData['pdf_3_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_3' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_3']);
                        }
                    } else {
                        unset($postData['pdf_3']);
                    }
                } else {
                    unset($postData['pdf_3']);
                }

                // Pdf 4
                if (!empty($postData['pdf_4'])) {
                    if (
                        !empty($postData['pdf_4']->getClientFileName()) &&
                        !empty($postData['pdf_4']->getClientMediaType()) &&
                        !empty($postData['pdf_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_4']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_4' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_4']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_4'] = $postData['pdf_4']->getClientFileName();
                            $postData['pdf_4_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_4' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_4']);
                        }
                    } else {
                        unset($postData['pdf_4']);
                    }
                } else {
                    unset($postData['pdf_4']);
                }

                // Pdf 5
                if (!empty($postData['pdf_5'])) {
                    if (
                        !empty($postData['pdf_5']->getClientFileName()) &&
                        !empty($postData['pdf_5']->getClientMediaType()) &&
                        !empty($postData['pdf_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_5']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_5' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_5']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_5'] = $postData['pdf_5']->getClientFileName();
                            $postData['pdf_5_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_5' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_5']);
                        }
                    } else {
                        unset($postData['pdf_5']);
                    }
                } else {
                    unset($postData['pdf_5']);
                }

                // Pdf 6
                if (!empty($postData['pdf_6'])) {
                    if (
                        !empty($postData['pdf_6']->getClientFileName()) &&
                        !empty($postData['pdf_6']->getClientMediaType()) &&
                        !empty($postData['pdf_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_6']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_6' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_6']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_6'] = $postData['pdf_6']->getClientFileName();
                            $postData['pdf_6_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_6' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_6']);
                        }
                    } else {
                        unset($postData['pdf_6']);
                    }
                } else {
                    unset($postData['pdf_6']);
                }

                // Pdf 7
                if (!empty($postData['pdf_7'])) {
                    if (
                        !empty($postData['pdf_7']->getClientFileName()) &&
                        !empty($postData['pdf_7']->getClientMediaType()) &&
                        !empty($postData['pdf_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_7']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_7' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_7']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_7'] = $postData['pdf_7']->getClientFileName();
                            $postData['pdf_7_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_7' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_7']);
                        }
                    } else {
                        unset($postData['pdf_7']);
                    }
                } else {
                    unset($postData['pdf_7']);
                }
                
                // Pdf 8
                if (!empty($postData['pdf_8'])) {
                    if (
                        !empty($postData['pdf_8']->getClientFileName()) &&
                        !empty($postData['pdf_8']->getClientMediaType()) &&
                        !empty($postData['pdf_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_8']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_8' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_8']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_8'] = $postData['pdf_8']->getClientFileName();
                            $postData['pdf_8_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_8' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_8']);
                        }
                    } else {
                        unset($postData['pdf_8']);
                    }
                } else {
                    unset($postData['pdf_8']);
                }

                // Pdf 9
                if (!empty($postData['pdf_9'])) {
                    if (
                        !empty($postData['pdf_9']->getClientFileName()) &&
                        !empty($postData['pdf_9']->getClientMediaType()) &&
                        !empty($postData['pdf_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['pdf_9']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $pdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'admin' . DS . 'content' . DS . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_9' . '.' . 'pdf';
                        $pdfContents = file_get_contents($postData['pdf_9']->getStream()->getMetadata('uri'));
                        if ($pdfContents) {
                            file_put_contents($pdfUri, $pdfContents);

                            $postData['pdf_9'] = $postData['pdf_9']->getClientFileName();
                            $postData['pdf_9_file'] = '/yab_cms_ff/img/admin/content/' . 'article_type_attribute_choice_pdf_' . h($uuidId) . '_9' . '.' . 'pdf';
                        } else {
                            unset($postData['pdf_9']);
                        }
                    } else {
                        unset($postData['pdf_9']);
                    }
                } else {
                    unset($postData['pdf_9']);
                }
            }

            $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->patchEntity(
                $articleTypeAttributeChoice,
                $postData
            );
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeEdit', $this, [
                'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            ]);
            if ($this->ArticleTypeAttributeChoices->save($articleTypeAttributeChoice)) {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onEditSuccess', $this, [
                    'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute choice has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onEditFailure', $this, [
                    'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
                ]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The article type attribute choice could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $articleTypeAttributes = $this->ArticleTypeAttributeChoices->ArticleTypeAttributes
            ->find('list',
                order: ['ArticleTypeAttributes.alias' => 'ASC'],
                keyField: 'id',
                valueField: 'title_alias'
            )
            ->where(['ArticleTypeAttributes.type' => 'select']);

        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeEditRender', $this, [
            'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            'ArticleTypeAttributes' => $articleTypeAttributes,
        ]);

        $this->set(compact('articleTypeAttributeChoice', 'articleTypeAttributes'));
    }

    /**
     * Delete method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|null
     */
    public function delete(int $id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.beforeDelete', $this, [
            'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
        ]);
        if ($this->ArticleTypeAttributeChoices->delete($articleTypeAttributeChoice)) {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onDeleteSuccess', $this, [
                'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type attribute choice has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.ArticleTypeAttributeChoices.onDeleteFailure', $this, [
                'ArticleTypeAttributeChoice' => $articleTypeAttributeChoice,
            ]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The article type attribute choice could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Import method
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function import()
    {
        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $postData = $this->getRequest()->getData();

            // check if delimiter and enclosure are set
            ($postData['delimiter'] == ''? $del = ';': $del = $postData['delimiter']);
            ($postData['enclosure'] == ''? $encl = '"': $encl = $postData['enclosure']);

            if (in_array($postData['file']->getClientMediaType(), [
                'text/comma-separated-values',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext'
            ])) {
                $targetPath = TMP . $postData['file']->getClientFileName();
                $postData['file']->moveTo($targetPath);

                // Transform the csv cols and rows into a associative array based on the alias and rows
                $articleTypeAttributeChoices = $this->Global->csvToArray($targetPath, $del, $encl);
                if (is_array($articleTypeAttributeChoices) && !empty($articleTypeAttributeChoices)) {
                    unlink($targetPath);
                }

                // Check array keys
                if (!empty($articleTypeAttributeChoices[0])) {
                    $headerArray = $this->ArticleTypeAttributeChoices->tableColumns;
                    $headerArrayDiff = array_diff($headerArray, array_keys($articleTypeAttributeChoices[0]));
                    if (!empty($headerArrayDiff)) {
                        $this->Flash->set(
                            __d('yab_cms_ff', 'The uploaded CSV file is incorrectly structured. Please check the format or use a new CSV file.'),
                            ['element' => 'default', 'params' => ['class' => 'error']]
                        );
                        return $this->redirect(['action' => 'import']);
                    }
                } else {
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The uploaded CSV file is empty.'),
                        ['element' => 'default', 'params' => ['class' => 'error']]
                    );
                    return $this->redirect(['action' => 'import']);
                }

                // Log request
                if ($postData['log'] == 1) {
                    $this->Global->logRequest($this, 'csvImport', $this->defaultTable, $articleTypeAttributeChoices);
                }

                $i = 0; // imported
                $u = 0; // updated

                foreach ($articleTypeAttributeChoices as $articleTypeAttributeChoice) {
                    $dateTime = DateTime::now();
                    $existent = $this->ArticleTypeAttributeChoices
                        ->find('all')
                        ->where([
                            'article_type_attribute_id' => $articleTypeAttributeChoice['article_type_attribute_id'],
                            'value' => $articleTypeAttributeChoice['value'],
                        ])
                        ->first();

                    if (empty($existent)) {
                        $entity = $this->ArticleTypeAttributeChoices->newEmptyEntity(); // create
                        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->patchEntity(
                            $entity,
                            Hash::merge(
                                $articleTypeAttributeChoice,
                                [
                                    'created' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                    'modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                                ]
                            )
                        );
                        if ($this->ArticleTypeAttributeChoices->save($articleTypeAttributeChoice)) {
                            $i++;
                        }
                    } else {
                        $existent = $this->ArticleTypeAttributeChoices->get($existent->id); // update
                        $articleTypeAttributeChoice = $this->ArticleTypeAttributeChoices->patchEntity(
                            $existent,
                            Hash::merge(
                                $articleTypeAttributeChoice,
                                ['modified' => $dateTime->i18nFormat('yyyy-MM-dd HH:mm:ss')]
                            )
                        );
                        if ($this->ArticleTypeAttributeChoices->save($articleTypeAttributeChoice)) {
                            $u++;
                        }
                    }
                }
                $this->Flash->set(
                    __d(
                        'yab_cms_ff',
                        'You imported {imported} and updated {updated} records.',
                        ['imported' => $i, 'updated' => $u]
                    ),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'You can only send files with the csv extension csv. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'warning']]
                );
            }

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Export method
     *
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function export()
    {
        $articleTypeAttributeChoices = $this->ArticleTypeAttributeChoices->find('all');
        $delimiter = ';';
        $enclosure = '"';
        $header = $this->ArticleTypeAttributeChoices->tableColumns;
        $extract = $this->ArticleTypeAttributeChoices->tableColumns;

        $this->setResponse($this->getResponse()->withDownload(strtolower($this->defaultTable) . '.' . 'csv'));
        $this->set(compact('articleTypeAttributeChoices'));
        $this
            ->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'serialize' => 'articleTypeAttributeChoices',
                'delimiter' => $delimiter,
                'enclosure' => $enclosure,
                'header'    => $header,
                'extract'   => $extract,
            ]);
    }
}
