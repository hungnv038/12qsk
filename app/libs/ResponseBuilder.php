<?php

class ResponseBuilder extends Response
{
    //send error message to client
    // Input: Error Message and Error code
    public static function error($exception)
    {
        // log
        Log::error($exception->getMessage()."\n ERROR_CODE: ".$exception->getCode()."\n INPUT: ".json_encode(InputHelper::getAllInput())."\n HEADER: ".json_encode(Request::header())."\n Trace: ".$exception->getTraceAsString());

        if ($exception instanceof APIException) {
            return Response::json(array('error'=>
                    array('message'=>$exception->getMessage(),'code'=> $exception->getCode())),
                                        $exception->getHttpCode());
        } else {
            return Response::json(array('error'=>
                array('message'=>"SERVER ERROR:".$exception->getMessage(),'code'=> 500)),500);
        }

    }
    public static function success($result=null)
    {
        QueryHelper::LogQueries();
        if($result!=null) {
            return Response::json($result);
        }
    }
}
