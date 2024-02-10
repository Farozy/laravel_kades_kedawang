<?php

namespace App\Http\Controllers\Api\Kades;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Token;
use Exception;
use Illuminate\Http\Request;

class TokenApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(
                    errorResponse(401, "Token tidak ditemukan", 'Unauthorized'), 401
                );
            }

            $dataContact = Token::where('token', $token)->first();

            if(!$dataContact) {
                return response()->json(
                    failResponse(404, "Token tidak dikenali"), 404
                );
            }

            return response()->json(
                successResponse(201, "Data token berhasil didapatkan", $dataContact)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                ["token" => "required", "expiry_date" => "required"],
                ["token.required" => "Field token harus diisi", "expiry_date.required" => "Field expiry date harus diisi"]
            );

            $datToken = Token::create($request->all());

            return response()->json(
                successResponse(201, "Token berhasil disimpan", $datToken)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function show($phone)
    {
        try {
            $lastContact = Contact::where('phone', $phone)
                ->with("token")
                ->orderBy('id', 'desc')
                ->first();

            $lastClient = Client::where('phone', $phone)
                ->with("token")
                ->orderBy('id', 'desc')
                ->first();

            return response()->json(
                successResponse(200, "Data token dengan kontak", $lastContact != null ? $lastContact : $lastClient)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }
}
