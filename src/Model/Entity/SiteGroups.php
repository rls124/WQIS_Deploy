<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class SiteGroups extends Entity {
    protected $_accessible = [
        "groupKey" => true,
        "groupName" => true,
		"groupDescription" => true,
		"visibleTo" => true
    ];
}