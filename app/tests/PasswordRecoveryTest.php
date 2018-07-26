<?php

class PasswordRecoveryTest extends TestCase
{
    public function testGetForm()
    {
        $response = $this->get('/user/password-recovery');
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertTrue(strpos($content, 'Account Recovery') !== false);
    }

    public function testPostInvalidEmailAddress()
    {
        $data = [
            'email' => '',
            'g-recaptcha-response' => ''
        ];
        $response = $this->post('/user/password-recovery', $data);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
