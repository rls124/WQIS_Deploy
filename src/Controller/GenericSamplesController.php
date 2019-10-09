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
				
				$this->set("fileTypeName", "Bacteria Samples");
			}
			else if ($fileType == 2) {
				//nutrient
				$this->loadModel('NutrientSamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Phosphorus', 'PhosphorusException', 'NitrateNitrite', 'NitrateNitriteException', 'DRP', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Phosphorus (mg/L)", "Nitrate/Nitrite (mg/L)", "Dissolved Reactive Phosphorus", "Comments");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->NutrientSamples);
				
				$this->set("fileTypeName", "Nutrient Samples");
			}
			else if ($fileType == 3) {
				//pesticide
				$this->loadModel('PesticideSamples');
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine', 'AltrazineException', 'Alachlor', 'AlachlorException', 'Metolachlor', 'MetolachlorException', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Atrazine", "Alachlor", "Metolachlor", "Comments");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->PesticideSamples);
				
				$this->set("fileTypeName", "Pesticide Samples");
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
				
				$this->set("fileTypeName", "Water Quality Samples");
			}
			else if ($fileType == 5) {
				//site information
				$this->loadModel('SiteLocations');
				$columnIDs = array('Site_Number', 'Monitored', 'Longitude', 'Latitude', 'Site_Location', 'Site_Name');
				$columnText = array("Site Number", "Longitude", "Latitude", "Site Location", "Site Name");
				GenericSamplesController::uploadGeneric($columnIDs, $columnText, $csv, $this->SiteLocations);
				
				$this->set("fileTypeName", "Site info");
			}
		}
	}

	public function getFileType($csv) {
		/*
		use column header names from the CSV to determine which type of file is being uploaded
		rather complicated because we have to not only check which type of file it is, but also make sure that *all* required columns are present and *no* extras are.
		which itself is complicated by the existence of multiple different formats in the sample/export files we're looking at. So check all of the valid options
	
		many of these entries will disappear once we obsolete the exception columns
		*/
		
		$headerRow = $csv[0];
		
		$bacteriaHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("ecoliraw", "ecolirawcount", "ecoli raw", "ecoli_raw", "ecoli raw count", "ecoli_raw_count"),
			array("ecoli"),
			array("ecoli exception", "ecoli_exception", "ecoliexception"),
			array("total coliform raw", "total_coliform_raw", "totalcoliformraw", "total coliform raw count", "total_coliform_raw_count", "totalcoliformrawcount"),
			array("total coliform", "total_coliform", "totalcoliform"),
			array("coliform exception", "coliform_exception", "coliformexception", "total coliform exception", "total_coliform_exception", "totalcoliformexception"),
			array("comments"),
		);
		
		$nutrientHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("phosphorus"),
			array("phosphorus exception", "phosphorus_exception", "phosphorusexception"),
			array("nh3-n", "nitrate nitrite", "nitrate_nitrite", "nitratenitrite"),
			array("nh3-n exception", "nh3-n_exception", "nh3-nexception", "nitrate nitrite exception", "nitrate_nitrite_exception", "nitratenitriteexception"),
			array("drp"),
			array("comments"),
		);
		
		$pesticideHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("atrazine"),
			array("atrazine exception", "atrazine_exception", "atrazineexception"),
			array("alachlor"),
			array("alachor exception", "alachor_exception", "alachlorexception"),
			array("metolachlor"),
			array("metolachor exception", "metolachor_exception", "metolachlorexception"),
			array("comments"),
		);
		
		$WQMHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "sample_number"),
			array("time"),
			array("water temp", "water_temp", "watertemp", "water temperature", "water_temperature", "watertemperature"),
			array("water temp exception", "water_temp_exception", "watertempexception", "water temperature exception", "water_temperature_exception", "watertemperatureexception"),
			array("ph"),
			array("ph exception", "ph_exception", "phexception"),
			array("conductivity"),
			array("conductivity exception", "conductivity_exception", "conductivityexception"),
			array("tds"),
			array("tds exception", "tds_exception", "tdsexception"),
			array("do"),
			array("do exception", "do_exception", "doexception"),
			array("turbidity (meter reading)"),
			array("turbidity exception", "turbidity_exception", "turbidityexception"),
			array("turbidity (scale value)"),
			array("comments"),
			array("import date", "import_date", "importdate"),
			array("import time", "import_time", "importtime"),
			array("requires checking", "requires_checking", "requireschecking"),
		);
		
		$siteInfoHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("longitude"),
			array("latitude"),
			array("site location", "site_location", "sitelocation"),
			array("site name", "site_name", "sitename"),
		);
		
		$validHeaders = array($bacteriaHeader, $nutrientHeader, $pesticideHeader, $WQMHeader, $siteInfoHeader);
		
		for ($typeNumber=0; $typeNumber<sizeof($validHeaders); $typeNumber++) {
			$correctType = true;
			
			for ($i=0; $i<sizeof($headerRow); $i++) {
				$cell = strtolower($headerRow[$i]); //eliminates capitalization as a concern
				$cellValid = false;
				
				for ($j=0; $j<sizeof($validHeaders[$typeNumber]); $j++) {
					//check each variant
					for ($k=0; $k<sizeof($validHeaders[$typeNumber][$j]); $k++) {
						if ($cell == $validHeaders[$typeNumber][$j][$k]) {
							$cellValid = true;
							break 2;
						}
					}
				}
				
				if ($cellValid == false) {
					$correctType = false;
					break;
				}
			}
			
			if ($correctType == true) {
				return $typeNumber + 1;
			}
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
				//success! For output we don't care about successful rows at all, just increment the number of successes
				$countSuccesses++;
			}
			else {
				//failure
				$currentRow[] = $entity->getErrors();
				$log[] = $currentRow;
				$countFails++;
			}
		}
		
		$this->set(compact('log'));
		$this->set(compact('columnsText'));
		$this->set('countSuccesses', $countSuccesses);
		$this->set('countFails', $countFails);
	}
}