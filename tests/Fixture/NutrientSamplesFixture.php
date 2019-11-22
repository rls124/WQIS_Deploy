<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NutrientSamplesFixture
 *
 */
class NutrientSamplesFixture extends TestFixture
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
        'Phosphorus' => ['type' => 'decimal', 'length' => 5, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'NH3-N' => ['type' => 'decimal', 'length' => 5, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'DRP' => ['type' => 'decimal', 'length' => 5, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'Comments' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'fk_site_location_id3' => ['type' => 'index', 'columns' => ['site_location_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
            'Sample_Number' => ['type' => 'unique', 'columns' => ['Sample_Number'], 'length' => []],
            'fk_site_location_id3' => ['type' => 'foreign', 'columns' => ['site_location_id'], 'references' => ['site_locations', 'Site_Number'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'Phosphorus' => 1.5,
            'NH3-N' => 1.5,
            'DRP' => 1.5,
            'Comments' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
