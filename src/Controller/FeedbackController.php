<?php
	namespace App\Controller;

	use App\Controller\AppController;
	use Cake\Event\Event;
	use Cake\Log\Log;

	class FeedbackController extends AppController {
		public function beforeFilter(Event $event) {
			parent::beforeFilter($event);
			//Which pages a user that is not logged in can access
			$this->Auth->allow(["userfeedback"]);
		}
		
		public function userFeedback() {
			$Feedback = $this->Feedback->newEntity();
			
			//check if user is logged in
			if ($this->Auth->user()) {
				$this->set("loggedIn", true);
				if ($this->request->is('post')) {
					//get POST data and store it in the new entity
					$Feedback->Feedback = $this->request->getData('feedback');
					$Feedback->User = $this->Auth->User('username');
					//save entity
					if ($this->Feedback->save($Feedback)) {
						$this->Flash->success(__('Your feedback has been saved.'));

						return $this->redirect(['action' => 'userfeedback']);
					}
					else {
						$this->Flash->error(__('The feedback could not be saved. Please, try again.'));
					}
				}
			}
			else {
				$this->set("loggedIn", false);
				if ($this->request->is('post')) {
					//get POST data and store it in the new entity
					$Feedback->Feedback = $this->request->getData("feedback");
					$Feedback->Name = $this->request->getData("name");
					$Feedback->Email = $this->request->getData("email");
					//save entity
					if ($this->Feedback->save($Feedback)) {
						$this->Flash->success(__('Your feedback has been saved.'));

						return $this->redirect(['action' => 'userfeedback']);
					}
					else {
						$this->Flash->error(__('The feedback could not be saved. Please, try again.'));
					}
				}
			}
		}

		public function adminFeedback() {
			$FeedbackText = $this->Feedback->find('all')->order(['Date' => 'Desc']);
			$this->set(compact('FeedbackText'));
		}

		public function deleteFeedback() {
			$this->render(false);
			//Ensure the request has the relevant field
			if (!$this->request->getData('ID')) {

				return;
			}

			$id = $this->request->getData('ID');
			//Get the feedback
			$record = $this->Feedback
				->find('all')
				->where(['ID = ' => $id])
				->first();
			//Delete it
			$this->Feedback->delete($record);
		}
	}