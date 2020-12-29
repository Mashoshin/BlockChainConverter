<?php

namespace app\src\Modules\Rate\Infrastructure\Service;

use app\src\Modules\Rate\Infrastructure\DataProvider\CurrencyRateDataProvider;
use app\src\Core\Domain\OperationResponse;
use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Domain\Service\ConvertService;
use app\src\Modules\Rate\Domain\Service\CurrencyMarkUpService;
use app\src\Modules\Rate\Domain\Validator\RequestDTOValidator;
use app\src\Modules\Rate\Infrastructure\ServiceClient\BlockChainServiceClient;

class CurrencyRatesService
{
	private BlockChainServiceClient $blockChainServiceClient;

	private CurrencyMarkUpService $currencyMarkUpService;

	private ConvertService $convertService;

	private RequestDTOValidator $requestDTOValidator;

	private CurrencyRateDataProvider $currencyRateDataProvider;

	public function __construct(
		BlockChainServiceClient $blockChainServiceClient,
		CurrencyMarkUpService $currencyMarkUpService,
		ConvertService $convertService,
		RequestDTOValidator $requestDTOValidator,
		CurrencyRateDataProvider $currencyRateDataProvider
	) {
		$this->blockChainServiceClient = $blockChainServiceClient;
		$this->currencyMarkUpService = $currencyMarkUpService;
		$this->convertService = $convertService;
		$this->requestDTOValidator = $requestDTOValidator;
		$this->currencyRateDataProvider = $currencyRateDataProvider;
	}

	/**
	 * @param string|null $name
	 *
	 * @return OperationResponse
	 */
	public function get(?string $name)
	{
		$currencyName = $name ? strtoupper($name) : null;

		if ($currencyName) {
			return $this->getOne($currencyName);
		}

		return $this->getAll();
	}

	/**
	 * @param string $name
	 *
	 * @return OperationResponse
	 */
	private function getOne(string $name): OperationResponse
	{
		$result = null;
		$message = null;
		$code = null;
		try {
			$currency = $this->currencyRateDataProvider->getOneByName($name);
			$this->currencyMarkUpService->calculateWithMarkUp($currency);
			$result = $currency->jsonSerialize();
			$status = 'success';
			$code = 200;
		} catch (\Exception $exception) {
			$status = 'error';
			$code = 500;
			$message = $exception->getMessage();
		}

		return new OperationResponse($status, $result, $message, $code);
	}

	/**
	 * @return OperationResponse
	 */
	private function getAll(): OperationResponse
	{
		$result = null;
		$message = null;
		$code = null;
		try {
			$currencyRates = $this->currencyRateDataProvider->getAll();
			$result = [];
			foreach ($currencyRates as $rate) {
				$this->currencyMarkUpService->calculateWithMarkUp($rate);
				$result = array_merge($result, $rate->jsonSerialize());
			}
			uasort($result, fn ($first, $second) => $first['buy'] <=> $second['buy']);

			$status = 'success';
			$code = 200;
		} catch (\Exception $exception) {
			$status = 'error';
			$code = 500;
			$message = $exception->getMessage();
		}

		return new OperationResponse($status, $result, $message, $code);
	}

	public function convert(RequestDTO $requestDTO): OperationResponse
	{
		$result = null;
		$message = null;
		$code = null;
		try {
			$this->requestDTOValidator->validate($requestDTO);
			$currencyName = $requestDTO->getCurrencyFrom() !== 'BTC'
				? $requestDTO->getCurrencyFrom()
				: $requestDTO->getCurrencyTo();
			$currency = $this->currencyRateDataProvider->getOneByName($currencyName);
			$convertedData = $this->convertService->convertWithMarkUp($requestDTO ,$currency);
			$result = $convertedData->jsonSerialize();
			$status = 'success';
			$code = 200;
		} catch (\Exception $exception) {
			$status = 'error';
			$code = 500;
			$message = $exception->getMessage();
		}

		return new OperationResponse($status, $result, $message, $code);
	}
}
