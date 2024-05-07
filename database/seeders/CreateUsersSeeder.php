<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CreateUsersSeeder extends Seeder {
    /**
     * Run the database seeders.
     */
    public function run() {

        // $this->createAdmins();
        $this->createUsers();
    }

    public function createAdmins() {
        echo "Super Admin email: \n";
        $email = fgets( fopen( "php://stdin", "r" ) );
        $this->createSuperAdmin( trim( $email ) );
    }

    public function createUsers() {
        echo "How many user would you like to create?\n";
        $line = fgets( fopen( "php://stdin", "r" ) );
        $length = intval( $line );

        $faker = \Faker\Factory::create();

        for ( $i = 0; $i < $length; $i++ ) {

            $this->createUser( $faker->email(), $faker->name() );
        }
    }

    public function createUser( string $email, string $name ) {
        $is_admin = false;
        // Default password
        $password = app()->environment( 'production' ) ? Str::random() : '123456';

        $user = new User();
        $customer = new Customer();

        $createUser = $user->create( [
            'first_name' => $name,
            'last_name' => '',
            'email' => $email,
            'password' => bcrypt( $password ),
            'timezone' => config( 'app.timezone' ),
            'locale' => config( 'app.locale' ),
            'active_portal' => $is_admin ? 'admin' : 'customer',
            'is_customer' => !$is_admin,
            'is_admin' => $is_admin,
            'email_verified_at' => Carbon::now(),
        ] );

        if ( !$createUser->save() ) {
            return false;
        }

        $createCustomer = $customer->create( [
            'user_id' => $createUser->id,
            'phone' => '012345678890',
            'notifications' => json_encode( [
                'login' => 'no',
                'tickets' => 'yes',
                'sender_id' => 'yes',
                'keyword' => 'yes',
                'subscription' => 'yes',
                'promotion' => 'yes',
                'profile' => 'yes',
            ] ),
        ] );

        if ( !$createCustomer->save() ) {
            return false;
        }

        $permissions = json_decode( $createUser->customer->permissions, true );
        $createUser->api_token = $createUser->createToken( $email, $permissions )->plainTextToken;
        $createUser->save();

        $this->command->getOutput()->writeln( "<info>User name: </info> $name" );
        $this->command->getOutput()->writeln( "<info>User email: </info> $email" );
        $this->command->getOutput()->writeln( "<info>User password: </info> $password" );
        $this->command->getOutput()->writeln( "" );
    }

    /**
     * create super admin
     */
    public function createSuperAdmin( string $email ) {
        // Default password
        $password = app()->environment( 'production' ) ? Str::random() : $email;

        // Create super admin user
        $user = new User();
        $role = new Role();
        $customer = new Customer();

        // DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
        // $user->truncate();
        // $role->truncate();
        // $customer->truncate();
        // DB::table( 'role_user' )->truncate();
        // DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );

        /*
         * Create roles
         */

        $superAdminRole = $role->create( [
            'name' => 'administrator',
            'status' => true,
        ] );

        foreach ( [
            'access backend',
            'view customer',
            'create customer',
            'edit customer',
            'delete customer',
            'view subscription',
            'new subscription',
            'manage subscription',
            'delete subscription',
            'manage plans',
            'create plans',
            'edit plans',
            'delete plans',
            'manage currencies',
            'create currencies',
            'edit currencies',
            'delete currencies',
            'view sending_servers',
            'create sending_servers',
            'edit sending_servers',
            'delete sending_servers',
            'view keywords',
            'create keywords',
            'edit keywords',
            'delete keywords',
            'view sender_id',
            'create sender_id',
            'edit sender_id',
            'delete sender_id',
            'view blacklist',
            'create blacklist',
            'edit blacklist',
            'delete blacklist',
            'view spam_word',
            'create spam_word',
            'edit spam_word',
            'delete spam_word',
            'view administrator',
            'create administrator',
            'edit administrator',
            'delete administrator',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'general settings',
            'system_email settings',
            'authentication settings',
            'notifications settings',
            'localization settings',
            'pusher settings',
            'view languages',
            'new languages',
            'manage languages',
            'delete languages',
            'view payment_gateways',
            'update payment_gateways',
            'view email_templates',
            'update email_templates',
            'view background_jobs',
            'view purchase_code',
            'manage update_application',
            'manage maintenance_mode',
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view sms_history',
            'view block_message',
            'manage coverage_rates',
        ] as $name ) {
            $superAdminRole->permissions()->create( ['name' => $name] );
        }

        $superAdmin = $user->create( [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'image' => null,
            'email' => $email,
            'password' => bcrypt( $password ),
            'status' => true,
            'is_admin' => true,
            'locale' => app()->getLocale(),
            'timezone' => config( 'app.timezone' ),
            'email_verified_at' => now(),
        ] );

        $superAdmin->api_token = $superAdmin->createToken( $email )->plainTextToken;
        $superAdmin->save();

        $superAdmin->roles()->save( $superAdminRole );

        $this->command->getOutput()->writeln( "<info>Admin email: </info> $email" );
        $this->command->getOutput()->writeln( "<info>password: </info> $password \n" );
    }
}
