<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    //
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        // $this->artisan('passport:install');
    }
}
