<?php

namespace console\controllers;

use console\models\LogService;
use console\models\TaskService;
use yii\console\ExitCode;
use yii\di\Container;

class DataPullingController extends \yii\console\Controller
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

    public function actionRun(): int
    {
        try {
            $this->taskService->dataPulling();
        } catch (\Exception $exception) {
            $this->logService->sendError($exception);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}