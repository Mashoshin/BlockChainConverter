<?php

namespace app\src\Modules\Rate\Infrastructure\ServiceClient;

use app\src\Modules\Rate\Domain\Service\BlockChainServiceClientInterface;
use yii\httpclient\Client;

class BlockChainServiceClient
{
	private const GET_RATES_URL = 'https://blockchain.info/ticker';

	/**
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\httpclient\Exception
	 */
	public function getRates(): array
	{
		$client = new Client();
		$response = $client->createRequest()
			->setMethod('GET')
			->setUrl(self::GET_RATES_URL)
			->send();

		if (!$response->isOk) {
			return new \DomainException('Currency rates not found');
		}

		return $response->getData();
	}
}
