<?php

namespace console\models\TaskService;

use console\models\ApiRequestService;

class TaskService
{
    private TaskRepository $taskRepository;
    private ApiRequestService $apiRequestService;

    public function __construct(
        TaskRepository $taskRepository,
        ApiRequestService $apiRequestService
    )
    {
        $this->taskRepository = $taskRepository;
        $this->apiRequestService = $apiRequestService;
    }

    public function isQueueClear(): bool
    {
        return $this->taskRepository->isQueueClear();
    }

    public function createTasks(): void
    {
        foreach (TaskRecord::TYPES as $type) {
            $this->taskRepository->create($type);
        }
    }

    public function dataPulling()
    {
        // В первую очередь, займёмся проверкой задач в процессе.
        $tasksInProcess = $this->taskRepository->getTasksByStatus(TaskRecord::IN_PROCESS);
        foreach ($tasksInProcess as $task) {

            $task = $this->apiRequestService->updateTaskInProcess($task);
            // Если статус таска не изменился, значит апи вернул ошибку, что данные не готовы. Дальше идти смысла нет - заканчиваем процесс.
            if ($task->status == TaskRecord::IN_PROCESS) {
                return;
            }
            $this->taskRepository->updateTaskModel($task);
        }

        // Затем проверяем задачи, которые в очереди.
        $tasksInQueue = $this->taskRepository->getTasksByStatus(TaskRecord::IN_QUEUE);
        foreach ($tasksInQueue as $task) {
            // Если они есть, то отправляем их в работу. Точнее оправляем одну задачу и заканчиваем цикл.
            $task = $this->apiRequestService->pushTaskToProcess($task);
            $this->taskRepository->updateTaskModel($task);
            break;
        }
        // Если их нет, то аривидерчи.
    }
}