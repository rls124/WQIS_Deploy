<?php

    namespace App\Controller;

    use App\Controller\AppController;

    class GenericSamplesController extends AppController {

	public function uploadlog() {
		//get the data from the file
		$file = $this->request->getData('file');
		
		if ($this->request->is('post') && $file) {
			//Check if file is valid
			$valid = $this->_fileIsValid($file);
			if (!$valid['isValid']) {
				$this->set(compact('valid'));
				return;
			}
			
			$csv = array_map('str_getcsv', file($file['tmp_name']));
			
			$fileType = GenericSamplesController::getFileType($csv);
			
			if ($fileType == 1) {
				//bacteria
				$this->loadModel('BacteriaSamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'EcoliRawCount', 'Ecoli', 'EcoliException', 'TotalColiformRawCount', 'TotalColiform', 'ColiformException', 'Comments');
				$columnText = array("Site Number", "Date", "Sample Number", "Ecoli Raw Count", "Ecoli", "Total Coliform Raw Count", "Total Coliform", "Comments");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->BacteriaSamples);
			}
			else if ($fileType == 2) {
				//nutrient
				$this->loadModel('NutrientSamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Phosphorus', 'PhosphorusException', 'NitrateNitrite', 'NitrateNitriteException', 'DRP', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Phosphorus (mg/L)", "Nitrate/Nitrite (mg/L)", "Dissolved Reactive Phosphorus", "Comments");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->NutrientSamples);
			}
			else if ($fileType == 3) {
				//pesticide
				$this->loadModel('PesticideSamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine', 'AltrazineException', 'Alachlor', 'AlachlorException', 'Metolachlor', 'MetolachlorException', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Atrazine", "Alachlor", "Metolachlor", "Comments");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->PesticideSamples);
			}
			else if ($fileType == 4) {
				//wqm
				$this->loadModel('WaterQualitySamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Time', 'Water_Temp',
					'Water_Temp_Exception', 'pH', 'pH_Exception', 'Conductivity', 'Conductivity_Exception', 'TDS',
					'TDS_Exception', 'DO', 'DO_Exception', 'Turbidity', 'Turbidity_Exception', 'Turbidity_Scale_Value',
					'Comments', 'Import_Date', 'Import_Time', 'Requires_Checking');
				$columnText = array("Site Number", "Date", "Sample number", "Time", "Water Temp", "PH", "Conductivity", "TDS", "DO", "Turbidity", "Turbidity (scale value)", "Comments", "Import Date", "Import Time", "Requires Checking");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->WaterQualitySamples);
			}
			else if ($fileType == 5) {
				//site information
				$this->loadModel('SiteLocations');
				$columnIDs = array('Site_Number', 'Monitored', 'Longitude', 'Latitude', 'Site_Location', 'Site_Name');
				$columnText = array("Site Number", "Longitude", "Latitude", "Site Location", "Site Name");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->SiteLocations);
			}
		}
	}

	public function getFileType($csv) {
		//these terms are unique to their respective file type (and complex enough to not be accidentally found inside another string)
		$bacteriaUniqueTerm = "coliform";
		$nutrientUniqueTerm = "phosphorus";
		$pesticideUniqueTerm = "atrazine";
		$WQMUniqueTerm = "conductivity";
		$siteInfoUniqueTerm = "longitude";
		
		$headerRow = implode(",", $csv[0]);
		
		if (stripos($headerRow, $bacteriaUniqueTerm) !== false) {
			return 1;
		}
		else if (stripos($headerRow, $nutrientUniqueTerm) !== false) {
			return 2;
		}
		else if (stripos($headerRow, $pesticideUniqueTerm) !== false) {
			return 3;
		}
		else if (stripos($headerRow, $WQMUniqueTerm) !== false) {
			return 4;
		}
		else if (stripos($headerRow, $siteInfoUniqueTerm) !== false) {
			return 5;
		}
	}
	
	public function uploadGeneric($columnIDs, $columnsText, $csv, $modelBare) {
		$log = array();
		
		$countSuccesses = 0;
		$countFails = 0;
		
		//go through each non-header row
		for ($row=1; $row<sizeof($csv); $row++) {
			$currentRow = array();
			$uploadData = [];
			
			//Get every column's data in the row
		    for ($column = 0; $column < sizeof($columnIDs); $column++) {
				$currentElement = $csv[$row][$column];
				$currentColumn = $columnIDs[$column];
				//Check if the current column name does not contain exception
				if (strpos($currentColumn, "Exception") === false) {
					$currentRow[] = $currentElement;
				}

				$uploadData[$currentColumn] = $currentElement;
		    }
			
			//create the entity to save
			$entity = $modelBare->patchEntity($modelBare->newEntity(), $uploadData);
			
			if ($modelBare->save($entity)) {
				//success
				
				//$currentRow[] = "File uploaded successfully";
				//we don't care about successful rows at all, just increment the number of successes
				$countSuccesses++;
			}
			else {
				//failure
				$currentRow[] = $entity->getErrors();
				$log[] = $currentRow;
				$countFails++;
			}
			//$log[] = $currentRow;
		}
		
		$this->set(compact('log'));
		$this->set(compact('columnsText'));
		$this->set('countSuccesses', $countSuccesses);
		$this->set('countFails', $countFails);
	}
}