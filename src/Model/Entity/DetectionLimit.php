<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DetectionLimit Entity
 *
 * @property string $Measure
 * @property float $Lowest_Acceptable_Value
 * @property float $Highest_Acceptable_Value
 */
class DetectionLimit extends Entity
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
        'Lowest_Acceptable_Value' => true,
        'Highest_Acceptable_Value' => true
    ];
}
