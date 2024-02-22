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
use YabCmsFf\Utility\YabCmsFf;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Intervention\Image\ImageManager as ImageManager;

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
        'limit' => 25,
        'maxLimit' => 50,
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
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->UserProfiles
            ->find('search', search: $this->getRequest()->getQueryParams())
            ->contain(['Users']);

        YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeIndexRender', $this, [
            'Query' => $query,
        ]);

        $this->set('userProfiles', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param int|null $id
     * @return void
     */
    public function view(int $id = null)
    {
        $userProfile = $this->UserProfiles->get($id, contain: [
            'Users',
            'Regions',
            'Countries',
        ]);

        $Users = TableRegistry::getTableLocator()->get('YabCmsFf.Users');
        $users = $Users->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name')
        ->toArray();

        YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeViewRender', $this, [
            'UserProfile' => $userProfile,
            'Users' => $users,
        ]);

        $this->set(compact('userProfile', 'users'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void|null
     */
    public function add()
    {
        $userProfile = $this->UserProfiles->newEmptyEntity();
        if ($this->getRequest()->is('post')) {

            $postData = $this->getRequest()->getData();

            if (
                !empty($postData['image_file']->getClientFileName()) &&
                !empty($postData['image_file']->getClientMediaType()) &&
                in_array($postData['image_file']->getClientMediaType(), [
                    'image/jpeg',
                    'image/jpg',
                ])
            ) {
                $profileRootPhotoUri = WWW_ROOT . 'img' . DS . h($postData['foreign_key']) . '_' . '.' . 'jpg';
                $profilePhotoUri = WWW_ROOT . 'img' . DS . h($postData['foreign_key']) . '.' . 'jpg';
                $profilePhotoContents = file_get_contents($postData['image_file']->getStream()->getMetadata('uri'));
                if ($profilePhotoContents) {
                    file_put_contents($profileRootPhotoUri, $profilePhotoContents);

                    $blankImageUri = ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS .  'blank_image_800' . '.' . 'jpg';
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

                    ImageManager::configure(['driver' => 'imagick']);
                    $profilePhoto = ImageManager::make($profilePhotoUri);
                    $profilePhoto->resize(400, 400);
                    $profilePhoto->insert(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                    $profilePhoto->save($profilePhotoUri);

                    $postData['image'] = '/img/' . h($postData['foreign_key']) . '.' . 'jpg';
                } else {
                    $postData['image'] = '/yab_cms_ff/img/avatars/avatar.jpg';
                }
            }

            $user = $this->UserProfiles->patchEntity(
                $userProfile,
                Hash::merge($this->getRequest()->getData(), [
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
                ])
            );
            YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeAdd', $this, ['User' => $user]);
            if ($this->UserProfiles->save($user)) {
                YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onAddSuccess', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onAddFailure', $this, ['User' => $user]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $users = $this->UserProfiles->Users->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name_username_email');

        YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeAddRender', $this, [
            'UserProfile' => $userProfile,
            'Users' => $users,
        ]);

        $this->set(compact('userProfile', 'users'));
    }

    /**
     * Edit method
     *
     * @param int|null $id
     *
     * @return \Cake\Http\Response|void|null
     */
    public function edit(int $id = null)
    {
        $userProfile = $this->UserProfiles->get($id, contain: ['Users']);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $postData = $this->getRequest()->getData();

            if (
                !empty($postData['image_file']->getClientFileName()) &&
                !empty($postData['image_file']->getClientMediaType()) &&
                in_array($postData['image_file']->getClientMediaType(), [
                    'image/jpeg',
                    'image/jpg',
                ])
            ) {
                $profileRootPhotoUri = WWW_ROOT . 'img' . DS . h($postData['foreign_key']) . '_' . '.' . 'jpg';
                $profilePhotoUri = WWW_ROOT . 'img' . DS . h($postData['foreign_key']) . '.' . 'jpg';
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

                    ImageManager::configure(['driver' => 'imagick']);
                    $profilePhoto = ImageManager::make($profilePhotoUri);
                    $profilePhoto->resize(400, 400);
                    $profilePhoto->insert(ROOT . DS . 'plugins' . DS . 'YabCmsFf' . DS . 'webroot' . DS . 'img' . DS . 'watermark' . '.' . 'png');
                    $profilePhoto->save($profilePhotoUri);

                    $postData['image'] = '/img/' . h($postData['foreign_key']) . '.' . 'jpg';
                } else {
                    $postData['image'] = '/yab_cms_ff/img/avatars/avatar.jpg';
                }
            }

            $userProfile = $this->UserProfiles->patchEntity(
                $userProfile,
                Hash::merge(
                    $this->getRequest()->getData(),
                    [
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
            YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeEdit', $this, ['UserProfile' => $userProfile]);
            if ($this->UserProfiles->save($userProfile)) {
                YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onEditSuccess', $this, ['UserProfile' => $userProfile]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile has been saved.'),
                    ['element' => 'default', 'params' => ['class' => 'success']]
                );

                return $this->redirect(['action' => 'index']);
            } else {
                YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onEditFailure', $this, ['UserProfile' => $userProfile]);
                $this->Flash->set(
                    __d('yab_cms_ff', 'The user profile could not be saved. Please, try again.'),
                    ['element' => 'default', 'params' => ['class' => 'error']]
                );
            }
        }

        $users = $this->UserProfiles->Users->find('list', order: ['Users.name' => 'ASC'], keyField: 'id', valueField: 'name_username_email');

        YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeEditRender', $this, [
            'UserProfile' => $userProfile,
            'Users' => $users,
        ]);

        $this->set(compact('userProfile', 'users'));
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
        $userProfile = $this->UserProfiles->get($id);
        YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.beforeDelete', $this, ['UserProfile' => $userProfile]);
        if ($this->UserProfiles->delete($userProfile)) {
            YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onDeleteSuccess', $this, ['UserProfile' => $userProfile]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The user profile has been deleted.'),
                ['element' => 'default', 'params' => ['class' => 'success']]
            );
        } else {
            YabCmsFf::dispatchEvent('Controller.Admin.UserProfiles.onDeleteFailure', $this, ['UserProfile' => $userProfile]);
            $this->Flash->set(
                __d('yab_cms_ff', 'The user profile could not be deleted. Please, try again.'),
                ['element' => 'default', 'params' => ['class' => 'error']]
            );
        }

        return $this->redirect(['action' => 'index']);
    }
}
