<?php

namespace app\src\Modules\Rate\Domain\Validator;

use app\src\Core\Domain\ValidationException;
use app\src\Modules\Rate\Domain\DTO\RequestDTO;

class RequestDTOValidator
{
	/** @var float  */
	private const MIN_CONVERTED_VALUE = 0.01;

	/**
	 * @param RequestDTO $dto
	 *
	 * @return bool
	 * @throws ValidationException
	 */
	public function validate(RequestDTO $dto): bool
	{
		if (!$dto->getCurrencyFrom()) {
			throw new ValidationException('Need currency_from param.');
		}

		if (!$dto->getCurrencyTo()) {
			throw new ValidationException('Need currency_to param.');
		}

		if (!$dto->getValue()) {
			throw new ValidationException('Need value param.');
		}

		if ($dto->getCurrencyFrom() !== 'BTC' && $dto->getCurrencyTo() !== 'BTC') {
			throw new ValidationException('One of the convertible currencies must be BTC.');
		}

		if ($dto->getCurrencyFrom() === 'BTC' && $dto->getCurrencyTo() === 'BTC') {
			throw new ValidationException('You cannot convert BTC to BTC.');
		}

		if ($dto->getValue() < self::MIN_CONVERTED_VALUE) {
			throw new ValidationException("The minimum exchange must not be less than 0.01 {$dto->getCurrencyFrom()}.");
		}

		return true;
	}
}
