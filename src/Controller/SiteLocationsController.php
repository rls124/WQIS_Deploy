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
			$this->loadModel('BacteriaSamples');
			$this->loadModel('PesticideSamples');
			$this->loadModel('NutrientSamples');
			$this->loadModel('WaterQualitySamples');
			switch ($this->request->getData('category')) {

				case "bacteria":
					//Get min/max date of all the sites
					$measureQuery = $this->BacteriaSamples
							->find('all', [
								'conditions' => [
									'site_location_id' => $site
								],
								'fields' => [
									'mindate' => 'MIN(Date)',
									'maxdate' => 'MAX(Date)'
								]
							])->first();

					break;
				case "nutrient":
					//Get min/max date of all the sites
					$measureQuery = $this->NutrientSamples
							->find('all', [
								'conditions' => [
									'site_location_id' => $site
								],
								'fields' => [
									'mindate' => 'MIN(Date)',
									'maxdate' => 'MAX(Date)'
								]
							])->first();
					break;
				case "pesticide":
					//Get min/max date of all the sites
					$measureQuery = $this->PesticideSamples
							->find('all', [
								'conditions' => [
									'site_location_id' => $site
								],
								'fields' => [
									'mindate' => 'MIN(Date)',
									'maxdate' => 'MAX(Date)'
								]
							])->first();
					break;
				case "wqm":
					//Get min/max date of all the sites
					$measureQuery = $this->WaterQualitySamples
							->find('all', [
								'conditions' => [
									'site_location_id' => $site
								],
								'fields' => [
									'mindate' => 'MIN(Date)',
									'maxdate' => 'MAX(Date)'
								]
							])->first();
					break;
				default:
					$this->response->body(json_encode(['', '']));
					return $this->response;
			}
			//Format date properly
			$mindate = date('m/d/Y', strtotime($measureQuery['mindate']));
			$maxdate = date('m/d/Y', strtotime($measureQuery['maxdate']));
			$dateRange = [$mindate, $maxdate];
			$this->response->body(json_encode($dateRange));
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
				$bactQuery = "SELECT max(Date) as 'Date', Ecoli, site_location_id " .
					"FROM bacteria_samples " .
					"group by site_location_id " .
					"order by site_location_id";
				$bactDateAndData = $connection->execute($bactQuery)->fetchAll('assoc');

				$nutrientQuery = "SELECT max(Date) as 'Date', site_location_id, Phosphorus, NitrateNitrite, DRP, Ammonia " .
					"FROM nutrient_samples " .
					"group by site_location_id " .
					"order by site_location_id";
				$nutrientDateAndData = $connection->execute($nutrientQuery)->fetchAll('assoc');

				$pestQuery = "SELECT max(Date) as 'Date', site_location_id, Atrazine, Alachlor, Metolachlor " . 
					"FROM pesticide_samples " .
					"group by site_location_id " .
					"order by site_location_id";
				$pestDateAndData = $connection->execute($pestQuery)->fetchAll('assoc');
                                
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