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
use Intervention\Image\ImageManager;

/**
 * UserProfiles Controller
 *
 * @property \YabCmsFf\Model\Table\UserProfilesTable $UserProfiles
 */
class UserProfilesController extends AppController
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
        'limit'     => 30,
        'maxLimit'  => 45,
        'sortableFields' => [
            'id',
            'user_id',
            'foreign_key',
            'prefix',
            'salutation',
            'suffix',
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'birthday',
            'website',
            'telephone',
            'mobilephone',
            'fax',
            'company',
            'street',
            'street_addition',
            'postcode',
            'city',
            'region_id',
            'country_id',
            'about_me',
            'tags',
            'timezone',
            'image',
            'view_counter',
            'status',
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

        if ($this->getRequest()->getParam('action') === 'countDiaryEntries') {
            $this->FormProtection->setConfig('unlockedActions', ['countDiaryEntries']);
        }
        if ($this->getRequest()->getParam('action') === 'countTimelineEntries') {
            $this->FormProtection->setConfig('unlockedActions', ['countTimelineEntries']);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void|null
     */
    public function index()
    {
        $query = $this->UserProfiles
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->where(['UserProfiles.status' => 1]);

        YabCmsFf::dispatchEvent('Controller.UserProfiles.beforeIndexRender', $this, ['Query' => $query]);

        $this->set('userProfiles', $this->paginate($query));
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
            $userProfile = $this->UserProfiles
                ->find()
                ->where(['UserProfiles.foreign_key' => $foreignKey])
                ->contain('Users.UserProfileDiaryEntries', function ($q) {
                    return $q
                        ->orderBy(['UserProfileDiaryEntries.created' => 'DESC']);
                })
                ->contain('Users.UserProfileTimelineEntries', function ($q) {
                    return $q
                        ->orderBy(['UserProfileTimelineEntries.entry_date' => 'DESC']);
                })
                ->first();
            if (!empty($userProfile->id)) {

                $userProfileCountQuery = $this->UserProfiles->updateQuery();
                $userProfileCountQuery
                    ->update('user_profiles')
                    ->set(['view_counter' => $userProfile->view_counter + 1])
                    ->where(['id' => $userProfile->id])
                    ->execute();

                $this->set(compact('userProfile'));
            } else {
                $this->Flash->set(
                    __d('yab_cms_ff', 'The profile could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );

                return $this->redirect($this->referer());
            }
        } else {
            $this->Flash->set(
                __d('yab_cms_ff', 'The profile could not be found. Please, try again.'),
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

            $postData = $this->getRequest()->getData();

            if (
                !empty($postData['image_file']->getClientFileName()) &&
                !empty($postData['image_file']->getClientMediaType()) &&
                in_array($postData['image_file']->getClientMediaType(), [
                    'image/jpeg',
                    'image/jpg',
                ])
            ) {
                $profileRootPhotoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'avatars' . DS . 'profile_avatar_' .  h($postData['foreign_key']) . '_' . '.' . 'jpg';
                $profilePhotoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'avatars' . DS . 'profile_avatar_' .  h($postData['foreign_key']) . '.' . 'jpg';
                $profilePhotoContents = file_get_contents($postData['image_file']->getStream()->getMetadata('uri'));
                if ($profilePhotoContents) {
                    file_put_contents($profileRootPhotoUri, $profilePhotoContents);

                    $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                    $size = getimagesize($profileRootPhotoUri);
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
                    $src = imagecreatefromstring(file_get_contents($profileRootPhotoUri));
                    $dst = imagecreatetruecolor(intval($width), intval($height));
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                    $blankImage = imagecreatefromjpeg($blankImageUri);
                    if (
                        imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                        imagejpeg($blankImage, $profilePhotoUri)
                    ) {
                        unlink($profileRootPhotoUri);
                    }

                    $imageManager = ImageManager::imagick();
                    $profilePhoto = $imageManager->read($profilePhotoUri);
                    $profilePhoto->resize(400, 400);
                    $profilePhoto->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                    $profilePhoto->save($profilePhotoUri);

                    $postData['image'] = '/yab_cms_ff/img/avatars/' . 'profile_avatar_' .  h($postData['foreign_key']) . '.' . 'jpg';
                } else {
                    $postData['image'] = '/yab_cms_ff/img/avatars/avatar.jpg';
                }
            }

            $userProfile = $this->UserProfiles->newEmptyEntity();
            $userProfile = $this->UserProfiles->patchEntity(
                $userProfile,
                Hash::merge($this->getRequest()->getData(), [
                    'user_id'           => $userId,
                    'uuid_id'           => h($postData['uuid_id']),
                    'foreign_key'       => h($postData['foreign_key']),
                    'first_name'        => h($postData['first_name']),
                    'middle_name'       => h($postData['middle_name']),
                    'last_name'         => h($postData['last_name']),
                    'website'           => h($postData['website']),
                    'telephone'         => h($postData['telephone']),
                    'mobilephone'       => h($postData['mobilephone']),
                    'fax'               => h($postData['fax']),
                    'company'           => h($postData['company']),
                    'street'            => h($postData['street']),
                    'street_addition'   => h($postData['street_addition']),
                    'postcode'          => h($postData['postcode']),
                    'city'              => h($postData['city']),
                    'about_me'          => h($postData['about_me']),
                    'tags'              => h($postData['tags']),
                    'image'             => h($postData['image']),
                    'view_counter'      => 0,
                    'status'            => 1,
                ])
            );
            YabCmsFf::dispatchEvent('Controller.UserProfiles.beforeAdd', $this, ['UserProfile' => $userProfile]);
            if ($this->UserProfiles->save($userProfile)) {
                YabCmsFf::dispatchEvent('Controller.UserProfiles.onAddSuccess', $this, ['UserProfile' => $userProfile]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfiles.onAddFailure', $this, ['UserProfile' => $userProfile]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        return $this->redirect($this->referer());
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

            $userProfile = $this->UserProfiles
                ->find()
                ->where([
                    'UserProfiles.user_id'      => $userId,
                    'UserProfiles.foreign_key'  => h($postData['foreign_key']),
                ])
                ->first();
            if (!empty($userProfile->id)) {

                if (
                    !empty($postData['image_file']->getClientFileName()) &&
                    !empty($postData['image_file']->getClientMediaType()) &&
                    in_array($postData['image_file']->getClientMediaType(), [
                        'image/jpeg',
                        'image/jpg',
                    ])
                ) {
                    $profileRootPhotoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'avatars' . DS . 'profile_avatar_' .  h($postData['foreign_key']) . '_' . '.' . 'jpg';
                    $profilePhotoUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'avatars' . DS . 'profile_avatar_' .  h($postData['foreign_key']) . '.' . 'jpg';
                    $profilePhotoContents = file_get_contents($postData['image_file']->getStream()->getMetadata('uri'));
                    if ($profilePhotoContents) {
                        file_put_contents($profileRootPhotoUri, $profilePhotoContents);

                        $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'blank_image_800' . '.' . 'jpg';
                        $size = getimagesize($profileRootPhotoUri);
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
                        $src = imagecreatefromstring(file_get_contents($profileRootPhotoUri));
                        $dst = imagecreatetruecolor(intval($width), intval($height));
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, intval($width), intval($height), $size[0], $size[1]);
                        $blankImage = imagecreatefromjpeg($blankImageUri);
                        if (
                            imagecopymerge($blankImage, $dst, intval($dst_x), intval($dst_y), 0, 0, imagesx($dst), imagesy($dst), 100) &&
                            imagejpeg($blankImage, $profilePhotoUri)
                        ) {
                            unlink($profileRootPhotoUri);
                        }

                        $imageManager = ImageManager::imagick();
                        $profilePhoto = $imageManager->read($profilePhotoUri);
                        $profilePhoto->resize(400, 400);
                        $profilePhoto->place(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                        $profilePhoto->save($profilePhotoUri);

                        $postData['image'] = '/yab_cms_ff/img/avatars/' . 'profile_avatar_' .  h($postData['foreign_key']) . '.' . 'jpg';
                    } else {
                        $postData['image'] = '/yab_cms_ff/img/avatars/avatar.jpg';
                    }
                }

                $userProfile = $this->UserProfiles->get($userProfile->id);
                $userProfile = $this->UserProfiles->patchEntity(
                    $userProfile,
                    Hash::merge(
                        $this->getRequest()->getData(),
                        [
                            'id'                => $userProfile->id,
                            'user_id'           => $userId,
                            'uuid_id'           => h($postData['uuid_id']),
                            'foreign_key'       => h($postData['foreign_key']),
                            'first_name'        => h($postData['first_name']),
                            'middle_name'       => h($postData['middle_name']),
                            'last_name'         => h($postData['last_name']),
                            'website'           => h($postData['website']),
                            'telephone'         => h($postData['telephone']),
                            'mobilephone'       => h($postData['mobilephone']),
                            'fax'               => h($postData['fax']),
                            'company'           => h($postData['company']),
                            'street'            => h($postData['street']),
                            'street_addition'   => h($postData['street_addition']),
                            'postcode'          => h($postData['postcode']),
                            'city'              => h($postData['city']),
                            'about_me'          => h($postData['about_me']),
                            'tags'              => h($postData['tags']),
                            'image'             => h($postData['image']),
                            'status'            => h($postData['status']),
                        ]
                    )
                );
                YabCmsFf::dispatchEvent('Controller.UserProfiles.beforeEdit', $this, ['UserProfile' => $userProfile]);
                if ($this->UserProfiles->save($userProfile)) {
                    YabCmsFf::dispatchEvent('Controller.UserProfiles.onEditSuccess', $this, ['UserProfile' => $userProfile]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile has been saved.'),
                        ['element' => 'default', 'params' => ['class' => 'success']]
                    );
                } else {
                    YabCmsFf::dispatchEvent('Controller.UserProfiles.onEditFailure', $this, ['UserProfile' => $userProfile]);
                    $this->Flash->set(
                        __d('yab_cms_ff', 'The user profile could not be saved. Please, try again.'),
                        ['element' => 'default', 'params' => ['class' => 'error']]
                    );
                }
            } else {
                YabCmsFf::dispatchEvent('Controller.UserProfiles.onEditFailure', $this, ['UserProfile' => $userProfile]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile could not be found. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        return $this->redirect($this->referer());
    }

    /**
     * Count diary entries method
     *
     * @return \Cake\Http\Response
     */
    public function countDiaryEntries()
    {
        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $postData = $this->getRequest()->getData();

            $userProfile = $this->UserProfiles
                ->find()
                ->where(['UserProfiles.foreign_key'  => $postData['foreign_key']])
                ->first();
            if (!empty($userProfile->id)) {
                $userProfileDiaryEntries = $this->UserProfiles->Users->UserProfileDiaryEntries
                    ->find()
                    ->where(['UserProfileDiaryEntries.user_id' => $userProfile->user_id])
                    ->count();
                if (!empty($userProfileDiaryEntries)) {
                    $this->response->getBody()->write(json_encode(['diaryEntriesCounter' => $userProfileDiaryEntries]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                } else {
                    $this->response->getBody()->write(json_encode(['diaryEntriesCounter' => 0]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                }
            }
        }

        return $this->redirect($this->referer());
    }

    /**
     * Count timeline entries method
     *
     * @return \Cake\Http\Response
     */
    public function countTimelineEntries()
    {
        if ($this->getRequest()->is('post') || !empty($this->getRequest()->getData())) {

            $postData = $this->getRequest()->getData();

            $userProfile = $this->UserProfiles
                ->find()
                ->where(['UserProfiles.foreign_key'  => $postData['foreign_key']])
                ->first();
            if (!empty($userProfile->id)) {
                $userProfileTimelineEntries = $this->UserProfiles->Users->UserProfileTimelineEntries
                    ->find()
                    ->where(['UserProfileTimelineEntries.user_id' => $userProfile->user_id])
                    ->count();
                if (!empty($userProfileTimelineEntries)) {
                    $this->response->getBody()->write(json_encode(['timelineEntriesCounter' => $userProfileTimelineEntries]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                } else {
                    $this->response->getBody()->write(json_encode(['timelineEntriesCounter' => 0]));
                    $this->response = $this->response->withType('json');
                    return $this->response;
                }
            }
        }

        return $this->redirect($this->referer());
    }
}
