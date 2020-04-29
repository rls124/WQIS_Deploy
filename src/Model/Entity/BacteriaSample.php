<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class BacteriaSample extends Entity {
	protected $_accessible = [
		"site_location_id" => true,
		"Date" => true,
		"Sample_Number" => true,
		"Ecoli" => true,
		"TotalColiform" => true,
		"BacteriaComments" => true,
		"site_location" => true
	];

	protected function _setDate($date) {
		return date("Y-m-d", strtotime($date));
	}
}