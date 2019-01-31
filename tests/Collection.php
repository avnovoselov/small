<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Collection extends TestCase
{
	/**
	 * @throws Exception
	 */
	public function testAssoc()
	{
		$users = [
			['id' => 1, 'name' => 'Alexander', 'age' => 21],
			['id' => 2, 'name' => 'Elena', 'age' => 24],
			['id' => 3, 'name' => 'Michael', 'age' => 25],
			['id' => 4, 'name' => 'Valentina', 'age' => 22],
			['_id' => 5, 'name' => 'Maria', 'age' => 24],
		];

		$usersAssoc = \Small\Helper\Collection::assoc($users, 'id', \Small\Helper\Collection::ASSOC_ACTION_OVERWRITE);

		$this->assertArrayHasKey(1, $usersAssoc);
		$this->assertArrayHasKey(2, $usersAssoc);
		$this->assertArrayHasKey(3, $usersAssoc);
		$this->assertArrayHasKey(4, $usersAssoc);
		$this->assertArrayNotHasKey(5, $usersAssoc);
		$this->assertEquals(4, count($usersAssoc));
		$this->assertEquals('Elena', $usersAssoc[2]['name']);

		$location = [
			['id' => 1, 'user' => 1, 'city' => 'Moscow'],
			['id' => 2, 'user' => 1, 'city' => 'Phuket'],
			['id' => 3, 'user' => 2, 'city' => 'Saint-Petersburg'],
			['id' => 4, 'user' => 3, 'city' => 'Phuket'],
			['id' => 5, 'user' => 2, 'city' => 'Ekaterinburg'],
		];

		$locationAssoc = \Small\Helper\Collection::assoc($location, 'user', \Small\Helper\Collection::ASSOC_ACTION_IGNORE);
		$this->assertEquals(3, count($locationAssoc));
		$this->assertEquals('Moscow', $locationAssoc[1]['city']);

		$document = [
			['id' => 1, 'user' => 1, 'number' => 9001],
			['id' => 2, 'user' => 2, 'number' => 9002],
			['id' => 3, 'user' => 3, 'number' => 9003],
			['id' => 4, 'user' => 1, 'number' => 9004],
		];

		$documentAssoc = \Small\Helper\Collection::assoc($document, 'user', \Small\Helper\Collection::ASSOC_ACTION_GROUP);
		$this->assertArrayNotHasKey(4, $documentAssoc);
		$this->assertArrayHasKey(2, $documentAssoc);
		$this->assertEquals(2, count($documentAssoc[1]));

		$documentLog = [
			['id' => 1, 'user' => 1, 'passport' => '1111 111111'],
			['id' => 2, 'user' => 2, 'passport' => '1112 222222'],
			['id' => 3, 'user' => 1, 'license' => '111 1111'],
			['id' => 4, 'user' => 2, 'license' => '112 2222'],
			['id' => 5, 'user' => 1, 'inn' => '22334455'],
			['id' => 6, 'user' => 3, 'passport' => '1113 333333'],
		];

		$documentLogAssoc = \Small\Helper\Collection::assoc($documentLog, 'user', \Small\Helper\Collection::ASSOC_ACTION_MERGE);
		$this->assertEquals(count(array_unique(array_column($documentLog, 'user'))), count($documentLogAssoc));
		$this->assertArrayHasKey(3, $documentLogAssoc);
		$this->assertArrayNotHasKey(6, $documentLogAssoc);
		$this->assertArrayHasKey('passport', $documentLogAssoc[1]);
		$this->assertArrayHasKey('license', $documentLogAssoc[1]);
		$this->assertArrayHasKey('inn', $documentLogAssoc[1]);
		$this->assertArrayNotHasKey('inn', $documentLogAssoc[2]);

		try {
			$users = [
				['id' => 1, 'name' => 'Alexander', 'access' => 'client'],
				['id' => 1, 'name' => 'Elena', 'access' => 'manager'],
			];
			\Small\Helper\Collection::assoc($users, 'id', \Small\Helper\Collection::ASSOC_ACTION_EXCEPTION);
		} catch (Exception $e) {
			$this->assertEquals('Duplicate elements', $e->getMessage());
		}
	}
}