<?php

class PWATest extends TestCase
{
    public function testGet()
    {
        $response = $this->get('/manifest.json');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
