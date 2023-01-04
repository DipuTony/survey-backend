<?php

if (!function_exists('responseMsg')) {
    function responseMsg($status, $message, $data)
    {
        $message = ["status" => $status, "message" => $message, "data" => $data];
        return $message;
    }
}
