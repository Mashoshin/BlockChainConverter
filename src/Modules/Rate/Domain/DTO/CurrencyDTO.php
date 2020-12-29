<?php

namespace app\src\Modules\Rate\Domain\DTO;

class CurrencyDTO implements \JsonSerializable
{
	public ?string $name;

	public ?float $m15;

	public ?float $last;

	public ?float $buy;

	public ?float $sell;

	public ?string $symbol;

	public function __construct(
		?string $name,
		?float $m15,
		?float $last,
		?float $buy,
		?float $sell,
		?string $symbol
	) {
		$this->name = $name;
		$this->m15 = $m15;
		$this->last = $last;
		$this->buy = $buy;
		$this->sell = $sell;
		$this->symbol = $symbol;
	}

	public function jsonSerialize() {
		return [
			$this->name => [
				'15m' => $this->m15,
				'last' => $this->last,
				'buy' => $this->buy,
				'sell' => $this->sell,
				'symbol' => $this->symbol
			]
		];
	}

	/**
	 * @return float|null
	 */
	public function getSell(): ?float
	{
		return $this->sell;
	}

	/**
	 * @return float|null
	 */
	public function getBuy(): ?float
	{
		return $this->buy;
	}
}
