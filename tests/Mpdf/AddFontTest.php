<?php

namespace Mpdf;

class AddFontTest extends \Mpdf\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf();
	}

	public function testAddFont()
	{
		$this->mpdf->AddFont('sun-exta');
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage Font "font" is not supported
	 */
	public function testAddUnsupportedFont()
	{
		$this->mpdf->AddFont('font');
	}

}
