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
Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to (http://council.test).

1. Visit: http://council.test/register and register an account.
2. Edit 'config/council.php', and add any email address that should be marked as an administrator.
3. Visit: http://council.test/admin/channels to seed your forum with one or more channels..