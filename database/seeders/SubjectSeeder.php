<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('types')->insert(
            [
                ['name'=>'Sciences'],
                ['name'=>'Social Sciences']
            ]
        );

        $subject=[
            [
                'subject_id' => 1,
                'name'    => 'គណិតវិទ្យា',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'រូបវិទ្យា',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'ភាសាខ្មែរ',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'ប្រវត្តិវិទ្យា',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'ជីវៈវិទ្យា',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'គីមីវិទ្យា',
                'icon'    => '',
            ],
            [
                'subject_id' => 1,
                'name'    => 'ពលរដ្ធវិទ្យា',
                'icon'    => '',
            ],
        ];
        DB::table('subject')->insert($subject);
        

    }
}
