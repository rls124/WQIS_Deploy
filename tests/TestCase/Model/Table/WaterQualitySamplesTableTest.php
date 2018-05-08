<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WaterQualitySamplesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WaterQualitySamplesTable Test Case
 */
class WaterQualitySamplesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WaterQualitySamplesTable
     */
    public $WaterQualitySamples;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.water_quality_samples',
        'app.site_locations',
        'app.bacteria_samples',
        'app.hydrolab_samples',
        'app.nutrient_samples',
        'app.pesticide_samples'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('WaterQualitySamples') ? [] : ['className' => WaterQualitySamplesTable::class];
        $this->WaterQualitySamples = TableRegistry::get('WaterQualitySamples', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WaterQualitySamples);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
