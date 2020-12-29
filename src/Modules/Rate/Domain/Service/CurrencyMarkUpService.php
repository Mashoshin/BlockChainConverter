<?php

namespace app\src\Modules\Rate\Domain\Service;

use app\src\Modules\Rate\Domain\DTO\CurrencyDTO;

class CurrencyMarkUpService
{
	/** @var float */
	private const PERCENT = 0.02;

	/**
	 * @param CurrencyDTO $dto
	 *
	 * @return CurrencyDTO
	 */
	public function calculateWithMarkUp(CurrencyDTO $dto): CurrencyDTO
	{
		$dto->buy += $dto->buy * self::PERCENT;
		$dto->sell -= $dto->sell * self::PERCENT;
		return $dto;
	}
}
