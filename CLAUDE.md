# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Running the project

Requires XAMPP. Start Apache and MySQL via the XAMPP Control Panel, then open `http://localhost/WebTech01/` in a browser. There is no build step, no package manager, and no test suite.

The database is imported once via phpMyAdmin using `auto24.sql`.

## Architecture

Auto24 is a PHP/JS car marketplace built on a custom MVC framework with a front controller.

**Request flow:**
1. All requests hit `index.php` (front controller) via `.htaccess` rewriting
2. `Router` matches the URL and dispatches to a `Controller::method()`
3. Controllers call Models for data, then call `$this->render('view/name')` or `$this->json([...])`
4. Views include `app/views/partials/nav.php` and `app/views/partials/footer.php`
5. On load, JS (`validation.js` DOMContentLoaded) calls `loadAuthState()` to populate the global `authState` object, then calls all page `init*` functions

**Directory structure:**
- `core/` — Framework: `Database.php` (mysqli singleton), `Model.php`, `Controller.php` (render/json/redirect/requireLogin), `Router.php`
- `app/controllers/` — One controller per domain (Auth, Car, Listing, Booking, Admin, Message, Favorite, Merkliste, Page, Home); `MerklisteController` handles the saved-cars wishlist
- `app/models/` — One model per table (Car, User, Listing, Booking, Message, Favorite)
- `app/views/` — Views mirroring controller structure; `partials/nav.php` and `partials/footer.php` are shared
- `js/` — All JS is loaded globally via `footer.php`; each file defines named functions that guard with early returns
- `validation.js` — Bootstrap entry point: single `DOMContentLoaded` that awaits `loadAuthState()` then calls all `init*` functions

**Constants:**
- `BASE_PATH` — filesystem root (`__DIR__ . '/'`)
- `BASE_URL` — URL prefix (`/WebTech01`); used in PHP for hrefs/redirects and in JS (defined in `footer.php` as `const BASE_URL`)
- `VIEW_PATH` — shortcut to `app/views/`

**Database access:**
All DB access goes through `Database::getInstance()` which returns a singleton `mysqli` connection to `localhost/auto24`. Queries are built via plain string interpolation — no escaping, no prepared statements (intentional for this project; SQL-injection hardening is out of scope).

**Auth:**
Session-based via PHP `$_SESSION`. Two hardcoded accounts in `AuthController::login()`: `admin`/`Admin1234` (admin) and `TestUser`/`TestPass123` (demo user). Passwords for real users are stored in plaintext in the DB.

**Listing lifecycle (two-table system):**
User submissions go into the `listings` table with `status = 'eingereicht'`. When an admin approves a listing, `AdminController` copies the record into the `cars` table and sends an inbox message to the user. Rejection only updates the status to `abgelehnt` — no `cars` row is created.

**Image storage:**
Listing/car images are stored as base64 data URLs (`data:image/jpeg;base64,...`) directly in the DB as `MEDIUMTEXT`. There is no `uploads/` folder. `listings.images` and `cars.imagepath` both hold the data URL string. The `ListingController::create()` method converts the uploaded file to base64 before storing.

**PDF export:**
`CarController::pdf()` serves a standalone print-friendly HTML page at `/cars/{id}/pdf`. It loads all vehicle data from the `cars` table and triggers `window.print()` via a button. No external PDF library — uses the browser's native print-to-PDF. A link to this page is shown on the car detail page.

**Server-rendered HTML fragments:**
Several controller methods return ready-made HTML instead of JSON (e.g. `ListingController::listHtml()`, `MessageController::listHtml()`, `BookingController::listHtml()`). JS inserts this directly with `innerHTML` for dynamic tab content in the user and admin dashboards.

**Key data tables:**
- `cars` — public vehicle listings (visible in the marketplace)
- `listings` — user-submitted inserate awaiting moderation
- `users` — registered accounts
- `bookings` — test-drive bookings
- `messages` — inbox notifications per user
