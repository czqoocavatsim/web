# czqo-core 
### The website for VATSIM's Gander Oceanic FIR https://czqo.vatcan.ca
---

#### Initial setup process

1. Rename `.env.exmaple` to `.env` and fill required fields.
2. Create a SQL database, and put the credentials in `.env`.
3. Run `php artisan migrate --seed` (runs database migrations and seeds with required rows).
4. Run `php artisan key:generate`.
5. Login with VATSIM demo SSO.
6. Give that new account in the `users` table a `permissions` value of `4`.

#### Contributing

If you wish to contribute, submit a pull request, create an issue, or email Liesel.
