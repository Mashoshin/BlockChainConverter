<?php

namespace app\src\Modules\Rate\Domain\Service;

use app\src\Modules\Rate\Domain\DTO\CurrencyDTO;
use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Domain\DTO\ResponseDto;

class ConvertService
{
	/** @var int  */
	private const PURCHASE_ROUND_VALUE = 2;

	/** @var int  */
	private const SELL_ROUND_VALUE = 10;

	private CurrencyMarkUpService $currencyMarkUpService;

	public function __construct(
		CurrencyMarkUpService $currencyMarkUpService
	)
	{
		$this->currencyMarkUpService = $currencyMarkUpService;
	}

	public function convertWithMarkUp(RequestDTO $requestDTO, CurrencyDTO $currencyDTO): ResponseDto
	{
		$this->currencyMarkUpService->calculateWithMarkUp($currencyDTO);

		if ($requestDTO->getCurrencyFrom() === "BTC") {
			$rate = $currencyDTO->getSell();
			$convertedValue = $this->calculateForPurchase($rate, $requestDTO->getValue());
		}

		if ($requestDTO->getCurrencyTo() === "BTC") {
			$rate = $currencyDTO->getBuy();
			$convertedValue = $this->calculateForSell($rate, $requestDTO->getValue());
		}

		return new ResponseDto(
			$requestDTO->getCurrencyFrom(),
			$requestDTO->getCurrencyTo(),
			$requestDTO->getValue(),
			$convertedValue,
			$rate
		);
	}

	private function calculateForPurchase(float $rate, float $value)
	{
		$convertedValue = $value * $rate;

		// Округляем до 2 знаков после запятой в меньшую сторону
		return round(
			$convertedValue,
			self::PURCHASE_ROUND_VALUE,
			PHP_ROUND_HALF_DOWN
		);
	}

	private function calculateForSell(float $rate, float $value)
	{
		$convertedValue = $value / $rate;
		// Округляем до 10 знаков после запятой в большую сторону
		return round(
			$convertedValue,
			self::SELL_ROUND_VALUE,
			PHP_ROUND_HALF_UP
		);
	}
}
