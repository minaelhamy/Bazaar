# Bazaar Guide

## Overview

Bazaar is a Laravel-based multi-tenant commerce platform designed to work like a Shopify-style SaaS.
The platform itself lives on a main domain such as `bazaar.hatchers.ai`, while merchants can run storefronts on:

- path-based URLs like `bazaar.hatchers.ai/vendor-slug`
- platform subdomains like `vendor.bazaar.hatchers.ai`
- custom domains such as `brand.com`

At a high level, Bazaar supports:

- platform landing pages and vendor registration
- admin and vendor dashboards
- merchant storefronts
- customer authentication
- carts, checkout, orders, and payments
- blogs, FAQs, banners, themes, and landing-page content
- optional add-ons such as social login, WhatsApp, Telegram, PWA, custom domains, and more

This document explains how Bazaar works, how the codebase is organized, how it was deployed on Namecheap shared hosting, and how to operate it day to day.

## Product Structure

Bazaar has three major runtime surfaces:

1. Platform

- public marketing/landing pages
- pricing and store listing pages
- platform-level vendor registration
- platform admin login under `/admin`

2. Merchant Admin

- vendor dashboard
- product, order, theme, payment, and settings management
- landing-page and storefront branding controls
- custom domain setup

3. Storefront

- merchant-facing online store
- customer login and registration
- cart, checkout, payment, order tracking, blogs, FAQs, wallet, and profile pages

## Architecture

### Framework

Bazaar is a Laravel application. Core framework behavior is standard Laravel:

- routing in `routes/`
- controllers in `app/Http/Controllers/`
- models in `app/Models/`
- middleware in `app/Http/Middleware/`
- views in `resources/views/`
- config in `config/`

### Controller Layers

The application is organized by audience/use case:

- `app/Http/Controllers/admin`
  - admin and vendor dashboard flows
  - settings, products, orders, themes, plans, media, etc.
- `app/Http/Controllers/web`
  - storefront/customer-facing controllers
  - home, product detail, cart, checkout, login, wallet
- `app/Http/Controllers/landing`
  - platform landing pages
- `app/Http/Controllers/addons`
  - add-on features
- `app/Http/Controllers/addons/included`
  - built-in modular features like blogs, coupons, languages, etc.
- `app/Http/Controllers/api`
  - API endpoints

### Data Model

The app uses Eloquent models for platform, store, and order data. Main model groups include:

- tenant and identity
  - `User`
  - `RoleManager`
  - `RoleAccess`
  - `Transaction`
  - `PricingPlan`
  - `CustomDomain`
- storefront/catalog
  - `Category`
  - `Item`
  - `Variants`
  - `Extra`
  - `ProductImage`
  - `StoreCategory`
  - `Tax`
  - `Shipping`
- orders and customers
  - `Cart`
  - `Order`
  - `OrderDetails`
  - `Favorite`
  - `Coupons`
  - `QuestionAnswer`
  - `Testimonials`
- site and branding
  - `Settings`
  - `OtherSettings`
  - `LandingSettings`
  - `Banner`
  - `Theme`
  - `SocialLinks`
  - `Footerfeatures`
  - `About`
  - `Terms`
  - `Privacypolicy`
  - `Faq`
  - `WhoWeAre`
- integrations and add-ons
  - `Payment`
  - `Firebase`
  - `TelegramMessage`
  - `WhatsappMessage`
  - `SystemAddons`

### Route Layout

The route layer is split between:

- `routes/web.php`
  - primary admin, landing, and storefront routes
- additional feature route files in `routes/`
  - payments
  - social logins
  - blogs
  - product reviews
  - custom domain
  - shipping
  - language/currency
  - many add-ons

Important route concepts:

- admin area is under `/admin`
- platform landing pages are scoped to the platform domain via `landingMiddleware`
- storefront routes are grouped separately for:
  - `env('WEBSITE_HOST')` with a `/{vendor}` prefix
  - `'{store_subdomain}.' . env('WEBSITE_HOST')`
  - `'{custom_domain}'` for non-platform custom domains

This split is what allows Bazaar to support both the main platform and tenant storefronts in one codebase.

## Multi-Tenant Storefront Logic

Bazaar’s multi-tenant behavior is centered in:

- [helper.php](/Users/minaelhamy/Downloads/Bazaar/app/Helpers/helper.php)
- [FrontMiddleware.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Middleware/FrontMiddleware.php)
- [UserMiddleware.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Middleware/UserMiddleware.php)

The important helper methods are:

- `currentHost()`
- `platformHost()`
- `isPlatformHost()`
- `isPlatformSubdomain()`
- `currentStoreUser()`
- `storeinfo()`
- `storefront_base_url()`
- `storefront_url()`
- `storefront_request_is()`

### How store resolution works

When a request comes in, Bazaar determines whether it is:

- the platform domain
- a platform subdomain
- a custom domain

Then it resolves the store by:

- explicit route slug on the platform host
- subdomain slug on `*.bazaar.hatchers.ai`
- custom domain mapping from the database

### Custom-domain entitlement

Custom domains are not simply matched by hostname. Bazaar also enforces:

- the custom domain record must be active/connected
- the merchant must be entitled to use custom domains

This prevents a merchant from using a custom domain unless their subscription state allows it.

## Frontend Rendering

Views are split by audience:

- `resources/views/landing`
  - platform landing pages
- `resources/views/admin`
  - admin/vendor dashboard
- `resources/views/front`
  - storefront views
- `resources/views/email`
  - email templates
- `resources/views/errors`
  - error pages

Storefront theme layouts and shared storefront UI are primarily under:

- `resources/views/front/theme`

Landing layout pieces are under:

- `resources/views/landing/layout`

### Theme and asset behavior

Merchant logos, favicons, hero images, banners, and product images are uploaded into:

- `storage/app/public/admin-assets/...`
- `storage/app/public/item/...`

That matters for deployment because those folders are runtime content, not source-controlled content.

## Configuration

The most important environment variables for Bazaar are:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bazaar.hatchers.ai

WEBSITE_HOST=bazaar.hatchers.ai
STORE_SUBDOMAIN_ROUTING=true
ASSETPATHURL=storage/app/public/
Environment=live
```

Important meanings:

- `APP_URL`
  - canonical platform URL
- `WEBSITE_HOST`
  - main platform host used in route grouping and host detection
- `STORE_SUBDOMAIN_ROUTING`
  - enables storefront URL generation through tenant subdomains
- `ASSETPATHURL`
  - asset base path used across the theme

## Database

Bazaar does not rely only on the default Laravel migrations.

The full application schema comes from the SQL dump:

- [store_mart.sql](/Users/minaelhamy/Downloads/Bazaar/storage/app/public/store_mart.sql)

This dump contains:

- full table definitions
- seed/default records
- initial admin user
- settings and add-on defaults

For first-time setup, the correct flow is:

1. create the MySQL database
2. import `store_mart.sql`
3. configure `.env`
4. run Laravel cache clear commands

Using only `php artisan migrate` on an empty database is not sufficient for this codebase.

## What We Changed To Prepare Bazaar For Launch

### 1. Removed installer and licensing flow

We removed the install/license gating so the app can boot directly from source and `.env` without running an installation wizard.

### 2. Fixed tenant host routing

We updated the route and helper logic so Bazaar can correctly support:

- `bazaar.hatchers.ai`
- `tenant.bazaar.hatchers.ai`
- custom tenant domains

### 3. Updated storefront links

We replaced old path-style storefront links with helper-based links using:

- `helper::storefront_url(...)`
- `helper::storefront_request_is(...)`

This made internal navigation work correctly across platform-host, subdomain, and custom-domain storefronts.

### 4. Fixed custom-domain billing enforcement

We restored custom-domain entitlement checks so merchants cannot use a custom domain unless they are allowed by plan/subscription settings.

### 5. Fixed notification links

We updated email and WhatsApp order/tracking URLs to use the merchant’s real storefront host rather than forcing old path-style URLs.

### 6. Fixed platform homepage routing

We removed the conflicting root admin route so the platform homepage can show the landing page while admin login remains at `/admin`.

### 7. Made the landing footer paragraph editable in admin

We changed the footer paragraph from a translation-only value to a database-backed setting with fallback behavior.

### 8. Hardened cPanel deployment

We added `.cpanel.yml` and tuned it so deploys:

- sync code from repo to live folder
- preserve runtime uploads
- recreate runtime Laravel directories
- reset permissions
- clear Laravel caches

## Namecheap Deployment

### Live path layout used for Bazaar

Repository checkout:

```text
/home/hatchwan/repo/bazaar.hatchers.ai
```

Live application:

```text
/home/hatchwan/bazaar.hatchers.ai
```

Document root:

```text
/home/hatchwan/bazaar.hatchers.ai/public
```

### cPanel deployment file

The deploy file is:

- [.cpanel.yml](/Users/minaelhamy/Downloads/Bazaar/.cpanel.yml)

It currently:

- creates Laravel runtime directories
- syncs repo code into the live app
- excludes:
  - `.env`
  - `vendor/`
  - runtime uploaded media under `storage/app/public/admin-assets/`
  - runtime uploaded product files under `storage/app/public/item/`
- resets permissions
- runs:
  - `php artisan config:clear`
  - `php artisan cache:clear`
  - `php artisan route:clear`

This is important because Bazaar stores media changes on the live server, and a naive `rsync --delete` would otherwise wipe them on every deployment.

### Shared-hosting specifics

Bazaar was prepared for Namecheap shared hosting with:

- `bazaar.hatchers.ai` as the platform host
- wildcard support for `*.bazaar.hatchers.ai`
- live code deployed from Git/cPanel

Key shared-hosting caveats:

- wildcard SSL is required for merchant subdomains
- shared hosting is not ideal for long-running queue workers or heavy background processing
- resource limits can become a bottleneck as the platform grows

## How To Deploy Bazaar

### First-time deployment

1. push code to GitHub
2. clone or pull repo on Namecheap under the repo path
3. configure cPanel Git deployment
4. point `bazaar.hatchers.ai` document root to the live app’s `public/`
5. create `.env`
6. import the full SQL dump
7. run:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan route:clear
chmod -R 775 storage bootstrap/cache
```

### Ongoing deployments

1. push to GitHub
2. pull in cPanel Git Version Control
3. click Deploy
4. verify homepage, admin, and storefront

Because `.cpanel.yml` is in place, runtime folders and cache clearing are automated during deploy.

## How To Use Bazaar

### Platform admin

Admin login is at:

```text
/admin
```

The platform admin can:

- manage plans
- manage vendors
- manage global settings
- control landing page branding
- review subscriptions and transactions
- manage add-ons and integrations

### Vendor workflow

A typical vendor flow looks like this:

1. vendor registers
2. vendor logs into `/admin`
3. vendor configures:
   - store details
   - theme
   - logo/favicon
   - products
   - categories
   - shipping/taxes
   - payment methods
4. vendor receives a storefront URL
5. vendor begins selling

### Customer workflow

A typical customer flow looks like this:

1. customer visits storefront
2. browses products
3. adds products to cart
4. signs in or continues as guest depending on store settings
5. checks out
6. receives order confirmation/tracking links

### Landing page management

From the admin basic/landing settings page, Bazaar can manage:

- website title
- copyright
- logo, dark logo, favicon
- landing enable/disable
- footer description
- social links
- footer features
- landing hero/banner images
- other landing section visuals

## Files To Know First

If you are onboarding into Bazaar, these are the highest-value files to read first:

- [routes/web.php](/Users/minaelhamy/Downloads/Bazaar/routes/web.php)
- [helper.php](/Users/minaelhamy/Downloads/Bazaar/app/Helpers/helper.php)
- [FrontMiddleware.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Middleware/FrontMiddleware.php)
- [UserMiddleware.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Middleware/UserMiddleware.php)
- [HomeController.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Controllers/web/HomeController.php)
- [WebSettingsController.php](/Users/minaelhamy/Downloads/Bazaar/app/Http/Controllers/admin/WebSettingsController.php)
- [footer.blade.php](/Users/minaelhamy/Downloads/Bazaar/resources/views/landing/layout/footer.blade.php)
- [.cpanel.yml](/Users/minaelhamy/Downloads/Bazaar/.cpanel.yml)
- [DEPLOYMENT.md](/Users/minaelhamy/Downloads/Bazaar/DEPLOYMENT.md)

## Operational Notes

### Media persistence

If logos, favicons, hero banners, or product images disappear after deploy, the first thing to check is whether the deploy sync is overwriting runtime media folders.

### Runtime folders

Laravel will fail with 500 errors if these directories are missing:

- `storage/framework/sessions`
- `storage/framework/views`
- `storage/framework/cache/data`
- `storage/logs`
- `bootstrap/cache`

Bazaar’s deployment process now recreates them automatically.

### SQL and seed data

Because the app depends on the SQL dump, changes to default settings and initial records should be treated carefully when creating new environments.

## Recommended Next Documentation

This guide is the broad project document. The next useful docs would be:

- an admin operations guide
- a vendor onboarding guide
- a release/deployment checklist
- a Servio adaptation guide based on Bazaar

