<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SiteLocationsTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable("site_locations");
		$this->setDisplayField("Site_Number");
		$this->setPrimaryKey("ID");

		$this->hasMany("BacteriaSamples", [
			"foreignKey" => "site_location_id"
		]);
		$this->hasMany("HydrolabSamples", [
			"foreignKey" => "site_location_id"
		]);
		$this->hasMany("NutrientSamples", [
			"foreignKey" => "site_location_id"
		]);
		$this->hasMany("PesticideSamples", [
			"foreignKey" => "site_location_id"
		]);
		$this->hasMany("WaterQualitySamples", [
			"foreignKey" => "site_location_id"
		]);
	}

	public function validationDefault(Validator $validator) {
		$validator
			->integer("ID")
			->allowEmpty("ID", "create");

		$validator
			->integer("Site_Number")
			->requirePresence("Site_Number", "create")
			->notEmpty("Site_Number");
		
		$validator
			->numeric("Longitude")
			->requirePresence("Longitude", "create")
			->notEmpty("Longitude");

		$validator
			->numeric("Latitude")
			->requirePresence("Latitude", "create")
			->notEmpty("Latitude");

		$validator
			->scalar("Site_Location")
			->maxLength("Site_Location", 120)
			->requirePresence("Site_Location", "create")
			->notEmpty("Site_Location");

		$validator
			->scalar("Site_Name")
			->maxLength("Site_Name", 120)
			->requirePresence("Site_Name", "create")
			->notEmpty("Site_Name");
			
		$validator
			->scalar("groups")
			->maxLength("groups", 200);

		return $validator;
	}

	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->isUnique(["Site_Number"]));

		return $rules;
	}
}