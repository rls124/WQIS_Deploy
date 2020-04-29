<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

class PhysicalSamplesTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable('physical_samples');
		$this->setDisplayField('Sample_Number');
		$this->setPrimaryKey('Sample_Number');

		$this->belongsTo('SiteLocations', [
			'foreignKey' => 'site_location_id'
		]);
	}

	public function validationDefault(Validator $validator) {
		$validator
			->integer('ID')
			->allowEmpty('ID', 'create');

		$validator
			->date('Date', 'mdy')
			->requirePresence('Date', 'create')
			->notEmpty('Date')
			->add('Date', 'custom', [
				'rule' => function ($value, $context) {
					//validate that the row actually has some sample data associated with it
					$data = $context["data"];
					
					$allNull = true;
					
					foreach (['Bridge_to_Water_Height', 'Water_Temp', 'pH', 'Conductivity', 'TDS', 'DO', 'Turbidity', 'Turbidity_Scale_Value', 'PhysicalComments'] as $key) {
						if (isset($data[$key]) && $data[$key] != null && $data[$key] != "") {
							$allNull = false;
							break;
						}
					}
					
					return !$allNull;
				},
				'message' => 'null row'
			]);

		$validator
			->integer('Sample_Number')
			->requirePresence('Sample_Number', 'create')
			->notEmpty('Sample_Number')
			->add('Sample_Number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

		$validator
			->time('Time')
			->requirePresence('Time', 'create')
			->allowEmpty('Time');

		$validator
			->decimal('Bridge_to_Water_Height')
			->allowEmpty('Bridge_to_Water_Height');

		$validator
			->decimal('Water_Temp')
			->allowEmpty('Water_Temp');

		$validator
			->decimal('pH')
			->allowEmpty('pH');

		$validator
			->decimal('Conductivity')
			->allowEmpty('Conductivity');

		$validator
			->decimal('TDS')
			->allowEmpty('TDS');

		$validator
			->decimal('DO')
			->allowEmpty('DO');

		$validator
			->decimal('Turbidity')
			->allowEmpty('Turbidity');

		$validator
			->integer('Turbidity_Scale_Value')
			->allowEmpty('Turbidity_Scale_Value');

		$validator
			->scalar('PhysicalComments')
			->maxLength('PhysicalComments', 200)
			->allowEmpty('PhysicalComments');

		$validator
			->date('Import_Date')
			->allowEmpty('Import_Date');

		$validator
			->time('Import_Time')
			->allowEmpty('Import_Time');

		return $validator;
	}
	
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->isUnique(['Sample_Number']));

		return $rules;
	}
	
	public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options) {
		//format times before we attempt to load them into the database. Necessary because otherwise, single-digit hour times would fail
		foreach (['Time', 'Import_Time'] as $key) {
			if (isset($data[$key]) && is_string($data[$key])) {
				$data[$key] = \Cake\I18n\Time::parseTime($data[$key], 'HH:mm');
			}
		}
		
		//treat values like "n/a" or "no data" as null fields (they'll still show as the original values if theres other errors though, to help the user figure out what went wrong)
		foreach (['Bridge_to_Water_Height', 'Water_Temp', 'pH', 'Conductivity', 'TDS', 'DO', 'Turbidity', 'Turbidity_Scale_Value'] as $key) {
			if (isset($data[$key]) && is_string($data[$key])) {
				if (in_array(strtolower($data[$key]), ["n/a", "na", "nd", "no data"])) {
					$data[$key] = null;
				}
			}
		}
	}
}