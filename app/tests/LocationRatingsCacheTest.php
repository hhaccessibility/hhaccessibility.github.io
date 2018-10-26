<?php

class LocationRatingsCacheTest extends TestCase
{
    private function processCache($request_path, $data = [])
    {
        $response = $this->post($request_path, $data);
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $response_data = json_decode($content);
        return $response_data;
    }
    
    /**
     * Tests a rating cache calculation
     *
     * @return void
     */
    public function testLocationRatingsCachePost()
    {
        $response_data = $this->processCache('/api/populate-ratings-cache');
        $this->assertTrue(is_object($response_data));
        $this->assertTrue(isset($response_data->number_rated));
        $this->assertInternalType('int', $response_data->number_rated);
        $this->assertInternalType('int', $response_data->number_unrated);
        $this->assertTrue($response_data->number_rated >= 0);
        $this->assertTrue($response_data->number_unrated >= 0);

        // Check that progress was made.
        $number_remaining = $response_data->number_unrated;
        $response_data = $this->processCache('/api/populate-ratings-cache');
        if ($number_remaining > 0) {
            $this->assertTrue($number_remaining > $response_data->number_unrated);
        }
    }

    public function testRootLocationGroupRatingsCachePost()
    {
        $response_data = $this->processCache('/api/populate-root-group-ratings-cache');
        $this->assertTrue(is_object($response_data));
    }

    public function testLocationGroupRatingsCachePost()
    {
        $data = [
            'location_group_id' => 1
        ];
        $response_data = $this->processCache('/api/populate-group-ratings-cache', $data);
        $this->assertTrue(is_object($response_data));
    }
}
