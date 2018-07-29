<?php

class LocationRatingTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->flushSession();
        $data = ['email' => 'josh.greig2@gmail.com', 'password' => 'password'];
        $response = $this->post('/signin', $data);
    }

    private function checkLocationRatingContent($content)
    {
        $this->assertTrue(strpos($content, 'Tim Hortons') !== false);
        $this->assertTrue(strpos($content, 'Rate') !== false);
        $this->assertTrue(strpos($content, 'View') !== false);
        $this->assertTrue(strpos($content, 'Are there picture-based symbols for information and signage?') !== false);
        $this->assertTrue(strpos($content, 'question-explanation-link') !== false);
    }

    public function testGetAmenityReporting()
    {
        $response = $this->get('/location-reporting/00000000-0000-0000-0000-000000000001/6');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->checkLocationRatingContent($content);
    }

    public function testGetAmenityRating()
    {
        $response = $this->get('/location/rating/00000000-0000-0000-0000-000000000001/6');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->checkLocationRatingContent($content);
    }

    public function testSetComment()
    {
        srand(time());
        $data = [
            'question_category_id' => 6,
            'location_id' => '00000000-0000-0000-0000-000000000001',
            'comment' => 'Hello World'.rand()
        ];
        $response = $this->put('location/rating/comment', $data);
        $this->assertEquals(200, $response->getStatusCode());
        $response = $this->get('/location/rating/00000000-0000-0000-0000-000000000001/6');
        $content = $response->getContent();
        // The recently set comment should appear in the HTML content.
        $this->assertTrue(strpos($content, $data['comment']) !== false);

        // There should be no problem setting the comment to an empty string.
        // This is how the user clears his comment.
        $data['comment'] = '';
        $response = $this->put('location/rating/comment', $data);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
