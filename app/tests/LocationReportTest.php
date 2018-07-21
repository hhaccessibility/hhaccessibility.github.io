<?php

class LocationReportTest extends TestCase
{
    public function testGet()
    {
        $response = $this->get('/location/report/00000000-0000-0000-0000-000000000001');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'Tim Hortons') !== false);
        $this->assertTrue(strpos($content, 'universal-personal') !== false);
    }
}
