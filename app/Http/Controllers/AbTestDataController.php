<?php

namespace App\Http\Controllers;

use App\Models\AbTestData;

class AbTestDataController 
{
    public function index(){
        $testData = AbTestData::all();
        foreach ($testData as $data) {
            echo "ID: " . $data->id . ", ab_testname: " . $data->ab_testname . "<br>";
        }
    }
}
