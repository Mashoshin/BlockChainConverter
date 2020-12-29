<?php

namespace app\src\Modules\Rate\Domain\DTO;

class ResponseDto implements \JsonSerializable
{
	/** @var string  */
	private string $currencyFrom;

	/** @var string  */
	private string $currencyTo;

	/** @var float  */
	private float $value;

	/** @var float  */
	private float $convertedValue;

	/** @var float  */
	private float $rate;

	public function __construct(
		string $currencyFrom,
		string $currencyTo,
		float $value,
		float $convertedValue,
		float $rate
	) {
		$this->currencyFrom = $currencyFrom;
		$this->currencyTo = $currencyTo;
		$this->value = $value;
		$this->convertedValue = $convertedValue;
		$this->rate = $rate;
	}

	/**
	 * @return float
	 */
	public function getConvertedValue(): float
	{
		return $this->convertedValue;
	}

	public function jsonSerialize()
	{
		return [
			'currency_from' => $this->currencyFrom,
			'currency_to' => $this->currencyTo,
			'value' => $this->value,
			'convertedValue' => $this->convertedValue,
			'rate' => $this->rate,
		];
	}
}