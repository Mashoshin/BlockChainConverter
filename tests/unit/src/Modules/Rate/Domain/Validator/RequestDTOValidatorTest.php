<?php

namespace unit\src\Modules\Rate\Domain\Validator;

use app\src\Core\Domain\ValidationException;
use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Domain\Validator\RequestDTOValidator;
use Codeception\Test\Unit;

class RequestDTOValidatorTest extends Unit
{
	private RequestDTOValidator $validator;

	public function _inject(RequestDTOValidator $requestDTOValidator)
	{
		$this->validator = $requestDTOValidator;
	}

	public function testShouldThrowExceptionIfCurrencyFromIsNull()
	{
		$requestDTO = $this->createRequestDTO(null, 'USD', 1);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('Need currency_from param.');
		$this->validator->validate($requestDTO);
	}

	public function testShouldThrowExceptionIfCurrencyToIsNull()
	{
		$requestDTO = $this->createRequestDTO('USD', null, 1);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('Need currency_to param.');
		$this->validator->validate($requestDTO);
	}

	public function testShouldThrowExceptionIfValueIsNull()
	{
		$requestDTO = $this->createRequestDTO('USD', 'BTC', null);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('Need value param.');
		$this->validator->validate($requestDTO);
	}

	public function testShouldThrowExceptionIfNoCurrencyIsBTC()
	{
		$requestDTO = $this->createRequestDTO('USD', 'RUB', 1);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('One of the convertible currencies must be BTC.');
		$this->validator->validate($requestDTO);
	}

	public function testShouldThrowExceptionIfBoothCurrenciesAreBTC()
	{
		$requestDTO = $this->createRequestDTO('BTC', 'BTC', 1);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('You cannot convert BTC to BTC.');
		$this->validator->validate($requestDTO);
	}

	public function testShouldThrowExceptionIfConvertedValueIsTooSmall()
	{
		$requestDTO = $this->createRequestDTO('USD', 'BTC', 0.005);
		$this->expectException(ValidationException::class);
		$this->expectExceptionMessage('The minimum exchange must not be less than 0.01 USD.');
		$this->validator->validate($requestDTO);
	}

	private function createRequestDTO(?string $currencyFrom, ?string $currencyTo, ?float $value)
	{
		return new RequestDTO([
			'currency_from' => $currencyFrom,
			'currency_to' => $currencyTo,
			'value' => $value
		]);
	}
}
