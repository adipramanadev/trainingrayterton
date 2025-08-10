<?php

namespace App\Http\Controllers;

use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;

class BelajarController extends Controller
{
    //fucntion index 
    public function index()
    {
        // return '<h1>Hello World Via Controller </h1>';
        return view('master');
    }

    //buat function baru 
    public function view()
    {
        //variable 
        $hello = 'Hello World';
        $nama = 'nama kalian';

        // $data= [
        //     'hello2'=> 'Hello World 2',
            // 'nama2' =>'nama kalian 2'
        // ];
        // return view('views',$data); //nanti ini kita buat file di folder views
        return view('views', compact('hello','nama'));
    }
}
