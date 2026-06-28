# Mipo Dadang Leadership Foundation — Website & Discipleship Portal

A complete, **dependency-free PHP + MySQL** application built for easy cPanel / shared-hosting
deployment. No Composer, no build step, no framework to install — just upload, set your database
credentials, import one SQL file, and it runs.

It includes three parts in one codebase:

1. **Public website (CMS)** — home, about, our work / news, give, contact.
2. **Discipleship Learning Portal** — members register, work through modules → lessons → resources,
   and their progress is tracked per account.
3. **Admin / Content Studio** — manage modules, lessons, resources, news stories, members,
   contact messages, and site settings.

---

## Requirements

- PHP **7.4+** (works on 8.0 / 8.1 / 8.2 / 8.3) with PDO MySQL — standard on every cPanel host.
- MySQL / MariaDB.
- Apache with `mod_rewrite` (cPanel default).

---

## Deploy to cPanel — step by step

### 1. Create the database
In cPanel → **MySQL® Databases**:
- Create a new database (e.g. `cpaneluser_mdlf`).
- Create a database user with a strong password.
- Add the user to the database with **All Privileges**.
- Note the database name, username and password.

### 2. Edit `config.php`
Open `config.php` and set the database section. **Production must use MySQL**, so set
`'driver' => 'mysql'` (the shipped default is `'sqlite'`, which is only for local development):
```php
'db' => [
    'driver'  => 'mysql',              // production: 'mysql'  (local dev: 'sqlite')
    'host'    => 'localhost',
    'name'    => 'cpaneluser_mdlf',
    'user'    => 'cpaneluser_mdlf',
    'pass'    => 'your_db_password',
    'charset' => 'utf8mb4',
],
```
Leave `base_url` empty unless auto-detection fails. Set `debug` to `false` once the site is live.

### 3. Upload the files
- Zip is easiest: in cPanel → **File Manager**, open `public_html`, upload `mdlf.zip`, then **Extract**.
- Put the **contents** of the project directly in `public_html` (so `index.php` sits in `public_html/`).
- To run it in a subfolder instead (e.g. `public_html/mdlf/`), upload there and open
  `.htaccess` and uncomment/set `RewriteBase /mdlf/`.

### 4. Create the tables
Pick **one**:
- **Option A — Installer:** visit `https://yoursite.com/install.php` once. It creates all tables and
  seed content, then tells you to delete the file.
- **Option B — phpMyAdmin:** open your database → **Import** → choose `database/schema.sql` → Go.

### 5. First login
- Admin: `admin@mdlf.org` / `admin1234`  → **change this immediately**
- Sample member: `disciple@mdlf.org` / `disciple1234`

Admin panel: `https://yoursite.com/admin`

### 6. Clean up
- **Delete `install.php`** from the server.
- Set `'debug' => false` in `config.php`.

---

## Deploy to Hostinger (cPanel) — step by step

Hostinger plans that include **cPanel** work exactly like above; these are the Hostinger-specific
clicks. (On Hostinger's own **hPanel** the equivalents are noted in brackets.)

### 1. Pick PHP 8.x
cPanel → **Select PHP Version** (hPanel → **Advanced → PHP Configuration**). Choose **PHP 8.1 or 8.2**
and make sure these extensions are enabled (they are by default): **pdo_mysql**, **mysqli**, **fileinfo**, **gd**.

### 2. Create the MySQL database
cPanel → **MySQL® Databases** (hPanel → **Databases → MySQL Databases**):
1. Create a database — Hostinger prefixes it, e.g. `u123456789_mdlf`.
2. Create a database user with a **strong password**, e.g. `u123456789_mdlf`.
3. **Add the user to the database** with **ALL PRIVILEGES**.
4. Note the **database name, user, password**. Host stays `localhost`.

### 3. Upload the files
- cPanel → **File Manager** → open **`public_html`**.
- Upload `mdlf.zip` (zip the project first, including the dot-files — `.htaccess`, `storage/uploads/.htaccess`)
  and use **Extract**. Put the **contents** so `index.php` sits directly in `public_html/`.
  *(Subfolder install? Upload into `public_html/mdlf/` and uncomment `RewriteBase /mdlf/` in `.htaccess`.)*
- Hostinger forces HTTPS via a free SSL certificate — leave `base_url` empty and it auto-detects `https://`.

### 4. Configure `config.php`
File Manager → **Edit** `config.php`:
```php
'db' => [
    'driver'  => 'mysql',
    'host'    => 'localhost',
    'name'    => 'u123456789_mdlf',
    'user'    => 'u123456789_mdlf',
    'pass'    => 'your_db_password',
    'charset' => 'utf8mb4',
],
```

### 5. Create the tables + seed content
Pick **one**:
- **Installer:** visit `https://yourdomain.com/install.php` once. It creates all tables (users, pages,
  **blocks**, **media**, modules, lessons, posts, objectives, settings…) and seeds the starter pages.
- **phpMyAdmin:** cPanel → **phpMyAdmin** → select your DB → **Import** → `database/schema.sql` → **Go**.
  *(The installer is preferred — it also seeds the home page's block layout.)*

### 6. Make uploads writable
The media library and logo/favicon save into **`storage/uploads/`**.
- In File Manager, select **`storage/uploads`** → **Permissions** → set to **755** (Hostinger runs PHP as
  your user, so 755 is enough; use 775 only if uploads fail).
- That folder ships with its own `.htaccess` so the files are publicly served but never executed — keep it.

### 7. First login & lock down
- Sign in at `https://yourdomain.com/admin` → `admin@mdlf.org` / `admin1234` → **change the password now**.
- **Delete `install.php`**, set `'debug' => false` in `config.php`.
- Confirm a page loads, then open **Admin → Pages → Build** (or any page → **Edit this page**) to edit live.

### Troubleshooting on Hostinger
- **500 error** → almost always `config.php` DB credentials, or `driver` left as `sqlite`. Set `debug => true`
  temporarily to see the message, then back to `false`.
- **Images/uploads show as broken (403)** → keep `storage/uploads/.htaccess`; check the `storage/uploads`
  folder permissions (755).
- **Pretty URLs 404** → ensure `.htaccess` uploaded (it's a dot-file; enable “show hidden files” in File Manager)
  and that `mod_rewrite`/LiteSpeed rewrite is on (default on Hostinger).

---

## URLs

| Area | Path |
|------|------|
| Home | `/` |
| About | `/about` |
| Our work / news | `/our-work` |
| Single story | `/story/{slug}` |
| Discipleship overview | `/discipleship` |
| Give | `/give` |
| Contact | `/contact` |
| Member portal | `/portal` (login `/portal/login`, register `/portal/register`) |
| Admin / CMS | `/admin` |

---

## Project structure

```
index.php            Front controller (routes every request)
bootstrap.php        Sessions, config, DB connect, base-URL detection
config.php           Your database credentials  ← edit this
install.php          One-time installer (delete after use)
.htaccess            Pretty-URL rewrite + security
database/schema.sql  Tables + seed data
assets/              css / js / img (training photos included)
app/
  core/              db.php, helpers.php, Router.php
  controllers/       public.php, portal.php, admin.php
  views/
    layouts/         public, auth, portal, admin + partials
    public/          home, about, work, post, discipleship, give, contact, error
    portal/          login, register, dashboard, module, lesson
    admin/           login, dashboard, posts, modules, lessons, messages, members, settings
storage/             writable scratch space
```

---

## Security notes

- Passwords are hashed with PHP `password_hash()` (bcrypt).
- All forms are CSRF-protected; all database access uses prepared statements.
- `config.php`, `*.sql`, and the `app/`, `database/`, `storage/` folders are blocked from direct
  web access via `.htaccess`.
- After install: delete `install.php`, change the admin password, and set `debug` to `false`.

---

## Editing content

Everything public is editable from **/admin** — no code needed:
- **Modules & Lessons** — build the discipleship curriculum, attach resources (scripture, PDF, video, link).
- **News & Stories** — publish updates; set a cover image by filename from `/assets/img/`.
- **Settings** — site name, mission, verse, contact details, giving/bank details, and the home-page impact numbers.

To add new images, upload them into `assets/img/` (File Manager) and reference the filename.
