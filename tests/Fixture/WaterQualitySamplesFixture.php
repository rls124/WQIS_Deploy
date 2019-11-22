<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WaterQualitySamplesFixture
 *
 */
class WaterQualitySamplesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'ID' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'site_location_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'Date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'Sample_Number' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'Time' => ['type' => 'time', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'Water_Temp' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pH' => ['type' => 'decimal', 'length' => 4, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'Conductivity' => ['type' => 'decimal', 'length' => 5, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'TDS' => ['type' => 'decimal', 'length' => 4, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'DO' => ['type' => 'decimal', 'length' => 5, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'Turbidity' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'Turbidity_Scale_Value' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'Comments' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'Import_Date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'Import_Time' => ['type' => 'time', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'Requires_Checking' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'fk_site_location_id5' => ['type' => 'index', 'columns' => ['site_location_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
            'Sample_Number' => ['type' => 'unique', 'columns' => ['Sample_Number'], 'length' => []],
            'fk_site_location_id5' => ['type' => 'foreign', 'columns' => ['site_location_id'], 'references' => ['site_locations', 'Site_Number'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'ID' => 1,
            'site_location_id' => 1,
            'Date' => '2017-12-01',
            'Sample_Number' => 1,
            'Time' => '01:17:59',
            'Water_Temp' => 1,
            'pH' => 1.5,
            'Conductivity' => 1.5,
            'TDS' => 1.5,
            'DO' => 1.5,
            'Turbidity' => 1,
            'Turbidity_Scale_Value' => 1,
            'Comments' => 'Lorem ipsum dolor sit amet',
            'Import_Date' => '2017-12-01',
            'Import_Time' => '01:17:59',
            'Requires_Checking' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
