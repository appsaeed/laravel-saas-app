<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
        /**
         * Run the database seeders.
         *
         * @return void
         */
        public function run()
        {

                Language::truncate();

                $get_language = [
                        [
                                'name'     => 'English',
                                'code'     => 'en',
                                'iso_code' => 'us',
                                'status'   => true,
                        ],
                        [
                                'name'     => 'French',
                                'code'     => 'fr',
                                'iso_code' => 'fr',
                                'status'   => true,
                        ],
                        [
                                'name'     => 'Spanish',
                                'code'     => 'es',
                                'iso_code' => 'es',
                                'status'   => true,
                        ],

                        [
                                'name'     => 'Portuguese',
                                'code'     => 'pt',
                                'iso_code' => 'br',
                                'status'   => true,
                        ],
                        [
                                'name'     => 'German',
                                'code'     => 'de',
                                'iso_code' => 'de',
                                'status'   => true,
                        ],
                ];

                foreach ($get_language as $lan) {
                        Language::create($lan);
                }
        }
}
