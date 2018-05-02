<?php

namespace App\Http\Controllers;

use App\Helper;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const SUCCESS_MSG = 'Your operation was successful.';
    const ERROR_MSG = 'Oops! An unexpected error occurred. Please, try again later.';

    protected $resourceName;

    protected function setResourceName(string $resourceName){
        $this->resourceName = ucfirst($resourceName);
    }

    protected function invalid($validator){
        $message = implode(', ', $validator->errors()->all());

        return $this->failure($message);
    }

    protected function notFound(){
        $message = 'This ' .($this->resourceName ? strtolower($this->resourceName) : 'resource').' was not found on the server';

        return $this->failure($message);
    }

    protected function exception(Exception $bug){
        $message = "Oops. An unexpected error occured ({$bug->getMessage()})";

        return $this->failure($message);
    }

    protected function paginated($data, string $message = null){
//        dd($data);
        $body = [];
        $body['has_next'] = $data->currentPage() < $data->lastPage();
        $body['has_prev'] = $data->currentPage() > 1;
        $body['page'] = $data->currentPage();
        $body['data'] = $data->items();
        $body['status'] = true;
        $body['message'] = $message == null ? self::SUCCESS_MSG : $message;

        return response()->json($body);
    }

    protected function success(string $message = null, $data = null){
        if(is_null($message)){
            $message = self::SUCCESS_MSG;
        }
        return $this->responseJson($data, $message, true);
    }

    protected function failure(string $message = null, $data = null){
        if(is_null($message)){
            $message = self::ERROR_MSG;
        }
        return $this->responseJson($data, $message, false);
    }

    private function responseJson($data, string $message, bool $status){
        return Helper::responseJson($data, $message, $status);
    }
}
