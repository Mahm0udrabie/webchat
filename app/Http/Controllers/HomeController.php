<?php

namespace App\Http\Controllers;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getData()
    {
        $response = Curl::to('https://jsonplaceholder.typicode.com/users/1')
                            ->get();
        dd($response);
    }
}
