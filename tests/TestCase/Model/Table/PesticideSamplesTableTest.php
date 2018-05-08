<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PesticideSamplesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PesticideSamplesTable Test Case
 */
class PesticideSamplesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PesticideSamplesTable
     */
    public $PesticideSamples;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pesticide_samples',
        'app.site_locations',
        'app.bacteria_samples',
        'app.hydrolab_samples',
        'app.nutrient_samples',
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
        $config = TableRegistry::exists('PesticideSamples') ? [] : ['className' => PesticideSamplesTable::class];
        $this->PesticideSamples = TableRegistry::get('PesticideSamples', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PesticideSamples);

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
