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
			
			$amount = $_POST["amountEnter"];
			$searchRange = $_POST["overUnderSelect"];
			
			if ($measures == "") {
				$this->log("measures was nothing", 'debug');
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
			
			if ($inputType == "bacteria") {
				$this->loadModel('BacteriaSamples');
			
				$modelName = "BacteriaSamples";
				$modelBare = $this->BacteriaSamples;
				$measureType='Ecoli';
			}
			else if ($inputType == "nutrient") {
				$this->loadModel('NutrientSamples');
				
				$modelName = "NutrientSamples";
				$modelBare = $this->NutrientSamples;
			
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
			else if ($inputType == "pesticide") {
				$this->loadModel('PesticideSamples');
			
				$modelName = "PesticideSamples";
				$modelBare = $this->PesticideSamples;
			
				if ($measurementSelect == "alachlor") {
					$measureType='Alachlor';
				}
				else if ($measurementSelect == "atrazine") {
					$measureType='Atrazine';
				}
				else if ($measurementSelect == "metolachlor") {
					$measureType='Metolachlor';
				}
			}
			elseif ($inputType == "physical") {
				$this->loadModel('PhysicalSamples');
				
				$modelName = "PhysicalSamples";
				$modelBare = $this->PhysicalSamples;
				
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
					'Date  <= ' => $endDate,
					$measureType . $searchDirection => $amount
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
							'site_location_id' => $sites[0],
							$modelName . '.Date >=' => $startDate,
							$modelName . '.Date <= ' => $endDate,
							$modelName . '.' . $measureType . $searchDirection => $amount
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