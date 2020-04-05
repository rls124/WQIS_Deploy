<?php
	namespace App\Controller;

	use App\Controller\AppController;
	use Cake\Datasource\ConnectionManager;
	/**
	 * SiteLocations Controller
	 *
	 * @property \App\Model\Table\SiteLocationsTable $SiteLocations
	 *
	 * @method \App\Model\Entity\SiteLocation[] paginate($object = null, array $settings = [])
	 */
	class SiteLocationsController extends AppController {
		public function chartselection() {
			//do nothing, we don't need any data retrieved until the client-side js requests it
		}
		
		public function chartsInitData() {
			//get measurementSettings, sites, and groups all in a single query to reduce loading time
			$this->render(false);
			$this->loadModel("MeasurementSettings");
			$this->loadModel("SiteGroups");
			
			$bacteriaSettings = $this->MeasurementSettings->find("all")
				->where(["Category" => "Bacteria"]);
				
			$pesticideSettings = $this->MeasurementSettings->find("all")
				->where(["Category" => "Pesticide"]);
				
			$nutrientSettings = $this->MeasurementSettings->find("all")
				->where(["Category" => "Nutrient"]);
				
			$physicalSettings = $this->MeasurementSettings->find("all")
				->where(["Category" => "Physical"]);
			
			$groups = $this->SiteGroups->find("all");
			
			//get the sites
			$sites = $this->SiteLocations->find("all")->order("Site_Number");
			$connection = ConnectionManager::get("default");
			
			$mapData = ["SiteData" => $sites];
			$tableNames = ["bacteria_samples", "nutrient_samples", "pesticide_samples", "physical_samples"];
			
			for ($i=0; $i<sizeof($tableNames); $i++) {
				$query = "select * from (select site_location_id, max(Date) as maxdate from " .
					$tableNames[$i] .
					" group by site_location_id) as x inner join " .
					$tableNames[$i] .
					" as f on f.site_location_id = x.site_location_id and f.Date = x.maxdate ORDER BY `f`.`site_location_id` ASC";
					
				$queryResult = $connection->execute($query)->fetchAll("assoc");
				$mapData = array_merge($mapData, [$tableNames[$i] => $queryResult]);
			}
			
			$json = json_encode([
				"settings" => ["bacteria" => $bacteriaSettings, "nutrient" => $nutrientSettings, "pesticide" => $pesticideSettings, "physical" => $physicalSettings],
				"groups" => $groups,
				"mapData" => $mapData
			]);
			
			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType("json");
		
			return $this->response;
		}

		public function daterange() {
			$this->render(false);

			$sites = $this->request->getData("sites");
			
			$model = ucfirst($this->request->getData("category")) . "Samples";
			$this->loadModel($model);
			
			//get min/max date of all the sites
			$measureQuery = $this->$model
				->find("all", [
					"conditions" => [
						"site_location_id IN " => $sites
					],
					"fields" => [
						"mindate" => "MIN(Date)",
						"maxdate" => "MAX(Date)"
					]
				])->first();
			
			//format date properly
			$mindate = date("m/d/Y", strtotime($measureQuery["mindate"]));
			$maxdate = date("m/d/Y", strtotime($measureQuery["maxdate"]));
			$dateRange = json_encode([$mindate, $maxdate]);
			
			$this->response = $this->response->withStringBody($dateRange);
			$this->response = $this->response->withType("json");
			
			return $this->response;
		}

		public function sitemanagement() {
			$SiteLocations = $this->SiteLocations->find("all")->order(["Site_Number" => "ASC"]);
			$numSites = $SiteLocations->count();
			
			$this->set(compact("SiteLocations"));
			$this->set(compact("numSites"));
		}
		
		public function updatefield() {
			$this->render(false);
		
			//Ensure sample number data was included
			if (!$this->request->getData("siteNumber")) {
				return;
			}
			$siteNumber = $this->request->getData("siteNumber");
		
			$parameter = $this->request->getData("parameter");
			$value = $this->request->getData("value");
			
			if ($parameter != "groups") {
				//Get the site we are editing
				$site = $this->SiteLocations
					->find("all")
					->where(["Site_Number" => $siteNumber])
					->first();
				//Set the edited field
				$site->$parameter = $value;
				//Save changes
				$this->SiteLocations->save($site);
			}
			else {
				//need to handle groups separately, because we get the value as an array but need to convert to comma-separated values for DB storage
			}
		}

		public function fetchsitedata() {
			$this->render(false);
			//Check if siteid is set
			if (!$this->request->getData("siteid")) {
				return;
			}
			$siteid = $this->request->getData("siteid");

			$site = $this->SiteLocations
				->find("all")
				->where(["ID" => $siteid])
				->first();

			$json = json_encode(["sitenumber" => $site->Site_Number,
				"longitude" => $site->Longitude,
				"latitude" => $site->Latitude,
				"sitelocation" => $site->Site_Location,
				"sitename" => $site->Site_Name]);
			
			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType("json");
				
			return $this->response;
		}

		public function updatesitedata() {
			$this->render(false);

			//Check if siteid is set
			if (!$this->request->getData("siteid")) {
				return;
			}
			$siteid = $this->request->getData("siteid");

			$site = $this->SiteLocations
				->find("all")
				->where(["ID" => $siteid])
				->first();

			//Update all the fields
			$site->Longitude = $this->request->getData("longitude");
			$site->Latitude = $this->request->getData("latitude");
			$site->Site_Location = $this->request->getData("location");
			$site->Site_Name = $this->request->getData("sitename");

			if ($this->SiteLocations->save($site)) {
				return;
			}
		}

		public function addsite() {
			$this->render(false);
			$SiteLocation = $this->SiteLocations->newEntity();
			if ($this->request->is("post")) {
				$SiteLocation = $this->SiteLocations->patchEntity($SiteLocation, $this->request->getData());

				$Site_Number = $this->request->getData("Site_Number");

				if ($this->SiteLocations->save($SiteLocation)) {
					$site = $this->SiteLocations
						->find("all")
						->where(["Site_Number" => $Site_Number])
						->first();
					$this->response->type("json");

					$json = json_encode(["siteid" => $site->ID]);
					$this->response->body($json);
					return;
				}
			}
		}

		public function deletesite() {
			$this->render(false);
			//Check if siteid is set
			if (!$this->request->getData("siteid")) {
				return;
			}
			$siteid = $this->request->getData("siteid");
			$site = $this->SiteLocations
				->find("all")
				->where(["ID" => $siteid])
				->first();
			//delete the site
			$this->SiteLocations->delete($site);
		}
	}