<?php

use Pest\TestSuite;

/*
|--------------------------------------------------------------------------
| Test Suite Configuration
|--------------------------------------------------------------------------
|
| Here you may define all of the test suites for your application as
| well as their directories. Test suites may be grouped together, and
| may be given additional options like `parallel` or `processes`.
|
| Parfait is a beautiful and minimal testing framework for PHP with
| a focus on simplicity. It was carefully crafted to bring the joy
| of testing to PHP.
|
*/

uses()->group('unit')->in('tests/Unit');
uses()->group('feature')->in('tests/Feature'); 