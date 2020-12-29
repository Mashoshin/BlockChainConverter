<?php

namespace app\src\Modules\Rate\Domain\DTO;

class RequestDTO
{
	/** @var string|null  */
	private ?string $currency_from;

	/** @var string|null  */
	private ?string $currency_to;

	/** @var float|null  */
	private ?float $value;

	public function __construct($data)
	{
		$this->currency_from = isset($data['currency_from']) && is_string($data['currency_from'])
			? strtoupper($data['currency_from']) : null;
		$this->currency_to = isset($data['currency_to']) && is_string($data['currency_to'])
			? strtoupper($data['currency_to']) : null;
		$this->value = isset($data['value']) ? $data['value'] : null;
	}

	/**
	 * @return string|null
	 */
	public function getCurrencyFrom()
	{
		return $this->currency_from;
	}

	/**
	 * @return string|null
	 */
	public function getCurrencyTo()
	{
		return $this->currency_to;
	}

	/**
	 * @return float|null
	 */
	public function getValue() {
		return $this->value;
	}
}
