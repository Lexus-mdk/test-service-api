<?php

namespace console\models\TaskService;

class TaskRepository
{
    public function isQueueClear(): bool
    {
        return (bool) TaskRecord::find()
            ->where(['status' => [TaskRecord::IN_PROCESS, TaskRecord::IN_QUEUE]])
            ->one();
    }

    public function create(int $type): bool
    {
        $task = new TaskRecord();
        $task->status = TaskRecord::IN_QUEUE;
        $task->type = $type;
        $task->created_at = $task->updated_at = time();
        return $task->save();
    }

    public function getTasksByStatus(int $status): array
    {
        /** @var TaskRecord[] $result */
        $result = TaskRecord::find()
            ->where(['status' => $status])
            ->orderBy('created_at')
            ->all();

        return $result;
    }

    public function updateTaskModel(TaskRecord $task)
    {
        $task->updated_at = time();
        $task->save();
    }
}