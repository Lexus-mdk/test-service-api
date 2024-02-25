<?php

namespace console\controllers;

use console\models\LogService;
use console\models\TaskService;
use yii\console\ExitCode;
use yii\di\Container;

class TaskController extends \yii\console\Controller
{
    public TaskService $taskService;
    public LogService $logService;

    public function __construct($id, $module, $config = [])
    {
        $container = new Container();
        $this->taskService = $container->get(TaskService::class);
        $this->logService = $container->get(LogService::class);
        parent::__construct($id, $module, $config);
    }

    public function actionCreate(): int
    {
        if (!$this->taskService->isQueueClear()) {
            return ExitCode::TEMPFAIL;
        }

        try {
            $this->taskService->createTasks();
        } catch (\Exception $exception) {
            $this->logService->sendError($exception);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}