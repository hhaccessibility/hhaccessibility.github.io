<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginApiTest extends TestCase
{
    /**
     * Tests a successful authentication
	 * 
     * @return void
     */
    public function testPost()
    {
		$data = ['username' => 'test', 'password' => 'password'];
		$content = $this->post('/login', $data)->seeStatusCode(302);
    }
}
