# Gander Oceanic Web
### Central Repository for the CZQO web team
---
## Installation - Laravel site
To work on the Laravel site, you must have the following installed:
- Composer
- XAMPP (for PHP and MySQL)
- A suitable editor (recommended PhpStorm, could also use Atom or VS Code)

#### Installation process
- Clone the project to a folder of your choice. It is not required to clone it to htdocs in XAMPP as the included `php artisan serve` development server works just fine.
- Run `composer install` in the Laravel (czqo) directory.
- Create a `.env` file with the following contents:
```ini
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:hN0efpNVdD/IYTqOZ5fDeYo/vR7EXMRKWPxE+8nGcic=
APP_DEBUG=true
APP_URL=localhost
LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=czqovatcan
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=sparkpost
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=25 
MAIL_USERNAME=//username
MAIL_PASSWORD=//add password
MAIL_ENCRYPTION=tls
SPARKPOST_SECRET=//secret
SPARKPOST_KEY=//key

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```
- Run `php artisan key:generate`
- Run `php artisan config:cache`
- In phpMyAdmin or any SQL database tool connected to your MySQL server, create a database named `czqovatcan`.
- Run `php artisan migrate --seed`.
- Attempt to run the application by running `php artisan serve` in the Laravel directory.
- Go to `localhost:8000` in your browser and login with the VATSIM SSO test system with the credientials `1300012`/`1300012` (CID/pass). This user is 12th Test with an ADM rating.
- Edit the `1300012` user in the `users` table to have a permission level of `4`. This will give `1300012` all the permissions available on the site.
- Have fun!

#### If you encounter any issues, feel feel to create an issue in the repository.

