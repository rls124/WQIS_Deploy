<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $userid
 * @property bool $admin
 * @property string $username
 * @property string $userpw
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $organization
 * @property string $position
 * @property \Cake\I18n\FrozenDate $Created
 * @property string $securityquestion1
 * @property string $securityanswer1
 * @property string $securityquestion2
 * @property string $securityanswer2
 * @property string $securityquestion3
 * @property string $securityanswer3
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'admin' => true,
        'username' => true,
        'userpw' => true,
        'firstname' => true,
        'lastname' => true,
        'email' => true,
        'organization' => true,
        'position' => true,
        'Created' => true,
        'securityquestion1' => true,
        'securityanswer1' => true,
        'securityquestion2' => true,
        'securityanswer2' => true,
        'securityquestion3' => true,
        'securityanswer3' => true
    ];

    protected function _setuserpw($password) {
        return (new DefaultPasswordHasher())->hash($password);
    }

}
