<?php

namespace console\models\ApiRequestService;

class ApiRequestRepository
{
    public function getRequestByTaskId(int $taskId): ?ApiRequestRecord
    {
        return ApiRequestRecord::findOne(['task_id' => $taskId]);
    }

    public function create(int $requestId, int $taskId)
    {
        $request = new ApiRequestRecord();
        $request->request_id = $requestId;
        $request->task_id = $taskId;
        $request->save();
    }
}