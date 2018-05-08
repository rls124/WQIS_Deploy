<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HydrolabSamplesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HydrolabSamplesTable Test Case
 */
class HydrolabSamplesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HydrolabSamplesTable
     */
    public $HydrolabSamples;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hydrolab_samples',
        'app.site_locations',
        'app.bacteria_samples',
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
        $config = TableRegistry::exists('HydrolabSamples') ? [] : ['className' => HydrolabSamplesTable::class];
        $this->HydrolabSamples = TableRegistry::get('HydrolabSamples', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HydrolabSamples);

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
