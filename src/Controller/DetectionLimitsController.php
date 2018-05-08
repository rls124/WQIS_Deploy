<?php

	namespace App\Controller;

	use App\Controller\AppController;

	/**
	 * DetectionLimits Controller
	 *
	 * @property \App\Model\Table\DetectionLimitsTable $DetectionLimits
	 *
	 * @method \App\Model\Entity\DetectionLimit[] paginate($object = null, array $settings = [])
	 */
	class DetectionLimitsController extends AppController {

		public function limits() {
			$DetectionLimits = $this->DetectionLimits->find('all');
			$this->set(compact('DetectionLimits'));
		}

		public function updatefield() {
			$this->render(false);
			//Ensure that measure is in POST data
			if (!$this->request->getData('measure')) {
				return;
			}
			$measure = $this->request->getData('measure');

			//Get the detection limit we are editing
			$DetectionLimit = $this->DetectionLimits
				->find('all')
				->where(['Measure = ' => $measure])
				->first();
			$parameter = $this->request->getData('parameter');
			$value = $this->request->getData('value');
			//Set the edited field
			$DetectionLimit->$parameter = $value;
			//Save changes
			$this->DetectionLimits->save($DetectionLimit);
		}

	}
