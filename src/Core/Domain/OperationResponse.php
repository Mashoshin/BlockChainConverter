<?php

namespace app\src\Core\Domain;

class OperationResponse implements \JsonSerializable
{
	/** @var string */
	private string $status;

	/** @var array|null */
	private ?array $data;

	/** @var string|null */
	private ?string $message;

	/** @var int|null */
	private ?int $statusCode;

	/**
	 * OperationResponse constructor.
	 * @param string $status
	 * @param array|null $data
	 * @param string|null $message
	 * @param int|null $statusCode
	 */
	public function __construct(string $status, ?array $data = null, ?string $message = null, ?int $statusCode = null)
	{
		$this->status = $status;
		$this->data = $data;
		$this->message = $message;
		$this->statusCode = $statusCode;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * @return array|null
	 */
	public function getData(): ?array
	{
		return $this->data;
	}

	/**
	 * @return string|null
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * @return int|null
	 */
	public function getStatusCode(): ?int
	{
		return $this->statusCode;
	}

	public function jsonSerialize()
	{
		$data = [
			'status' => $this->status,
			'code' => $this->statusCode,
		];

		$this->status === 'success'
			? $data['data'] = $this->getData()
			: $data['message'] = $this->getMessage();

		return $data;
	}
}