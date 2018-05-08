<?php

    namespace App\Controller;

    use App\Controller\AppController;

    /**
     * BacteriaSamples Controller
     *
     * @property \App\Model\Table\BacteriaSamplesTable $BacteriaSamples
     *
     * @method \App\Model\Entity\BacteriaSample[] paginate($object = null, array $settings = [])
     */
    class BacteriaSamplesController extends AppController {

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
		    'tableType' => 'bacteria'
		]);
		//If no POST data and there is sessions data, and the table type is bacteria
	    } elseif ($this->request->session() && $this->request->session()->read('tableType') === 'bacteria') {
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
	    $bacteriaSamples = $this->paginate(
		$this->BacteriaSamples->find('all', [
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'BacteriaSamples.Date >=' => $startdate,
				'BacteriaSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->BacteriaSamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();
	    $this->set(compact('siteLocation'));
	    $this->set(compact('bacteriaSamples'));
	    $this->set('_serialize', ['bacteriaSamples']);
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
		    'measureType' => 'bacteria'
		]);
		//If no POST data and there is sessions data, and the table type is bacteria
	    } elseif ($this->request->session() && $this->request->session()->read('measureType') === 'bacteria') {
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
	    $bacteriaSamples = $this->paginate(
		$this->BacteriaSamples->find('all', [
		    'fields' => [
			'Date',
			'Sample_Number',
			'measure' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id' => $site,
			    [
				'BacteriaSamples.Date >=' => $startdate,
				'BacteriaSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'Desc'])
	    );
	    //Get the info about the site number
	    $siteLocation = $this->BacteriaSamples->SiteLocations->find('all', [
		    'conditions' => [
			'Site_number' => $site
		    ]
		])->first();
	    $measureName = "";
	    //Set the measure name based off of the measure
	    switch ($measure) {
		case 'ecoli':
		    $measureName = 'E. Coli (CFU/100 mil)';
		    break;
		default:
		    $measureName = $measure;
		    break;
	    }
	    $this->set(compact('startdate'));
	    $this->set(compact('enddate'));
	    $this->set(compact('measureName'));
	    $this->set(compact('siteLocation'));
	    $this->set(compact('bacteriaSamples'));
	    $this->set('_serialize', ['bacteriaSamples']);
	}

	public function entryform() {
	    $bacteriaSample = $this->BacteriaSamples->newEntity();
	    //Check if the request is post, and the request has at least one sample
	    if ($this->request->is('post') && $this->request->getData('site_location_id-0')) {
		$successes = 0;
		$fails = 0;
		$failsDetailed = "";
		//Get the total rows submitted
		$rows = $this->request->getData('totalrows');
		//All of the columns that will be filled upon submission
		$columns = array('site_location_id', 'Date', 'Sample_Number', 'EcoliRawCount',
		    'Ecoli', 'EcoliException', 'TotalColiformRawCount', 'TotalColiform', 'ColiformException', 'Comments');
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
		    $bacteriaSample = $this->BacteriaSamples->patchEntity($this->BacteriaSamples->newEntity(), $rowData);
		    if ($this->BacteriaSamples->save($bacteriaSample)) {
			$successes++;
		    } else {
			$fails++;
			$failsDetailed .= $rowData['Sample_Number'] . ', ';
		    }
		}
		if ($successes) {
		    $this->Flash->success(__($successes . ' bacteria sample(s) has been saved.'));
		}
		if ($fails) {
		    $this->Flash->error(__($fails . ' bacteria sample(s) could not be saved. Failure on number(s): ' . substr($failsDetailed, 0, strlen($failsDetailed) - 2)));
		}
	    }
	    $siteLocations = $this->BacteriaSamples->SiteLocations->find('all');
	    $this->set(compact('bacteriaSample', 'siteLocations'));
	    $this->set('_serialize', ['bacteriaSample']);

	    $rawCount = [];
	    for ($i = 0; $i <= 51; $i++) {
		$rawCount[] = $i;
	    }
	    $this->set(compact('rawCount'));
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
		$columns = array('site_location_id', 'Date', 'Sample_Number', 'EcoliRawCount',
		    'Ecoli', 'EcoliException', 'TotalColiformRawCount', 'TotalColiform', 'ColiformException', 'Comments');
		$log = array();
		//Go through each non-header row
		for ($row = 1; $row < sizeof($csv); $row++) {

		    $currentRow = array();
		    $uploadData = [];
		    //Get every column's data in the row
		    for ($column = 0; $column < sizeof($columns); $column++) {
			$currentElement = $csv[$row][$column];
			$currentColumn = $columns[$column];
			//Check if the current column name does not contain exception
			if (strpos($currentColumn, "Exception") === false) {
			    $currentRow[] = $currentElement;
			}

			$uploadData[$currentColumn] = $currentElement;
		    }
		    //Create the entity to save
		    $bacteriaSample = $this->BacteriaSamples->patchEntity($this->BacteriaSamples->newEntity(), $uploadData);

		    if ($this->BacteriaSamples->save($bacteriaSample)) {
			$currentRow[] = "File uploaded successfully";
		    } else {
			$currentRow[] = $bacteriaSample->getErrors();
		    }
		    $log[] = $currentRow;
		}
		$this->set(compact('log'));
	    }
	}

	public function updatefield() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');
	    //Get the sample we are editing
	    $bacteriaSample = $this->BacteriaSamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    $parameter = $this->request->getData('parameter');
	    $value = $this->request->getData('value');
	    //Set the edited field
	    $bacteriaSample->$parameter = $value;
	    //Save changes
	    $this->BacteriaSamples->save($bacteriaSample);
	}

	public function deleteRecord() {
	    $this->render(false);
	    //Ensure sample number data was included
	    if (!$this->request->getData('sampleNumber')) {
		return;
	    }
	    $sampleNumber = $this->request->getData('sampleNumber');
	    //Get the sample we are deleting
	    $bacteriaSample = $this->BacteriaSamples
		->find('all')
		->where(['Sample_Number = ' => $sampleNumber])
		->first();
	    //Delete it
	    $this->BacteriaSamples->delete($bacteriaSample);
	}

	public function chartView() {
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
		case 'ecoli':
		    $thresMeasure = 'E. coli. (CFU/100 ml)';
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
	    $bacteriaSamples = $this->BacteriaSamples->find('all', [
		    'fields' => [
			'site' => 'site_location_id',
			'date' => 'Date',
			'value' => $measure
		    ],
		    'conditions' => [
			'and' => [
			    'site_location_id IN ' => $sites,
			    [
				'BacteriaSamples.Date >=' => $startdate,
				'BacteriaSamples.Date <= ' => $enddate
			    ]
			]
		    ]
		])->order(['Date' => 'ASC']);
	    $response = $this->response;
	    $response->type('json');
	    $response->body(json_encode([$bacteriaSamples, $threshold]));
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
