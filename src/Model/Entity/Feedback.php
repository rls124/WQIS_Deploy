<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Feedback extends Entity {
    protected $_accessible = [
        "Feedback" => true,
        "User" => true,
		"Name" => true,
		"Email" => true,
		"Date" => true
    ];
    
    protected function _setDate($date) {
        return date("Y-m-d", strtotime($date));
    }
}