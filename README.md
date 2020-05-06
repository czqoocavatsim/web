# czqo-core 
### The website for VATSIM's Gander Oceanic FIR https://ganderoceanic.com
---
### Contributing

We would love you to help out with the website! If you find something and fix it, or notice something, or even have a feature request, feel free to make a pull request or an issue.

#### Submitting an Issue or Pull Request
Guidelines for submitting an **issue**:

- Be sensible, and don't spam with unnecessary issues.
- Tell us:
  - What is the issue/feature?
  - Why does it need to be fixed/why is it important to add?
  - How can we reproduce the issue? (if it is a bug)
  - What have you already tried? (if it is a bug)

Guidelines for submitting a **pull request**:
- Be sensible as stated above.
- Tell us:
  - What you have fixed/added and where you fixed it
  - Why it was a problem, or why it was neccessary/nice to add
- Document/comment your code. This is important for us and future developers so they can understand what you have written.

### Initial setup process

1. Rename `.env.exmaple` to `.env` and fill required fields. For SSO credentials, use the Demo vACC key from the VATSIM forums. For VATSIM Connect, you will need correct keys from VATSIM Connect. These are not publicly available.
2. Create a SQL database, and put the credentials in `.env`.
3. Run `php artisan migrate --seed` (runs database migrations and seeds with required rows).
4. Run `php artisan key:generate`.
5. Login with VATSIM demo SSO.
6. Give that new account in the `users` table a `permissions` value of `4`.


