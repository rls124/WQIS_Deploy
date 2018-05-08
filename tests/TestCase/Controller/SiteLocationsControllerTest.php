<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SiteLocationsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\SiteLocationsController Test Case
 */
class SiteLocationsControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
