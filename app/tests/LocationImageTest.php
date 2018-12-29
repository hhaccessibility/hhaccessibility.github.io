<?php

class LocationImageTest extends TestCase
{
    private function getImages()
    {
        $response = $this->get('/api/location/image/00000000-0000-0000-0000-000000000002');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $data = json_decode($content);
        $this->assertTrue(is_array($data));
        $this->assertTrue(count($data) > 0); // We want some data to test with.
        return $data;
    }

    public function testGetImages()
    {
        $data = $this->getImages();
        foreach ($data as $image) {
            $this->assertTrue(isset($image->id));
            $this->assertTrue(isset($image->created_at));
            $this->assertTrue(isset($image->uploader_name));
        }
    }

    public function testGetImage()
    {
        $images = $this->getImages();
        $response = $this->get('/location/image/' . $images[0]->id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
