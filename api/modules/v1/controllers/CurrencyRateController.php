<?php

namespace app\api\modules\v1\controllers;

use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Infrastructure\Service\CurrencyRatesService;

class CurrencyRateController extends AuthorizedController
{
	private CurrencyRatesService $currencyRatesService;

	public function __construct(
		$id,
		$module,
		CurrencyRatesService $currencyRatesService,
		$config = []
	) {
		$this->currencyRatesService = $currencyRatesService;
		parent::__construct($id, $module, $config);
	}

	public function actionSelect()
	{
		$method  = \Yii::$app->request->get('method');
		if ($method === 'rates') {
			return $this->rates();
		}
		if ($method === 'convert') {
			return $this->convert();
		}
	}

	private function rates()
	{
		$name = \Yii::$app->request->get('currency');
		$response = $this->currencyRatesService->get($name);
		return $this->asJson($response->jsonSerialize());
	}

	private function convert()
	{
		$params = \Yii::$app->request->post();
		$requestDTO = new RequestDTO($params);
		$response = $this->currencyRatesService->convert($requestDTO);
		return $this->asJson($response->jsonSerialize());
	}
}
