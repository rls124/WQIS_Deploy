<?php

    namespace App\Controller;

    use App\Controller\AppController;

    /**
     * PesticideSamples Controller
     *
     * @property \App\Model\Table\PesticideSamplesTable $PesticideSamples
     *
     * @method \App\Model\Entity\PesticideSample[] paginate($object = null, array $settings = [])
     */
    class PesticideSamplesController extends AppController {

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
		    'tableType' => 'pesticide'
		]);
		//If no POST data and there is sessions data, and the table type is pesticide
	    } elseif ($this->request->session() && $this->request->session()->read('tableType') === 'pesticide') {
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
	    $pesticideSamples = $this->paginate(
		$this->PesticideSamples->find('all', [
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'PesticideSamples.Date >=' => $startdate,
				'PesticideSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->PesticideSamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();
	    $this->set(compact('siteLocation'));
	    $this->set(compact('pesticideSamples'));
	    $this->set('_serialize', ['pesticideSamples']);
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
		    'measureType' => 'pesticide'
		]);
		//If no POST data and there is sessions data, and the table type is bacteria
	    } elseif ($this->request->session() && $this->request->session()->read('measureType') === 'pesticide') {
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
	    $pesticideSamples = $this->paginate(
		$this->PesticideSamples->find('all', [
		    'fields' => [
			'Date',
			'Sample_Number',
			'measure' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'PesticideSamples.Date >=' => $startdate,
				'PesticideSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->PesticideSamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();
	    $measureName = "";
	    //Set the measure name based off of the measure
	    switch ($measure) {
		case 'alachlor':
		    $measureName = 'Alachlor (µg/L)';
		    break;
		case 'atrazine':
		    $measureName = 'Atrazine (µg/L)';
		    break;
		case 'metolachlor':
		    $measureName = 'Metolachlor (µg/L)';
		    break;
		default:
		    $measureName = $measure;
		    break;
	    }
	    $this->set(compact('startdate'));
	    $this->set(compact('enddate'));
	    $this->set(compact('measureName'));
	    $this->set(compact('siteLocation'));
	    $this->set(compact('pesticideSamples'));
	    $this->set('_serialize', ['pesticideSamples']);
	}

	public function entryform() {
	    $pesticideSample = $this->PesticideSamples->newEntity();
	    //Check if the request is post, and the request has at least one sample
	    if ($this->request->is('post') && $this->request->getData('site_location_id-0')) {
		$successes = 0;
		$fails = 0;
		$failsDetailed = "";
		//Get the total rows submitted
		$rows = $this->request->getData('totalrows');
		//All of the columns that will be filled upon submission
		$columns = array('site_location_id', 'Date', 'Sample_Number', 'Altrazine',
		    'AltrazineException', 'Alachlor', 'AlachlorException', 'Metolachlor', 'MetolachlorException', 'Comments');
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
		    $pesticideSample = $this->PesticideSamples->patchEntity($this->PesticideSamples->newEntity(), $rowData);
		    if ($this->PesticideSamples->save($pesticideSample)) {
			$successes++;
		    } else {
			$fails++;
			$failsDetailed .= $rowData['Sample_Number'] . ', ';
		    }
		}

		if ($successes) {
		    $this->Flash->success(__($successes . ' pesticide sample(s) has been saved.'));
		}
		if ($fails) {
		    $this->Flash->error(__($fails . ' pesticide sample(s) could not be saved. Failure on number(s): ' . substr($failsDetailed, 0, strlen($failsDetailed) - 2)));
		}
	    }
	    $siteLocations = $this->PesticideSamples->SiteLocations->find('all');
	    $this->set(compact('pesticideSample', 'siteLocations'));
	    $this->set('_serialize', ['pesticideSample']);
	}

	public function updatefield() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');

	    //Get the sample we are editing
	    $pesticideSample = $this->PesticideSamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    $parameter = $this->request->getData('parameter');
	    $value = $this->request->getData('value');
	    //Set the edited field
	    $pesticideSample->$parameter = $value;
	    //Save changes
	    $this->PesticideSamples->save($pesticideSample);
	}

	public function deleteRecord() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');
	    //Get the sample we are deleting
	    $pesticideSample = $this->PesticideSamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    //Delete it
	    $this->PesticideSamples->delete($pesticideSample);
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
	    $pesticideSamples = $this->PesticideSamples->find('all', [
		    'fields' => [
			'site' => 'site_location_id',
			'date' => 'Date',
			'value' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id IN ' => $sites,
			    [
				'PesticideSamples.Date >=' => $startdate,
				'PesticideSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'ASC']);

	    $response = $this->response;
	    $response->type('json');
	    $response->body(json_encode([$pesticideSamples, $threshold]));
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
