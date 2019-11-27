<?php

    namespace App\Model\Entity;

    use Cake\ORM\Entity;

    /**
     * PhysicalSample Entity
     *
     * @property int $ID
     * @property int $site_location_id
     * @property \Cake\I18n\FrozenDate $Date
     * @property int $Sample_Number
     * @property \Cake\I18n\Time $Time
     * @property float $Bridge_to_Water_Height
     * @property float $Water_Temp
     * @property float $pH
     * @property float $Conductivity
     * @property float $TDS
     * @property float $DO
     * @property float $Turbidity
     * @property int $Turbidity_Scale_Value
     * @property string $PhysicalComments
     * @property \Cake\I18n\Time $Import_Date
     * @property \Cake\I18n\Time $Import_Time
     * @property string $Requires_Checking
     *
     * @property \App\Model\Entity\SiteLocation $site_location
     */
    class PhysicalSample extends Entity {

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
            'site_location_id' => true,
            'Date' => true,
            'Sample_Number' => true,
            'Time' => true,
            'Bridge_to_Water_Height' => true,
            'Water_Temp' => true,
            'pH' => true,
            'Conductivity' => true,
            'TDS' => true,
            'DO' => true,
            'Turbidity' => true,
            'Turbidity_Scale_Value' => true,
            'PhysicalComments' => true,
            'Import_Date' => true,
            'Import_Time' => true,
            'Requires_Checking' => true,
            'site_location' => true
        ];

        protected function _setDate($date) {
            return date('Y-m-d', strtotime($date));
        }
    }