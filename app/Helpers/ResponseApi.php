<?php

function successResponse($status, $message, $data = null, $token = null): array
{
    if( $data === null ) {
        return [
            "status" => $status,
            "message" => $message,
        ];
    }

    if ($token !== null) {
        return [
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "token" => $token
        ];
    }

    return [
        "status" => $status,
        "message" => $message,
        "data" => $data
    ];
}

function errorResponse($status, $message, $error): array
{
    return array(
        "status" => $status,
        "message" => $message,
        "error" => $error
    );
}

function failResponse($status, $message): array
{
    return array(
        "status" => $status,
        "message" => $message
    );
}
