<?php

    namespace App\Model\Entity;

    use Cake\ORM\Entity;

    /**
     * SiteLocation Entity
     *
     * @property int $Site_Number
     * @property float $Longitude
     * @property float $Latitude
     * @property string $Site_Location
     * @property string $Site_Name
	 * @property string $groups
     *
     * @property \App\Model\Entity\BacteriaSample[] $bacteria_samples
     * @property \App\Model\Entity\NutrientSample[] $nutrient_samples
     * @property \App\Model\Entity\PesticideSample[] $pesticide_samples
     * @property \App\Model\Entity\PhysicalSample[] $physical_samples
     */
    class SiteLocation extends Entity {

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
            'Site_Number' => true,
            'Longitude' => true,
            'Latitude' => true,
            'Site_Location' => true,
            'Site_Name' => true,
            'bacteria_samples' => true,
            'nutrient_samples' => true,
            'pesticide_samples' => true,
            'physical_samples' => true,
			'groups' => true
        ];
    }