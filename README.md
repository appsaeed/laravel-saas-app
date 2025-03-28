## This public repository provides details, with a private repo available.

# A Laravel-based Task Management Application for Businesses
SaaS Tasks is a web-application built with the Laravel PHP framework that helps businesses manage tasks and projects efficiently. Offered as a Software-as-a-Service (SaaS) solution. Features admin and user dashboards for application control
## Key Features

<!-- START -->
  - **Real-Time Chat System**:  The integrated chat system fosters real-time communication among team members. Discuss tasks, share ideas, and collaborate effectively within the platform.

  - **Task Creation and Management**: Create, assign, and track tasks with due dates, priorities, and detailed descriptions.

  - **Project Management**: Organize tasks into projects for better visibility and collaboration.

  - **Automated Deadline Reminders**: Never miss a deadline again. Leverage built-in cron jobs to trigger automated notifications before deadlines, keeping everyone on track.

  - **Notification System**: Stay informed with customizable notifications. Receive alerts about task assignments, deadlines, and project updates to ensure everyone's in the loop.

  - **Admin Control panel**:  Enjoy comprehensive admin privileges. Manage user accounts, control access levels, and configure application settings to suit your team's needs.

  - **Language Translation**:  Cater to a global audience.  SaaS Tasks offers support for multiple languages, making it accessible to a broader user base.

  - **Team Collaboration**: Assign tasks to team members, track progress, and communicate within the platform.

  - **User Management**: Create user accounts with different permission levels for access control.

  - **Reporting and Analytics**: Gain insights into team productivity and project progress through reports and visualizations.

  - **Security**: Built-in security features help protect your data from unauthorized access. Secure user authentication and data encryption to ensure information confidentiality.

  - **Scalability**: Laravel's modular architecture allows the application to grow seamlessly as your business needs evolve.

  - **Customization**: The Applicaion offers flexibility to customize the application to your specific needs.

  - **Developer-friendly**: Laravel's clean syntax and extensive functionalities make development and maintenance efficient.

  - **Ideal for**: Freelancers and small businesses | Marketing agencies | Project management teams | Any organization looking to improve task management and team collaboration
<!--FINISHED-->
Dashboard
<div style="display:flex;  gap: 10px;">
    <div>
        admin
        <img src="https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/admin.png" alt="">
    </div>
    <div>
        customer dashboard
        <img src="https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/dashboard.png" alt="">
    </div>
    <div>
        customer dashboard
        <img src="https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/customer-dashboard-home.png" alt="">
    </div>
</div>

# Instructions
1. Make sure you have [php](https://www.php.net/) 8.0.2 or higher and [composer](https://getcomposer.org)

2. After initialization the project files rename .env.example to .env

3. Install php dependencies

```sh
composer install
```

4. Start local development server from root directory.

```sh
php artisan serve
```

5. open url to start installation your_application.com/install and complete installation process
![Install starting](https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/install-start.png)
![Create admin](https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/install-admin.png)

6. check files and folders permissions is correct by following installation
7. when accurate assets issues install node modules by using the following command
````sh
npm install
# build assets
npm run build
````
# Admin dashboard

1. user can update profile
2. can chat task base messages
3. admin is able control user permissions for
4. admin can setup mail settings, database settings, language settings
5. admin can manage all tasks and user crud operations
6. Add language
7. add users
8. And much more

#### menus

- dashboard
- Users
- administrators
  - administrators
  - roles
- Settings
  - All settings
  - Countries
  - Languages
  - Email Templates
  - update application
- Theme customizer
- Todos
  - all
  - Created
  - In progress
  - Reveiew
  - Complete

![admin dashboard](https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/admin.png)

# Client dashboard

1. user get access which permissions are granted by admin so default many permissions are granted
2. can create, update, delete task and much more
3. get receved email
4. task update notification
5. task dedline notification
6. can create chat message
7. can use realtime chat message system

#### menus

- dashboard
- Todos
  - Created
  - In progress
  - Reveiew
  - Complete

![dashboard](https://raw.githubusercontent.com/appsaeed/assets/main/images/crm/customer-dashboard.png)

# environment

1. setup environment variables in .env file on root directory
2. add pusher credentials for realtime chat messages

# develpers

1. first need to be installl node packages and php packages

```sh
npm install
# or
bun install
```

for bulid javascript resources

```sh
npm run build
# or
bun run build
```

Install composer for php package

```sh
composer install
```
Start the server

```sh
php artisan serve
```

