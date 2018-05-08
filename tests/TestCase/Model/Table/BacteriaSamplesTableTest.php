<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BacteriaSamplesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BacteriaSamplesTable Test Case
 */
class BacteriaSamplesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BacteriaSamplesTable
     */
    public $BacteriaSamples;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bacteria_samples',
        'app.site_locations',
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
        $config = TableRegistry::exists('BacteriaSamples') ? [] : ['className' => BacteriaSamplesTable::class];
        $this->BacteriaSamples = TableRegistry::get('BacteriaSamples', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BacteriaSamples);

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
