# Council

This is an open source forum that was built with help from the laracasts series "Let's build a forum with Laravel and TDD" and "How to manage an Open Source Project".

## Installation

### Prerequisites
* To run this project, you must have PHP 7 installed as a prerequisite.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet.
* If you want to use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on Mac or compile from source (https://redis.io/topics/quickstart)

### Step 1.

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone https://github.com/Harselan/council.git
cd council && composer install && npm install
php artisan council:install
npm run dev
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's ´.env´ file. In the example below, we've named the database, "council."

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=council
DB_USERNAME=root
DB_PASSWORD=
```

Then, migrate your database to create tables.

```
php artisan migrate
```

### Step 3.

1. Visit: http://council.test/register and register an account.
2. Edit 'config/council.php', adding the email address of the account you just created.
3. Visit: http://council.test/admin/channels and add at least one channel.

Once finished, clear your server cache, and you're all set to go!

```
php artisan cache:clear
```