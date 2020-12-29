<?php

namespace app\api\modules\v1\controllers;

use app\src\Core\Domain\OperationResponse;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;

class AuthorizedController extends Controller
{
	public function runAction($id, $params = [])
	{
		try {
			return parent::runAction($id, $params);
		} catch (\Throwable $e) {
			$status = 'error';
			$message = $e->getMessage();
			$code = 500;
			$response = new OperationResponse($status, null, $message, $code);
			return $this->asJson($response->jsonSerialize());
		}
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::class
		];
		$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

		return $behaviors;
	}

	public function beforeAction($action)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return parent::beforeAction($action);
	}
}
