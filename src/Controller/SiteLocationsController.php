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

		public function uploadlog() {
			//Get the data from the file
			$file = $this->request->getData('file');
			if ($this->request->is('post') && $file) {
				//Check if file is valid
				$valid = $this->_fileIsValid($file);
				if (!$valid['isValid']) {
					$this->set(compact('valid'));
					return;
				}
				$csv = array_map('str_getcsv', file($file['tmp_name']));
				//Columns in the file
				$columns = array('Site_Number', 'Monitored', 'Longitude', 'Latitude', 'Site_Location', 'Site_Name');
				$log = array();
				//Go through each non-header row
				for ($row = 1; $row < sizeof($csv); $row++) {

					$currentRow = array();
					$uploadData = [];

					//Get every column's data in the row
					for ($column = 0; $column < sizeof($columns); $column++) {
						$currentElement = $csv[$row][$column];
						$currentColumn = $columns[$column];
						$currentRow[] = $currentElement;
						$uploadData[$currentColumn] = $currentElement;
					}

					//Create the entity to save
					$siteLocation = $this->SiteLocations->patchEntity($this->SiteLocations->newEntity(), $uploadData);

					if ($this->SiteLocations->save($siteLocation)) {
						$currentRow[] = "File uploaded successfully";
					} else {
						$currentRow[] = $siteLocation->getErrors();
					}
					$log[] = $currentRow;
				}
				$this->set(compact('log'));
			}
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

			$this->response->type('json');

			$json = json_encode(['sitenumber' => $site->Site_Number,
				'monitored' => $site->Monitored,
				'longitude' => $site->Longitude,
				'latitude' => $site->Latitude,
				'sitelocation' => $site->Site_Location,
				'sitename' => $site->Site_Name]);
			$this->response->body($json);
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
			$site->Monitored = $this->request->getData('monitored');
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
//                $Longitude = $this->request->getData('Longitude');
//                $Latitude = $this->request->getData('Latitude');
//                $Site_Location = $this->request->getData('Site_Location');
//                $Site_Name = $this->request->getData('Site_Name');

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
			$this->render(false);
			if ($this->request->is('post')) {
				//Get the sites
				$sites = $this->SiteLocations
					->find('all')
                                        ->order('Site_Number');

                                $this->loadModel('BacteriaSamples');
                                $this->loadModel('NutrientSamples');
                                $this->loadModel('PesticideSamples');
                                
                                $connection = ConnectionManager::get('default');
                                $bactQuery = "SELECT max(Date) as 'Date', Ecoli, site_location_id " .
                                                        "FROM bacteria_samples " .
                                                        "group by site_location_id " .
                                                        "order by site_location_id";
                                $bactDateAndData = $connection->execute($bactQuery)->fetchAll('assoc');
                                
                                $nutrientQuery = "SELECT max(Date) as 'Date', site_location_id, Phosphorus, NitrateNitrite, DRP " .
                                                        "FROM nutrient_samples " .
                                                        "group by site_location_id " .
                                                        "order by site_location_id";
                                $nutrientDateAndData = $connection->execute($nutrientQuery)->fetchAll('assoc');
                                
                                $pestQuery = "SELECT max(Date) as 'Date', site_location_id, Atrazine, Alachlor, Metolachlor " . 
                                                        "FROM pesticide_samples " .
                                                        "group by site_location_id " .
                                                        "order by site_location_id";
                                $pestDateAndData = $connection->execute($pestQuery)->fetchAll('assoc');
                                
				$this->response->type('json');
				$json = json_encode([
                                    'SiteData' => $sites, 
                                    'BacteriaData' => $bactDateAndData, 
                                    'NutrientData' => $nutrientDateAndData, 
                                    'PestData' => $pestDateAndData]);
				$this->response->withStringBody($json);
			}
		}

	}
