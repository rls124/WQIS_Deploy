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
			
			$data = "";
			$sampleQuery = $modelBare;
			
			//All the conditions that must be true go here
			$andConditions = [];
			array_push($andConditions, [
				'Date  >=' => $startDate,
				'Date  <= ' => $endDate,
				$measurementSelect . $searchDirection => $amount
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
							$modelName . '.' . $measurementSelect . $searchDirection => $amount
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