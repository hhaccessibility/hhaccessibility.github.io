<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CopyrightTest extends TestCase
{
    /**
     * A test to see that 'JMCC' is contained on the site's home page.
	 * This is more important as an example to learn from than a useful test since
	 * it isn't a clear business requirement that 'JMCC' be contained in the home page.
     *
     * @return void
     */
    public function testJMCCInHomePage()
    {
        $this->visit('/')
             ->see('JMCC');
    }
}
