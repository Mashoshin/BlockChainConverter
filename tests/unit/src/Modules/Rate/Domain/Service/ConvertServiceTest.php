<?php

namespace unit\src\Modules\Rate\Domain\Service;

use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Domain\DTO\ResponseDto;
use app\src\Modules\Rate\Domain\Service\ConvertService;
use app\src\Modules\Rate\Infrastructure\DataProvider\CurrencyRateDataProvider;
use Codeception\Test\Unit;

class ConvertServiceTest extends Unit
{
	private ConvertService $convertService;

	private CurrencyRateDataProvider $currencyRateDataProvider;

	public function _inject(ConvertService $convertService, CurrencyRateDataProvider $currencyRateDataProvider)
	{
		$this->convertService = $convertService;
		$this->currencyRateDataProvider = $currencyRateDataProvider;
	}

	public function testConvertForPurchase()
	{
		$currencyRateWithoutMarkUp = $this->currencyRateDataProvider->getOneByName('USD');
		// стоимость одного биткоина
		$buyRate = $currencyRateWithoutMarkUp->getBuy();
		$requestDTO = $this->createRequestDTO('USD', 'BTC', $buyRate);
		$actual = $this->convertService->convertWithMarkUp($requestDTO, $currencyRateWithoutMarkUp);
		$this->assertInstanceOf(ResponseDto::class, $actual);
		$this->assertTrue(1 / $actual->getConvertedValue() > 1);

	}

	public function testConvertForSell()
	{
		$requestDTO = $this->createRequestDTO('BTC', 'USD', 1);
		$currencyRateWithoutMarkUp = $this->currencyRateDataProvider->getOneByName('USD');
		$sellRate = $currencyRateWithoutMarkUp->getSell();
		$actual = $this->convertService->convertWithMarkUp($requestDTO, $currencyRateWithoutMarkUp);
		$this->assertInstanceOf(ResponseDto::class, $actual);

		$this->assertTrue($sellRate / $actual->getConvertedValue() > 1);

	}

	private function createRequestDTO(string $currencyFrom, string $currencyTo, float $value)
	{
		return new RequestDTO([
			'currency_from' => $currencyFrom,
			'currency_to' => $currencyTo,
			'value' => $value
		]);
	}
}