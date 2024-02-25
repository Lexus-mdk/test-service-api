<?php

namespace console\models;

use console\models\ApiRequestService\ApiRequestRepository;
use console\models\ApiResponseService\ApiResponseRepository;
use console\models\TaskService\TaskRecord;

class ApiRequestService
{
    const API_URL = '';
    private ApiRequestRepository $apiRequestRepository;
    private ApiResponseRepository $apiResponseRepository;

    public function __construct(
        ApiRequestRepository $apiRequestRepository,
        ApiResponseRepository $apiResponseRepository
    )
    {
        $this->apiRequestRepository = $apiRequestRepository;
        $this->apiResponseRepository = $apiResponseRepository;
    }

    public function updateTaskInProcess(TaskRecord $task): TaskRecord
    {
        $request = $this->apiRequestRepository->getRequestByTaskId($task->id);
        if (!$request || !$request->request_id) {
            return $this->pushTaskToProcess($task);
        }

        $endpoint = $this->getEndpoint($task->type);
        list($response, $errCode) = $this->getRequestResult($endpoint, $request->id);
        if ($errCode == 503) {
            // Представим себе, что 503 статус значит, что задача ещё выполняется
            return $task;
        } elseif ($errCode) {
            $task->status = TaskRecord::ERROR;
            return $task;
        }

        $this->apiResponseRepository->saveResponse($response, $request->id);
        $task->status = TaskRecord::SUCCESS;
        return $task;
    }

    public function pushTaskToProcess(TaskRecord $task): TaskRecord
    {
        $endpoint = $this->getEndpoint($task->type);
        list($response, $errCode) = $this->setRequest($endpoint);

        if ($errCode) {
            $task->status = TaskRecord::ERROR;
            return $task;
        }

        $response = json_decode($response, true);

        $this->apiRequestRepository->create($response['RequestId'], $task->id);
        $task->status = TaskRecord::IN_PROCESS;
        return $task;
    }

    private function getEndpoint(int $type): string
    {
        switch ($type) {
            case TaskRecord::TYPE_USER:
                return 'users';
            case TaskRecord::TYPE_PRODUCT:
                return 'products';
            case TaskRecord::TYPE_PAYMENT:
                return 'payments';
        }

        throw new \Exception('Айайай, такого типа не существует(');
    }

    private function getRequestResult(string $endpoint, int $request_id): array
    {
        $endpoint = $endpoint . '/get';
        $data = ['RequestId' => $request_id];

        return $this->sendRequest($endpoint, false, $data);
    }

    private function setRequest(string $endpoint): array
    {
        $endpoint = $endpoint . '/setTask';
        return $this->sendRequest($endpoint, true, []);
    }

    private function sendRequest(string $url, bool $post = false, array $fields = []): array
    {
        $httpHeaders = [];

        $newUrl = self::API_URL . $url;
        if (!$post) {
            $newUrl = $newUrl . '?' . http_build_query($fields);
        }

        $ch = curl_init($newUrl);
        curl_setopt($ch, CURLOPT_POST, $post);

        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $httpHeaders[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $response = curl_exec($ch);
        $result = [$response, curl_errno($ch)];
        curl_close($ch);

        return $result;
    }
}