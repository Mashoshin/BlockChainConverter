<?php

namespace unit\src\Modules\Rate\Infrastructure\ServiceClient;

use app\src\Modules\Rate\Infrastructure\ServiceClient\BlockChainServiceClient;
use Codeception\Test\Unit;

class BlockChainServiceClientTest extends Unit
{
	public function testGetRates()
	{
		$actual = (new BlockChainServiceClient())->getRates();
		$this->assertIsArray($actual);
	}
}