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
			$MeasurementSettings = $this->MeasurementSettings->find("all");
			$this->set(compact("MeasurementSettings"));
		}

		public function updatefield() {
			$this->render(false);

			//Ensure that measure is in POST data
			if (!$this->request->getData("measure")) {
				return;
			}
			$measure = $this->request->getData("measure");

			//Get the settings we are editing
			$settings = $this->MeasurementSettings
				->find("all")
				->where(["measureKey" => $measure])
				->first();
			$parameter = $this->request->getData("parameter");
			$value = $this->request->getData("value");
			//Set the edited field
			$settings->$parameter = $value;
			//Save changes
			$this->MeasurementSettings->save($settings);
		}
		
		public function settingsData() {
			$this->render(false);
			
			$bacteria = $this->MeasurementSettings->find("all")
				->where(["Category" => "Bacteria"]);
				
			$pesticide = $this->MeasurementSettings->find("all")
				->where(["Category" => "Pesticide"]);
				
			$nutrient = $this->MeasurementSettings->find("all")
				->where(["Category" => "Nutrient"]);
				
			$physical = $this->MeasurementSettings->find("all")
				->where(["Category" => "Physical"]);
			
			$this->response = $this->response->withStringBody(json_encode(["bacteria" => $bacteria, "nutrient" => $nutrient, "pesticide" => $pesticide, "physical" => $physical]));
			$this->response = $this->response->withType("json");
		
			return $this->response;
		}
		
		public function benchmarkdata() {
			$this->render(false);
		
			$benchmarks = $this->MeasurementSettings->find("all", [
				"fields" => [
					"measureKey",
					"min" => "benchmarkMinimum",
					"max" => "benchmarkMaximum"
				]
			]);
		
			$this->response = $this->response->withStringBody(json_encode($benchmarks));
			$this->response = $this->response->withType("json");
		
			return $this->response;
		}
	}
?>