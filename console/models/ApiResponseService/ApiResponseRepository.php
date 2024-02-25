<?php

namespace console\models\ApiResponseService;

class ApiResponseRepository
{
    public function saveResponse(string $responseBody, int $requestId)
    {
        if (strlen($responseBody) > 200) { // Допустим, 200 байтов - предел для запроса, тогда ...
            $responses = str_split($responseBody, 200);
            foreach ($responses as $key => $responsePart) {
                $response = new ApiResponseRecord();
                $response->request_id = $requestId;
                $response->response = $responsePart;
                if ($key > 0 && isset($parentResponseId)) {
                    $response->parent_response_id = $parentResponseId;
                }
                $response->save();
                $parentResponseId = $response->id;
            }
        } else {
            $response = new ApiResponseRecord();
            $response->request_id = $requestId;
            $response->response = $responseBody;
            $response->save();
        }
    }
}