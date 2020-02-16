<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SiteGroups Entity
 *
 * @property string $groupKey
 * @property string $groupName
 * @property string $groupDescription
 */
class SiteGroups extends Entity {

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
        'groupKey' => true,
        'groupName' => true,
		'groupDescription' => true
    ];
}