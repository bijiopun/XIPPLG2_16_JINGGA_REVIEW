<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // Menampilkan semua buku
    public function index()
    {
        return response()->json(Book::with(['user', 'category'])->get(), 200);
    }

    // Menampilkan buku berdasarkan ID
    public function show($id)
    {
        $book = Book::with(['user', 'category'])->find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book, 200);
    }

    // Menambahkan buku baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'writer' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string',
            'year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($request->all());

        return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
    }

    // Mengupdate buku
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'writer' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'category_id' => 'sometimes|exists:categories,id',
            'publisher' => 'sometimes|string',
            'year' => 'sometimes|digits:4|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book->update($request->all());

        return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
    }

    // Menghapus buku
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();
        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
