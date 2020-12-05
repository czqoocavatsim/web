<p align="center"><img src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png"height="100"></p>

<p align="center">The website for VATSIM's Gander Oceanic OCA<br>https://ganderoceanic.com</p>

---
### Contributing

We would love you to help out with the website! If you find something and fix it, or notice something, or even have a feature request, feel free to make a pull request or an issue.

#### Using this for your own VATSIM website

czqo-core is licensed under the **MIT License**. You are free to use code from the repository within the reigns of that license.

However, if you wish to use czqo-core as a basis for your own VATSIM related website (e.g. an FIR), we humbly ask the following:

* You **provide credit for major portions of this repository used** to Gander Ocenaic OCA with a link to this repository.
* You **do not** use the same public facing user interface or branding as the Gander Oceanic OCA website. This is important in ensuring that we maintain our brand identity. It is fine to keep admin-only user interfaces (e.g. the create news article form).

If you require assistance with some aspect of czqo-core, feel free to DM one of us on Discord or send an email. We cannot guarantee support.

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

---

### Setup process

It's mostly basic Laravel setup, however these are the steps specific to CZQO:

##### .env file

The following values must be filled in the .env file
```
#If you want to send mail
MAILGUN_DOMAIN=
MAILGUN_SECRET=

#Channel IDs for Discord channels if you want them to work
DISCORD_WEB_LOGS=
DISCORD_GUILD_LOGS=
DISCORD_ANNOUNCEMENTS=
DISCORD_ENDORSEMENTS=
DISCORD_MARKETING=
DISCORD_STAFF=

#Discord OAuth keys for linking/server joining
DISCORD_KEY=
DISCORD_SECRET=
DISCORD_BOT_TOKEN=
DISCORD_REDIRECT_URI= #for the link method
DISCORD_REDIRECT_URI_JOIN= #for the join server method
DISCORD_GUILD_ID= #your servers ID

#VATSIM connect
CONNECT_REDIRECT_URI=
CONNECT_CLIENT_ID=
CONNECT_SECRET=

#Twitter
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_TOKEN_SECRET=

#DO Spaces (you can replace DO spaces with Amazon S3 easily)
DIGITALOCEAN_SPACES_KEY=
DIGITALOCEAN_SPACES_SECRET=
DIGITALOCEAN_SPACES_ENDPOINT=
DIGITALOCEAN_SPACES_REGION=
DIGITALOCEAN_SPACES_BUCKET=
```

##### Database seeding
Run the migrations as normal (`php artisan migrate`). Then you need to run the seeders. Look in `database/seeders` and run each seeder through this command:
`php artisan db:seed --class=CLASSNAMEHERE`

For example, `PermissionsSeeder` would be `php artisan db:seed --class=PermissionsSeeder`.

If you want to add extra permissions/roles, put them in a seeder.

##### Permissions

This site uses the [Laravel Permissions](https://docs.spatie.be/laravel-permission/v3/introduction/) package by Spatie. To give your user administrator permissions, do the following:

* Run the seeders.
* Login with the user.
* Go to the `model_has_roles` table in your database.
* Create a row where the `model_id` is the user's CID, `model_type` is `App\Models\Users\User`, and `role_id` is `1.`
* Refresh on the website. You should now have administrator permissions.
