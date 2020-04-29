<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class PhysicalSample extends Entity {
	protected $_accessible = [
		"site_location_id" => true,
		"Date" => true,
		"Sample_Number" => true,
		"Time" => true,
		"Bridge_to_Water_Height" => true,
		"Water_Temp" => true,
		"pH" => true,
		"Conductivity" => true,
		"TDS" => true,
		"DO" => true,
		"Turbidity" => true,
		"Turbidity_Scale_Value" => true,
		"PhysicalComments" => true,
		"Import_Date" => true,
		"Import_Time" => true,
		"site_location" => true
	];

	protected function _setDate($date) {
		return date("Y-m-d", strtotime($date));
	}
}