<?php

namespace App\Http\Controllers;

use App\Models\TestData;
use Illuminate\Routing\Controller;

class TestDataController extends Controller
{
    public function index(){
        $testData = TestData::all();
        foreach ($testData as $data) {
            echo "ID: " . $data->id . ", testname: " . $data->testname . "<br>";
        }
    }
}
