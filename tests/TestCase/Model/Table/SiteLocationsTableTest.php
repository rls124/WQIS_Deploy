<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SiteLocationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SiteLocationsTable Test Case
 */
class SiteLocationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SiteLocationsTable
     */
    public $SiteLocations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.site_locations',
        'app.bacteria_samples',
        'app.hydrolab_samples',
        'app.nutrient_samples',
        'app.pesticide_samples',
        'app.water_quality_samples'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SiteLocations') ? [] : ['className' => SiteLocationsTable::class];
        $this->SiteLocations = TableRegistry::get('SiteLocations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SiteLocations);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
