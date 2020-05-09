<?php
namespace App\View;

use Cake\Event\EventManager;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

class AjaxView extends AppView {
	public $layout = "ajax";

	public function initialize() {
		parent::initialize();

		$this->response = $this->response->withType("ajax");
	}
}