<?php
/*
Pages controller. Handles (ideally) static pages in the /pages/ path
*/
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;

class PagesController extends AppController {
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		//Which pages a user that is not logged in can access
		$this->Auth->allow(["display"]);
	}
	
	//this is what gets run before each page loads. Every page in /pages/ uses this, not separate controller methods
	public function display(...$path) {
		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		if (in_array('..', $path, true) || in_array('.', $path, true)) {
			throw new ForbiddenException();
		}
		$page = $subpage = null;

		if (!empty($path[0])) {
			$page = $path[0];
			if ($page != "about" && !$this->Auth->user()) {
				//we only want to allow access to the about page, not help or admin panel, unless the user is logged in
				$this->redirect([
					"controller" => "Users",
					"action" => "login"
				]);
			}
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		$this->set(compact("page", "subpage"));

		try {
			$this->render(implode('/', $path));
		}
		catch (MissingTemplateException $exception) {
			if (Configure::read("debug")) {
				throw $exception;
			}
			throw new NotFoundException();
		}
	}
}