<?php
	namespace App\Controller;

	use App\Controller\AppController;

	/**
	 * MeasurementSettings Controller
	 *
	 * @property \App\Model\Table\MeasurementSettingsTable $MeasurementSettings
	 *
	 * @method \App\Model\Entity\MeasurementSettings[] paginate($object = null, array $settings = [])
	 */
	class MeasurementSettingsController extends AppController {
		public function measurementsettings() {
			$MeasurementSettings = $this->MeasurementSettings->find('all');
			$this->set(compact('MeasurementSettings'));
		}

		public function updatefield() {
			$this->render(false);

			//Ensure that measure is in POST data
			if (!$this->request->getData('measure')) {
				return;
			}
			$measure = $this->request->getData('measure');

			//Get the settings we are editing
			$settings = $this->MeasurementSettings
				->find('all')
				->where(['measureKey = ' => $measure])
				->first();
			$parameter = $this->request->getData('parameter');
			$value = $this->request->getData('value');
			//Set the edited field
			$settings->$parameter = $value;
			//Save changes
			$this->MeasurementSettings->save($settings);
		}
	}
?>