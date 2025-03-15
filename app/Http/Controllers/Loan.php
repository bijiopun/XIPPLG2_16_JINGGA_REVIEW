<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    // Menampilkan semua peminjaman
    public function index()
    {
        return response()->json(Loan::with(['book', 'user'])->get(), 200);
    }

    // Menampilkan peminjaman berdasarkan ID
    public function show($id)
    {
        $loan = Loan::with(['book', 'user'])->find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }
        return response()->json($loan, 200);
    }

    // Membuat peminjaman baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date',
            'status' => 'required|in:borrowed,returned',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $loan = Loan::create($request->all());

        return response()->json(['message' => 'Loan created successfully', 'loan' => $loan], 201);
    }

    // Mengupdate peminjaman
    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'return_date' => 'nullable|date',
            'status' => 'in:borrowed,returned',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $loan->update($request->all());

        return response()->json(['message' => 'Loan updated successfully', 'loan' => $loan], 200);
    }

    // Menghapus peminjaman
    public function destroy($id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loan->delete();
        return response()->json(['message' => 'Loan deleted successfully'], 200);
    }
}
