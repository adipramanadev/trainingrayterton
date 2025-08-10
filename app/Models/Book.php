<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //deklarasikan fillabelnya 
    protected $guarded =[]; //lebih simpel 
    // protected $fillable =['nama','pengarang','penerbit'];
}
