<?php

namespace App\Http\Controllers\Api\Kades;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Token;
use Exception;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    public function index()
    {
        try {
            $clients = Client::get();

            return response()->json(
                successResponse(200, "Data Semua Client", $clients)
            );
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                ["phone" => "required", "name" => "required"],
                ["phone.required" => "Field telepon harus diisi", "name.required" => "Field nama harus diisi"]
            );

            $client = Client::where("phone", $request->phone)->first();

            if($client) {
                return response()->json(
                    failResponse(409, "Data client sudah ada"), 409
                );
            }

            $dataClient = Client::create($request->all());

            return response()->json(
                successResponse(201, "Data client berhasil ditambahkan", $dataClient)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate(
                ["phone" => "required", "name" => "required"],
                ["phone.required" => "Field telepon harus diisi", "name.required" => "Field nama harus diisi"]
            );

            $client = Client::where("phone", $request->phone);

            if(!$client->first()) {
                return response()->json(
                    failResponse(404, "Data client tidak ditemukan"), 404
                );
            }

            $dataClient = $client->update($request->all());

            return response()->json(
                successResponse(200, "Data client berhasil diperbarui", $dataClient)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function destroy($phone)
    {
        try {
            $client = Client::where("phone", $phone);

            if(!$client->first()) {
                return response()->json(
                    failResponse(404, "Data client tidak ditemukan"), 404
                );
            }

            $client->delete();

            return response()->json(
                successResponse(200, "Data client berhasil dihapus")
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
            $client = Client::where("phone", $phone);

            if(!$client->first()) {
                return response()->json(
                    failResponse(404, "Data kontak tidak ditemukan"), 404
                );
            }

            return response()->json(
                successResponse(200, "Data client berhasil ditemukan", $client->first())
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }

    public function token(Request $request)
    {
        try {
            $request->validate(
                ["token" => "required", "expiry_date" => "required"],
                ["token.required" => "Field token harus diisi", "expiry_date.required" => "Field expiry date harus diisi"]
            );

            $dataContact = Token::create($request->all());

            return response()->json(
                successResponse(201, "Token berhasil didapatkan", $dataContact)
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }
}
