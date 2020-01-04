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
			$category = $this->request->getData('type');
			$measurementSelect = $this->request->getData('measurementSelect');
			$amount = $_POST["amountEnter"];
			$searchRange = $_POST["overUnderSelect"];
			
			$modelName = ucfirst($category) . "Samples";
			$this->loadModel($modelName);
			$modelBare = $this->$modelName;
			
			if ($measures == "") {
				$measures = 'all';
			}
			
			if ($searchRange == "over") {
				$searchDirection = ' >=';
			}
			else if($searchRange == "under") {
				$searchDirection = ' <=';
			}
			else if($searchRange == "equals") {
				$searchDirection = ' ==';
			}
			
			if ($category == "bacteria") {
				$measureType='Ecoli';
			}
			else if ($category == "nutrient") {
				if ($measurementSelect == 'nitrateNitrite') {
					$measureType='NitrateNitrite';
				}
				else if ($measurementSelect == 'phosphorus') {
					$measureType='Phosphorus';
				}
				else if ($measurementSelect == 'drp') {
					$measureType='DRP';
				}
				else if ($measurementSelect == 'ammonia') {
					$measureType='Ammonia';
				}
			}	
			else if ($category == "pesticide") {
				$measureType = $measurementSelect;
			}
			elseif ($category == "physical") {
				if ($measurementSelect == 'conductivity') {
					$measureType='Conductivity';
				}
				else if ($measurementSelect == 'do') {
					$measureType='DO';
				}
				else if ($measurementSelect == 'bridge_to_water_height') {
					$measureType='Bridge_to_Water_Height';
				}
				else if ($measurementSelect == 'ph') {
					$measureType='pH';
				}
				else if ($measurementSelect == 'water_temp') {
					$measureType='Water_Temp';
				}
				else if ($measurementSelect == 'tds') {
					$measureType='TDS';
				}
				else if($measurementSelect == 'turbidity') {
					$measureType='Turbidity';
				}
			}
			
			$data = "";
			$sampleQuery = $modelBare;
			
			//All the conditions that must be true go here
			$andConditions = [];
			array_push($andConditions, [
				'Date  >=' => $startDate,
				'Date  <= ' => $endDate,
				$measureType . $searchDirection => $amount
			]);

			if (!in_array('all', $sites)) {
				array_push($andConditions, ['site_location_id IN ' => $sites]);
			}
			
			//the fields that will be returned
			$fields = [];
			if (!in_array('all', $measures)) {
				array_push($fields, 'site_location_id');
				array_push($fields, 'Date');
				array_push($fields, 'Sample_Number');
				
				//push each selected measure into the fields
				foreach ($measures as $m) {
					array_push($fields, $m);
				}
			}
				
			if ($amount != '') {
				$data = $sampleQuery->find('all', [
					'fields' => $fields,
					'conditions' => [
						'and' => [
							'site_location_id IN ' => $sites,
							$modelName . '.Date >=' => $startDate,
							$modelName . '.Date <= ' => $endDate,
							$modelName . '.' . $measureType . $searchDirection => $amount
						]
					]
				]);
			}
			else {
				$data = $sampleQuery->find('all', [
					'fields' => $fields,
					'conditions' => [
						'and' => [
							'site_location_id IN ' => $sites,
							$modelName . '.Date >=' => $startDate,
							$modelName . '.Date <= ' => $endDate,
						]
					]
				]);
			}

			$this->set(compact('data'));
			
			return $this->response->withType("application/json")->withStringBody(json_encode($data));
		}
	}
?>