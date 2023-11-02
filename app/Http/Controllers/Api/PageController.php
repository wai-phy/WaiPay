<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //testing 
    public function testing(){
        return response()->json(
            [
                'result' => 1,
                'message' => 'successfully',
                'data' => 'Testing',
            ]
            );
    }
}
