<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController
{
    public function test(){
        /*$csvFile = file('..\..\dbwt2\resources\misc\article.csv');
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }*/


        /*while(($handle = fopen('..\..\dbwt2\resources\misc\article.csv', 'r')) != false) {
            $header = fgetcsv($handle, 1000, ';'); // column names

            $articles[] = $header;
            while($data = fgetcsv($handle, 1000 , ';')) {
                $data = array_map(function($value) { return $value === 'NULL' ? null : $value; }, $data); // Replace 'NULL' with null
                $data = array_map('utf8_encode', $data); // Encode data to UTF-8
                $row = array_combine($header, $data);
            }
        }*/


        $entries = [];
        $handle = fopen(__DIR__ . '/../../../resources/misc/' . 'article.csv', 'r');
        for($i = 0; $i < 20; $i++) {
            $header = fgetcsv($handle, 1000, ';');
            $entries[] = $header;
        }
        fclose($handle);
        return response()->json(User::factory()->count(10)->make());
    }
}
