<?php

class PWATest extends TestCase
{
    // Tests that the manifest.json is referenced from the home page.  It should be in a meta tag on the home page.
    public function testGetManifestFromHomePage()
    {
        $response = $this->get('/');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'manifest.json') !== false);
    }

    public function testGetManifest()
    {
        $response = $this->get('/manifest.json');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetRatableLocation()
    {
        // Coordinates of Tim Hortons
        $longitude = -83.04508872;
        $latitude = 42.31594457;
        $response = $this->get('/api/location/nearby/'.$longitude.'/'.$latitude);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent());
        $this->assertTrue($data->name === 'Tim Hortons');

        // Coordinates of a random place in the Pacific Ocean.
        $longitude = 168.5798541;
        $latitude = 31.0437175;
        $response = $this->get('/api/location/nearby/'.$longitude.'/'.$latitude);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
