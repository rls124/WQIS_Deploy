<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Groupings Entity
 *
 * @property string $groupingKey
 * @property string $group_ID
 * @property string $site_ID
 */
class Groupings extends Entity {

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
        'groupingKey' => true,
        'group_ID' => true,
		'site_ID' => true
    ];
}