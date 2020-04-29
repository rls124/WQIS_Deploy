<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class PesticideSample extends Entity {
	protected $_accessible = [
		"site_location_id" => true,
		"Date" => true,
		"Sample_Number" => true,
		"Atrazine" => true,
		"Alachlor" => true,
		"Metolachlor" => true,
		"PesticideComments" => true,
		"site_location" => true
	];

	protected function _setDate($date) {
		return date("Y-m-d", strtotime($date));
	}
}