<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class NutrientSample extends Entity {
	protected $_accessible = [
		"site_location_id" => true,
		"Date" => true,
		"Sample_Number" => true,
		"Phosphorus" => true,
		"NitrateNitrite" => true,
		"DRP" => true,
		"Ammonia" => true,
		"NutrientComments" => true,
		"site_location" => true
	];

	protected function _setDate($date) {
		return date("Y-m-d", strtotime($date));
	}
}