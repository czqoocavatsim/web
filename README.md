# czqo-core 
### The website for VATSIM's Gander Oceanic FIR https://ganderoceanic.com
---

#### Initial setup process

1. Rename `.env.exmaple` to `.env` and fill required fields. For SSO credentials, use the Demo vACC key from the VATSIM forums. For VATSIM Connect, you will need correct keys from VATSIM Connect. These are not publicly available.
2. Create a SQL database, and put the credentials in `.env`.
3. Run `php artisan migrate --seed` (runs database migrations and seeds with required rows).
4. Run `php artisan key:generate`.
5. Login with VATSIM demo SSO.
6. Give that new account in the `users` table a `permissions` value of `4`.

#### Contributing

If you wish to contribute, feel free to submit a pull request. Alternatively, contact Liesel.
