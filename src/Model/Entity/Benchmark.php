<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Benchmark Entity
 *
 * @property string $Measure
 * @property float $Minimum_Acceptable_Value
 * @property float $Maximum_Acceptable_Value
 */
class Benchmark extends Entity
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
        'Minimum_Acceptable_Value' => true,
        'Maximum_Acceptable_Value' => true
    ];
}
