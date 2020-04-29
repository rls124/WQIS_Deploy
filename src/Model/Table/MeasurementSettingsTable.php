<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MeasurementSettingsTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable("measurement_settings");
		$this->setDisplayField("measureKey");
		$this->setPrimaryKey("measureKey");
	}

	public function validationDefault(Validator $validator) {
		$validator
			->scalar("measureKey")
			->maxLength("measureKey", 100)
			->allowEmpty("measureKey", "create");

		$validator
			->numeric("benchmarkMinimum")
			->allowEmpty("benchmarkMinimum");
			
		$validator
			->numeric("benchmarkMaximum")
			->allowEmpty("benchmarkMaximum");

		$validator
			->numeric("detectionMinimum")
			->allowEmpty("detectionMinimum");
			
		$validator
			->numeric("detectionMaximum")
			->allowEmpty("detectionMaximum");

		return $validator;
	}
}