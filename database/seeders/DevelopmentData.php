<?php

namespace Database\Seeders;


use App\Helpers\UserHelper;
use App\Models\Ab_Article;
use App\Models\Ab_User;
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
        $this->seed_users();
        $this->seed_articles();
        $this->seed_articlecategories();
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

    private function seed_users()
    {
        $arr = $this->read_csv_into_array('user.csv');
        for($i = 1; $i < sizeof($arr); $i++){
            DB::table('ab_user')->insert([
                'id' => $arr[$i][0],
                'ab_name' => $arr[$i][1],
                'ab_password' => sha1(UserHelper::get_salt() . $arr[$i][2]),
                'ab_mail' => $arr[$i][3]
            ]);
        }
    }

    private function seed_articles()
    {
        $arr = $this->read_csv_into_array('article.csv');

        for($i = 1; $i < sizeof($arr); $i++){
            /*
            $ab_article = new Ab_Article();
            $ab_article->primaryKey = $arr[$i][0];
            $ab_article->ab_name = $arr[$i][1];
            $ab_article->ab_price = $arr[$i][2];
            $ab_article->ab_description = $arr[$i][3];
            $ab_article->ab_creator_id = $arr[$i][4];
            $ab_article->ab_createdate = $arr[$i][5];
            $ab_article->save();*/
            $unformattedTimestamp = Carbon::createFromFormat('d.m.y H:i', $arr[$i][5]);
            $formattedTimestamp = $unformattedTimestamp->format('Y-m-d H:i:s');

            DB::table('ab_article')->insert([
                'id' => $arr[$i][0],
                'ab_name' => $arr[$i][1],
                'ab_price' => intval($arr[$i][2]),
                'ab_description' => $arr[$i][3],
                'ab_creator_id' => $arr[$i][4],
                'ab_createdate' => $formattedTimestamp,
            ]);
        }
    }

    private function seed_articlecategories()
    {
        $arr = $this->read_csv_into_array('articlecategory.csv');
        for($i = 1; $i < sizeof($arr); $i++){
            DB::table('ab_articlecategory')->insert([
                'id' => $arr[$i][0],
                'ab_name' => $arr[$i][1],
                'ab_parent' => ($arr[$i][2] === "NULL") ? null : $arr[$i][2]
            ]);
        }
    }

}
