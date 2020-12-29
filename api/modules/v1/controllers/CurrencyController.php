<?php

namespace app\api\modules\v1\controllers;

use app\src\Modules\Rate\Domain\DTO\RequestDTO;
use app\src\Modules\Rate\Infrastructure\Service\CurrencyRatesService;

/**
 * Альтернативный контроллер, по моему мнению боллее понятный
 *
 * @package app\api\modules\v1\controllers
 */
class CurrencyController extends AuthorizedController
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

	protected function verbs()
	{
		return [
			'rates' => ['GET'],
			'convert' => ['POST']
		];
	}

	/**
	 * GET v1/rates
	 *
	 * GET params:
	 *  string 'currency' - currency name
	 *
	 * @return \yii\web\Response
	 */
	public function actionGetRates()
	{
		$name = \Yii::$app->request->get('currency');
		$response = $this->currencyRatesService->get($name);
		return $this->asJson($response->jsonSerialize());
	}

	/**
	 * POST v1/convert
	 *
	 * POST params:
	 *  string 'currency_from'
	 *  string 'currency_to'
	 *  float 'value'
	 *
	 * @return \yii\web\Response
	 */
	public function actionConvert()
	{
		$params = \Yii::$app->request->post();
		$requestDTO = new RequestDTO($params);
		$response = $this->currencyRatesService->convert($requestDTO);
		return $this->asJson($response->jsonSerialize());
	}
}
