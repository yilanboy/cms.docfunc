<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $links = [
            [
                'title' => 'Laravel China 社區',
                'link' => 'https://learnku.com/laravel',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Laravel 6.0 初體驗！',
                'link' => 'https://ithelp.ithome.com.tw/users/20120550/ironman/2575',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Laracasts',
                'link' => 'https://laracasts.com/',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Laravel Business',
                'link' => 'https://www.youtube.com/channel/UCTuplgOBi6tJIlesIboymGA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Andre Madarang',
                'link' => 'https://www.youtube.com/channel/UCtb40EQj2inp8zuaQlLx3iQ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('links')->insert($links);
    }

    public function down(): void
    {
        DB::table('links')->truncate();
    }
};
