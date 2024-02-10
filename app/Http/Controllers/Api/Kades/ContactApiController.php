<?php

namespace App\Http\Controllers\Api\Kades;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::get();

            return response()->json(
                successResponse(200, "Data Semua Kontak", $contacts)
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

            $contact = Contact::where("phone", $request->phone)->first();

            if($contact) {
                return response()->json(
                    failResponse(409, "Data kontak sudah ada"), 409
                );
            }

            $dataContact = Contact::create($request->all());

            return response()->json(
                successResponse(201, "Data kontak berhasil ditambahkan", $dataContact)
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

            $contact = Contact::where("phone", $request->phone);

            if(!$contact->first()) {
                return response()->json(
                    failResponse(404, "Data kontak tidak ditemukan"), 404
                );
            }

            $dataContact = $contact->update($request->all());

            return response()->json(
                successResponse(200, "Data kontak berhasil diperbarui", $dataContact)
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
            $contact = Contact::where("phone", $phone);

            if(!$contact->first()) {
                return response()->json(
                    failResponse(404, "Data kontak tidak ditemukan"), 404
                );
            }

            $contact->delete();

            return response()->json(
                successResponse(200, "Data kontak berhasil dihapus")
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
            $contact = Contact::where("phone", $phone);

            if(!$contact->first()) {
                return response()->json(
                    failResponse(404, "Data kontak tidak ditemukan"), 404
                );
            }

            return response()->json(
                successResponse(200, "Data kontak berhasil ditemukan", $contact->first())
            );
        }catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal Server Error", $error->getMessage()), 500
            );
        }
    }
}
