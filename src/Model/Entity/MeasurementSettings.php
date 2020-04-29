<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class MeasurementSettings extends Entity {
	protected $_accessible = [
		"measureKey" => true,
		"measureName" => true,
		"benchmarkMinimum" => true,
		"benchmarkMaximum" => true,
		"detectionMinimum" => true,
		"detectionMaximum" => true
	];
}