<?php

namespace unit\src\Modules\Rate\Domain\Service;

use app\src\Modules\Rate\Domain\DTO\CurrencyDTO;
use app\src\Modules\Rate\Domain\Service\CurrencyMarkUpService;
use Codeception\Test\Unit;

class CurrencyMarkUpServiceTest extends Unit
{
	private CurrencyMarkUpService $currencyMarkUpService;

	public function _inject(CurrencyMarkUpService $currencyMarkUpService)
	{
		$this->currencyMarkUpService = $currencyMarkUpService;
	}

	public function testCalculateWithMarkUp()
	{
		$dto = new CurrencyDTO(
			'USD',
			23000,
			23000,
			23000,
			23000,
			'$'
		);

		$expectedBuy = $dto->buy + ($dto->buy * 0.02);
		$expectedSell = $dto->sell - ($dto->sell * 0.02);
		$actual = $this->currencyMarkUpService->calculateWithMarkUp($dto);
		$this->assertEquals($expectedBuy, $actual->buy);
		$this->assertEquals($expectedSell, $actual->sell);
	}
}
