<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BuildingApiTest extends TestCase
{
    /**
	 * Checks that the HTTP GET api returns the expected format
	 * for /api/buildings
     *
     * @return void
     */
    public function testGet()
    {
		$buildings_content = $this->get('/api/buildings')->seeStatusCode(200)->response->getContent();
		$buildings_data = json_decode($buildings_content);
		$this->assertTrue(is_array($buildings_data));
		foreach ($buildings_data as $building) {
			$this->assertTrue(is_object($building));
			$this->assertTrue(is_int($building->id));
			$this->assertTrue($building->owner_user_id === NULL || is_int($building->owner_user_id));
			$this->assertTrue($building->name === NULL || is_string($building->name));
			$this->assertTrue(is_int($building->longitude) || is_float($building->longitude));
			$this->assertTrue(is_int($building->longitude) || is_float($building->longitude));
		}
    }
}
