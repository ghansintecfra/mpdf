<?php

namespace Mpdf;

use Mockery;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	protected function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

}
