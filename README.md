# Nyumbani — Rent a house in Tanzania 🇹🇿

A Laravel + Tailwind web app that helps local residents find houses to rent across
Tanzania (Arusha, Dar es Salaam, Mwanza, Moshi, Zanzibar and more). Renters can
browse and filter listings and contact landlords directly by **phone call or WhatsApp**;
landlords can list their property for free.

## Features

For renters (public site):
- **Landing page** with a hero search (region, property type, budget in TZS)
- **Browse page** with live filters, quick type chips, and a **map view** (Leaflet / OpenStreetMap)
- **Property detail page** — photo **gallery**, amenities, a location **map**, and one-tap **Call / WhatsApp** the landlord
- **Save / favourite** houses with no login needed (stored in the browser) + a **Saved** page
- Prices shown in **Tanzanian Shillings (TZS)**

For landlords & admins (Filament panel at `/admin`):
- **Accounts** — landlords register/log in and **manage only their own listings**
- Create/edit properties with **multiple photos**, amenities, map coordinates, and availability
- **Admins** see and manage every listing and can feature properties on the homepage
- Seeded with realistic sample listings across 10 regions

## Tech stack

- Laravel 12 (PHP 8.2) · MySQL · Blade
- Filament v3 (admin panel & landlord accounts)
- Tailwind CSS v4 + Vite · Leaflet (maps)

## Accounts & approval

New landlords register at `/admin/register` but start **pending** — they can log in and see a
"pending approval" dashboard, but **cannot list properties until an admin approves them**.
Admins approve accounts under **Administration → Landlords & users** (an "Approve" action per row).

| Role | How | Access |
|------|-----|--------|
| **Admin** | Seeded: `test@example.com` / `password` | Manage all listings & users, approve landlords, feature homes |
| **Landlord (approved)** | Seeded demo: `landlord@example.com` / `password` | Manage their own listings only |
| **Landlord (pending)** | Register at `/admin/register` | Log in only; must be approved before listing |

The dashboard shows **role-aware stat widgets** (landlords see their own numbers; admins see
platform-wide totals) plus a "latest listings" table scoped to what the user can manage.

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

Then open **http://127.0.0.1:8000** (public site) or **http://127.0.0.1:8000/admin** (landlord/admin panel).

> Tip: `composer run dev` starts the server, queue, logs and Vite together in one command.

## Where things live

| What | File |
|------|------|
| Public routes | [routes/web.php](routes/web.php) |
| Public controller | [app/Http/Controllers/PropertyController.php](app/Http/Controllers/PropertyController.php) |
| Property model (price/WhatsApp/gallery/filters) | [app/Models/Property.php](app/Models/Property.php) |
| Landlord panel resource (owner-scoped) | [app/Filament/Resources/PropertyResource.php](app/Filament/Resources/PropertyResource.php) |
| Panel config (brand, registration, profile) | [app/Providers/Filament/AdminPanelProvider.php](app/Providers/Filament/AdminPanelProvider.php) |
| Favourites (browser storage) | [resources/js/favorites.js](resources/js/favorites.js) |
| Database schema | [database/migrations/](database/migrations) |
| Sample data | [database/factories/PropertyFactory.php](database/factories/PropertyFactory.php) · [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) |
| Pages | [resources/views/](resources/views) |
| Tests | [tests/Feature/FilamentPanelTest.php](tests/Feature/FilamentPanelTest.php) |

## Ideas for next steps

- Renter accounts so favourites sync across devices
- A map-picker in the panel (click to set coordinates) instead of typing lat/lng
- Report a listing / landlord verification badge
- Email/WhatsApp enquiry notifications to landlords
