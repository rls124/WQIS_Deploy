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
			$siteLocations = $this->SiteLocations->find('all');

			$this->set(compact('siteLocations'));
			$this->set('_serialize', ['siteLocations']);
		}

		public function entryform() {
			$siteLocation = $this->SiteLocations->newEntity();
			//Check if the request is post
			if ($this->request->is('post')) {
				$siteLocation = $this->SiteLocations->patchEntity($siteLocation, $this->request->getData());
				//Save new entity
				if ($this->SiteLocations->save($siteLocation)) {
					$this->Flash->success(__('The site location has been saved.'));

					return $this->redirect(['action' => 'entryform']);
				}
				$this->Flash->error(__('The site location could not be saved. Please, try again.'));
			}
			$this->set(compact('siteLocation'));
			$this->set('_serialize', ['siteLocation']);
		}

		public function daterange() {
			$this->render(false);
			//Ensure that site is in POST data
			if (!$this->request->getData('site')) {
				return;
			}

			$site = $this->request->getData('site');
			
			switch ($this->request->getData('category')) {
				case "bacteria":
					$this->loadModel('BacteriaSamples');
					$model = $this->BacteriaSamples;
					break;
				case "nutrient":
					$this->loadModel('NutrientSamples');
					$model = $this->NutrientSamples;
					break;
				case "pesticide":
					$this->loadModel('PesticideSamples');
					$model = $this->PesticideSamples;
					break;
				case "wqm":
					$this->loadModel('WaterQualitySamples');
					$model = $this->WaterQualitySamples;
					break;
				default:
					$this->response->body(json_encode(['', '']));
					return $this->response;
			}
			
			//Get min/max date of all the sites
			$measureQuery = $model
				->find('all', [
					'conditions' => [
						'site_location_id' => $site
					],
					'fields' => [
						'mindate' => 'MIN(Date)',
						'maxdate' => 'MAX(Date)'
					]
				])->first();
			
			//Format date properly
			$mindate = date('m/d/Y', strtotime($measureQuery['mindate']));
			$maxdate = date('m/d/Y', strtotime($measureQuery['maxdate']));
			$dateRange = json_encode([$mindate, $maxdate]);
			
			$this->response = $this->response->withStringBody($dateRange);
			$this->response = $this->response->withType('json');
			
			return $this->response;
		}

		public function sitemanagement() {
			$SiteLocations = $this->SiteLocations->find('all')
				->order(['Site_Number' => 'ASC']);
			$this->set(compact('SiteLocations'));
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
			if (!$this->request->getData('siteid')) {
				return;
			}
			$siteid = $this->request->getData('siteid');

			$site = $this->SiteLocations
				->find('all')
				->where(['ID = ' => $siteid])
				->first();

			//Update all the fields			
			$monitored = $this->request->getData('monitored');
			if ($monitored == "1" || $monitored == "true") {
				$site->Monitored == true;
			}
			else {
				$site->Monitored == false;
			}
			
			$site->Longitude = $this->request->getData('longitude');
			$site->Latitude = $this->request->getData('latitude');
			$site->Site_Location = $this->request->getData('location');
			$site->Site_Name = $this->request->getData('sitename');

			if ($this->SiteLocations->save($site)) {
				return;
			}
		}

		public function addsite() {
			$this->render(false);
			$SiteLocation = $this->SiteLocations->newEntity();
			if ($this->request->is('post')) {
				$SiteLocation = $this->SiteLocations->patchEntity($SiteLocation, $this->request->getData());

//				$this->log($this->request, 'debug');

				$Site_Number = $this->request->getData('Site_Number');

				if ($this->SiteLocations->save($SiteLocation)) {
					$site = $this->SiteLocations
						->find('all')
						->where(['Site_Number = ' => $Site_Number])//, 'Longitude = ' => $Longitude, 'Latitude = ' => $Latitude, 'Site_Location = ' => $Site_Location, 'Site_Name = ' => $Site_Name])
						->first();
					$this->response->type('json');

					$json = json_encode(['siteid' => $site->ID]);
					$this->response->body($json);
					return;
				}
				else {
					$this->log("error", 'debug');
				}
			}
		}

		public function deletesite() {
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
			//Delete the site
			$this->SiteLocations->delete($site);
		}

		public function fetchSites() {
			$this->autoRender = false;
			
			if ($this->request->is('post')) {
				//Get the sites
				$sites = $this->SiteLocations->find('all')->order('Site_Number');

				$this->loadModel('BacteriaSamples');
				$this->loadModel('NutrientSamples');
				$this->loadModel('PesticideSamples');

				$connection = ConnectionManager::get('default');
				
				//returns the most recent value for each site location
				$bactQuery = "select f.Date as 'Date', f.site_location_id as 'site_location_id', f.Ecoli as 'Ecoli'" .
					" from (" .
					"select site_location_id, max(Date) as maxdate" .
					" from bacteria_samples group by site_location_id" .
					") as x inner join bacteria_samples as f on f.site_location_id = x.site_location_id and f.Date = x.maxdate ORDER BY `f`.`site_location_id` ASC";
				$bactDateAndData = $connection->execute($bactQuery)->fetchAll('assoc');

				$nutrientQuery = "SELECT f.Date as 'Date', f.site_location_id as 'site_location_id', f.Phosphorus as 'Phosphorus', f.NitrateNitrite as 'NitrateNitrite', f.DRP as 'DRP', f.Ammonia as 'Ammonia'" .
					" from (" .
					"select site_location_id, max(Date) as maxdate" .
					" from nutrient_samples group by site_location_id" .
					") as x inner join nutrient_samples as f on f.site_location_id = x.site_location_id and f.Date = x.maxdate ORDER BY `f`.`site_location_id` ASC";
				$nutrientDateAndData = $connection->execute($nutrientQuery)->fetchAll('assoc');

				$pesticideQuery = "SELECT f.Date as 'Date', f.site_location_id as 'site_location_id', f.Atrazine as 'Atrazine', f.Alachlor as 'Alachlor', f.Metolachlor as 'Metolachlor'" .
					" from (" .
					"select site_location_id, max(Date) as maxdate" .
					" from pesticide_samples group by site_location_id" .
					") as x inner join pesticide_samples as f on f.site_location_id = x.site_location_id and f.Date = x.maxdate ORDER BY `f`.`site_location_id` ASC";
				$pestDateAndData = $connection->execute($pesticideQuery)->fetchAll('assoc');
                                
				$json = json_encode([
					'SiteData' => $sites, 
					'BacteriaData' => $bactDateAndData, 
					'NutrientData' => $nutrientDateAndData, 
					'PestData' => $pestDateAndData]);
				
				$this->response = $this->response->withStringBody($json);
				$this->response = $this->response->withType('json');
				
				return $this->response;
			}
		}
	}