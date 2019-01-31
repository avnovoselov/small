<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Arguments extends TestCase
{
	/**
	 * @throws Exception
	 */
	public function test()
	{
		$this->assertFalse((new \Small\Arguments(['verbose' => false]))->get('verbose'));
		$this->assertNull((new \Small\Arguments())->get('missed-argument'));
	}
}