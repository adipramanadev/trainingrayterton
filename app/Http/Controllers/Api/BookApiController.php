<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
class BookApiController extends Controller
{
    //function indexnya 
    public function index()
    {
        $buku = Book::all();

        return response()->json([
            'msg'=>true, 
            'data'=>$buku
        ]);
    }
}
