<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Pest Test Case Configuration
|--------------------------------------------------------------------------
|
| This file lets Pest know which base test case class to use and sets up
| common test traits like RefreshDatabase for all Feature tests.
|
*/

uses(TestCase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Feature');
