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
 * UserProfileDiaryEntries Controller
 *
 * @property \YabCmsFf\Model\Table\UserProfileDiaryEntriesTable $UserProfileDiaryEntries
 */
class UserProfileDiaryEntriesController extends AppController
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
            'entry_title',
            'entry_body',
            'entry_avatar',
            'entry_star_counter',
            'created',
            'modified',
        ],
        'order' => ['created' => 'DESC']
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

        if ($this->getRequest()->getParam('action') === 'countUp') {
            $this->FormProtection->setConfig('unlockedActions', ['countUp']);
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
                $query = $this->UserProfileDiaryEntries
                    ->find('search', search: $this->getRequest()->getQueryParams())
                    ->where(['UserProfileDiaryEntries.user_id' => $userProfile->user_id]);

                YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.beforeIndexRender', $this, ['Query' => $query]);

                $this->set('userProfileDiaryEntries', $this->paginate($query));
                $this->set('userProfile', $userProfile);
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The user profile diary entries could not be found. Please, try again.'),
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
            $userProfileDiaryEntry = $this->UserProfileDiaryEntries
                ->find()
                ->where(['UserProfileDiaryEntries.foreign_key' => $foreignKey])
                ->contain('Users.UserProfiles')
                ->first();
            if (!empty($userProfileDiaryEntry->id)) {

                $userProfileDiaryEntryCountQuery = $this->UserProfileDiaryEntries->updateQuery();
                $userProfileDiaryEntryCountQuery
                    ->update('user_profile_diary_entries')
                    ->set(['view_counter' => $userProfileDiaryEntry->view_counter + 1])
                    ->where(['id' => $userProfileDiaryEntry->id])
                    ->execute();

                $this->set('userProfileDiaryEntry', $userProfileDiaryEntry);
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'The diary entry could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect($this->referer());
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The diary entry could not be found. Please, try again.'),
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

            $userId = $session->check('Auth.User.id')? $session->read('Auth.User.id'): null;

            $uuidId = Text::uuid();
            $foreignKey = Text::uuid();

            $postData = $this->getRequest()->getData();

            if (
                !empty($postData['entry_avatar_file']->getClientFileName()) &&
                !empty($postData['entry_avatar_file']->getClientMediaType()) &&
                in_array($postData['entry_avatar_file']->getClientMediaType(), [
                    'image/jpeg',
                    'image/jpg',
                ])
            ) {
                $entryRootAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($foreignKey) . '_' . '.' . 'jpg';
                $entryAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($foreignKey) . '.' . 'jpg';
                $entryAvatarContents = file_get_contents($postData['entry_avatar_file']->getStream()->getMetadata('uri'));
                if ($entryAvatarContents) {
                    file_put_contents($entryRootAvatarUri, $entryAvatarContents);

                    $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                    $size = getimagesize($entryRootAvatarUri);
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
                    $src = imagecreatefromstring(file_get_contents($entryRootAvatarUri));
                    $dst = imagecreatetruecolor(intval($width), intval($height));
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                    $blankImage = imagecreatefromjpeg($blankImageUri);
                    if (
                        imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                        imagejpeg($blankImage, $entryAvatarUri)
                    ) {
                        unlink($entryRootAvatarUri);
                    }

                    $imageManager = ImageManager::imagick();
                    $entryAvatar = $imageManager->read($entryAvatarUri);
                    $entryAvatar->resize(80, 80);
                    $entryAvatar->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                    $entryAvatar->save($entryAvatarUri);

                    $postData['entry_avatar'] = '/yab_cms_ff/img/content/' . 'diary_entry_avatar_' . h($foreignKey) . '.' . 'jpg';
                }
            } elseif (
                !empty($postData['entry_avatar_file']->getClientFileName()) &&
                !empty($postData['entry_avatar_file']->getClientMediaType()) &&
                in_array($postData['entry_avatar_file']->getClientMediaType(), ['image/gif'])
            ) {
                $entryAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($foreignKey) . '.' . 'gif';
                $entryAvatarContents = file_get_contents($postData['entry_avatar_file']->getStream()->getMetadata('uri'));
                if ($entryAvatarContents) {
                    file_put_contents($entryAvatarUri, $entryAvatarContents);

                    $entryAvatarGif = new Imagick($entryAvatarUri);
                    if ($entryAvatarGif->getImageFormat() == 'GIF') {
                        $entryAvatarGif = $entryAvatarGif->coalesceImages();
                        do {
                            $entryAvatarGif->resizeImage(80, 80, Imagick::FILTER_BOX, 1);
                        } while ($entryAvatarGif->nextImage());

                        $entryAvatarGif = $entryAvatarGif->deconstructImages();
                    } else {
                        $entryAvatarGif->resizeImage(80, 80, Imagick::FILTER_LANCZOS, 1, true);
                    }
                    $entryAvatarGif->writeImages($entryAvatarUri, true);

                    $postData['entry_avatar'] = '/yab_cms_ff/img/content/' . 'diary_entry_avatar_' . h($foreignKey) . '.' . 'gif';
                }
            }

            $userProfileDiaryEntry = $this->UserProfileDiaryEntries->newEmptyEntity();
            $userProfileDiaryEntry = $this->UserProfileDiaryEntries->patchEntity(
                $userProfileDiaryEntry,
                Hash::merge($this->getRequest()->getData(), [
                    'user_id'               => $userId,
                    'uuid_id'               => $uuidId,
                    'foreign_key'           => $foreignKey,
                    'entry_title'           => h($postData['entry_title']),
                    'entry_body'            => h($postData['entry_body']),
                    'entry_avatar'          => h($postData['entry_avatar']),
                    'entry_star_counter'    => 0,
                    'view_counter'          => 0,
                ])
            );
            YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.beforeAdd', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
            if ($this->UserProfileDiaryEntries->save($userProfileDiaryEntry)) {
                YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.onAddSuccess', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile diary entry has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.onAddFailure', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile diary entry could not be saved. Please, try again.'),
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

            $userProfileDiaryEntry = $this->UserProfileDiaryEntries
                ->find()
                ->where([
                    'UserProfileDiaryEntries.user_id'      => $userId,
                    'UserProfileDiaryEntries.foreign_key'  => $postData['foreign_key'],
                ])
                ->first();
            if (!empty($userProfileDiaryEntry->id)) {

                if (
                    !empty($postData['entry_avatar_file']->getClientFileName()) &&
                    !empty($postData['entry_avatar_file']->getClientMediaType()) &&
                    in_array($postData['entry_avatar_file']->getClientMediaType(), [
                        'image/jpeg',
                        'image/jpg',
                    ])
                ) {
                    $entryRootAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($postData['foreign_key']) . '_' . '.' . 'jpg';
                    $entryAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($postData['foreign_key']) . '.' . 'jpg';
                    $entryAvatarContents = file_get_contents($postData['entry_avatar_file']->getStream()->getMetadata('uri'));
                    if ($entryAvatarContents) {
                        file_put_contents($entryRootAvatarUri, $entryAvatarContents);

                        $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                        $size = getimagesize($entryRootAvatarUri);
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
                        $src = imagecreatefromstring(file_get_contents($entryRootAvatarUri));
                        $dst = imagecreatetruecolor(intval($width), intval($height));
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                        $blankImage = imagecreatefromjpeg($blankImageUri);
                        if (
                            imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                            imagejpeg($blankImage, $entryAvatarUri)
                        ) {
                            unlink($entryRootAvatarUri);
                        }

                        $imageManager = ImageManager::imagick();
                        $entryAvatar = $imageManager->read($entryAvatarUri);
                        $entryAvatar->resize(80, 80);
                        $entryAvatar->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                        $entryAvatar->save($entryAvatarUri);

                        $postData['entry_avatar'] = '/yab_cms_ff/img/content/' . 'diary_entry_avatar_' . h($postData['foreign_key']) . '.' . 'jpg';
                    }
                } elseif (
                    !empty($postData['entry_avatar_file']->getClientFileName()) &&
                    !empty($postData['entry_avatar_file']->getClientMediaType()) &&
                    in_array($postData['entry_avatar_file']->getClientMediaType(), ['image/gif'])
                ) {
                    $entryAvatarUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'content' . DS . 'diary_entry_avatar_' . h($postData['foreign_key']) . '.' . 'gif';
                    $entryAvatarContents = file_get_contents($postData['entry_avatar_file']->getStream()->getMetadata('uri'));
                    if ($entryAvatarContents) {
                        file_put_contents($entryAvatarUri, $entryAvatarContents);

                        $entryAvatarGif = new Imagick($entryAvatarUri);
                        if ($entryAvatarGif->getImageFormat() == 'GIF') {
                            $entryAvatarGif = $entryAvatarGif->coalesceImages();
                            do {
                                $entryAvatarGif->resizeImage(80, 80, Imagick::FILTER_BOX, 1);

                            } while ($entryAvatarGif->nextImage());

                            $entryAvatarGif = $entryAvatarGif->deconstructImages();
                        } else {
                            $entryAvatarGif->resizeImage(80, 80, Imagick::FILTER_LANCZOS, 1, true);
                        }
                        $entryAvatarGif->writeImages($entryAvatarUri, true);

                        $postData['entry_avatar'] = '/yab_cms_ff/img/content/' . 'diary_entry_avatar_' . h($postData['foreign_key']) . '.' . 'gif';
                    }
                }

                $userProfileDiaryEntry = $this->UserProfileDiaryEntries->get($userProfileDiaryEntry->id);
                $userProfileDiaryEntry = $this->UserProfileDiaryEntries->patchEntity(
                    $userProfileDiaryEntry,
                    Hash::merge(
                        $this->getRequest()->getData(),
                        [
                            'id'            => $userProfileDiaryEntry->id,
                            'user_id'       => $userId,
                            'entry_title'   => h($postData['entry_title']),
                            'entry_body'    => h($postData['entry_body']),
                            'entry_avatar'  => h($postData['entry_avatar']),
                        ]
                    )
                );
                YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.beforeEdit', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                if ($this->UserProfileDiaryEntries->save($userProfileDiaryEntry)) {
                    YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.onEditSuccess', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile diary entry has been saved.'),
                        ['element' => 'default', 'params' => ['class' => 'success']]
                    );
                } else {
                    YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.onEditFailure', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile diary entry could not be saved. Please, try again.'),
                        ['element' => 'default', 'params' => ['class' => 'error']]
                    );
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfileDiaryEntries.onEditFailure', $this, ['UserProfileDiaryEntry' => $userProfileDiaryEntry]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile diary entry could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }

            return $this->redirect($this->referer());
        }
    }

    /**
     * Count up method
     *
     * @return \Cake\Http\Response
     */
    public function countUp()
    {
        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $postData = $this->getRequest()->getData();

            $existentUserProfileDiaryEntry = $this->UserProfileDiaryEntries
                ->find()
                ->where(['UserProfileDiaryEntries.foreign_key'  => $postData['foreign_key']])
                ->first();
            if (!empty($existentUserProfileDiaryEntry->id)) {
                $userProfileDiaryEntry = $this->UserProfileDiaryEntries->get($existentUserProfileDiaryEntry->id);
                $userProfileDiaryEntry = $this->UserProfileDiaryEntries->patchEntity(
                    $userProfileDiaryEntry,
                    [
                        'id'                    => $userProfileDiaryEntry->id,
                        'entry_star_counter'    => $existentUserProfileDiaryEntry->entry_star_counter + 1,
                    ]
                );
                if ($this->UserProfileDiaryEntries->save($userProfileDiaryEntry)) {
                    $updatedUserProfileDiaryEntry = $this->UserProfileDiaryEntries
                        ->find()
                        ->where(['UserProfileDiaryEntries.foreign_key'  => $postData['foreign_key']])
                        ->select(['entry_star_counter'])
                        ->first();

                    $this->response->getBody()->write(json_encode(['entryStarCounter' => $updatedUserProfileDiaryEntry->entry_star_counter]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                } else {
                    $updatedUserProfileDiaryEntry = $this->UserProfileDiaryEntries
                        ->find()
                        ->where(['UserProfileDiaryEntries.foreign_key'  => $postData['foreign_key']])
                        ->select(['entry_star_counter'])
                        ->first();

                    $this->response->getBody()->write(json_encode(['entryStarCounter' => $updatedUserProfileDiaryEntry->entry_star_counter]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                }
            }
        }

        return $this->redirect($this->referer());
    }
}
