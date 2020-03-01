<?php
	namespace App\Controller;

	use App\Controller\AppController;
	use Cake\Log\Log;
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
			$this->loadModel("SiteLocations");
			$this->loadModel("SiteGroups");
			
			$siteLocations = $this->SiteLocations->find('all');
			$siteGroups = $this->SiteGroups->find('all');

			$this->set(compact('siteLocations'));
			$this->set(compact('siteGroups'));
			$this->set('_serialize', ['siteLocations']);
		}
		
		public function siteinfo() {
			$this->loadModel("SiteLocations");
			
			$SiteLocations = $this->SiteLocations->find("all")->order(["Site_Number" => "ASC"]);
			$numSites = $SiteLocations->count();
			
			$this->set(compact("SiteLocations"));
			$this->set(compact("numSites"));
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
			$SiteLocations = $this->SiteLocations->find("all")
				->order(["Site_Number" => "ASC"]);
			$this->set(compact("SiteLocations"));
		}

		public function fetchsitedata() {
			$this->render(false);
			//Check if siteid is set
			if (!$this->request->getData('siteid')) {
				return;
			}
			$siteid = $this->request->getData('siteid');

			$site = $this->SiteLocations
				->find('all')
				->where(['ID = ' => $siteid])
				->first();

			$json = json_encode(['sitenumber' => $site->Site_Number,
				'monitored' => $site->Monitored,
				'longitude' => $site->Longitude,
				'latitude' => $site->Latitude,
				'sitelocation' => $site->Site_Location,
				'sitename' => $site->Site_Name]);
			
			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType('json');
				
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
			$monitored = $this->request->getData("monitored");
			$site->Monitored = ($monitored == "1" || $monitored == "true");
			
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

		public function fetchSites() {
			$this->render(false);
			
			$this->loadModel("SiteLocations");
			
			if ($this->request->is("POST")) {
				//get the sites
				$sites = $this->SiteLocations->find("all")->order("Site_Number");

				$connection = ConnectionManager::get("default");
				
				$data = ["SiteData" => $sites];
				$tableNames = ["bacteria_samples", "nutrient_samples", "pesticide_samples", "physical_samples"];
				
				for ($i=0; $i<sizeof($tableNames); $i++) {
					$query = "select * from (select site_location_id, max(Date) as maxdate from " .
						$tableNames[$i] .
						" group by site_location_id) as x inner join " .
						$tableNames[$i] .
						" as f on f.site_location_id = x.site_location_id and f.Date = x.maxdate ORDER BY `f`.`site_location_id` ASC";
						
					$queryResult = $connection->execute($query)->fetchAll("assoc");
					$data = array_merge($data, [$tableNames[$i] => $queryResult]);
				}
				
				$this->response = $this->response->withStringBody(json_encode($data));
				$this->response = $this->response->withType("json");
				
				return $this->response;
			}
		}
	}