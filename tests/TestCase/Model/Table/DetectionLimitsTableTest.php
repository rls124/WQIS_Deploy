<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DetectionLimitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DetectionLimitsTable Test Case
 */
class DetectionLimitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DetectionLimitsTable
     */
    public $DetectionLimits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.detection_limits'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DetectionLimits') ? [] : ['className' => DetectionLimitsTable::class];
        $this->DetectionLimits = TableRegistry::get('DetectionLimits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DetectionLimits);

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
