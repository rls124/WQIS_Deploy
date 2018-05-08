<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BenchmarksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BenchmarksTable Test Case
 */
class BenchmarksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BenchmarksTable
     */
    public $Benchmarks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.benchmarks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Benchmarks') ? [] : ['className' => BenchmarksTable::class];
        $this->Benchmarks = TableRegistry::get('Benchmarks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Benchmarks);

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
