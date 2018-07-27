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
}
