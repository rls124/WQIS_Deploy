<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity {
    protected $_accessible = [
        "admin" => true,
        "username" => true,
        "userpw" => true,
        "firstname" => true,
        "lastname" => true,
        "email" => true,
        "organization" => true,
        "position" => true,
        "Created" => true,
        "securityquestion1" => true,
        "securityanswer1" => true,
        "securityquestion2" => true,
        "securityanswer2" => true,
        "securityquestion3" => true,
        "securityanswer3" => true,
		"hasTakenTutorial" => true
    ];

    protected function _setuserpw($password) {
        return (new DefaultPasswordHasher())->hash($password);
    }
}