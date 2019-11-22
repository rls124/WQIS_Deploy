<?php
    namespace App\Controller;

    use App\Controller\AppController;

    class GenericSamplesController extends AppController {
	
	public function removeBOM($str) {
		//remove the UTF-8 byte order mark (BOM). Excel exports CSVs with this, its unneeded and breaks our importer, so remove it
		if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
			$str=substr($str, 3);
		}
		return $str;
	}	
	
	public function tableview() {
		//check if there is post data
		if ($this->request->getData()) {
			$type = $_POST["categorySelect"];
			
			//set all relevant POST data to variables
			$startdate = date('Ymd', strtotime($this->request->getData('startdate')));
			$enddate = date('Ymd', strtotime($this->request->getData('enddate')));
			$site = $this->request->getData('site');
			$amount = $_POST["amountEnter"];
			$searchRange = $_POST["overUnderSelect"];
			$measurementSelect = $_POST["measurementSelect"];

		}
		else {
			$type = $_SESSION["tableType"];
			//set all relevant SESSION data to variables
			
			$startdate = date('Ymd', strtotime($_SESSION['startdate']));
			$enddate = date('Ymd', strtotime($_SESSION['enddate']));
			$site = $_SESSION["site"];
			$amount = $_SESSION["amountEnter"];
			$searchRange = $_SESSION["overUnderSelect"];
			$measurementSelect = $_SESSION["measurementSelect"];
		
		}
		
		if($searchRange == "over"){
			$searchDirection = ' >=';
		}else if($searchRange == "under"){
			$searchDirection = ' <=';
		}else if($searchRange == "equals"){
			$searchDirection = ' ==';
		}
		
		if ($type == "bacteria") {
			$this->loadModel('BacteriaSamples');
			
			$modelName = "BacteriaSamples";
			$modelBare = $this->BacteriaSamples;
			$measureType='Ecoli';
		}
		else if ($type == "nutrient") {
			$this->loadModel('NutrientSamples');
			
			$modelName = "NutrientSamples";
			$modelBare = $this->NutrientSamples;
			
			if($measurementSelect == 'nitrateNitrite'){
				$measureType='NitrateNitrite';
			}else if($measurementSelect == 'phosphorus'){
				$measureType='Phosphorus';
			}else if($measurementSelect == 'drp'){
				$measureType='DRP';
			}else if($measurementSelect == 'ammonia'){
				$measureType='Ammonia';
			}
		}
		else if ($type == "pesticide") {
			$this->loadModel('PesticideSamples');
			
			$modelName = "PesticideSamples";
			$modelBare = $this->PesticideSamples;
			
			if($measurementSelect == "alachlor"){
				$measureType='Alachlor';
			}else if($measurementSelect == "atrazine"){
				$measureType='Atrazine';
			}else if($measurementSelect == "metolachlor"){
				$measureType='Metolachlor';
			}
		}
		elseif ($type == "wqm") {
			$this->loadModel('WaterQualitySamples');
			
			$modelName = "WaterQualitySamples";
			$modelBare = $this->WaterQualitySamples;
			
			if($measurementSelect == 'conductivity'){
				$measureType='Conductivity';
			}else if($measurementSelect == 'do'){
				$measureType='DO';
			}else if($measurementSelect == 'bridge_to_water_height'){
				$measureType='Bridge_to_Water_Height';
			}else if($measurementSelect == 'ph'){
				$measureType='pH';
			}else if($measurementSelect == 'water_temp'){
				$measureType='Water_Temp';
			}else if($measurementSelect == 'tds'){
				$measureType='TDS';
			}else if($measurementSelect == 'turbidity'){
				$measureType='Turbidity';
			}
		}
	
		if($amount!=''){
		$samples = $this->paginate(
		$modelBare->find('all', [
			'conditions' => [
			
			'and' => [
				'site_location_id' => $site,				
				$modelName . '.Date >=' => $startdate,
				$modelName . '.Date <= ' => $enddate,
				$modelName . '.' . $measureType . $searchDirection => $amount
			]
				
	
			]
		])->order(['Date' => 'Desc'])
		);
		}else{
		$samples = $this->paginate(
		$modelBare->find('all', [
			'conditions' => [
			
			'and' => [
				'site_location_id' => $site,
				$modelName == "BacteriaSamples",					
				$modelName . '.Date >=' => $startdate,
				$modelName . '.Date <= ' => $enddate,
			]
				
			]
		])->order(['Date' => 'Desc'])
		);
		}
		
		//get the info about the site number
		$siteLocation = $modelBare->SiteLocations->find('all', [
			'conditions' => [
			'Site_number' => $site
			]
		])->first();
		
		//write data into session
	
		$this->request->getSession()->write([
			'startdate' => $startdate,
			'enddate' => $enddate,
			'site' => $site,
			'tableType' => $type,
			'siteLocation' => $siteLocation
		]);
		
		$this->set(compact('siteLocation'));
		$this->set(compact('samples'));
		$this->set('_serialize', ['samples']);
		$this->set('sampleType', $type);
	}

	public function uploadlog() {
		//record start time so we can figure out how long the whole page took to load, including the controller
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$startTime = $time;
		
		//get the data from the file
		$file = $_FILES['file'];
		$fileName = $_FILES['file']['name']; // store the name of the file like upload.png
		$this->set('fileName', $fileName);
		
		if ($this->request->is('post') && $file) {
			//Check if file is valid
			$valid = $this->_fileIsValid($file);
			if (!$valid['isValid']) {
				$this->set(compact('valid'));
				return;
			}
			
			$csv = array_map('str_getcsv', file($file['tmp_name']));
			
			$fileType = GenericSamplesController::getFileTypeFromHeaders($csv); //determine filetype from column headers
			
			if ($fileType == 1) {
				//bacteria
				$model = "BacteriaSamples";
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'EcoliRawCount', 'Ecoli', 'EcoliException', 'TotalColiformRawCount', 'TotalColiform', 'ColiformException', 'Comments');
				$columnText = array("Site Number", "Date", "Sample Number", "Ecoli Raw Count", "Ecoli", "Total Coliform Raw Count", "Total Coliform", "Comments");
				
				$this->set("fileTypeName", "Bacteria Samples");
			}
			else if ($fileType == 2) {
				//nutrient
				$model = "NutrientSamples";
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Phosphorus', 'PhosphorusException', 'NitrateNitrite', 'NitrateNitriteException', 'DRP', 'Ammonia', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Phosphorus (mg/L)", "Nitrate/Nitrite (mg/L)", "Dissolved Reactive Phosphorus", "Ammonia", "Comments");
				
				$this->set("fileTypeName", "Nutrient Samples");
			}
			else if ($fileType == 3) {
				//pesticide
				$model = "PesticideSamples";
				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine', 'AltrazineException', 'Alachlor', 'AlachlorException', 'Metolachlor', 'MetolachlorException', 'Comments');
				$columnText = array("Site Number", "Date", "Sample number", "Atrazine", "Alachlor", "Metolachlor", "Comments");
				
				$this->set("fileTypeName", "Pesticide Samples");
			}
			else if ($fileType == 4) {
				//wqm
				$model = "WaterQualitySamples";
 				$columnIDs = array('site_location_id', 'Date', 'Sample_Number', 'Time', 'Bridge_to_Water_Height', 'Water_Temp', 'Water_Temp_Exception', 'pH', 'pH_Exception','Conductivity', 'Conductivity_Exception', 'TDS', 'TDS_Exception', 'DO', 'DO_Exception', 'Turbidity', 'Turbidity_Exception', 'Turbidity_Scale_Value', 'Comments', 'Import_Date', 'Import_Time', 'Requires_Checking');
				$columnText = array("Site Number", "Date", "Sample number", "Time", "Bridge to Water Height", "Water Temp", "PH", "Conductivity", "TDS", "DO", "Turbidity", "Turbidity (scale value)", "Comments", "Import Date", "Import Time", "Requires Checking");
				
				$this->set("fileTypeName", "Water Quality Samples");
			}
			else if ($fileType == 5) {
				//site information
				$model = "SiteLocations";
				$columnIDs = array('Site_Number', 'Monitored', 'Longitude', 'Latitude', 'Site_Location', 'Site_Name');
				$columnText = array("Site Number", "Longitude", "Latitude", "Site Location", "Site Name");
				
				$this->set("fileTypeName", "Site info");
			}
			
			$this->loadModel($model);
		
			$log = array();
		
			$countSuccesses = 0;
			$countFails = 0;
		
			$numColumns = sizeof($columnIDs);
		
			//go through each non-header row
			for ($row=1; $row<sizeof($csv); $row++) {
				$currentRow = [];
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
				try {
					$entity = $this->$model->patchEntity($this->$model->newEntity(), $uploadData);
				
					if ($this->$model->save($entity)) {
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
				catch (\PDOException $e) {
					//database error, most likely because of an unmet foreign key constraint (nonexistent site)
					$currentRow[] = "<span style=\"color: red;\">Referenced site number does not exist, add it</span>";
					$log[] = $currentRow;
					$countFails++;
				}
			}
		
			$this->set(compact('log'));
			$this->set(compact('columnText'));
			$this->set('countSuccesses', $countSuccesses);
			$this->set('countFails', $countFails);
			$this->set('startTime', $startTime);
		}
	}

	public function getFileTypeFromHeaders($csv) {
		/*
		use column header names from the CSV to determine which type of file is being uploaded
		rather complicated because we have to not only check which type of file it is, but also make sure that *all* required columns are present and *no* extras are.
		which itself is complicated by the existence of multiple different formats in the sample/export files we're looking at. So check all of the valid options
		*/
		
		$headerRow = $csv[0];
		
		$headerRow[0] = GenericSamplesController::removeBOM($headerRow[0]); //fix issue with Excel exporting CSVs with UTF-8 BOM encoding
		
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
			array("ammonia"),
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
			array("bridge_to_water_height", "bridge to water height"),
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
	
	public function deleteRecord() {
		$this->render(false);

		//ensure sample number data was included
		if (!$this->request->getData('sampleNumber')) {
			return;
		}
		$sampleNumber = $this->request->getData('sampleNumber');
		
		if ($_POST["type"] == "bacteria") {
			$this->loadModel('BacteriaSamples');
			$modelBare = $this->BacteriaSamples;
		}
		elseif ($_POST["type"] == "nutrient") {
			$this->loadModel('NutrientSamples');
			$modelBare = $this->NutrientSamples;
		}
		elseif ($_POST["type"] == "pesticide") {
			$this->loadModel('PesticideSamples');
			$modelBare = $this->PesticideSamples;
		}
		elseif ($_POST["type"] == "wqm") {
			$this->loadModel('WaterQualitySamples');
			$modelBare = $this->WaterQualitySamples;
		}
		
		//Get the sample we are deleting
		$sample = $modelBare
			->find('all')
			->where(['Sample_Number = ' => $sampleNumber])
			->first();
		
		//delete it
		$modelBare->delete($sample);
	}

	public function entryform() {
		if (isset($_POST["ecolirawcount-0"]) || $_POST["entryType"] == "bacteria") { //should return true either if we're trying to go to the entry form, or already submitted from it. Need to clean this up later
			$this->loadModel('BacteriaSamples');
			
			$name = "bacteria";
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'EcoliRawCount',
				'Ecoli', 'EcoliException', 'TotalColiformRawCount', 'TotalColiform', 'ColiformException', 'Comments');
			
			$modelBare = $this->BacteriaSamples;
		}
		elseif (isset($_POST["phosphorus-0"]) || $_POST["entryType"] == "nutrient") { //nutrient form or form submission
			$this->loadModel('NutrientSamples');
			
			$name = "nutrient";
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Phosphorus',
				'PhosphorusException', 'NitrateNitrite', 'NitrateNitriteException', 'DRP', 'Ammonia', 'Comments');
			
			$modelBare = $this->NutrientSamples;
		}
		elseif (isset($_POST["atrazine-0"]) || $_POST["entryType"] == "pesticide") { //pesticide form or form submission
			$this->loadModel('PesticideSamples');
			
			$name = "pesticide";
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine',
				'AltrazineException', 'Alachlor', 'AlachlorException', 'Metolachlor', 'MetolachlorException', 'Comments');
			
			$modelBare = $this->PesticideSamples;
		}
		elseif (isset($_POST["ph-0"]) || $_POST["entryType"] == "wqm") { //water quality form or form submission
			$this->loadModel('WaterQualitySamples');
			
			$name = "waterQualitySample";
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Time',
				'Bridge_to_Water_Height', 'Water_Temp',
				'Water_Temp_Exception', 'pH', 'pH_Exception', 'Conductivity', 'Conductivity_Exception', 'TDS',
				'TDS_Exception', 'DO', 'DO_Exception', 'Turbidity', 'Turbidity_Exception', 'Turbidity_Scale_Value',
				'Comments', 'Import_Date', 'Import_Time', 'Requires_Checking');
			
			$modelBare = $this->WaterQualitySamples;
		}
		
		$rows = $this->request->getData('totalrows');
		$request = $this->request;
		
		$sample = $modelBare->newEntity();
		
		//check if the request is post, and the request has at least one sample
		if ($request->is('post') && $request->getData('site_location_id-0')) {
			$successes = 0;
			$fails = 0;
			$failsDetailed = "";
			
			//rows start at number 0, meaning we have to include the amount
			for ($i = 0; $i <= $rows; $i++) {
				$rowData = [];
				
				//go through each column and find the postdata name that is associated
				for ($col = 0; $col < sizeof($columns); $col++) {
					$requestField = "";

					if ($columns[$col] !== 'Date') {
						$requestField = strtolower($columns[$col]) . "-" . $i;
					}
					else {
						$requestField = $columns[$col];
					}
					$rowData[$columns[$col]] = $request->getData($requestField);
				}
			
				//create the entity to save
				$sample = $modelBare->patchEntity($modelBare->newEntity(), $rowData);
				if ($modelBare->save($sample)) {
					$successes++;
				}
				else {
					$fails++;
					$failsDetailed .= $rowData['Sample_Number'] . ', ';
				}
			}
			if ($successes) {
				$this->Flash->success(__($successes . ' ' . $name . ' sample(s) has been saved.'));
			}
			if ($fails) {
				$this->Flash->error(__($fails . ' ' . $name . ' sample(s) could not be saved. Failure on number(s): ' . substr($failsDetailed, 0, strlen($failsDetailed) - 2)));
			}
		}
		
		$siteLocations = $modelBare->SiteLocations->find('all');
		$this->set(compact('sample', 'siteLocations'));
		$this->set('_serialize', ['sample']);

		$rawCount = [];
		for ($i = 0; $i <= 51; $i++) {
			$rawCount[] = $i;
		}
		$this->set(compact('rawCount'));
		
		if (isset($_POST["entryType"])) {
			$this->set('formType', $_POST["entryType"]);
		}
	}

	public function updatefield() {
		$this->render(false);
		
		//Ensure sample number data was included
		if (!$this->request->getData('sampleNumber')) {
			return;
		}
		$sampleNumber = $this->request->getData('sampleNumber');
		
		$parameter = $this->request->getData('parameter');
		$parameter = strtolower($parameter); //shouldn't need to do this, but it'll reduce the risk of someone fucking this up again. Like I did.
		$value = $this->request->getData('value');
		
		if ($parameter == "ecolirawcount" || $parameter == "ecoli" || $parameter == "totalcoliformrawcount" || $parameter == "totalcoliform" || $parameter == "bacteriacomments") { //bacteria
			$this->loadModel('BacteriaSamples');
			$modelBare = $this->BacteriaSamples;
		}
		elseif ($parameter == "nitratenitrite" || $parameter == "phosphorus" || $parameter == "drp" || $parameter == "ammonia" || $parameter == "nutrientcomments") { //nutrient
			$this->loadModel('NutrientSamples');
			$modelBare = $this->NutrientSamples;
		}
		elseif ($parameter == "atrazine" || $parameter == "alachlor" || $parameter == "metolachlor" || $parameter == "pesticidecomments") { //pesticide
			$this->loadModel('PesticideSamples');
			$modelBare = $this->PesticideSamples;
		}
		elseif ($parameter == "conductivity" || $parameter == "do" || $parameter == "bridge_to_water_height" || $parameter == "ph" || $parameter == "water_temp" || $parameter == "tds" || $parameter == "turbidity" || $parameter == "waterqualitycomments") { //water quality meter
			$this->loadModel('WaterQualitySamples');
			$modelBare = $this->WaterQualitySamples;
		}
		
		else {
			$this->log("request: " . print_r($_POST, true), 'debug');
		}
		
		//Get the sample we are editing
		$sample = $modelBare
			->find('all')
			->where(['Sample_Number = ' => $sampleNumber])
			->first();
		//Set the edited field
		$parameter = $this->request->getData('parameter');
		$sample->$parameter = $value;
		//Save changes
		$modelBare->save($sample);
	}

	public function chartView() {
		$this->loadModel("SiteLocations");
		$siteLocations = $this->SiteLocations->find('all');

		$this->set(compact('siteLocations'));
		$this->set('_serialize', ['siteLocations']);
		$this->set('chartType', $_POST["categorySelect"]);
	}

	public function graphdata() {
		$this->render(false);
		$this->loadModel("Benchmarks");
		
		//get request data
		$startdate = date('Ymd', strtotime($this->request->getData('startdate')));
		$enddate = date('Ymd', strtotime($this->request->getData('enddate')));
		$sites = $this->request->getData('sites');
		$measure = $this->request->getData('measure');
		
		//we cant get the category directly from POST data, so determine it from the measures we get. Not efficient, not pretty, good enough
		if ($measure == "ecoli") { //bacteria category
			$model = "BacteriaSamples";

			//Set the name of the measure
			switch ($measure . "") {
			case 'ecoli':
				$thresMeasure = 'E. coli. (CFU/100 ml)';
				break;
			default:
				$thresMeasure = $measure;
				break;
			}
		}
		elseif (in_array($measure, ["nitrateNitrite", "phosphorus", "drp", "ammonia"])) { //nutrient
			$model = "NutrientSamples";

			//Set the name of the measure
			switch ($measure . "") {
			case 'phosphorus':
				$thresMeasure = 'Total Phosphorus (mg/L)';
				break;
			case 'nitrateNitrite':
				$thresMeasure = 'Nitrate/Nitrite (mg/L)';
				break;
			case 'drp':
				$thresMeasure = 'Dissolved Reactive Phosphorus (mg/L)';
				break;
			case 'ammonia':
				$thresMeasure = 'Ammonia (mg/L)';
			default:
				$thresMeasure = $measure;
				break;
			}
		}
		elseif (in_array($measure, ["alachlor", "atrazine", "metolachlor"])) { //pesticide
			$model = "PesticideSamples";

			//set the name of the measure
			switch ($measure . "") {
			case 'alachlor':
				$thresMeasure = 'Alachlor (µg/L)';
				break;
			case 'atrazine':
				$thresMeasure = 'Atrazine (µg/L)';
				break;
			case 'metolachlor':
				$thresMeasure = 'Metolachlor (µg/L)';
				break;
			default:
				$thresMeasure = $measure;
				break;
			}
		}
		elseif (in_array($measure, ["conductivity", "do", "ph", "water_temp", "tds", "turbidity", "bridge_to_water_height"])) { //water quality meter
			$model = "WaterQualitySamples";

			//Set the name of the measure
			switch ($measure . "") {
			case 'conductivity':
				$thresMeasure = 'Conductivity (mS/cm)';
				break;
			case 'do':
				$thresMeasure = 'Dissolved Oxygen (mg/L)';
				break;
			case 'ph':
				$thresMeasure = 'pH';
				break;
			case 'water_temp':
				$thresMeasure = 'Water Temperature%';
				break;
			case 'bridge_to_water_height':
				$thresMeasure = 'Bridge to Water Height';
				break;
			case 'tds':
				$thresMeasure = 'Total Dissolved Solids (g/L)';
				break;
			case 'turbidity':
				$thresMeasure = 'Turbidity (NTU)';
				break;
			default:
				$thresMeasure = $measure;
				break;
			}
		}
		
		$this->loadModel($model);
		
		//Get theshold data
		$threshold = $this->Benchmarks->find('all', [
			'fields' => [
			'min' => 'Minimum_Acceptable_Value',
				'max' => 'Maximum_Acceptable_Value'
			],
			'conditions' => [
				'and' => [
				'Measure LIKE' => $thresMeasure
				]
			]
		]);
		
		//If there is no min/max for theshold, set as null
		if ($threshold->isEmpty()) {
			$threshold = [['min' => NULL, 'max' => NULL]];
		}
		
		//Get data requested
		$samples = $this->$model->find('all', [
			'fields' => [
			'site' => 'site_location_id',
			'date' => 'Date',
			'value' => $measure
			],
			'conditions' => [
			'and' => [
				'site_location_id IN ' => $sites,
				[
				$model . '.Date >=' => $startdate,
				$model . '.Date <= ' => $enddate
				]
			]
			]
		])->order(['Date' => 'ASC']);
		
		$this->response = $this->response->withStringBody(json_encode([$samples, $threshold]));
		$this->response = $this->response->withType('json');
		
		return $this->response;
	}

	public function daterange() {
		//current implementation is probably not what we actually want. Checks if theres any data for any sample type. Need to fix later		
		
		$this->render(false);
		
		$this->loadModel("BacteriaSamples");
		$this->loadModel("NutrientSamples");
		$this->loadModel("PesticideSamples");
		$this->loadModel("WaterQualitySamples");
		
		//ensure that sites is in POST data
		if (!$this->request->getData('sites')) {
			return;
		}
		
		$sites = $this->request->getData('sites');
		
		//get min/max date of all the sites for each sample type
		
		//bacteria
		$measureQuery = $this->BacteriaSamples
				->find('all', [
				'conditions' => [
					'site_location_id IN ' => $sites
				],
				'fields' => [
					'mindate' => 'MIN(Date)',
					'maxdate' => 'MAX(Date)'
				]
				])->first();
		
		$bacteriaMinDate = date('m/d/Y', strtotime($measureQuery['mindate']));
		$bacteriaMaxDate = date('m/d/Y', strtotime($measureQuery['maxdate']));
					
		//nutrient
		$measureQuery = $this->NutrientSamples
				->find('all', [
				'conditions' => [
					'site_location_id IN ' => $sites
				],
				'fields' => [
					'mindate' => 'MIN(Date)',
					'maxdate' => 'MAX(Date)'
				]
				])->first();
				
		$nutrientMinDate = date('m/d/Y', strtotime($measureQuery['mindate']));
		$nutrientMaxDate = date('m/d/Y', strtotime($measureQuery['maxdate']));
		
		
		//pesticide
		$measureQuery = $this->PesticideSamples
				->find('all', [
				'conditions' => [
					'site_location_id IN ' => $sites
				],
				'fields' => [
					'mindate' => 'MIN(Date)',
					'maxdate' => 'MAX(Date)'
				]
				])->first();
				
		$pesticideMinDate = date('m/d/Y', strtotime($measureQuery['mindate']));
		$pesticideMaxDate = date('m/d/Y', strtotime($measureQuery['maxdate']));
		
		
		//WQM
		$measureQuery = $this->WaterQualitySamples
				->find('all', [
				'conditions' => [
					'site_location_id IN ' => $sites
				],
				'fields' => [
					'mindate' => 'MIN(Date)',
					'maxdate' => 'MAX(Date)'
				]
				])->first();
				
		$wqmMinDate = date('m/d/Y', strtotime($measureQuery['mindate']));
		$wqmMaxDate = date('m/d/Y', strtotime($measureQuery['maxdate']));	
		
		//find the overall min and max
		$mins = array($bacteriaMinDate, $nutrientMinDate, $pesticideMinDate, $wqmMinDate);
		$maxes = array($bacteriaMaxDate, $nutrientMaxDate, $pesticideMaxDate, $wqmMaxDate);
		
		$minDate = $mins[0];
		$maxDate = $maxes[0];
		
		for ($i=1; $i<count($mins); $i++) {
			if ($minDate > $mins[$i]) {
				$minDate = $mins[$i];
			}
			
			if ($maxDate < $maxes[$i]) {
				$maxDate = $maxes[$i];
			}
		}
		
		$dateRange = [$minDate, $maxDate];
		
		$this->response = $this->response->withStringBody(json_encode([$minDate, $maxDate]));
		$this->response = $this->response->withType('json');
				
		return $this->response;
	}

	public function getmonitoredsites() {
		$this->render(false);
		$this->loadModel("SiteLocations");
		//Get monitored sites
		$monitoredSites = $this->SiteLocations
		->find('all', [
		'conditions' => [
			'Monitored' => "1"
		],
		'fields' => [
			'Site_Number'
		]
		]);
		$this->response->body(json_encode($monitoredSites));
		return $this->response;
	}
}