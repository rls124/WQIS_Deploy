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
			$startdate = date('Ymd', strtotime($this->request->getData('startdate')));
			$enddate = date('Ymd', strtotime($this->request->getData('enddate')));
			$sites = $this->request->getData('sites');

			$measures = $this->request->getData('measures');
			$inputType = $this->request->getData('type');
			
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
				case 'wqm':
					$this->loadModel("WaterQualitySamples");
					$sampleQuery = $this->WaterQualitySamples;
					break;
				default:
					$sampleQuery = "";
					break;
			}
			if ($sampleQuery !== "") {
				//All the conditions that must be true go here
				$andConditions = [];
				array_push($andConditions, [
					'Date  >=' => $startdate,
					'Date  <= ' => $enddate
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