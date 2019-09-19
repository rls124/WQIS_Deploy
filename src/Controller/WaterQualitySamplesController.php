<?php

    namespace App\Controller;

    use App\Controller\AppController;

    /**
     * WaterQualitySamples Controller
     *
     * @property \App\Model\Table\WaterQualitySamplesTable $WaterQualitySamples
     *
     * @method \App\Model\Entity\WaterQualitySample[] paginate($object = null, array $settings = [])
     */
    class WaterQualitySamplesController extends AppController {

	public function tableview() {
	    //Checks to see if this had POST data
	    if ($this->request->getData()) {
		//Set all relevant POST data to variables

		$startdate = date('Ymd', strtotime($this->request->getData('startdate')));
		$enddate = date('Ymd', strtotime($this->request->getData('enddate')));
		$site = $this->request->getData('site');
		//Write POST data into session
		$this->request->session()->write([
		    'startdate' => $startdate,
		    'enddate' => $enddate,
		    'site' => $site,
		    'tableType' => 'wqm'
		]);
		//If no POST data and there is sessions data, and the table type is wqm
	    } elseif ($this->request->session('tableType') && $this->request->session()->read('tableType') === 'wqm') {
		//Set all relevant session data to variables
		$startdate = $this->request->session()->read('startdate');
		$enddate = $this->request->session()->read('enddate');
		$site = $this->request->session()->read('site');
		//Else delete all session data and redirect to chartSelection page
	    } else {
		$this->request->session()->delete('startdate');
		$this->request->session()->delete('enddate');
		$this->request->session()->delete('site');
		$this->request->session()->delete('tableType');
		$this->redirect([
		    'controller' => 'SiteLocations',
		    'action' => 'chartselection',
		]);
		return;
	    }
	    //Find all samples found at the site between the date ranges
	    $wqmSamples = $this->paginate(
		$this->WaterQualitySamples->find('all', [
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'WaterQualitySamples.Date >=' => $startdate,
				'WaterQualitySamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->WaterQualitySamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();
	    $this->set(compact('siteLocation'));
	    $this->set(compact('wqmSamples'));
	    $this->set('_serialize', ['wqmSamples']);
	}

	public function measureview() {
	    //Checks to see if this had POST data
	    if ($this->request->getData()) {

		//Set all relevant POST data to variables
		$startdate = date('Ymd', strtotime($this->request->getData('startdate')));
		$enddate = date('Ymd', strtotime($this->request->getData('enddate')));
		$site = $this->request->getData('site');
		$measure = $this->request->getData('measurementSelect');
		//Write POST data into session
		$this->request->session()->write([
		    'startdate' => $startdate,
		    'enddate' => $enddate,
		    'site' => $site,
		    'measure' => $measure,
		    'measureType' => 'wqm'
		]);
		//If no POST data and there is sessions data, and the table type is bacteria
	    } elseif ($this->request->session() && $this->request->session()->read('measureType') === 'wqm') {
		$startdate = $this->request->session()->read('startdate');
		$enddate = $this->request->session()->read('enddate');
		$site = $this->request->session()->read('site');
		$measure = $this->request->session()->read('measure');
		//Else delete all session data and redirect to chartSelection page
	    } else {
		$this->request->session()->delete('startdate');
		$this->request->session()->delete('enddate');
		$this->request->session()->delete('site');
		$this->request->session()->delete('measureType');
		$this->request->session()->delete('measure');
		$this->redirect([
		    'controller' => 'SiteLocations',
		    'action' => 'chartselection',
		]);
		return;
	    }
	    //Find all the relevant samples found at the site between the date ranges
	    $wqmSamples = $this->paginate(
		$this->WaterQualitySamples->find('all', [
		    'fields' => [
			'Date',
			'Sample_Number',
			'measure' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'WaterQualitySamples.Date >=' => $startdate,
				'WaterQualitySamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->WaterQualitySamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();

	    $measureName = "";
	    //Set the measure name based off of the measure
	    switch ($measure) {
		case 'conductivity':
		    $measureName = 'Conductivity (mS/cm)';
		    break;
		case 'do':
		    $measureName = 'Dissolved Oxygen (mg/L)';
		    break;
		case 'ph':
		    $measureName = 'pH';
		    break;
		case 'water_temp':
		    $measureName = 'Water Temperature (°C)';
		    break;
		case 'tds':
		    $measureName = 'Total Dissolved Solids (g/L)';
		    break;
		case 'turbidity':
		    $measureName = 'Turbidity (NTU)';
		    break;
		default:
		    $measureName = $measure;
		    break;
	    }

	    $this->set(compact('startdate'));
	    $this->set(compact('enddate'));
	    $this->set(compact('measureName'));
	    $this->set(compact('siteLocation'));
	    $this->set(compact('wqmSamples'));
	    $this->set('_serialize', ['wqmSamples']);
	}

	public function entryform() {
	    $waterQualitySample = $this->WaterQualitySamples->newEntity();
	    //Check if the request is post, and the request has at least one sample
	    if ($this->request->is('post') && $this->request->getData('site_location_id-0')) {
		$successes = 0;
		$fails = 0;
		$failsDetailed = "";
		//Get the total rows submitted
		$rows = $this->request->getData('totalrows');
		//All of the columns that will be filled upon submission
		$columns = array('site_location_id', 'Date', 'Sample_Number', 'Time', 'Water_Temp',
		    'Water_Temp_Exception', 'pH', 'pH_Exception', 'Conductivity', 'Conductivity_Exception', 'TDS',
		    'TDS_Exception', 'DO', 'DO_Exception', 'Turbidity', 'Turbidity_Exception', 'Turbidity_Scale_Value',
		    'Comments', 'Import_Date', 'Import_Time', 'Requires_Checking');
		//rows start at number 0, meaning we have to include the amount.
		for ($i = 0; $i <= $rows; $i++) {
		    $rowData = [];
		    //Go through each column and find the postdata name that is associated
		    for ($col = 0; $col < sizeof($columns); $col++) {
			$requestField = "";
			if ($columns[$col] !== 'Date') {
			    $requestField = strtolower($columns[$col]) . "-" . $i;
			} else {
			    $requestField = $columns[$col];
			}
			$rowData[$columns[$col]] = $this->request->getData($requestField);
		    }
		    //Create the entity to save
		    $waterQualitySample = $this->WaterQualitySamples->patchEntity($this->WaterQualitySamples->newEntity(), $rowData);

		    if ($this->WaterQualitySamples->save($waterQualitySample)) {
			$successes++;
		    } else {
			$fails++;
			$failsDetailed .= $rowData['Sample_Number'] . ', ';
		    }
		}

		if ($successes) {
		    $this->Flash->success(__($successes . ' water quality meter sample(s) has been saved.'));
		}
		if ($fails) {
		    $this->Flash->error(__($fails . ' water quality meter sample(s) could not be saved. Failure on number(s): ' . substr($failsDetailed, 0, strlen($failsDetailed) - 2)));
		}
	    }
	    $siteLocations = $this->WaterQualitySamples->SiteLocations->find('all');
	    $this->set(compact('waterQualitySample', 'siteLocations'));
	    $this->set('_serialize', ['waterQualitySample']);
	}

	public function updatefield() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');

	    //Get the sample we are editing
	    $waterQualitySample = $this->WaterQualitySamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    $parameter = $this->request->getData('parameter');
	    $value = $this->request->getData('value');
	    //Set the edited field
	    $waterQualitySample->$parameter = $value;
	    //Save changes
	    $this->WaterQualitySamples->save($waterQualitySample);
	}

	public function deleteRecord() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');
	    //Get the sample we are deleting
	    $waterQualitySample = $this->WaterQualitySamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    //Delete it
	    $this->WaterQualitySamples->delete($waterQualitySample);
	}

	public function chartview() {
	    $this->loadModel("SiteLocations");
	    $siteLocations = $this->SiteLocations->find('all');

	    $this->set(compact('siteLocations'));
	    $this->set('_serialize', ['siteLocations']);
	}

	public function graphdata() {
	    $this->render(false);
	    $this->loadModel("Benchmarks");
	    // get request data
	    $startdate = date('Ymd', strtotime($this->request->getData('startdate')));
	    $enddate = date('Ymd', strtotime($this->request->getData('enddate')));
	    $sites = $this->request->getData('sites');
	    $measure = $this->request->getData('measure');

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
	    $wqmSamples = $this->WaterQualitySamples->find('all', [
		    'fields' => [
			'site' => 'site_location_id',
			'date' => 'Date',
			'value' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id IN ' => $sites,
			    [
				'WaterQualitySamples.Date >=' => $startdate,
				'WaterQualitySamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'ASC']);

	    $response = $this->response;
	    $response->type('json');
	    $response->body(json_encode([$wqmSamples, $threshold]));
	    return $response;
	}

	public function daterange() {
	    $this->render(false);
	    //Ensure that sites is in POST data
	    if (!$this->request->getData('sites')) {
		return;
	    }

	    $sites = $this->request->getData('sites');

	    //Get min/max date of all the sites
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

	    //Format date properly
	    $mindate = date('m/d/Y', strtotime($measureQuery['mindate']));
	    $maxdate = date('m/d/Y', strtotime($measureQuery['maxdate']));
	    $dateRange = [$mindate, $maxdate];
	    $this->response->body(json_encode($dateRange));
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
