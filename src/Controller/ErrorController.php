<?php
namespace App\Controller;

use Cake\Event\Event;

/*
Error Handling Controller

Controller used by ExceptionRenderer to render error responses
*/
class ErrorController extends AppController {
	public function initialize() {
		$this->loadComponent("RequestHandler");
	}

	public function beforeFilter(Event $event) {
		//do nothing
	}

	public function beforeRender(Event $event) {
		parent::beforeRender($event);

		$this->viewBuilder()->setTemplatePath("Error");
	}

	public function afterFilter(Event $event) {
		//do nothing
	}
}