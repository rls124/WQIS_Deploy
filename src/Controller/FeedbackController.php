<?php
	namespace App\Controller;

	use App\Controller\AppController;
	use Cake\Log\Log;

	class FeedbackController extends AppController {
		public function userFeedback() {
			$Feedback = $this->Feedback->newEntity();
			if ($this->request->is('post')) {
				//Get Post Data and store it in the new entity
				$Feedback->Feedback = $this->request->getData('feedback');
				$Feedback->User = $this->Auth->User('username');
				//Save entity
				if ($this->Feedback->save($Feedback)) {
					$this->Flash->success(__('Your feedback has been saved.'));

					return $this->redirect(['action' => 'userfeedback']);
				}
				else {
					$this->Flash->error(__('The feedback could not be saved. Please, try again.'));
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