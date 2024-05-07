<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder {
    /**
     * Run the database seeders.
     */
    public function run() {

        // Default password
        $defaultPassword = app()->environment( 'production' ) ? Str::random() : '12345678';
        $this->command->getOutput()->writeln( "<info>Default password:</info> $defaultPassword" );

        // Create super admin user
        User::where( 'email', 'appsaeed7@gmail.com' )->delete();
        Role::where( 'name', 'administrator' )->delete();

        $user = new User();
        $role = new Role();
        $customer = new Customer();

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // $user->truncate();
        // $role->truncate();
        // $customer->truncate();
        // DB::table('role_user')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            'email' => 'appsaeed7@gmail.com',
            'password' => bcrypt( $defaultPassword ),
            'status' => true,
            'is_admin' => true,
            'locale' => app()->getLocale(),
            'timezone' => config( 'app.timezone' ),
            'email_verified_at' => now(),
        ] );

        $superAdmin->api_token = $superAdmin->createToken( 'appsaeed7@gmail.com' )->plainTextToken;
        $superAdmin->save();

        $superAdmin->roles()->save( $superAdminRole );

    }
}
