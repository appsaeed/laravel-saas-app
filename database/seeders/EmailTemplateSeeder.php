<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\EmailTemplates;

class EmailTemplateSeeder extends Seeder
{
        /**
         * Run the database seeders.
         *
         * @return void
         */
        public function run(): void
        {

                EmailTemplates::truncate();

                $templates = [
                        [
                                'name'    => 'Customer Registration',
                                'slug'    => 'customer_registration',
                                'subject' => 'Welcome to {app_name}',
                                'content' => 'Hi {first_name} {last_name},
                                      Welcome to {app_name}! This message is an automated reply to your User Access request. Login to your User panel by using the details below:
                                      {login_url}
                                      Email: {email_address}
                                      Password: {password}',
                                'status'  => true,
                        ],
                        [
                                'name'    => 'Customer Registration Verification',
                                'slug'    => 'registration_verification',
                                'subject' => 'Registration Verification From {app_name}',
                                'content' => 'Hi {first_name} {last_name},
                                      Welcome to {app_name}! This message is an automated reply to your account verification request. Click the following url to verify your account:
                                      {verification_url}',
                                'status'  => true,
                        ],
                        [
                                'name'    => 'Password Reset',
                                'slug'    => 'password_reset',
                                'subject' => '{app_name} New Password',
                                'content' => 'Hi {first_name} {last_name},
                                      Password Reset Successfully! This message is an automated reply to your password reset request. Login to your account to set up your all details by using the details below:
                                      {login_url}
                                      Email: {email_address}
                                      Password: {password}',
                                'status'  => true,
                        ],
                        [
                                'name'    => 'Forgot Password',
                                'slug'    => 'forgot_password',
                                'subject' => '{app_name} password change request',
                                'content' => 'Hi {first_name} {last_name},
                                      Password Reset Successfully! This message is an automated reply to your password reset request. Click this link to reset your password:
                                      {forgot_password_link}
                                      Notes: Until your password has been changed, your current password will remain valid. The Forgot Password Link will be available for a limited time only.',
                                'status'  => true,
                        ],
                        [
                                'name'    => 'Login Notification',
                                'slug'    => 'login_notification',
                                'subject' => 'Your {app_name} Login Information',
                                'content' => 'Hi,
                                      You successfully logged in to {app_name} on {time} from ip {ip_address}.  If you did not login, please contact our support immediately.',
                                'status'  => true,
                        ],
                        [
                                'name'    => 'Customer Registration Notification',
                                'slug'    => 'registration_notification',
                                'subject' => 'New customer registered to {app_name}',
                                'content' => 'Hi,
                                      New customer named {first_name} {last_name} registered. Login to your portal to show details.
                                      {customer_profile_url}',
                                'status'  => true,
                        ],



                ];

                foreach ($templates as $tp) {
                        EmailTemplates::create($tp);
                }
        }
}
