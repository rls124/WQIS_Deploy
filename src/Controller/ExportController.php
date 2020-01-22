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
			//get request data
			$startDate = date('Ymd', strtotime($this->request->getData('startDate')));
			$endDate = date('Ymd', strtotime($this->request->getData('endDate')));
			$sites = $this->request->getData('sites');
			$amount = $_POST["amountEnter"];
			$searchDirection = $_POST["overUnderSelect"];
			$measurementSearch = $_POST["measurementSearch"];
			$category = $this->request->getData('category');
			$selectedMeasures = $_POST["selectedMeasures"];
			
			$modelName = ucfirst($category) . "Samples";
			$this->loadModel($modelName);
			$modelBare = $this->$modelName;
			
			$data = "";
			$sampleQuery = $modelBare;
			
			$fields = ['site_location_id', 'Date', 'Sample_Number'];
			$fields = array_merge($fields, $selectedMeasures);
		
			$andConditions = [
				'site_location_id IN' => $sites,
				$modelName . '.Date >=' => $startDate,
				$modelName . '.Date <= ' => $endDate
			];
		
			if ($amount != '') {
				$andConditions = array_merge($andConditions, [$modelName . '.' . $measurementSearch . ' ' . $searchDirection => $amount]);
			}
		
			$data = $this->$modelName->find('all', [
				'fields' => $fields,
				'conditions' => [
					'and' => $andConditions
				]
			])->order(['Date' => 'Desc']);

			$this->set(compact('data'));
			return $this->response->withType("application/json")->withStringBody(json_encode($data));
		}
	}
?>