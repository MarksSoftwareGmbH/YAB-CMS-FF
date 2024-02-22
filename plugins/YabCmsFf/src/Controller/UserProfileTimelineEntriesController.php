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
namespace YabCmsFf\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use YabCmsFf\Controller\AppController;
use YabCmsFf\Utility\YabCmsFf;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Intervention\Image\ImageManager;
use Imagick;

/**
 * UserProfileTimelineEntries Controller
 *
 * @property \YabCmsFf\Model\Table\UserProfileTimelineEntriesTable $UserProfileTimelineEntries
 */
class UserProfileTimelineEntriesController extends AppController
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
            'user_id',
            'foreign_key',
            'entry_no',
            'entry_ref_no',
            'entry_date',
            'entry_type',
            'entry_title',
            'entry_subtitle',
            'entry_body',
            'view_counter',
            'created',
            'modified',
        ],
        'order' => ['entry_date' => 'DESC']
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

        if ($this->request->getParam('action') === 'add') {
            $this->FormProtection->setConfig('unlockedActions', ['add']);
        }
    }

    /**
     * Index method
     *
     * @param string|null $foreignKey
     *
     * @return \Cake\Http\Response|null
     */
    public function index(string $foreignKey = null)
    {
        if (!empty($foreignKey)) {
            $UserProfiles = TableRegistry::getTableLocator()->get('YabCmsFf.UserProfiles');
            $userProfile = $UserProfiles
                ->find()
                ->where(['UserProfiles.foreign_key' => $foreignKey])
                ->first();
            if (!empty($userProfile->id)) {
                $query = $this->UserProfileTimelineEntries
                    ->find('search', search: $this->getRequest()->getQueryParams())
                    ->where(['UserProfileTimelineEntries.user_id' => $userProfile->user_id]);

                $userProfileTimelineEntriesAlt = $this->UserProfileTimelineEntries
                    ->find('all')
                    ->select(['id', 'entry_no', 'entry_title', 'entry_date', 'user_id'])
                    ->where(['UserProfileTimelineEntries.user_id' => $userProfile->user_id])
                    ->orderBy(['UserProfileTimelineEntries.created' => 'DESC'])
                    ->toArray();

                $userProfileTimelineEntriesList = [];
                if (!empty($userProfileTimelineEntriesAlt)) {
                    foreach ($userProfileTimelineEntriesAlt as $userProfileTimelineEntryAlt) {
                        $userProfileTimelineEntriesList[$userProfileTimelineEntryAlt->entry_no] =
                            $userProfileTimelineEntryAlt->entry_no . ' ' . '-' . ' '
                            . htmlspecialchars_decode($userProfileTimelineEntryAlt->entry_title) . ' '
                            . '(' . $userProfileTimelineEntryAlt->entry_date->format('M d, Y H:i:s') . ')';
                    }
                }

                YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.beforeIndexRender', $this, ['Query' => $query]);

                $this->set('userProfile', $userProfile);
                $this->set('userProfileTimelineEntries', $this->paginate($query));
                $this->set('userProfileTimelineEntriesList', $userProfileTimelineEntriesList);
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The user profile timeline entries could not be found. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect($this->referer());
        }
    }

    /**
     * View method
     *
     * @param string|null $foreignKey
     *
     * @return \Cake\Http\Response|void|null
     */
    public function view(string $foreignKey = null)
    {
        if (!empty($foreignKey)) {
            $userProfileTimelineEntryReference = [];

            $userProfileTimelineEntry = $this->UserProfileTimelineEntries
                ->find()
                ->where(['UserProfileTimelineEntries.foreign_key' => $foreignKey])
                ->contain('Users.UserProfiles')
                ->first();
            if (!empty($userProfileTimelineEntry->entry_ref_no)) {
                $userProfileTimelineEntryReference = $this->UserProfileTimelineEntries
                    ->find()
                    ->where(['UserProfileTimelineEntries.entry_no' => $userProfileTimelineEntry->entry_ref_no])
                    ->first();
            }

            if (!empty($userProfileTimelineEntry->id)) {

                $userProfileTimelineEntriesAlt = $this->UserProfileTimelineEntries
                    ->find('all')
                    ->select(['id', 'entry_no', 'entry_title', 'entry_date', 'user_id'])
                    ->where(['UserProfileTimelineEntries.user_id' => $userProfileTimelineEntry->user->id])
                    ->orderBy(['UserProfileTimelineEntries.created' => 'DESC'])
                    ->toArray();

                $userProfileTimelineEntriesList = [];
                if (!empty($userProfileTimelineEntriesAlt)) {
                    foreach ($userProfileTimelineEntriesAlt as $userProfileTimelineEntryAlt) {
                        $userProfileTimelineEntriesList[$userProfileTimelineEntryAlt->entry_no] =
                            $userProfileTimelineEntryAlt->entry_no . ' ' . '-' . ' '
                            . htmlspecialchars_decode($userProfileTimelineEntryAlt->entry_title) . ' '
                            . '(' . $userProfileTimelineEntryAlt->entry_date->format('M d, Y H:i:s') . ')';
                    }
                }

                $userProfileTimelineEntryCountQuery = $this->UserProfileTimelineEntries->updateQuery();
                $userProfileTimelineEntryCountQuery
                    ->update('user_profile_timeline_entries')
                    ->set(['view_counter' => $userProfileTimelineEntry->view_counter + 1])
                    ->where(['id' => $userProfileTimelineEntry->id])
                    ->execute();

                $this->set(compact(
                    'userProfileTimelineEntry',
                    'userProfileTimelineEntryReference',
                    'userProfileTimelineEntriesList'
                ));
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'The timeline entry could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect($this->referer());
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The timeline entry could not be found. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect($this->referer());
        }
    }

    /**
     * View by slug method
     *
     * @param string $slug
     * @param string $foreignKey
     *
     * @return \Cake\Http\Response|void|null
     */
    public function viewBySlug(string $slug, string $foreignKey)
    {
        if (!empty($foreignKey)) {
            $userProfileTimelineEntryReference = [];

            $userProfileTimelineEntry = $this->UserProfileTimelineEntries
                ->find()
                ->where(['UserProfileTimelineEntries.foreign_key' => $foreignKey])
                ->contain('Users.UserProfiles')
                ->first();
            if (!empty($userProfileTimelineEntry->entry_ref_no)) {
                $userProfileTimelineEntryReference = $this->UserProfileTimelineEntries
                    ->find()
                    ->where(['UserProfileTimelineEntries.entry_no' => $userProfileTimelineEntry->entry_ref_no])
                    ->first();
            }

            if (!empty($userProfileTimelineEntry->id)) {

                $userProfileTimelineEntriesAlt = $this->UserProfileTimelineEntries
                    ->find('all')
                    ->select(['id', 'entry_no', 'entry_title', 'entry_date', 'user_id'])
                    ->where(['UserProfileTimelineEntries.user_id' => $userProfileTimelineEntry->user->id])
                    ->orderBy(['UserProfileTimelineEntries.created' => 'DESC'])
                    ->toArray();

                $userProfileTimelineEntriesList = [];
                if (!empty($userProfileTimelineEntriesAlt)) {
                    foreach ($userProfileTimelineEntriesAlt as $userProfileTimelineEntryAlt) {
                        $userProfileTimelineEntriesList[$userProfileTimelineEntryAlt->entry_no] =
                            $userProfileTimelineEntryAlt->entry_no . ' ' . '-' . ' '
                            . htmlspecialchars_decode($userProfileTimelineEntryAlt->entry_title) . ' '
                            . '(' . $userProfileTimelineEntryAlt->entry_date->format('M d, Y H:i:s') . ')';
                    }
                }

                $userProfileTimelineEntryCountQuery = $this->UserProfileTimelineEntries->updateQuery();
                $userProfileTimelineEntryCountQuery
                    ->update('user_profile_timeline_entries')
                    ->set(['view_counter' => $userProfileTimelineEntry->view_counter + 1])
                    ->where(['id' => $userProfileTimelineEntry->id])
                    ->execute();

                $this->set(compact(
                    'userProfileTimelineEntry',
                    'userProfileTimelineEntryReference',
                    'userProfileTimelineEntriesList'
                ));

                $this->render('view');
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'The timeline entry could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect($this->referer());
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The timeline entry could not be found. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );

            return $this->redirect($this->referer());
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void|null
     */
    public function add()
    {
        $session = $this->getRequest()->getSession();

        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            ini_set('post_max_size', '512M');
            ini_set('upload_max_filesize', '512M');

            $userId = $session->check('Auth.User.id')? $session->read('Auth.User.id'): null;

            $foreignKey = Text::uuid();

            $postData = $this->getRequest()->getData();

            if ($postData['entry_body'] === '<p><br></p>') { unset($postData['entry_body']); }

            if (empty($postData['entry_link_1'])) { unset($postData['entry_link_1']); }
            if (empty($postData['entry_link_2'])) { unset($postData['entry_link_2']); }
            if (empty($postData['entry_link_3'])) { unset($postData['entry_link_3']); }
            if (empty($postData['entry_link_4'])) { unset($postData['entry_link_4']); }
            if (empty($postData['entry_link_5'])) { unset($postData['entry_link_5']); }
            if (empty($postData['entry_link_6'])) { unset($postData['entry_link_6']); }
            if (empty($postData['entry_link_7'])) { unset($postData['entry_link_7']); }
            if (empty($postData['entry_link_8'])) { unset($postData['entry_link_8']); }
            if (empty($postData['entry_link_9'])) { unset($postData['entry_link_9']); }

            if (
                !empty($postData['entry_image_1']) ||
                !empty($postData['entry_image_2']) ||
                !empty($postData['entry_image_3']) ||
                !empty($postData['entry_image_4']) ||
                !empty($postData['entry_image_5']) ||
                !empty($postData['entry_image_6']) ||
                !empty($postData['entry_image_7']) ||
                !empty($postData['entry_image_8']) ||
                !empty($postData['entry_image_9'])
            ) {

                // Image 1
                if (!empty($postData['entry_image_1'])) {
                    if (
                        !empty($postData['entry_image_1']->getClientFileName()) &&
                        !empty($postData['entry_image_1']->getClientMediaType()) &&
                        !empty($postData['entry_image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_1']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_1_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_1' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_1']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_1'] = $postData['entry_image_1']->getClientFileName();
                            $postData['entry_image_1_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_1' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_1']);
                        }
                    } elseif (
                        !empty($postData['entry_image_1']->getClientFileName()) &&
                        !empty($postData['entry_image_1']->getClientMediaType()) &&
                        !empty($postData['entry_image_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_1']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_1' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_1']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_1'] = $postData['entry_image_1']->getClientFileName();
                            $postData['entry_image_1_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_1' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_1']);
                        }
                    } else {
                        unset($postData['entry_image_1']);
                    }
                } else {
                    unset($postData['entry_image_1']);
                }

                // Image 2
                if (!empty($postData['entry_image_2'])) {
                    if (
                        !empty($postData['entry_image_2']->getClientFileName()) &&
                        !empty($postData['entry_image_2']->getClientMediaType()) &&
                        !empty($postData['entry_image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_2']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_2_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_2' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_2']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_2'] = $postData['entry_image_2']->getClientFileName();
                            $postData['entry_image_2_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_2' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_2']);
                        }
                    } elseif (
                        !empty($postData['entry_image_2']->getClientFileName()) &&
                        !empty($postData['entry_image_2']->getClientMediaType()) &&
                        !empty($postData['entry_image_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_2']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_2' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_2']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_2'] = $postData['entry_image_2']->getClientFileName();
                            $postData['entry_image_2_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_2' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_2']);
                        }
                    } else {
                        unset($postData['entry_image_2']);
                    }
                } else {
                    unset($postData['entry_image_2']);
                }

                // Image 3
                if (!empty($postData['entry_image_3'])) {
                    if (
                        !empty($postData['entry_image_3']->getClientFileName()) &&
                        !empty($postData['entry_image_3']->getClientMediaType()) &&
                        !empty($postData['entry_image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_3']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_3_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_3' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_3']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_3'] = $postData['entry_image_3']->getClientFileName();
                            $postData['entry_image_3_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_3' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_3']);
                        }
                    } elseif (
                        !empty($postData['entry_image_3']->getClientFileName()) &&
                        !empty($postData['entry_image_3']->getClientMediaType()) &&
                        !empty($postData['entry_image_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_3']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_3' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_3']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_3'] = $postData['entry_image_3']->getClientFileName();
                            $postData['entry_image_3_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_3' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_3']);
                        }
                    } else {
                        unset($postData['entry_image_3']);
                    }
                } else {
                    unset($postData['entry_image_3']);
                }

                // Image 4
                if (!empty($postData['entry_image_4'])) {
                    if (
                        !empty($postData['entry_image_4']->getClientFileName()) &&
                        !empty($postData['entry_image_4']->getClientMediaType()) &&
                        !empty($postData['entry_image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_4']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_4_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_4' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_4']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_4'] = $postData['entry_image_4']->getClientFileName();
                            $postData['entry_image_4_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_4' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_4']);
                        }
                    } elseif (
                        !empty($postData['entry_image_4']->getClientFileName()) &&
                        !empty($postData['entry_image_4']->getClientMediaType()) &&
                        !empty($postData['entry_image_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_4']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_4' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_4']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_4'] = $postData['entry_image_4']->getClientFileName();
                            $postData['entry_image_4_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_4' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_4']);
                        }
                    } else {
                        unset($postData['entry_image_4']);
                    }
                } else {
                    unset($postData['entry_image_4']);
                }

                // Image 5
                if (!empty($postData['entry_image_5'])) {
                    if (
                        !empty($postData['entry_image_5']->getClientFileName()) &&
                        !empty($postData['entry_image_5']->getClientMediaType()) &&
                        !empty($postData['entry_image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_5']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_5_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_5' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_5']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_5'] = $postData['entry_image_5']->getClientFileName();
                            $postData['entry_image_5_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_5' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_5']);
                        }
                    } elseif (
                        !empty($postData['entry_image_5']->getClientFileName()) &&
                        !empty($postData['entry_image_5']->getClientMediaType()) &&
                        !empty($postData['entry_image_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_5']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_5' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_5']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_5'] = $postData['entry_image_5']->getClientFileName();
                            $postData['entry_image_5_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_5' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_5']);
                        }
                    } else {
                        unset($postData['entry_image_5']);
                    }
                } else {
                    unset($postData['entry_image_5']);
                }

                // Image 6
                if (!empty($postData['entry_image_6'])) {
                    if (
                        !empty($postData['entry_image_6']->getClientFileName()) &&
                        !empty($postData['entry_image_6']->getClientMediaType()) &&
                        !empty($postData['entry_image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_6']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_6_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_6' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_6']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_6'] = $postData['entry_image_6']->getClientFileName();
                            $postData['entry_image_6_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_6' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_6']);
                        }
                    } elseif (
                        !empty($postData['entry_image_6']->getClientFileName()) &&
                        !empty($postData['entry_image_6']->getClientMediaType()) &&
                        !empty($postData['entry_image_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_6']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_6' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_6']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_6'] = $postData['entry_image_6']->getClientFileName();
                            $postData['entry_image_6_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_6' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_6']);
                        }
                    } else {
                        unset($postData['entry_image_6']);
                    }
                } else {
                    unset($postData['entry_image_6']);
                }

                // Image 7
                if (!empty($postData['entry_image_7'])) {
                    if (
                        !empty($postData['entry_image_7']->getClientFileName()) &&
                        !empty($postData['entry_image_7']->getClientMediaType()) &&
                        !empty($postData['entry_image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_7']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_7_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_7' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_7']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_7'] = $postData['entry_image_7']->getClientFileName();
                            $postData['entry_image_7_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_7' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_7']);
                        }
                    } elseif (
                        !empty($postData['entry_image_7']->getClientFileName()) &&
                        !empty($postData['entry_image_7']->getClientMediaType()) &&
                        !empty($postData['entry_image_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_7']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_7' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_7']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_7'] = $postData['entry_image_7']->getClientFileName();
                            $postData['entry_image_7_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_7' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_7']);
                        }
                    } else {
                        unset($postData['entry_image_7']);
                    }
                } else {
                    unset($postData['entry_image_7']);
                }

                // Image 8
                if (!empty($postData['entry_image_8'])) {
                    if (
                        !empty($postData['entry_image_8']->getClientFileName()) &&
                        !empty($postData['entry_image_8']->getClientMediaType()) &&
                        !empty($postData['entry_image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_8']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_8_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_8' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_8']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_8'] = $postData['entry_image_8']->getClientFileName();
                            $postData['entry_image_8_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_8' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_8']);
                        }
                    } elseif (
                        !empty($postData['entry_image_8']->getClientFileName()) &&
                        !empty($postData['entry_image_8']->getClientMediaType()) &&
                        !empty($postData['entry_image_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_8']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_8' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_8']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_8'] = $postData['entry_image_8']->getClientFileName();
                            $postData['entry_image_8_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_8' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_8']);
                        }
                    } else {
                        unset($postData['entry_image_8']);
                    }
                } else {
                    unset($postData['entry_image_8']);
                }

                // Image 9
                if (!empty($postData['entry_image_9'])) {
                    if (
                        !empty($postData['entry_image_9']->getClientFileName()) &&
                        !empty($postData['entry_image_9']->getClientMediaType()) &&
                        !empty($postData['entry_image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_9']->getClientMediaType(), [
                            'image/jpeg',
                            'image/jpg',
                        ])
                    ) {
                        $entryRootImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_9_' . '.' . 'jpg';
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($foreignKey) . '_9' . '.' . 'jpg';
                        $entryImageContents = file_get_contents($postData['entry_image_9']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryRootImageUri, $entryImageContents);

                            $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                            $size = getimagesize($entryRootImageUri);
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
                            $src = imagecreatefromstring(file_get_contents($entryRootImageUri));
                            $dst = imagecreatetruecolor(intval($width), intval($height));
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                            $blankImage = imagecreatefromjpeg($blankImageUri);
                            if (
                                imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                                imagejpeg($blankImage, $entryImageUri)
                            ) {
                                unlink($entryRootImageUri);
                            }

                            $imageManager = ImageManager::imagick();
                            $entryImage = $imageManager->read($entryImageUri);
                            $entryImage->resize(800, 800);
                            $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                            $entryImage->save($entryImageUri);

                            $postData['entry_image_9'] = $postData['entry_image_9']->getClientFileName();
                            $postData['entry_image_9_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($foreignKey) . '_9' . '.' . 'jpg';
                        } else {
                            unset($postData['entry_image_9']);
                        }
                    } elseif (
                        !empty($postData['entry_image_9']->getClientFileName()) &&
                        !empty($postData['entry_image_9']->getClientMediaType()) &&
                        !empty($postData['entry_image_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_image_9']->getClientMediaType(), ['image/gif'])
                    ) {
                        $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($foreignKey) . '_9' . '.' . 'gif';
                        $entryImageContents = file_get_contents($postData['entry_image_9']->getStream()->getMetadata('uri'));
                        if ($entryImageContents) {
                            file_put_contents($entryImageUri, $entryImageContents);

                            $entryImageGif = new Imagick($entryImageUri);
                            if ($entryImageGif->getImageFormat() == 'GIF') {
                                $entryImageGif = $entryImageGif->coalesceImages();
                                do {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                } while ($entryImageGif->nextImage());

                                $entryImageGif = $entryImageGif->deconstructImages();
                            } else {
                                $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            $entryImageGif->writeImages($entryImageUri, true);

                            $postData['entry_image_9'] = $postData['entry_image_9']->getClientFileName();
                            $postData['entry_image_9_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($foreignKey) . '_9' . '.' . 'gif';
                        } else {
                            unset($postData['entry_image_9']);
                        }
                    } else {
                        unset($postData['entry_image_9']);
                    }
                } else {
                    unset($postData['entry_image_9']);
                }
            }

            if (
                !empty($postData['entry_video_1']) ||
                !empty($postData['entry_video_2']) ||
                !empty($postData['entry_video_3']) ||
                !empty($postData['entry_video_4']) ||
                !empty($postData['entry_video_5']) ||
                !empty($postData['entry_video_6']) ||
                !empty($postData['entry_video_7']) ||
                !empty($postData['entry_video_8']) ||
                !empty($postData['entry_video_9'])
            ) {
                // Video 1
                if (!empty($postData['entry_video_1'])) {
                    if (
                        !empty($postData['entry_video_1']->getClientFileName()) &&
                        !empty($postData['entry_video_1']->getClientMediaType()) &&
                        !empty($postData['entry_video_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_1']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_1' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_1']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_1'] = $postData['entry_video_1']->getClientFileName();
                            $postData['entry_video_1_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_1' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_1']);
                        }
                    } else {
                        unset($postData['entry_video_1']);
                    }
                } else {
                    unset($postData['entry_video_1']);
                }

                // Video 2
                if (!empty($postData['entry_video_2'])) {
                    if (
                        !empty($postData['entry_video_2']->getClientFileName()) &&
                        !empty($postData['entry_video_2']->getClientMediaType()) &&
                        !empty($postData['entry_video_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_2']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_2' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_2']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_2'] = $postData['entry_video_2']->getClientFileName();
                            $postData['entry_video_2_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_2' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_2']);
                        }
                    } else {
                        unset($postData['entry_video_2']);
                    }
                } else {
                    unset($postData['entry_video_2']);
                }

                // Video 3
                if (!empty($postData['entry_video_3'])) {
                    if (
                        !empty($postData['entry_video_3']->getClientFileName()) &&
                        !empty($postData['entry_video_3']->getClientMediaType()) &&
                        !empty($postData['entry_video_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_3']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_3' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_3']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_3'] = $postData['entry_video_3']->getClientFileName();
                            $postData['entry_video_3_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_3' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_3']);
                        }
                    } else {
                        unset($postData['entry_video_3']);
                    }
                } else {
                    unset($postData['entry_video_3']);
                }

                // Video 4
                if (!empty($postData['entry_video_4'])) {
                    if (
                        !empty($postData['entry_video_4']->getClientFileName()) &&
                        !empty($postData['entry_video_4']->getClientMediaType()) &&
                        !empty($postData['entry_video_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_4']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_4' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_4']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_4'] = $postData['entry_video_4']->getClientFileName();
                            $postData['entry_video_4_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_4' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_4']);
                        }
                    } else {
                        unset($postData['entry_video_4']);
                    }
                } else {
                    unset($postData['entry_video_4']);
                }

                // Video 5
                if (!empty($postData['entry_video_5'])) {
                    if (
                        !empty($postData['entry_video_5']->getClientFileName()) &&
                        !empty($postData['entry_video_5']->getClientMediaType()) &&
                        !empty($postData['entry_video_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_5']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_5' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_5']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_5'] = $postData['entry_video_5']->getClientFileName();
                            $postData['entry_video_5_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_5' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_5']);
                        }
                    } else {
                        unset($postData['entry_video_5']);
                    }
                } else {
                    unset($postData['entry_video_5']);
                }

                // Video 6
                if (!empty($postData['entry_video_6'])) {
                    if (
                        !empty($postData['entry_video_6']->getClientFileName()) &&
                        !empty($postData['entry_video_6']->getClientMediaType()) &&
                        !empty($postData['entry_video_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_6']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_6' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_6']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_6'] = $postData['entry_video_6']->getClientFileName();
                            $postData['entry_video_6_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_6' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_6']);
                        }
                    } else {
                        unset($postData['entry_video_6']);
                    }
                } else {
                    unset($postData['entry_video_6']);
                }

                // Video 7
                if (!empty($postData['entry_video_7'])) {
                    if (
                        !empty($postData['entry_video_7']->getClientFileName()) &&
                        !empty($postData['entry_video_7']->getClientMediaType()) &&
                        !empty($postData['entry_video_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_7']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_7' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_7']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_7'] = $postData['entry_video_7']->getClientFileName();
                            $postData['entry_video_7_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_7' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_7']);
                        }
                    } else {
                        unset($postData['entry_video_7']);
                    }
                } else {
                    unset($postData['entry_video_7']);
                }

                // Video 8
                if (!empty($postData['entry_video_8'])) {
                    if (
                        !empty($postData['entry_video_8']->getClientFileName()) &&
                        !empty($postData['entry_video_8']->getClientMediaType()) &&
                        !empty($postData['entry_video_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_8']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_8' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_8']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_8'] = $postData['entry_video_8']->getClientFileName();
                            $postData['entry_video_8_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_8' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_8']);
                        }
                    } else {
                        unset($postData['entry_video_8']);
                    }
                } else {
                    unset($postData['entry_video_8']);
                }

                // Video 9
                if (!empty($postData['entry_video_9'])) {
                    if (
                        !empty($postData['entry_video_9']->getClientFileName()) &&
                        !empty($postData['entry_video_9']->getClientMediaType()) &&
                        !empty($postData['entry_video_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_video_9']->getClientMediaType(), ['video/mp4'])
                    ) {
                        $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($foreignKey) . '_9' . '.' . 'mp4';
                        $entryVideoContents = file_get_contents($postData['entry_video_9']->getStream()->getMetadata('uri'));
                        if ($entryVideoContents) {
                            file_put_contents($entryVideoUri, $entryVideoContents);

                            $postData['entry_video_9'] = $postData['entry_video_9']->getClientFileName();
                            $postData['entry_video_9_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($foreignKey) . '_9' . '.' . 'mp4';
                        } else {
                            unset($postData['entry_video_9']);
                        }
                    } else {
                        unset($postData['entry_video_9']);
                    }
                } else {
                    unset($postData['entry_video_9']);
                }
            }

            if (
                !empty($postData['entry_pdf_1']) ||
                !empty($postData['entry_pdf_2']) ||
                !empty($postData['entry_pdf_3']) ||
                !empty($postData['entry_pdf_4']) ||
                !empty($postData['entry_pdf_5']) ||
                !empty($postData['entry_pdf_6']) ||
                !empty($postData['entry_pdf_7']) ||
                !empty($postData['entry_pdf_8']) ||
                !empty($postData['entry_pdf_9'])
            ) {
                // Pdf 1
                if (!empty($postData['entry_pdf_1'])) {
                    if (
                        !empty($postData['entry_pdf_1']->getClientFileName()) &&
                        !empty($postData['entry_pdf_1']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_1']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_1']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_1' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_1']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_1'] = $postData['entry_pdf_1']->getClientFileName();
                            $postData['entry_pdf_1_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_1' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_1']);
                        }
                    } else {
                        unset($postData['entry_pdf_1']);
                    }
                } else {
                    unset($postData['entry_pdf_1']);
                }

                // Pdf 2
                if (!empty($postData['entry_pdf_2'])) {
                    if (
                        !empty($postData['entry_pdf_2']) &&
                        !empty($postData['entry_pdf_2']->getClientFileName()) &&
                        !empty($postData['entry_pdf_2']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_2']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_2']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_2' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_2']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_2'] = $postData['entry_pdf_2']->getClientFileName();
                            $postData['entry_pdf_2_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_2' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_2']);
                        }
                    } else {
                        unset($postData['entry_pdf_2']);
                    }
                } else {
                    unset($postData['entry_pdf_2']);
                }

                // Pdf 3
                if (!empty($postData['entry_pdf_3'])) {
                    if (
                        !empty($postData['entry_pdf_3']->getClientFileName()) &&
                        !empty($postData['entry_pdf_3']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_3']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_3']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_3' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_3']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_3'] = $postData['entry_pdf_3']->getClientFileName();
                            $postData['entry_pdf_3_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_3' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_3']);
                        }
                    } else {
                        unset($postData['entry_pdf_3']);
                    }
                } else {
                    unset($postData['entry_pdf_3']);
                }

                // Pdf 4
                if (!empty($postData['entry_pdf_4'])) {
                    if (
                        !empty($postData['entry_pdf_4']->getClientFileName()) &&
                        !empty($postData['entry_pdf_4']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_4']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_4']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_4' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_4']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_4'] = $postData['entry_pdf_4']->getClientFileName();
                            $postData['entry_pdf_4_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_4' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_4']);
                        }
                    } else {
                        unset($postData['entry_pdf_4']);
                    }
                } else {
                    unset($postData['entry_pdf_4']);
                }

                // Pdf 5
                if (!empty($postData['entry_pdf_5'])) {
                    if (
                        !empty($postData['entry_pdf_5']->getClientFileName()) &&
                        !empty($postData['entry_pdf_5']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_5']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_5']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_5' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_5']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_5'] = $postData['entry_pdf_5']->getClientFileName();
                            $postData['entry_pdf_5_file'] = '/pdf/' . h($foreignKey) . '_5' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_5']);
                        }
                    } else {
                        unset($postData['entry_pdf_5']);
                    }
                } else {
                    unset($postData['entry_pdf_5']);
                }

                // Pdf 6
                if (!empty($postData['entry_pdf_6'])) {
                    if (
                        !empty($postData['entry_pdf_6']->getClientFileName()) &&
                        !empty($postData['entry_pdf_6']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_6']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_6']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_6' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_6']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_6'] = $postData['entry_pdf_6']->getClientFileName();
                            $postData['entry_pdf_6_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_6' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_6']);
                        }
                    } else {
                        unset($postData['entry_pdf_6']);
                    }
                } else {
                    unset($postData['entry_pdf_6']);
                }

                // Pdf 7
                if (!empty($postData['entry_pdf_7'])) {
                    if (
                        !empty($postData['entry_pdf_7']->getClientFileName()) &&
                        !empty($postData['entry_pdf_7']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_7']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_7']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_7' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_7']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_7'] = $postData['entry_pdf_7']->getClientFileName();
                            $postData['entry_pdf_7_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_7' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_7']);
                        }
                    } else {
                        unset($postData['entry_pdf_7']);
                    }
                } else {
                    unset($postData['entry_pdf_7']);
                }

                // Pdf 8
                if (!empty($postData['entry_pdf_8'])) {
                    if (
                        !empty($postData['entry_pdf_8']->getClientFileName()) &&
                        !empty($postData['entry_pdf_8']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_8']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_8']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_8' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_8']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_8'] = $postData['entry_pdf_8']->getClientFileName();
                            $postData['entry_pdf_8_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_8' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_8']);
                        }
                    } else {
                        unset($postData['entry_pdf_8']);
                    }
                } else {
                    unset($postData['entry_pdf_8']);
                }

                // Pdf 9
                if (!empty($postData['entry_pdf_9'])) {
                    if (
                        !empty($postData['entry_pdf_9']->getClientFileName()) &&
                        !empty($postData['entry_pdf_9']->getClientMediaType()) &&
                        !empty($postData['entry_pdf_9']->getStream()->getMetadata('uri')) &&
                        in_array($postData['entry_pdf_9']->getClientMediaType(), ['application/pdf'])
                    ) {
                        $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($foreignKey) . '_9' . '.' . 'pdf';
                        $entryPdfContents = file_get_contents($postData['entry_pdf_9']->getStream()->getMetadata('uri'));
                        if ($entryPdfContents) {
                            file_put_contents($entryPdfUri, $entryPdfContents);

                            $postData['entry_pdf_9'] = $postData['entry_pdf_9']->getClientFileName();
                            $postData['entry_pdf_9_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($foreignKey) . '_9' . '.' . 'pdf';
                        } else {
                            unset($postData['entry_pdf_9']);
                        }
                    } else {
                        unset($postData['entry_pdf_9']);
                    }
                } else {
                    unset($postData['entry_pdf_9']);
                }
            }

            if (!empty($postData['entry_guitar_pro'])) {
                if (
                    !empty($postData['entry_guitar_pro']->getClientFileName()) &&
                    !empty($postData['entry_guitar_pro']->getClientMediaType()) &&
                    !empty($postData['entry_guitar_pro']->getStream()->getMetadata('uri')) &&
                    in_array($postData['entry_guitar_pro']->getClientMediaType(), ['application/octet-stream'])
                ) {
                    $clientFileName = $postData['entry_guitar_pro']->getClientFileName();
                    $clientFile = explode('.', $clientFileName);

                    $entryGuitarProUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gp_' . h($foreignKey) . '.' . $clientFile[1];
                    $entryGuitarProContents = file_get_contents($postData['entry_guitar_pro']->getStream()->getMetadata('uri'));
                    if ($entryGuitarProContents) {
                        file_put_contents($entryGuitarProUri, $entryGuitarProContents);

                        $postData['entry_guitar_pro'] = $postData['entry_guitar_pro']->getClientFileName();
                        $postData['entry_guitar_pro_file'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gp_' . h($foreignKey) . '.' . $clientFile[1];
                    } else {
                        unset($postData['entry_guitar_pro']);
                    }
                } else {
                    unset($postData['entry_guitar_pro']);
                }

            } else {
                unset($postData['entry_guitar_pro']);
            }

            $userProfileTimelineEntry = $this->UserProfileTimelineEntries->newEmptyEntity();
            $userProfileTimelineEntry = $this->UserProfileTimelineEntries->patchEntity(
                $userProfileTimelineEntry,
                Hash::merge($postData, [
                    'user_id'       => $userId,
                    'foreign_key'   => $foreignKey,
                    'view_counter'  => 0,
                ])
            );

            YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.beforeAdd', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
            if ($this->UserProfileTimelineEntries->save($userProfileTimelineEntry)) {
                YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.onAddSuccess', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile timeline entry has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.onAddFailure', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile timeline entry could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            return $this->redirect($this->referer());
        }
    }

    /**
     * Edit method
     *
     * @return \Cake\Http\Response|void|null
     */
    public function edit()
    {
        $session = $this->getRequest()->getSession();

        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $userId = $session->check('Auth.User.id')? $session->read('Auth.User.id'): null;

            $postData = $this->getRequest()->getData();

            $userProfileTimelineEntry = $this->UserProfileTimelineEntries
                ->find()
                ->where([
                    'UserProfileTimelineEntries.user_id'      => $userId,
                    'UserProfileTimelineEntries.foreign_key'  => $postData['foreign_key'],
                ])
                ->first();
            if (!empty($userProfileTimelineEntry->id)) {

                if (
                    !empty($postData['entry_image_1_file']) ||
                    !empty($postData['entry_image_2_file']) ||
                    !empty($postData['entry_image_3_file']) ||
                    !empty($postData['entry_image_4_file']) ||
                    !empty($postData['entry_image_5_file']) ||
                    !empty($postData['entry_image_6_file']) ||
                    !empty($postData['entry_image_7_file']) ||
                    !empty($postData['entry_image_8_file']) ||
                    !empty($postData['entry_image_9_file'])
                ) {
                    // Image 1
                    if (!empty($postData['entry_image_1_file'])) {
                        if (
                            !empty($postData['entry_image_1_file']->getClientFileName()) &&
                            !empty($postData['entry_image_1_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_1_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_1' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_1_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_1'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_1' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_1_file']) &&
                            !empty($postData['entry_image_1_file']->getClientFileName()) &&
                            !empty($postData['entry_image_1_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_1_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_1' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_1_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_1'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_1' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 2
                    if (!empty($postData['entry_image_2_file'])) {
                        if (
                            !empty($postData['entry_image_2_file']->getClientFileName()) &&
                            !empty($postData['entry_image_2_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_2_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_2' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_2_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_2'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_2' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_2_file']) &&
                            !empty($postData['entry_image_2_file']->getClientFileName()) &&
                            !empty($postData['entry_image_2_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_2_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_2' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_2_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_2'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_2' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 3
                    if (!empty($postData['entry_image_3_file'])) {
                        if (
                            !empty($postData['entry_image_3_file']->getClientFileName()) &&
                            !empty($postData['entry_image_3_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_3_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_3' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_3_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_3'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_3' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_3_file']) &&
                            !empty($postData['entry_image_3_file']->getClientFileName()) &&
                            !empty($postData['entry_image_3_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_3_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_3' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_3_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_3'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_3' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 4
                    if (!empty($postData['entry_image_4_file'])) {
                        if (
                            !empty($postData['entry_image_4_file']->getClientFileName()) &&
                            !empty($postData['entry_image_4_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_4_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_4' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_4_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_4'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_4' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_4_file']) &&
                            !empty($postData['entry_image_4_file']->getClientFileName()) &&
                            !empty($postData['entry_image_4_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_4_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_4' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_4_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_4'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_4' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 5
                    if (!empty($postData['entry_image_5_file'])) {
                        if (
                            !empty($postData['entry_image_5_file']->getClientFileName()) &&
                            !empty($postData['entry_image_5_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_5_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_5' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_5_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_5'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_5' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_5_file']) &&
                            !empty($postData['entry_image_5_file']->getClientFileName()) &&
                            !empty($postData['entry_image_5_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_5_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_5' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_5_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_5'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_5' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 6
                    if (!empty($postData['entry_image_6_file'])) {
                        if (
                            !empty($postData['entry_image_6_file']->getClientFileName()) &&
                            !empty($postData['entry_image_6_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_6_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_6' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_6_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_6'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_6' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_6_file']) &&
                            !empty($postData['entry_image_6_file']->getClientFileName()) &&
                            !empty($postData['entry_image_6_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_6_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_6' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_6_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_6'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_6' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 7
                    if (!empty($postData['entry_image_7_file'])) {
                        if (
                            !empty($postData['entry_image_7_file']->getClientFileName()) &&
                            !empty($postData['entry_image_7_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_7_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_7' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_7_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_7'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_7' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_7_file']) &&
                            !empty($postData['entry_image_7_file']->getClientFileName()) &&
                            !empty($postData['entry_image_7_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_7_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_7' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_7_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_7'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_7' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 8
                    if (!empty($postData['entry_image_8_file'])) {
                        if (
                            !empty($postData['entry_image_8_file']->getClientFileName()) &&
                            !empty($postData['entry_image_8_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_8_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_8' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_8_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_8'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_8' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_8_file']) &&
                            !empty($postData['entry_image_8_file']->getClientFileName()) &&
                            !empty($postData['entry_image_8_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_8_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_8' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_8_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_8'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_8' . '.' . 'gif';
                            }
                        }
                    }

                    // Image 9
                    if (!empty($postData['entry_image_9_file'])) {
                        if (
                            !empty($postData['entry_image_9_file']->getClientFileName()) &&
                            !empty($postData['entry_image_9_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_9_file']->getClientMediaType(), [
                                'image/jpeg',
                                'image/jpg',
                            ])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_img_' . h($postData['foreign_key']) . '_9' . '.' . 'jpg';
                            $entryImageContents = file_get_contents($postData['entry_image_9_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $imageManager = ImageManager::imagick();
                                $entryImage = $imageManager->read($entryImageUri);
                                $entryImage->resize(800, 800);
                                $entryImage->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark_800' . '.' . 'png');
                                $entryImage->save($entryImageUri);

                                $postData['entry_image_9'] = '/yab_cms_ff/img/content/' . 'timeline_entry_img_' . h($postData['foreign_key']) . '_9' . '.' . 'jpg';
                            }
                        } elseif (
                            !empty($postData['entry_image_9_file']) &&
                            !empty($postData['entry_image_9_file']->getClientFileName()) &&
                            !empty($postData['entry_image_9_file']->getClientMediaType()) &&
                            in_array($postData['entry_image_9_file']->getClientMediaType(), ['image/gif'])
                        ) {
                            $entryImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_9' . '.' . 'gif';
                            $entryImageContents = file_get_contents($postData['entry_image_9_file']->getStream()->getMetadata('uri'));
                            if ($entryImageContents) {
                                file_put_contents($entryImageUri, $entryImageContents);

                                $entryImageGif = new Imagick($entryImageUri);
                                if ($entryImageGif->getImageFormat() == 'GIF') {
                                    $entryImageGif = $entryImageGif->coalesceImages();
                                    do {
                                        $entryImageGif->resizeImage(800, 800, Imagick::FILTER_BOX, 1);
                                    } while ($entryImageGif->nextImage());

                                    $entryImageGif = $entryImageGif->deconstructImages();
                                } else {
                                    $entryImageGif->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                                }
                                $entryImageGif->writeImages($entryImageUri, true);

                                $postData['entry_image_9'] = '/yab_cms_ff/img/content/' . 'timeline_entry_gif_' . h($postData['foreign_key']) . '_9' . '.' . 'gif';
                            }
                        }
                    }
                }

                if (
                    !empty($postData['entry_video_1_file']) ||
                    !empty($postData['entry_video_2_file']) ||
                    !empty($postData['entry_video_3_file']) ||
                    !empty($postData['entry_video_4_file']) ||
                    !empty($postData['entry_video_5_file']) ||
                    !empty($postData['entry_video_6_file']) ||
                    !empty($postData['entry_video_7_file']) ||
                    !empty($postData['entry_video_8_file']) ||
                    !empty($postData['entry_video_9_file'])
                ) {
                    // Video 1
                    if (!empty($postData['entry_video_1_file'])) {
                        if (
                            !empty($postData['entry_video_1_file']) &&
                            !empty($postData['entry_video_1_file']->getClientFileName()) &&
                            !empty($postData['entry_video_1_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_1_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_1' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_1_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_1'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_1' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 2
                    if (!empty($postData['entry_video_2_file'])) {
                        if (
                            !empty($postData['entry_video_2_file']) &&
                            !empty($postData['entry_video_2_file']->getClientFileName()) &&
                            !empty($postData['entry_video_2_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_2_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_2' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_2_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_2'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_2' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 3
                    if (!empty($postData['entry_video_3_file'])) {
                        if (
                            !empty($postData['entry_video_3_file']) &&
                            !empty($postData['entry_video_3_file']->getClientFileName()) &&
                            !empty($postData['entry_video_3_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_3_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_3' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_3_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_3'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_3' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 4
                    if (!empty($postData['entry_video_4_file'])) {
                        if (
                            !empty($postData['entry_video_4_file']) &&
                            !empty($postData['entry_video_4_file']->getClientFileName()) &&
                            !empty($postData['entry_video_4_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_4_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_4' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_4_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_4'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_4' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 5
                    if (!empty($postData['entry_video_5_file'])) {
                        if (
                            !empty($postData['entry_video_5_file']) &&
                            !empty($postData['entry_video_5_file']->getClientFileName()) &&
                            !empty($postData['entry_video_5_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_5_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_5' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_5_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_5'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_5' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 6
                    if (!empty($postData['entry_video_6_file'])) {
                        if (
                            !empty($postData['entry_video_6_file']) &&
                            !empty($postData['entry_video_6_file']->getClientFileName()) &&
                            !empty($postData['entry_video_6_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_6_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_6' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_6_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_6'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_6' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 7
                    if (!empty($postData['entry_video_7_file'])) {
                        if (
                            !empty($postData['entry_video_7_file']) &&
                            !empty($postData['entry_video_7_file']->getClientFileName()) &&
                            !empty($postData['entry_video_7_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_7_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_7' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_7_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_7'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_7' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 8
                    if (!empty($postData['entry_video_8_file'])) {
                        if (
                            !empty($postData['entry_video_8_file']) &&
                            !empty($postData['entry_video_8_file']->getClientFileName()) &&
                            !empty($postData['entry_video_8_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_8_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_8' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_8_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_8'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_8' . '.' . 'mp4';
                            }
                        }
                    }

                    // Video 9
                    if (!empty($postData['entry_video_9_file'])) {
                        if (
                            !empty($postData['entry_video_9_file']) &&
                            !empty($postData['entry_video_9_file']->getClientFileName()) &&
                            !empty($postData['entry_video_9_file']->getClientMediaType()) &&
                            in_array($postData['entry_video_9_file']->getClientMediaType(), ['video/mp4'])
                        ) {
                            $entryVideoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_9' . '.' . 'mp4';
                            $entryVideoContents = file_get_contents($postData['entry_video_9_file']->getStream()->getMetadata('uri'));
                            if ($entryVideoContents) {
                                file_put_contents($entryVideoUri, $entryVideoContents);

                                $postData['entry_video_9'] = '/yab_cms_ff/img/content/' . 'timeline_entry_vid_' . h($postData['foreign_key']) . '_9' . '.' . 'mp4';
                            }
                        }
                    }
                }

                if (
                    !empty($postData['entry_pdf_1_file']) ||
                    !empty($postData['entry_pdf_2_file']) ||
                    !empty($postData['entry_pdf_3_file']) ||
                    !empty($postData['entry_pdf_4_file']) ||
                    !empty($postData['entry_pdf_5_file']) ||
                    !empty($postData['entry_pdf_6_file']) ||
                    !empty($postData['entry_pdf_7_file']) ||
                    !empty($postData['entry_pdf_8_file']) ||
                    !empty($postData['entry_pdf_9_file'])
                ) {
                    // Pdf 1
                    if (!empty($postData['entry_pdf_1_file'])) {
                        if (
                            !empty($postData['entry_pdf_1_file']) &&
                            !empty($postData['entry_pdf_1_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_1_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_1_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_1' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_1_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_1'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_1' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 2
                    if (!empty($postData['entry_pdf_2_file'])) {
                        if (
                            !empty($postData['entry_pdf_2_file']) &&
                            !empty($postData['entry_pdf_2_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_2_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_2_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_2' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_2_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_2'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_2' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 3
                    if (!empty($postData['entry_pdf_3_file'])) {
                        if (
                            !empty($postData['entry_pdf_3_file']) &&
                            !empty($postData['entry_pdf_3_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_3_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_3_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_3' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_3_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_3'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_3' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 4
                    if (!empty($postData['entry_pdf_4_file'])) {
                        if (
                            !empty($postData['entry_pdf_4_file']) &&
                            !empty($postData['entry_pdf_4_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_4_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_4_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri =ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_4' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_4_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_4'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_4' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 5
                    if (!empty($postData['entry_pdf_5_file'])) {
                        if (
                            !empty($postData['entry_pdf_5_file']) &&
                            !empty($postData['entry_pdf_5_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_5_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_5_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_5' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_5_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_5'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_5' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 6
                    if (!empty($postData['entry_pdf_6_file'])) {
                        if (
                            !empty($postData['entry_pdf_6_file']) &&
                            !empty($postData['entry_pdf_6_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_6_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_6_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_6' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_6_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_6'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_6' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 7
                    if (!empty($postData['entry_pdf_7_file'])) {
                        if (
                            !empty($postData['entry_pdf_7_file']) &&
                            !empty($postData['entry_pdf_7_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_7_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_7_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_7' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_7_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_7'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_7' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 8
                    if (!empty($postData['entry_pdf_8_file'])) {
                        if (
                            !empty($postData['entry_pdf_8_file']) &&
                            !empty($postData['entry_pdf_8_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_8_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_8_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_8' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_8_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_8'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_8' . '.' . 'pdf';
                            }
                        }
                    }

                    // Pdf 9
                    if (!empty($postData['entry_pdf_9_file'])) {
                        if (
                            !empty($postData['entry_pdf_9_file']) &&
                            !empty($postData['entry_pdf_9_file']->getClientFileName()) &&
                            !empty($postData['entry_pdf_9_file']->getClientMediaType()) &&
                            in_array($postData['entry_pdf_9_file']->getClientMediaType(), ['application/pdf'])
                        ) {
                            $entryPdfUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_9' . '.' . 'pdf';
                            $entryPdfContents = file_get_contents($postData['entry_pdf_9_file']->getStream()->getMetadata('uri'));
                            if ($entryPdfContents) {
                                file_put_contents($entryPdfUri, $entryPdfContents);

                                $postData['entry_pdf_9'] = '/yab_cms_ff/img/content/' . 'timeline_entry_pdf_' . h($postData['foreign_key']) . '_9' . '.' . 'pdf';
                            }
                        }
                    }
                }

                $userProfileTimelineEntry = $this->UserProfileTimelineEntries->get($userProfileTimelineEntry->id);
                $userProfileTimelineEntry = $this->UserProfileTimelineEntries->patchEntity(
                    $userProfileTimelineEntry,
                    Hash::merge($this->getRequest()->getData(), [
                        'user_id'           => $userId,
                        'entry_no'          => h($postData['entry_no']),
                        'entry_ref_no'      => h($postData['entry_ref_no']),
                        'entry_date'        => h($postData['entry_date']),
                        'entry_type'        => h($postData['entry_type']),
                        'entry_title'       => h($postData['entry_title']),
                        'entry_subtitle'    => h($postData['entry_subtitle']),
                        'entry_body'        => h($postData['entry_body']),
                        'entry_link_1'      => h($postData['entry_link_1']),
                        'entry_link_2'      => h($postData['entry_link_2']),
                        'entry_link_3'      => h($postData['entry_link_3']),
                        'entry_link_4'      => h($postData['entry_link_4']),
                        'entry_link_5'      => h($postData['entry_link_5']),
                        'entry_link_6'      => h($postData['entry_link_6']),
                        'entry_link_7'      => h($postData['entry_link_7']),
                        'entry_link_8'      => h($postData['entry_link_8']),
                        'entry_link_9'      => h($postData['entry_link_9']),
                        'entry_image_1'     => h($postData['entry_image_1']),
                        'entry_image_2'     => h($postData['entry_image_2']),
                        'entry_image_3'     => h($postData['entry_image_3']),
                        'entry_image_4'     => h($postData['entry_image_4']),
                        'entry_image_5'     => h($postData['entry_image_5']),
                        'entry_image_6'     => h($postData['entry_image_6']),
                        'entry_image_7'     => h($postData['entry_image_7']),
                        'entry_image_8'     => h($postData['entry_image_8']),
                        'entry_image_9'     => h($postData['entry_image_9']),
                        'entry_video_1'     => h($postData['entry_video_1']),
                        'entry_video_2'     => h($postData['entry_video_2']),
                        'entry_video_3'     => h($postData['entry_video_3']),
                        'entry_video_4'     => h($postData['entry_video_4']),
                        'entry_video_5'     => h($postData['entry_video_5']),
                        'entry_video_6'     => h($postData['entry_video_6']),
                        'entry_video_7'     => h($postData['entry_video_7']),
                        'entry_video_8'     => h($postData['entry_video_8']),
                        'entry_video_9'     => h($postData['entry_video_9']),
                        'entry_pdf_1'       => h($postData['entry_pdf_1']),
                        'entry_pdf_2'       => h($postData['entry_pdf_2']),
                        'entry_pdf_3'       => h($postData['entry_pdf_3']),
                        'entry_pdf_4'       => h($postData['entry_pdf_4']),
                        'entry_pdf_5'       => h($postData['entry_pdf_5']),
                        'entry_pdf_6'       => h($postData['entry_pdf_6']),
                        'entry_pdf_7'       => h($postData['entry_pdf_7']),
                        'entry_pdf_8'       => h($postData['entry_pdf_8']),
                        'entry_pdf_9'       => h($postData['entry_pdf_9']),
                    ])
                );
                YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.beforeEdit', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                if ($this->UserProfileTimelineEntries->save($userProfileTimelineEntry)) {
                    YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.onEditSuccess', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile timeline entry has been saved.'),
                        ['element' => 'default', 'params' => ['class' => 'success']]
                    );
                } else {
                    YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.onEditFailure', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile timeline entry could not be saved. Please, try again.'),
                        ['element' => 'default', 'params' => ['class' => 'error']]
                    );
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfileTimelineEntries.onEditFailure', $this, ['UserProfileTimelineEntry' => $userProfileTimelineEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile timeline entry could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            return $this->redirect($this->referer());
        }
    }
}
