<?php

class LocationReportTest extends TestCase
{
    public function testGetLocationDestroyEventInfo()
    {
        $response = $this->get('/location/report/00000000-0000-0000-0000-000000000574');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'Music Building') !== false);
        $this->assertTrue(strpos($content, 'This location was destroyed.') !== false);

        $response = $this->get('location/event/c74757d1-fc04-3006-8982-9839a84612f7');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'Learn more by visiting') !== false);
        $this->assertTrue(strpos($content, 'April 11, 2018') !== false);
    }

    public function testGet()
    {
        $response = $this->get('/location/report/00000000-0000-0000-0000-000000000001');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'Tim Hortons') !== false);
        $this->assertTrue(strpos($content, 'universal-personal') !== false);
        $this->assertTrue(strpos($content, 'This location was destroyed.') === false);
    }
}
