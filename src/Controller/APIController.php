<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class APIController extends AppController {
	//does nothing, placeholder used to suppress errors because of the way we're routing data and internally redirecting
	public function routeapi() {
	}
	
	//can be used by developers using the API to validate that they are indeed connecting/authenticating/sending JSON data correctly. If successful, returns data POSTed into it
	public function apitest() {
		$this->render(false);
		
		$session = $this->getRequest()->getSession();
		$postData = $session->read("postData");
	
		return $this->response->withType("application/json")->withStringBody(json_encode($postData));
	}
	
	public function apiversion() {
		return $this->response->withType("application/json")->withStringBody("1.0");
	}
}