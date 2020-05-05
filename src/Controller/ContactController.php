<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class ContactController extends AppController {
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		//which pages a user that is not logged in can access
		$this->Auth->allow(["contact"]);
	}
	
	public function contact() {
		$this->loadModel("Feedback");
		$Feedback = $this->Feedback->newEntity();
		
		//check if user is logged in
		if ($this->Auth->user()) {
			$this->set("loggedIn", true);
			if ($this->request->is("post")) {
				//get POST data and store it in the new entity
				$Feedback->Feedback = $this->request->getData("feedback");
				$Feedback->User = $this->Auth->User("username");
				//save entity
				if ($this->Feedback->save($Feedback)) {
					$this->Flash->success(__("Your message has been sent."));

					return $this->redirect(["action" => "contact"]);
				}
				else {
					$this->Flash->error(__("Your message could not be sent. Please try again later."));
				}
			}
		}
		else {
			$this->set("loggedIn", false);
			if ($this->request->is("post")) {
				//get POST data and store it in the new entity
				$Feedback->Feedback = $this->request->getData("feedback");
				$Feedback->Name = $this->request->getData("name");
				$Feedback->Email = $this->request->getData("email");
				//save entity
				if ($this->Feedback->save($Feedback)) {
					$this->Flash->success(__("Your message has been sent."));

					return $this->redirect(["action" => "contact"]);
				}
				else {
					$this->Flash->error(__("Your message could not be sent. Please try again later."));
				}
			}
		}
	}

	public function viewFeedback() {
		$this->loadModel("Feedback");
		$FeedbackText = $this->Feedback->find("all")->order(["Date" => "Desc"]);
		$this->set("hasFeedback", $FeedbackText->count() > 0);
		$this->set(compact("FeedbackText"));
	}

	public function deleteFeedback() {
		$this->render(false);
		$this->loadModel("Feedback");
		//Ensure the request has the relevant field
		if (!$this->request->getData("ID")) {
			return;
		}

		$id = $this->request->getData("ID");
		//Get the feedback
		$record = $this->Feedback
			->find("all")
			->where(["ID" => $id])
			->first();
		//Delete it
		$this->Feedback->delete($record);
	}
}