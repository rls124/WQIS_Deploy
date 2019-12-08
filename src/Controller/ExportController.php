<?php
	namespace App\Controller;

	use App\Controller\AppController;

	class ExportController extends AppController {
		public function export() {
			$this->loadModel("SiteLocations");
			$siteLocations = $this->SiteLocations->find('all');

			$this->set(compact('siteLocations'));
			$this->set('_serialize', ['siteLocations']);
		}

		public function exportData() {
			//Get POST data
			$startDate = date('Ymd', strtotime($this->request->getData('startDate')));
			$endDate = date('Ymd', strtotime($this->request->getData('endDate')));
			$sites = $this->request->getData('sites');

			$measures = $this->request->getData('measures');
			$inputType = $this->request->getData('type');
			
			if ($measures == "") {
				$this->log("measures was nothing", 'debug');
				$measures = 'all';
			}
			
			$data = "";
			//Load appropriate model and set the appropriate queryable object
			switch ($inputType . "") {
				case 'bacteria':
					$this->loadModel("BacteriaSamples");
					$sampleQuery = $this->BacteriaSamples;
					break;
				case 'nutrient':
					$this->loadModel("NutrientSamples");
					$sampleQuery = $this->NutrientSamples;
					break;
				case 'pesticide':
					$this->loadModel("PesticideSamples");
					$sampleQuery = $this->PesticideSamples;
					break;
				case 'physical':
					$this->loadModel("PhysicalSamples");
					$sampleQuery = $this->PhysicalSamples;
					break;
				default:
					$sampleQuery = "";
					break;
			}
			if ($sampleQuery !== "") {
				//All the conditions that must be true go here
				$andConditions = [];
				array_push($andConditions, [
					'Date  >=' => $startDate,
					'Date  <= ' => $endDate
				]);

				if (!in_array('all', $sites)) {
					array_push($andConditions, ['site_location_id IN ' => $sites]);
				}
				//The fields that will be returned
				$fields = [];
				if (!in_array('all', $measures)) {
					array_push($fields, 'site_location_id');
					array_push($fields, 'Date');
					array_push($fields, 'Sample_Number');
					//Push each selected measure into the fields
					foreach ($measures as $m) {
						array_push($fields, $m);
					}
				}

				$data = $sampleQuery->find('all', [
					'fields' => $fields,
					'conditions' => [
						'and' => [
							$andConditions
						]
					]
				]);
			}
			else {
				$data = ['error' => 'input type not found', 'listType' => $inputType];
			}

			$this->set(compact('data'));
			
			return $this->response->withType("application/json")->withStringBody(json_encode($data));
		}
	}