<?php

    namespace App\Model\Entity;

    use Cake\ORM\Entity;

    /**
     * PesticideSample Entity
     *
     * @property int $ID
     * @property int $site_location_id
     * @property \Cake\I18n\FrozenDate $Date
     * @property int $Sample_Number
     * @property float $Atrazine
     * @property float $Alachlor
     * @property float $Metolachlor
     * @property string $PesticideComments
     *
     * @property \App\Model\Entity\SiteLocation $site_location
     */
    class PesticideSample extends Entity {

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
            'Atrazine' => true,
            'Alachlor' => true,
            'Metolachlor' => true,
            'PesticideComments' => true,
            'site_location' => true
        ];

        protected function _setDate($date) {
            return date('Y-m-d', strtotime($date));
        }
    }