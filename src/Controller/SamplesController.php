<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class SamplesController extends AppController {
	public function removeBOM($str) {
		//remove the UTF-8 byte order mark (BOM). Excel exports CSVs with this, its unneeded and breaks our importer, so remove it
		if (substr($str, 0,3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
			$str = substr($str, 3);
		}
		return $str;
	}
	
	public function tablePages() {
		$this->render(false);
		
		//get request data
		$startDate = date("Ymd", strtotime($_POST["startDate"]));
		$endDate = date("Ymd", strtotime($_POST["endDate"]));
		$sites = $_POST["sites"];
		$amount = $_POST["filterAmount"];
		$searchDirection = $_POST["filterDirection"];
		$filterBy = $_POST["filterBy"];
		$category = $_POST["category"];
		$selectedMeasures = $_POST["selectedMeasures"];
		$aggregate = $_POST["aggregate"];
		
		//set model
		$model = ucfirst($category) . "Samples";
		$this->loadModel($model);
		
		$andConditions = [
			"site_location_id IN " => $sites,
			$model . ".Date >=" => $startDate,
			$model . ".Date <=" => $endDate
		];
		
		$andConditions = SamplesController::ensureNotNullCondition($andConditions, $selectedMeasures);
			
		if ($amount != "") {
			$andConditions = array_merge($andConditions, [$model . "." . $filterBy . " " . $searchDirection => $amount]);
		}
		
		if ($aggregate == "false") {
			//individual mode
			$count = $this->$model->find("all", [
				"conditions" => [
					"and" => $andConditions
				]
			])->count();
		}
		else {
			//aggregate mode
			$count = $this->$model->find("all", [
				"conditions" => [
					"and" => $andConditions
				],
				"group" => "Date"
			])->count();
		}
		
		return $this->response->withType("json")->withStringBody(json_encode($count));
	}

	public function tabledata() {
		$this->render(false);
		
		//get request data
		$startDate = date("Ymd", strtotime($_POST["startDate"]));
		$endDate = date("Ymd", strtotime($_POST["endDate"]));
		$sites = $_POST["sites"];
		$amount = $_POST["filterAmount"];
		$searchDirection = $_POST["filterDirection"];
		$filterBy = $_POST["filterBy"];
		$category = $_POST["category"];
		$selectedMeasures = $_POST["selectedMeasures"];
		$sortBy = $_POST["sortBy"];
		$sortDirection = $_POST["sortDirection"];
		$pageNum = $_POST["pageNum"];
		$numRows = $_POST["numRows"];
		$aggregate = $_POST["aggregate"];
		
		if ($numRows == -1) {
			//just set this to a very large number. Ideally would just remove the limit entirely, but thats a bit more logic
			$numRows = 100000;
		}
		
		//set model
		$model = ucfirst($category) . "Samples";
		$this->loadModel($model);
		
		$andConditions = [
			"site_location_id IN" => $sites,
			$model . ".Date >=" => $startDate,
			$model . ".Date <=" => $endDate
		];
		
		if ($amount != "") {
			$andConditions = array_merge($andConditions, [$model . "." . $filterBy . " " . $searchDirection => $amount]);
		}
		
		$andConditions = SamplesController::ensureNotNullCondition($andConditions, $selectedMeasures);
		
		if ($aggregate == "false") {
			//individual mode
			if ($this->Auth->user("admin")) { //check if user is admin, since non-admins don't see comments
				$fields = array_merge(["site_location_id", "Date", "Sample_Number"], $selectedMeasures, [(ucfirst($category) . "Comments")]);
			}
			else {
				$fields = array_merge(["site_location_id", "Date", "Sample_Number"], $selectedMeasures);
			}
		
			$query = $this->$model->find("all", [
				"fields" => $fields,
				"conditions" => [
					"and" => $andConditions
				],
				"limit" => $numRows,
				"page" => $pageNum
			])->order([$sortBy => $sortDirection]);
		}
		else {
			//aggregate mode
			$query = $this->$model->find();
			
			$selection = ["Date"];
			for ($i=0; $i<sizeof($selectedMeasures); $i++) {
				$selection = array_merge($selection, [$selectedMeasures[$i] => $query->func()->avg($selectedMeasures[$i])]);
			}
			
			$query->select($selection)
				->where($andConditions)
				->group("Date")
				->limit($numRows)
				->page($pageNum)
				->order([$sortBy => $sortDirection]);
		}
		
		return $this->response->withType("json")->withStringBody(json_encode($query));
	}

	public function uploadlog() {
		//record start time so we can figure out how long the whole page took to load, including the controller
		$time = explode(" ", microtime());
		$time = $time[1] + $time[0];
		$startTime = $time;

		//get the data from the file
		$file = $_FILES["file"];
		$fileName = $file["name"]; //store the name of the file like upload.png
		$this->set("fileName", $fileName);
		
		if ($this->request->is("post") && $file) {
			//check if file is valid
			$valid = $this->_fileIsValid($file);
			if (!$valid["isValid"]) {
				$this->set(compact("valid"));
				return;
			}
			
			$csv = array_map("str_getcsv", file($file["tmp_name"]));
			
			$fileTypeName = SamplesController::getFileTypeFromHeaders($csv); //determine filetype from column headers
			$model = $fileTypeName . "Samples";
			
			$this->loadModel("MeasurementSettings");
			$measuresAll = $this->MeasurementSettings->find("all", [
					"conditions" => [
						"and" => ["category" => $fileTypeName]
					],
					"fields" => ["measureKey", "measureName"]
				])
				->order(["measureKey" => "Asc"])
				->all();

			if ($fileTypeName == "Physical") { //hacky temp solution. THESE NEED TO BE ADDED TO THE MEASURES DATABASE
				$columnIDs = ["site_location_id", "Date", "Sample_Number", "Time", "Bridge_to_Water_Height"];
				$columnText = ["Site Number", "Date", "Sample Number", "Time", "Bridge to Water Height"];
			}
			else {
				$columnIDs = ["site_location_id", "Date", "Sample_Number"];
				$columnText = ["Site Number", "Date", "Sample Number"];
			}
			
			foreach ($measuresAll as $measure) {
				$columnIDs[] = $measure->measureKey;
				$columnText[] = $measure->measureName;
			}
			$columnIDs[] = $fileTypeName . "Comments";
			$columnText[] = "Comments";
			
			$this->set("fileTypeName", $fileTypeName . " Samples");
			
			$this->loadModel($model);
		
			$log = array();
		
			$countSuccesses = 0;
			$countFails = 0;
			
			$overwrite = (isset($_POST["overwrite"]) && $_POST["overwrite"] == "true");
		
			//go through each non-header row
			for ($row=1; $row<sizeof($csv); $row++) {
				$currentRow = [];
				$uploadData = [];
			
				//get every column's data in the row
				for ($column=0; $column<sizeof($columnIDs); $column++) {
					$currentElement = $csv[$row][$column];
					$currentColumn = $columnIDs[$column];
					
					$currentRow[] = $currentElement;
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
						//failure (or duplicate sample number)
						//overwrite duplicate sample number if user requested it
						if ($entity->getError("Sample_Number") != NULL && $overwrite) {
							$table = $this->$model
								->find("all")
								->where(["Sample_Number" => $currentRow[2]])
								->first();

							$table->site_location_id = $currentRow[0];
							$table->Date = $currentRow[1];
							switch ($model) {
								case "BacteriaSamples":
									$table->Ecoli = $currentRow[3];
									$table->TotalColiform = $currentRow[4];
									$table->BacteriaComments = $currentRow[5];
									break;
								case "NutrientSamples":
									$table->Phosphorus = $currentRow[3];
									$table->NitrateNitrite = $currentRow[4];
									$table->DRP = $currentRow[5];
									$table->Ammonia = $currentRow[6];
									$table->NutrientComments = $currentRow[7];
									break;
								case "PesticideSamples":
									$table->Atrazine = $currentRow[3];
									$table->Alachlor = $currentRow[4];
									$table->Metolachlor = $currentRow[5];
									$table->PesticideComments = $currentRow[6];
									break;
								case "PhysicalSamples":
									$table->Time = $currentRow[3];
									$table->Bridge_to_Water_Height = $currentRow[4];
									$table->Water_Temp = $currentRow[5];
									$table->pH = $currentRow[6];
									$table->Conductivity = $currentRow[7];
									$table->TDS = $currentRow[8];
									$table->DO = $currentRow[9];
									$table->Turbidity = $currentRow[10];
									$table->PhysicalComments = $currentRow[11];
									break;
							}

							if ($this->$model->save($table)) {
								$countSuccesses++;
							}
							else {
								$currentRow[] = $table->getErrors();
								$log[] = $currentRow;
								$countFails++;
							}
						} 
						else {
							$currentRow[] = $entity->getErrors();
							$log[] = $currentRow;
							$countFails++;
						}
					}
				}
				catch (\PDOException $e) {
					//database error, most likely because of an unmet foreign key constraint (nonexistent site)
					$currentRow[] = "<span style=\"color: red;\">Referenced site number does not exist, add it</span>";
					$log[] = $currentRow;
					$countFails++;
				}
			}
		
			$this->set(compact("log"));
			$this->set(compact("columnText"));
			$this->set("countSuccesses", $countSuccesses);
			$this->set("countFails", $countFails);
			$this->set("startTime", $startTime);
		}
	}

	public function getFileTypeFromHeaders($csv) {
		/*
		use column header names from the CSV to determine which type of file is being uploaded
		rather complicated because we have to not only check which type of file it is, but also make sure that *all* required columns are present and *no* extras are.
		which itself is complicated by the existence of multiple different formats in the sample/export files we're looking at. So check all of the valid options
		*/
		
		$headerRow = $csv[0];
		
		$headerRow[0] = SamplesController::removeBOM($headerRow[0]); //fix issue with Excel exporting CSVs with UTF-8 BOM encoding
		
		$types = ["Bacteria", "Nutrient", "Pesticide", "Physical"];
		
		$bacteriaHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("ecoli"),
			array("total coliform", "total_coliform", "totalcoliform"),
			array("comments"),
		);
		
		$nutrientHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("phosphorus"),
			array("nh3-n", "nitrate nitrite", "nitrate_nitrite", "nitratenitrite"),
			array("drp"),
			array("ammonia"),
			array("comments"),
		);
		
		$pesticideHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("atrazine"),
			array("alachlor"),
			array("metolachlor"),
			array("comments"),
		);
		
		$physicalHeader = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "sample_number"),
			array("time"),
			array("bridge_to_water_height", "bridge to water height"),
			array("water temp", "water_temp", "watertemp", "water temperature", "water_temperature", "watertemperature"),
			array("ph"),
			array("conductivity"),
			array("tds"),
			array("do"),
			array("turbidity"),
			array("comments"),
		);
		
		$nutrientHeaderAmmonia = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("phosphorus"),
			array("drp"),
			array("ammonia"),
			array("comments"),
		);
		$nutrientHeaderNN = array(
			array("site number", "site_number", "sitenumber"),
			array("date"),
			array("sample number", "sample_number", "samplenumber"),
			array("phosphorus"),
			array("nh3-n", "nitrate nitrite", "nitrate_nitrite", "nitratenitrite"),
			array("drp"),
			array("comments"),
		);
		
		$validHeaders = array($bacteriaHeader, $nutrientHeader, $pesticideHeader, $physicalHeader, $nutrientHeaderNN, $nutrientHeaderAmmonia);
		
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
				
				if (!$cellValid) {
					$correctType = false;
					break;
				}
			}
			
			if ($correctType) {
				return $types[$typeNumber];
			}
		}
	}
	
	public function deleteRecord() {
		$this->render(false);

		//ensure sample number data was included
		if (!$this->request->getData("sampleNumber")) {
			return;
		}
		$sampleNumber = $this->request->getData("sampleNumber");
		
		$model = ucfirst($_POST["type"]) . "Samples";
		$this->loadModel($model);
		
		//Get the sample we are deleting
		$sample = $this->$model
			->find("all")
			->where(["Sample_Number" => $sampleNumber])
			->first();
		
		//delete it
		$this->$model->delete($sample);
	}

	public function entryform() {
		if (!isset($_POST["entryType"])) {
			$mode = "submit";
			//already submitted from entry form
			if (isset($_POST["ecoli-0"])) { //bacteria
				$name = "bacteria";
			}
			elseif (isset($_POST["phosphorus-0"])) { //nutrient
				$name = "nutrient";
			}
			elseif (isset($_POST["atrazine-0"])) { //pesticide
				$name = "pesticide";
			}
			elseif (isset($_POST["ph-0"])) { //physical
				$name = "physical";
			}
		}
		else {
			$mode = "entry";
			//trying to go to the entry form
			$name = $_POST["entryType"];
		}
		
		if (!isset($name)) {
			$this->set("mode", "invalid");
			//not valid, just return and let the template handle it
			return;
		}
		
		$model = ucfirst($name) . "Samples";
		if ($name == "bacteria") {
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Ecoli', 'TotalColiform', 'BacteriaComments');
		}
		elseif ($name == "nutrient") {
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Phosphorus', 'NitrateNitrite', 'DRP', 'Ammonia', 'NutrientComments');
		}
		elseif ($name == "pesticide") {
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine', 'Alachlor', 'Metolachlor', 'PesticideComments');
		}
		elseif ($name == "physical") {
			$columns = array('site_location_id', 'Date', 'Sample_Number', 'Time', 'Bridge_to_Water_Height', 'Water_Temp', 'pH', 'Conductivity', 'TDS', 'DO', 'Turbidity', 'PhysicalComments');
		}
		$this->loadModel($model);
		
		$rows = $this->request->getData("totalrows");
		$request = $this->request;
		
		$sample = $this->$model->newEntity();
		
		//check if the request is post, and the request has at least one sample
		if ($request->is("post") && $request->getData("site_location_id-0")) {
			$successes = 0;
			$fails = 0;
			$failsDetailed = "";
			
			//rows start at number 0, meaning we have to include the amount
			for ($i = 0; $i <= $rows; $i++) {
				$rowData = [];
				
				//go through each column and find the postdata name that is associated
				for ($col = 0; $col < sizeof($columns); $col++) {
					$requestField = "";

					if ($columns[$col] !== "Date") {
						$requestField = strtolower($columns[$col]) . "-" . $i;
					}
					else {
						$requestField = $columns[$col];
					}
					$rowData[$columns[$col]] = $request->getData($requestField);
				}
			
				//create the entity to save
				$sample = $this->$model->patchEntity($this->$model->newEntity(), $rowData);
				if ($this->$model->save($sample)) {
					$successes++;
				}
				else {
					$fails++;
					$failsDetailed .= $rowData["Sample_Number"] . ", ";
				}
			}
			if ($successes) {
				$this->Flash->success(__($successes . " " . $name . " sample(s) has been saved."));
			}
			if ($fails) {
				$this->Flash->error(__($fails . ' ' . $name . ' sample(s) could not be saved. Failure on number(s): ' . substr($failsDetailed, 0, strlen($failsDetailed) - 2)));
			}
		}
		
		$siteLocations = $this->$model->SiteLocations->find("all");
		$this->set(compact("sample", "siteLocations"));
		$this->set("_serialize", ["sample"]);
		$this->set('mode', $mode);

		$rawCount = [];
		for ($i=0; $i<=51; $i++) {
			$rawCount[] = $i;
		}
		$this->set(compact("rawCount"));

		$this->set("formType", $name);
	}

	public function add() {
		//API handler for mobile collector app. Currently hardcoded to physical samples for dev purposes
		$this->render(false);
		$this->request->allowMethod(["post", "put"]);
		$modelName = "PhysicalSamples";
		$columns = array("site_location_id", "Date", "Sample_Number", "Time", "Bridge_to_Water_Height", "Water_Temp", "pH", "Conductivity", "TDS", "DO", "Turbidity", "PhysicalComments");
		
		$this->loadModel($modelName);
		$model = $this->$modelName;
		$request = $this->request;
		$sample = $model->newEntity();
		
		//go through each column and find the postdata name that is associated
		for ($col=0; $col<sizeof($columns); $col++) {
			$requestField = "";
			$requestField = $columns[$col];
			if ($request->getData($requestField) == null) {
				$rowData[$columns[$col]] = null;
				if ($col == 12) {
					$rowData[$columns[12]] = " "; //physical comments can't be null in db
				}
			}
			else {
				$rowData[$columns[$col]] = $request->getData($requestField);
			}
		}

		//create the entity to save
		$sample = $model->patchEntity($model->newEntity(), $rowData);
		if ($model->save($sample)) {
			$message = "Sample data was saved!";
		}
		else {
			$message = "Error saving sample data";
		}

		$this->set([
			"message" => $message,
			"_serialize" => ["message"]
		]);

		$this->response = $this->response->withType("text");
		$this->response->getBody()->write($message);
		return $this->response; //return success or error message to the app
	}

	public function updatefield() {
		$this->render(false);
		
		//ensure sample number data was included
		if (!$this->request->getData("sampleNumber")) {
			return;
		}
		$sampleNumber = $this->request->getData("sampleNumber");
		
		$parameter = $this->request->getData("parameter");
		$value = $this->request->getData("value");
		
		if (($exploded = preg_split("@(?=Comments)@", $parameter)) && sizeof($exploded) == 2 && $exploded[1] == "Comments") {
			$model = ucfirst($exploded[0]) . "Samples";
		}
		else {
			$this->loadModel("MeasurementSettings");
			$model = $this->MeasurementSettings->find("all")
				->select(["category"])
				->where(["measureKey" => $parameter])
				->first()->category . "Samples";
		}
		$this->loadModel($model);
		
		//get the sample we are editing
		$sample = $this->$model
			->find("all")
			->where(["Sample_Number" => $sampleNumber])
			->first();

		//save changes
		$sample->$parameter = $value;
		$this->$model->save($sample);
	}
	
	private function ensureNotNullCondition($conditionsList, $selectedMeasures) {
		//don't return rows in which none of the selected measurements actually have data in them
		$notAllNullString = "NOT ( (" . $selectedMeasures[0] . " IS NULL)";
		for ($i=1; $i<sizeof($selectedMeasures); $i++) {
			$notAllNullString = $notAllNullString . " AND (" . $selectedMeasures[$i] . " IS NULL)";
		}
		$notAllNullString = $notAllNullString . ")";
		return array_merge($conditionsList, array($notAllNullString));
	}
	
	public function graphdata() {
		$this->render(false);
		
		//get request data
		$startDate = date("Ymd", strtotime($this->request->getData("startDate")));
		$endDate = date("Ymd", strtotime($this->request->getData("endDate")));
		$sites = $this->request->getData("sites");
		$category = $_POST["category"];
		$amount = $_POST["amount"];
		$searchDirection = $_POST["filterDirection"];
		$filterBy = $_POST["filterBy"];
		$selectedMeasures = $_POST["selectedMeasures"];
		$aggregate = $_POST["aggregate"];
		
		//set model
		$model = ucfirst($category) . "Samples";
		$this->loadModel($model);
		
		$andConditions = [
			"site_location_id IN" => $sites,
			$model . ".Date >=" => $startDate,
			$model . ".Date <= " => $endDate
		];
		
		$andConditions = SamplesController::ensureNotNullCondition($andConditions, $selectedMeasures);
		
		if ($amount != "") {
			$andConditions = array_merge($andConditions, [$model . "." . $filterBy . " " . $searchDirection => $amount]);
		}
		
		if ($aggregate == "false") {
			//individual mode
			$fields = array_merge(["site" => "site_location_id", "Date"], $selectedMeasures);
			
			$query = $this->$model->find("all", [
				"fields" => $fields,
				"conditions" => [
					"and" => $andConditions
				]
			])->order(["Date" => "Asc"]);
		}
		else {
			//aggregate mode. Return average of all sites for each measure and each date
			$query = $this->$model->find();
			
			$selection = ["Date"];
			for ($i=0; $i<sizeof($selectedMeasures); $i++) {
				$selection = array_merge($selection, [$selectedMeasures[$i] => $query->func()->avg($selectedMeasures[$i])]);
			}
			
			$query->select($selection)
				->where($andConditions)
				->group("Date")
				->order(["Date" => "Asc"]);
		}
		
		return $this->response->withType("json")->withStringBody(json_encode($query));
	}
	
	//API methods
	public function getRecords() {
		$this->render(false);
		
		//we use this method both in the API and the site itself, so we have to be able to accept data either as $_POST or session-saved POST
		$session = $this->getRequest()->getSession();
		$postData = $session->check("postData") ? $session->read("postData") : $_POST;
		
		$query = "";
		
		//get request data and pre-process it
		$category = $postData["category"]; //must always be set
		$selectedMeasures = $postData["selectedMeasures"]; //must always be set
		
		//set model
		$model = ucfirst($category) . "Samples";
		$this->loadModel($model);
	
		if (isset($postData["sampleNumbers"])) {
			//we want to get a single sample with these numbers
			$sampleNumbers = $postData["sampleNumbers"];
			
			$query = $this->$model->find("all", [
					"fields" => array_merge(["site_location_id", "Date", "Sample_Number"], $selectedMeasures, [(ucfirst($category) . "Comments")]),
					"conditions" => [
						"and" => ["Sample_Number IN" => $sampleNumbers]
					]
				])->order(["Date" => "Desc"]);
		}
		else {
			//we need more data
			$sites = $postData["sites"];
			$amount = isset($postData["filterAmount"]) ? $postData["filterAmount"] : "";
			$filterBy = isset($postData["filterBy"]) ? $postData["filterBy"] : "";
			$searchDirection = isset($postData["filterDirection"]) ? $postData["filterDirection"] : "=";
			$aggregate = isset($postData["aggregate"]) ? $postData["aggregate"] : "false";
			
			$andConditions = [
				"site_location_id IN" => $sites,
			];
			
			if (isset($postData["startDate"])) {
				$andConditions = array_merge($andConditions, [$model . ".Date >=" => date("Ymd", strtotime($postData["startDate"]))]);
			}
			if (isset($postData["endDate"])) {
				$andConditions = array_merge($andConditions, [$model . ".Date <=" => date("Ymd", strtotime($postData["endDate"]))]);
			}
			if ($amount != "") {
				$andConditions = array_merge($andConditions, [$model . "." . $filterBy . " " . $searchDirection => $amount]);
			}
		
			$andConditions = SamplesController::ensureNotNullCondition($andConditions, $selectedMeasures);
			
			if ($aggregate == "true") {
				//aggregate mode
				$query = $this->$model->find();
				
				$selection = ["Date"];
				for ($i=0; $i<sizeof($selectedMeasures); $i++) {
					$selection = array_merge($selection, [$selectedMeasures[$i] => $query->func()->avg($selectedMeasures[$i])]);
				}
			
				$query->select($selection)
					->where($andConditions)
					->group("Date")
					->order(["Date" => "Desc"]);
			}
			else {
				//individual mode
				if ($this->Auth->user("admin")) { //check if user is admin, since non-admins don't see comments
					$fields = array_merge(["site_location_id", "Date", "Sample_Number"], $selectedMeasures, [(ucfirst($category) . "Comments")]);
				}
				else {
					$fields = array_merge(["site_location_id", "Date", "Sample_Number"], $selectedMeasures);
				}
		
				$query = $this->$model->find("all", [
					"fields" => $fields,
					"conditions" => [
						"and" => $andConditions
					]
				])->order(["Date" => "Desc"]);
			}
		}

		return $this->response->withType("application/json")->withStringBody(json_encode($query));
	}
}