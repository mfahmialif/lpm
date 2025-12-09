<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProdiSeeder::class);
        // $this->call(AccreditationRequirementEvidenceDemoSeeder::class);

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('dalwa123'),
                'role'     => 'admin',
            ],
        ]);
        DB::table('categories')->insert([
            [
                'name'        => 'game',
                'description' => null,
            ],
            [
                'name'        => 'news',
                'description' => null,
            ],
            [
                'name'        => 'event',
                'description' => null,
            ],
        ]);
        DB::table('news')->insert([
            [
                'slug'         => 'berita-1',
                'title'        => 'Berita 1',
                'body'         => 'Body Berita 1',
                'author_id'    => 1,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'slug'         => 'berita-2',
                'title'        => 'Berita 2',
                'body'         => 'Body Berita 2',
                'author_id'    => 1,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('news_categories')->insert([
            [
                'news_id'     => 1,
                'category_id' => 1,
            ],
            [
                'news_id'     => 1,
                'category_id' => 3,
            ],
            [
                'news_id'     => 1,
                'category_id' => 2,
            ],
        ]);

        DB::table('users')->insert([
            [
                'username'   => 'staff',
                'name'       => 'staff',
                'email'      => 'staff@example.com',
                'password'   => Hash::make('dalwa123'),
                'role'       => 'staff',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'user',
                'name'       => 'user',
                'email'      => 'user@example.com',
                'password'   => Hash::make('dalwa123'),
                'role'       => 'user',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('tags')->insert([
            [
                'name'        => 'tag1',
                'description' => null,
            ],
            [
                'name'        => 'tag2',
                'description' => null,
            ],
            [
                'name'        => 'tag3',
                'description' => null,
            ],
        ]);
        DB::table('tags')->insert([
            [
                'name' => 'S1-Sejarah Peradaban Islam',
            ],
            [
                'name' => 'S2-Manajemen Pendidikan Islam (Magister)',
            ],
            [
                'name' => 'S3-Pendidikan Agama Islam (Doktoral)',
            ],
            [
                'name' => 'S1-Ekonomi Syariah',
            ],
            [
                'name' => 'S1-Bimbingan dan Konseling Islam',
            ],
            [
                'name' => 'S1-Komunikasi dan Penyiaran Islam',
            ],
            [
                'name' => 'S1-Hukum Keluarga Islam',
            ],
            [
                'name' => 'S1-Pendidikan Agama Islam',
            ],
            [
                'name' => 'S1-Manajemen Pendidikan Islam',
            ],
            [
                'name' => 'S2-Pendidikan Bahasa Arab (Magister)',
            ],
            [
                'name' => 'S1-Pendidikan Bahasa Arab',
            ],
            [
                'name' => 'S2-Pendidikan Agama Islam (Magister)',
            ],
            [
                'name' => 'S1-Hukum Keluarga Islam (Double Degree)',
            ]
        ]);
        DB::table('units')->insert([
            [
                'name'        => 'lpm',
                'description' => null,
            ],
            [
                'name'        => 'lpkm',
                'description' => null,
            ],
            [
                'name'        => 'pkm',
                'description' => null,
            ],
        ]);
        DB::table('activities')->insert([
            [
                'code'         => \Crypt::encryptString('1'),
                'slug'         => 'activity-1',
                'title'        => 'Activity 1',
                'body'         => 'Body Activity 1',
                'author_id'    => 1,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'code'         => \Crypt::encryptString('2'),
                'slug'         => 'activity-2',
                'title'        => 'Activity 2',
                'body'         => 'Body Activity 2',
                'author_id'    => 1,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('activity_unit')->insert([
            [
                'activity_id' => 1,
                'unit_id'  => 1,
            ],
            [
                'activity_id' => 1,
                'unit_id'  => 3,
            ],
            [
                'activity_id' => 1,
                'unit_id'  => 2,
            ],
        ]);
        DB::table('activity_tag')->insert([
            [
                'activity_id' => 1,
                'tag_id'   => 1,
            ],
            [
                'activity_id' => 1,
                'tag_id'   => 3,
            ],
            [
                'activity_id' => 1,
                'tag_id'   => 2,
            ],
        ]);
    }
}
