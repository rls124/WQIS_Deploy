<?php
    /**
     * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
     * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
     *
     * Licensed under The MIT License
     * For full copyright and license information, please see the LICENSE.txt
     * Redistributions of files must retain the above copyright notice.
     *
     * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
     * @link      https://cakephp.org CakePHP(tm) Project
     * @since     0.2.9
     * @license   https://opensource.org/licenses/mit-license.php MIT License
     */

    namespace App\Controller;

    use Cake\Controller\Controller;
    use Cake\Event\Event;

    class AppController extends Controller {
        public function initialize() {
            parent::initialize();
			
			$this->loadComponent('RequestHandler', [
				'enableBeforeRedirect' => false,
			]);

            $this->loadComponent('RequestHandler');
            $this->loadComponent('Flash');

            $this->loadComponent('Auth', [
                'loginAction' => [
                    'controller' => 'Users',
                    'action' => 'login'
                ],
                'authError' => 'You are not authorized to view this page.',
                'authenticate' => [
                    'Form' => [
                        'fields' => ['username' => 'username', 'password' => 'userpw']
                    ]
                ],
                'storage' => 'Session'
            ]);
			
            //$this->loadComponent('Csrf'); //disabled because it breaks AJAX. Need to handle the token for this, evaluating impact
        }

        public function beforeRender(Event $event) {
            $this->loadComponent('Auth', [
                'loginAction' => [
                    'controller' => 'Users',
                    'action' => 'login'
                ],
                'authError' => 'You are not authorized to view this page.',
                'authenticate' => [
                    'Form' => [
                        'fields' => ['username' => 'username', 'password' => 'userpw']
                    ]
                ],
                'storage' => 'Session'
            ]);

            $this->set('userinfo', $this->Auth->user());
            $this->set('admin', $this->Auth->user('admin'));
        }

        public function _fileIsValid($file) {
            $allowed = array('csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel');
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            $fileValidMessage = array('isValid' => false, 'errorMessage' => '');
            if (!in_array($extension, $allowed)) {
                $fileValidMessage['errorMessage'] = 'Incorrect file extension detected';
            }
			else if (!($file['error'] == UPLOAD_ERR_OK)) {
                $fileValidMessage['errorMessage'] = 'File was unable to be uploaded';
            }
			else {
                $fileValidMessage['isValid'] = true;
            }
            return $fileValidMessage;
        }
    }