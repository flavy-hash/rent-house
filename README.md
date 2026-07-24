# Nyumbani — Rent a house in Tanzania 🇹🇿

A Laravel + Tailwind web app that helps local residents find houses to rent across
Tanzania (Arusha, Dar es Salaam, Mwanza, Moshi, Zanzibar and more). Renters can
browse and filter listings and contact landlords directly by **phone call or WhatsApp**;
landlords can list their property for free.

## Features

- **Landing page** with a hero search (region, property type, budget in TZS)
- **Browse page** with live filters — region, type, budget, and quick type chips
- **Property detail page** — photos, amenities, key facts, and one-tap **Call / WhatsApp** the landlord
- **List your property** form (with photo upload) for landlords
- Prices shown in **Tanzanian Shillings (TZS)**
- Seeded with realistic sample listings across 10 regions

## Tech stack

- Laravel 12 (PHP 8.2) · MySQL · Blade
- Tailwind CSS v4 + Vite

## Running the app locally

Requirements: PHP 8.2+, Composer, Node.js, and a running MySQL server.

```bash
# 1. Install dependencies (first time only)
composer install
npm install

# 2. Make sure the database exists and .env points to it
#    DB_DATABASE=rent_house  DB_USERNAME=root  DB_PASSWORD=

# 3. Set up the database with sample data
php artisan migrate --seed
php artisan storage:link   # so uploaded photos are served

# 4. Build the frontend and start the app (two terminals)
npm run dev                 # terminal 1 — compiles CSS/JS
php artisan serve           # terminal 2 — http://127.0.0.1:8000
```

Then open **http://127.0.0.1:8000**.

> Tip: `composer run dev` starts the server, queue, logs and Vite together in one command.

## Where things live

| What | File |
|------|------|
| Routes | [routes/web.php](routes/web.php) |
| Controller | [app/Http/Controllers/PropertyController.php](app/Http/Controllers/PropertyController.php) |
| Property model (formatting, WhatsApp link, filters) | [app/Models/Property.php](app/Models/Property.php) |
| Database schema | [database/migrations/](database/migrations) |
| Sample data | [database/factories/PropertyFactory.php](database/factories/PropertyFactory.php) · [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) |
| Pages | [resources/views/](resources/views) |

## Ideas for next steps

- Landlord accounts / login so people can manage their own listings
- Map view of properties
- Save / favourite listings
- Multiple photos per property
- Report a listing / verification badge
