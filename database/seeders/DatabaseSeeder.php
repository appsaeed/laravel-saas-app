<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call( UserSeeder::class );
        $this->call( AppConfigSeeder::class );
        $this->call( Countries::class );
        $this->call( LanguageSeeder::class );
        $this->call( CurrenciesSeeder::class );
        $this->call( EmailTemplateSeeder::class );
        $this->call( PaymentMethodsSeeder::class );
    }
}
