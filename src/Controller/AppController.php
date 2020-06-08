<?php
/*
App controller. Mainly handles user login
*/
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller {
	public function initialize() {
		parent::initialize();
		
		$this->loadComponent("RequestHandler", [
			"enableBeforeRedirect" => false,
		]);

		$this->loadComponent("RequestHandler");
		$this->loadComponent("Flash");
		
        if ($this->request->getParam("controller") === "API") {
			$this->loadComponent("Auth", [
				"loginAction" => [
					"controller" => "Users",
					"action" => "login"
				],
				"authError" => "You are not authorized to view this page.",
				"authenticate" => [
					"Basic" => [
						"fields" => ["username" => "username", "password" => "userpw"]
					]
				],
				"storage" => "Session"
			]);
		
			$user = $this->Auth->identify();
			$params = $this->request->getAttribute("params")["pass"];
			$session = $this->getRequest()->getSession();
			
			$postData = json_decode(file_get_contents("php://input"), true);
			
			$session->write("postData", $postData);
			
			$this->redirect(["controller" => $params[0], "action" => $params[1]]);
		}
		else {
			$this->loadComponent("Auth", [
				"loginAction" => [
					"controller" => "Users",
					"action" => "login"
				],
				"authError" => "You are not authorized to view this page.",
				"authenticate" => [
					"Form" => [
						"fields" => ["username" => "username", "password" => "userpw"]
					]
				],
				"storage" => "Session"
			]);
		}
	}

	public function beforeFilter(Event $event) {
		if ($this->request->getParam("_ext") === "json") {
			$this->Auth->setConfig("authenticate", [
				"Basic" => [
					"fields" => ["username" => "username", "password" => "userpw"]
				]]);
			$this->Auth->setConfig("storage", "Memory");
			$this->Auth->setConfig("unauthorizedRedirect", false);
		}
		return parent::beforeFilter($event);
	}

	public function beforeRender(Event $event) {
		if ($this->request->getParam("_ext") !== "json") {
			if ($this->request->getParam("controller") === "API") {
				$tarController = $this->request->getParam("action");
				$tarAction = $this->request->getAttribute('params')["pass"][0];
				$this->loadComponent("Auth", [
					"loginAction" => [
						"controller" => "Users",
						"action" => "login"
					],
					"authError" => "You are not authorized to view this page.",
					"authenticate" => [
						"Basic" => [
							"fields" => ["username" => "username", "password" => "userpw"]
						]
					],
					"storage" => "Session"
				]);
			
				$session = $this->getRequest()->getSession();
				$session->write("postData", $_POST);
				$this->redirect(["controller" => $tarController, "action" => $tarAction]);
			}
			else {
				$this->loadComponent("Auth", [
					"loginAction" => [
						"controller" => "Users",
						"action" => "login"
					],
					"authError" => "You are not authorized to view this page.",
					"authenticate" => [
						"Form" => [
							"fields" => ["username" => "username", "password" => "userpw"]
						]
					],
					"storage" => "Session"
				]);
			}
			
			$this->set("pageName", substr($this->request->getUri(), strrpos($this->request->getUri(), "/") + 1));

			$this->set("userinfo", $this->Auth->user());
			$this->set("admin", $this->Auth->user("admin"));
		}
	}

	public function _fileIsValid($file) {
		$allowed = array("csv", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel");
		$extension = pathinfo($file["name"], PATHINFO_EXTENSION);

		$fileValidMessage = array("isValid" => false, "errorMessage" => "");
		if (!in_array($extension, $allowed)) {
			$fileValidMessage["errorMessage"] = "Incorrect file extension detected";
		}
		else if (!($file["error"] == UPLOAD_ERR_OK)) {
			$fileValidMessage["errorMessage"] = "File was unable to be uploaded";
		}
		else {
			$fileValidMessage["isValid"] = true;
		}
		return $fileValidMessage;
	}
}