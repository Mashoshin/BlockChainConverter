<?php

namespace app\tests\unit\src\Modules\Rate\Infrastructure\DataProvider;

use app\src\Modules\Rate\Domain\DTO\CurrencyDTO;
use app\src\Modules\Rate\Infrastructure\DataProvider\CurrencyRateDataProvider;
use Codeception\Test\Unit;

class CurrencyRateDataProviderTest extends Unit
{
	private CurrencyRateDataProvider $currencyRateDataProvider;

	public function _inject(CurrencyRateDataProvider $currencyRateDataProvider) {
		$this->currencyRateDataProvider = $currencyRateDataProvider;
	}

	public function testGetOne()
	{
		/** @var CurrencyDTO $actual */
		$actual = $this->currencyRateDataProvider->getOneByName('USD');
		$this->assertNotNull($actual);
		$this->assertInstanceOf(CurrencyDTO::class, $actual);
		$this->assertIsFloat($actual->buy);
		$this->assertIsFloat($actual->sell);
		$this->assertIsString($actual->symbol);
	}

	public function testGetAll()
	{
		/** @var CurrencyDTO[] $actual */
		$actual = $this->currencyRateDataProvider->getAll();
		foreach ($actual as $currency) {
			$this->assertInstanceOf(CurrencyDTO::class, $currency);
			$this->assertIsFloat($currency->buy);
			$this->assertIsFloat($currency->sell);
			$this->assertIsString($currency->symbol);
		}
	}
}
