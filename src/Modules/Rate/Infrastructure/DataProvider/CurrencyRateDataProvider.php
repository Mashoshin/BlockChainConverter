<?php

namespace app\src\Modules\Rate\Infrastructure\DataProvider;

use app\src\Modules\Rate\Domain\DTO\CurrencyDTO;
use app\src\Modules\Rate\Infrastructure\ServiceClient\BlockChainServiceClient;
use src\Core\Domain\NotFoundException;

class CurrencyRateDataProvider
{
	private BlockChainServiceClient $blockChainServiceClient;

	public function __construct(BlockChainServiceClient $blockChainServiceClient)
	{
		$this->blockChainServiceClient = $blockChainServiceClient;
	}

	/**
	 * @param string $name
	 *
	 * @return CurrencyDTO
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\httpclient\Exception
	 */
	public function getOneByName(string $name): CurrencyDTO
	{
		$sources = $this->blockChainServiceClient->getRates();
		$source = $sources[$name];
		if (!$source) {
			throw new NotFoundException("Currency with name $name does not supported.");
		}

		return $this->map($source, $name);
	}

	/**
	 * @return CurrencyDTO[]
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\httpclient\Exception
	 */
	public function getAll()
	{
		$sources = $this->blockChainServiceClient->getRates();

		$result = [];
		foreach ($sources as $name => $values)
		{
			$result[] = $this->map($values, $name);
		}

		return $result;
	}

	/**
	 * @param array  $rate
	 * @param string $name
	 *
	 * @return CurrencyDTO
	 */
	private function map(array $rate, string $name): CurrencyDTO
	{
		return new CurrencyDTO(
			$name,
			$rate['15m'],
			$rate['last'],
			$rate['buy'],
			$rate['sell'],
			$rate['symbol']
		);
	}
}
