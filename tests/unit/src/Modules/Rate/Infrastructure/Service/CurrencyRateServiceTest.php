<?php

namespace unit\src\Modules\Rate\Infrastructure\Service;

use app\src\Core\Domain\OperationResponse;
use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Infrastructure\Service\CurrencyRatesService;
use Codeception\Test\Unit;

class CurrencyRateServiceTest extends Unit
{
	private CurrencyRatesService $currencyRatesService;

	public function _inject(CurrencyRatesService $currencyRatesService)
	{
		$this->currencyRatesService = $currencyRatesService;
	}

	public function testGetOne()
	{
		$actual = $this->currencyRatesService->get('USD');
		$this->assertInstanceOf(OperationResponse::class, $actual);
		$this->assertEquals(count($actual->getData()), 1);
		$this->assertIsArray($actual->getData()['USD']);
		$this->assertEquals($actual->getStatusCode(), 200);
		$this->assertEquals($actual->getStatus(), 'success');
	}

	public function testGetAll()
	{
		$actual = $this->currencyRatesService->get(null);
		$this->assertInstanceOf(OperationResponse::class, $actual);
		$this->assertTrue(count($actual->getData()) > 1);

		$acc = 0;
		foreach ($actual->getData() as $currency) {
			$this->assertTrue($currency['buy'] > $acc);
			$acc = $currency['buy'];
		}

		$this->assertEquals($actual->getStatusCode(), 200);
		$this->assertEquals($actual->getStatus(), 'success');
	}

	public function testConvert()
	{
		$requestDTO = new RequestDTO([
			'currency_from' => 'USD',
			'currency_to' => 'BTC',
			'value' => 1.5
		]);
		$actual = $this->currencyRatesService->convert($requestDTO);
		$this->assertInstanceOf(OperationResponse::class, $actual);
		$this->assertEquals($actual->getStatusCode(), 200);
		$this->assertEquals($actual->getStatus(), 'success');
	}
}