<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;

class UsersController extends AppController {
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		//which pages a user that is not logged in can access
		$this->Auth->allow(["login", "signup", "logout", "forgotpassword", "getSecurityQuestions", "verifySecurityQuestions", "edituserinfo"]);
	}

	public function signup() {
		if ($this->request->is("post")) {
			//Create and set data for a new User
			$user = $this->Users->newEntity();
			$user = $this->Users->patchEntity($user, $this->request->getData());

			$securityAnswer1 = strtolower($this->request->getData("securityanswer1"));
			$securityAnswer2 = strtolower($this->request->getData("securityanswer2"));
			$securityAnswer3 = strtolower($this->request->getData("securityanswer3"));

			//Hash security answers
			$user->securityanswer1 = Security::hash($securityAnswer1, "sha256");
			$user->securityanswer2 = Security::hash($securityAnswer2, "sha256");
			$user->securityanswer3 = Security::hash($securityAnswer3, "sha256");

			//Save user
			if ($this->Users->save($user)) {
				$this->Auth->setUser($user);
				$this->set("admin", $user["admin"]);

				return $this->redirect(["controller" => "siteLocations", "action" => "chartselection"]);
			}

			//If we could not save the user, display the appropriate message
			$username = $this->request->getData("username");
			$usererror = $this->Users
				->find("all")
				->where(["username" => $username])
				->first();
			if (count($usererror) > 0) {
				$this->Flash->error(__("There is already an account with the username: " . $username . ". Please choose another"));
				return;
			}
			$email = $this->request->getData("email");
			$usererror = $this->Users
				->find("all")
				->where(["email" => $email])
				->first();
			if (count($usererror) > 0) {
				$this->Flash->error(__("There is already an account with the email: " . $email . ". Please choose another"));
				return;
			}
			$this->Flash->error(__("The user could not be saved. Please, try again."));
			return;
		}
		$this->set(compact("user"));
		$this->set("_serialize", ["user"]);
	}

	public function login() {
		if ($this->request->is("post")) {
			//Ensure user exists
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				//Set admin status
				$this->set("admin", $user["admin"]);
				return $this->redirect(["controller" => "siteLocations", "action" => "chartselection"]);
			}
			$this->Flash->error("Invalid username or password.");
		}
	}

	public function logout() {
		$this->set("admin", false);
		return $this->redirect($this->Auth->logout());
	}

	public function usermanagement() {
		$Users = $this->Users->find("all")->order(["Created" => "Asc"]);
		$this->set(compact("Users"));
		//If this is post data and it has a username set
		if ($this->request->is("post") && $this->request->getData("username")) {
			//Create and save the entity
			$user = $this->Users->newEntity();
			$user = $this->Users->patchEntity($user, $this->request->getData());
			if ($this->Users->save($user)) {
				return;
			}
		}
	}

	public function deleteUser() {
		$this->render(false);
		//Ensure username is in the POST data
		if (!$this->request->getData("username")) {
			return;
		}
		$username = $this->request->getData("username");
		$user = $this->Users
			->find("all")
			->where(["username" => $username])
			->first();
		$this->Users->delete($user);
	}

	public function adduser() {
		$this->render(false);
		$user = $this->Users->newEntity();
		if ($this->request->is("post")) {
			$user = $this->Users->patchEntity($user, $this->request->getData());

			if ($this->Users->save($user)) {
				return;
			}
		}
	}

	public function fetchuserdata() {
		$this->render(false);
		if (!$this->request->getData("username")) {
			return;
		}
		$username = $this->request->getData("username");
		$user = $this->Users
			->find("all")
			->where(["username" => $username])
			->first();

		$json = json_encode(["username" => $user->username,
			"admin" => $user->admin,
			"firstname" => $user->firstname,
			"lastname" => $user->lastname,
			"email" => $user->email,
			"organization" => $user->organization,
			"position" => $user->position]);
		
		
		$this->response = $this->response->withStringBody($json);
		$this->response = $this->response->withType("json");
	
		return $this->response;
	}

	public function updateuserdata() {
		$this->render(false);
		//Check if username is set
		if (!$this->request->getData("username")) {
			return;
		}
		$username = $this->request->getData("username");

		$user = $this->Users
			->find("all")
			->where(["username" => $username])
			->first();
			
		//Update the data
		$user->firstname = $this->request->getData("firstname");
		$user->lastname = $this->request->getData("lastname");
		$user->email = $this->request->getData("email");
		$user->organization = $this->request->getData("organization");
		$user->position = $this->request->getData("position");
		$user->admin = $this->request->getData("admin");
		$userpw = $this->request->getData("userpw");
		$passConfirm = $this->request->getData("passconfirm");

		if ($userpw !== "" && $userpw === $passConfirm) {
			$user->userpw = $userpw;
		}
		
		if ($this->Users->save($user)) {
			return;
		}
	}

	public function edituserinfo() {
		if ($this->request->is("post") || $this->Auth->User("username")) {
			//If there is no first name, or passwords
			if (!$this->request->getData("firstname") && !($this->request->getData("userpw") && $this->request->getData("passConfirm"))) {
				if ($this->Auth->User("username")) {
					$username = $this->Auth->User("username");
					$user = $this->Users
						->find("all")
						->where(["username" => $username])
						->first();
					$this->set(compact("user"));
				}
				else {
					$username = $this->request->getData("username");
					$this->set(compact("username"));
				}
				return;
			}

			$userpw = $this->request->getData("userpw");
			$passConfirm = $this->request->getData("passConfirm");

			//if the user is logged in
			if ($this->Auth->User("username")) {
				$username = $this->Auth->User("username");
				$firstname = $this->request->getData("firstname");
				$lastname = $this->request->getData("lastname");
				$organization = $this->request->getData("organization");
				$position = $this->request->getData("position");
				$secQ1 = $this->request->getData("securityquestion1");
				$secA1 = strtolower($this->request->getData("securityanswer1"));
				$secQ2 = $this->request->getData("securityquestion2");
				$secA2 = strtolower($this->request->getData("securityanswer2"));
				$secQ3 = $this->request->getData("securityquestion3");
				$secA3 = strtolower($this->request->getData("securityanswer3"));

				$user = $this->Users
					->find("all")
					->where(["username" => $username])
					->first();
				//Update fields
				$user->firstname = $firstname;
				$user->lastname = $lastname;
				$user->organization = $organization;
				$user->position = $position;

				if ($userpw != "" && $userpw == $passConfirm) {
					$user->userpw = $userpw;
				}
				if ($secA1 != "") {
					$user->securityquestion1 = $secQ1;
					$user->securityanswer1 = Security::hash($secA1, "sha256");
				}
				if ($secA2 != "") {
					$user->securityquestion2 = $secQ2;
					$user->securityanswer2 = Security::hash($secA2, "sha256");
				}
				if ($secA3 != "") {
					$user->securityquestion3 = $secQ3;
					$user->securityanswer3 = Security::hash($secA3, "sha256");
				}

				if ($this->Users->save($user)) {
					$this->Flash->success("Your information has been changed");
					return $this->redirect(["controller" => "Users", "action" => "edituserinfo"]);
				}
				else {
					$this->Flash->error("Nothing was saved");
					return;
				}
				//if the user is not logged in
			}
			else {
				$username = $this->request->getData("username");
				if ($userpw != "" && $userpw == $passConfirm) {
					$user = $this->Users
						->find("all")
						->where(["username" => $username])
						->first();

					$user->userpw = $userpw;

					if ($this->Users->save($user)) {
						$this->Flash->success("Your password has been updated");
						if (!$this->Auth->User("username")) {
							return $this->redirect(["controller" => "Users", "action" => "login"]);
						}
						return;
					}
				}
			}
			$this->Flash->error(__("Your password could not be updated"));
			return;
		}
		return $this->redirect(["controller" => "Users", "action" => "login"]);
	}

	public function forgotpassword() {
		if ($this->Auth->User("username")) {
			return $this->redirect(["controller" => "Users", "action" => "edituserinfo"]);
		}
	}

	public function getSecurityQuestions() {
		$this->render(false);
		if ($this->request->is("post")) {
			$username = $this->request->getData("username");
			$user = $this->Users
				->find("all")
				->where(["username" => $username])
				->first();

			if ($user == null) {
				$json = json_encode([
					"Message" => "Error"
				]);
			}
			else {
				$json = json_encode([
					"Message" => "Success",
					"username" => $user->username,
					"securityquestion1" => $user->securityquestion1,
					"securityquestion2" => $user->securityquestion2,
					"securityquestion3" => $user->securityquestion3
				]);
			}
			
			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType("json");
	
			return $this->response;
		}
		return $this->redirect(["controller" => "Users", "action" => "login"]);
	}

	public function verifySecurityQuestions() {
		$this->render(false);
		if ($this->request->is("post")) {
			$username = $this->request->getData("username");
			$securityanswer1 = strtolower($this->request->getData("securityanswer1"));
			$securityanswer2 = strtolower($this->request->getData("securityanswer2"));
			$securityanswer3 = strtolower($this->request->getData("securityanswer3"));

			$user = $this->Users
				->find("all")
				->where(["username" => $username])
				->first();

			$responseMessage = "";
			$responseData = "";

			if ($user == null) {
				$responseMessage = "That username does not exist, please enter another";
				$responseData = "Error";
			}
			else {
				if ($user->securityanswer1 === Security::hash($securityanswer1, "sha256") &&
					$user->securityanswer2 === Security::hash($securityanswer2, "sha256") &&
					$user->securityanswer3 === Security::hash($securityanswer3, "sha256")) {
					$responseData = "GoToUserInformationPage";
				}
				else {
					$responseMessage = "One or more of the provided answers are incorrect";
					$responseData = "Error";
				}
			}

			$json = json_encode([
				"responseMessage" => $responseMessage,
				"responseData" => $responseData
			]);
			
			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType("json");
	
			return $this->response;
		}
		return $this->redirect(["controller" => "Users", "action" => "login"]);
	}
}