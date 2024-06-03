<?php

namespace Database\Seeders;


use App\Helpers\UserHelper;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
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
        //$this->seed_benutzer();
        $this->seed_articles();
        $this->seed_articlecategories();
        //WENN ES PROBLEME MIT DEN SEQUENZEN GIBT, SCHREIB MIR EINE METHODE ZUM MANUELLEN UPDATEN DER SEQUENZEN
        //$this->updateAllIDSequences();
    }

    private function read_csv_into_array(string $fileName) : array
    {
        $entries = [];
        $handle = fopen(__DIR__ . '/../../resources/misc/' . $fileName, 'r');
        if ($handle !== false) {
            while (!feof($handle)) {
                $line = fgetcsv($handle, 1000, ';');

                if ($line !== false) {
                    $entries[] = $line;
                }
            }
        }
        fclose($handle);
        return $entries;
    }

    private function seed_benutzer()
    {
        $arr = $this->read_csv_into_array('user.csv');
        for($i = 1; $i < sizeof($arr); $i++){
            DB::table('benutzer')->insert([
                //MUSS ENTFERNT WERDEN, DAMIT DIE SEQUENZEN BENUTZT WERDEN
                //'id' => $arr[$i][0],
                'name' => $arr[$i][1],
                'password' => sha1(UserHelper::get_salt() . $arr[$i][2]),
                'mail' => $arr[$i][3]
            ]);
        }
    }

    private function seed_articles()
    {
        $arr = $this->read_csv_into_array('article.csv');

        for($i = 1; $i < sizeof($arr); $i++){
            $unformattedTimestamp = Carbon::createFromFormat('d.m.y H:i', $arr[$i][5]);
            $formattedTimestamp = $unformattedTimestamp->format('Y-m-d H:i:s');

            DB::table('article')->insert([
                //MUSS ENTFERNT WERDEN, DAMIT DIE SEQUENZEN BENUTZT WERDEN
                //'id' => $arr[$i][0],
                'name' => $arr[$i][1],
                'price' => intval($arr[$i][2]),
                'description' => $arr[$i][3],
                'creator_id' => $arr[$i][4],
                'createdate' => $formattedTimestamp,
            ]);
        }
    }

    private function seed_articlecategories()
    {
        $arr = $this->read_csv_into_array('articlecategory.csv');
        for($i = 1; $i < sizeof($arr); $i++){
            DB::table('articlecategory')->insert([
                //MUSS ENTFERNT WERDEN, DAMIT DIE SEQUENZEN BENUTZT WERDEN
                //'id' => $arr[$i][0],
                'name' => $arr[$i][1],
                'parent' => ($arr[$i][2] === "NULL") ? null : $arr[$i][2]
            ]);
        }
    }

}
