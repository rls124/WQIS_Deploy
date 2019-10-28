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

    /**
     * Application Controller
     *
     * Add your application-wide methods in the class below, your controllers
     * will inherit them.
     *
     * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
     */
    class AppController extends Controller {

        /**
         * Initialization hook method.
         *
         * Use this method to add common initialization code like loading components.
         *
         * e.g. `$this->loadComponent('Security');`
         *
         * @return void
         */
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

            /*
             * Enable the following components for recommended CakePHP security settings.
             * see https://book.cakephp.org/3.0/en/controllers/components/security.html
             */
            //$this->loadComponent('Security');
            //$this->loadComponent('Csrf');
        }

        /**
         * Before render callback.
         *
         * @param \Cake\Event\Event $event The beforeRender event.
         * @return \Cake\Http\Response|null|void
         */
        public function beforeRender(Event $event) {
            // Note: These defaults are just to get started quickly with development
            // and should not be used in production. You should instead set "_serialize"
            // in each action as required.
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

            if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->getType(), ['application/json', 'application/xml'])
            ) {
                $this->set('_serialize', true);
            }
        }

        public function _fileIsValid($file) {
            $allowed = array('csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel');
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            $fileValidMessage = array('isValid' => false, 'errorMessage' => '');
            if (!in_array($extension, $allowed)) {
                $fileValidMessage['errorMessage'] = 'Incorrect file extension detected';
            } else if (!($file['error'] == UPLOAD_ERR_OK)) {
                $fileValidMessage['errorMessage'] = 'File was unable to be uploaded';
            } else {
                $fileValidMessage['isValid'] = true;
            }
            return $fileValidMessage;
        }
    }