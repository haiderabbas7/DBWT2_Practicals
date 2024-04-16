<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevelopmentData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedTable('ab_articlecategory', '../abalo/resources/misc/articlecategory.csv');
        $this->seedTable('ab_article', '../abalo/resources/misc/articles.csv');
        $this->seedTable('ab_user', '../abalo/resources/misc/user.csv');
    }

    /**
     * Opens the csv file and extracts the data, then puts them into the corresponding table
     *
     * @param string $tableName The table to be inserted into
     * @param string $fileName The file to open
     * @return void
     */
    private function seedTable(string $tableName, string $fileName) : void
    {
        if(($handle = fopen($fileName, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ';'); // column names
            while($data = fgetcsv($handle, 1000 , ';')) {
                $data = array_map(function($value) { return $value === 'NULL' ? null : $value; }, $data); // Replace 'NULL' with null
                $data = array_map('utf8_encode', $data); // Encode data to UTF-8
                $row = array_combine($header, $data);
                DB::table($tableName)->insert($row);
            }
        }
        fclose($handle);
    }
}
