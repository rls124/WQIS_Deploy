<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class SiteLocation extends Entity {
	protected $_accessible = [
		"Site_Number" => true,
		"Longitude" => true,
		"Latitude" => true,
		"Site_Location" => true,
		"Site_Name" => true,
		"bacteria_samples" => true,
		"nutrient_samples" => true,
		"pesticide_samples" => true,
		"physical_samples" => true,
		"groups" => true
	];
}