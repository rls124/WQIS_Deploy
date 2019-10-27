<?php

    namespace App\Model\Entity;

    use Cake\ORM\Entity;

    /**
     * WaterQualitySample Entity
     *
     * @property int $ID
     * @property int $site_location_id
     * @property \Cake\I18n\FrozenDate $Date
     * @property int $Sample_Number
     * @property \Cake\I18n\FrozenTime $Time
     * @property float $Bridge_To_Water_Height
     * @property int $Water_Temp
     * @property int $Water_Temp_Exception
     * @property float $pH
     * @property int $pH_Exception
     * @property float $Conductivity
     * @property int $Conductivity_Exception
     * @property float $TDS
     * @property int $TDS_Exception
     * @property float $DO
     * @property int $DO_Exception
     * @property int $Turbidity
     * @property int $Turbidity_Exception
     * @property int $Turbidity_Scale_Value
     * @property string $Comments
     * @property \Cake\I18n\FrozenDate $Import_Date
     * @property \Cake\I18n\FrozenTime $Import_Time
     * @property string $Requires_Checking
     *
     * @property \App\Model\Entity\SiteLocation $site_location
     */
    class WaterQualitySample extends Entity {

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
            'Bridge_To_Water_Height' => true,
            'Water_Temp' => true,
            'Water_Temp_Exception' => true,
            'pH' => true,
            'pH_Exception' => true,
            'Conductivity' => true,
            'Conductivity_Exception' => true,
            'TDS' => true,
            'TDS_Exception' => true,
            'DO' => true,
            'DO_Exception' => true,
            'Turbidity' => true,
            'Turbidity_Exception' => true,
            'Turbidity_Scale_Value' => true,
            'Comments' => true,
            'Import_Date' => true,
            'Import_Time' => true,
            'Requires_Checking' => true,
            'site_location' => true
        ];

        protected function _setDate($date) {
            return date('Y-m-d', strtotime($date));
        }

    }