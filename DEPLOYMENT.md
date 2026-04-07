# Bazaar Deployment Notes

## Target setup

- App URL: `https://bazaar.hatchers.ai`
- Shared hosting document root: point the subdomain to Laravel's `public/` directory
- Recommended `.env` values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bazaar.hatchers.ai
WEBSITE_HOST=bazaar.hatchers.ai
ASSETPATHURL=storage/app/public/
Environment=live
STORE_SUBDOMAIN_ROUTING=true
```

## Git-based deploy on Namecheap shared hosting

1. Create the `bazaar` subdomain in cPanel for `bazaar.hatchers.ai`.
2. Make sure its document root is the repo's `public/` directory.
3. Clone or pull the repo on the server with Git.
4. Run `composer install --no-dev --optimize-autoloader`.
5. Create the production `.env`.
6. Run:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
```

7. If Vite assets are needed, build them locally and commit the generated assets before pulling on shared hosting, or build on the server only if Node is available there.

## User shop subdomains

This is possible, but the clean version is usually:

- DNS wildcard record: `*.bazaar.hatchers.ai` pointing to the same hosting account
- Web server/cPanel wildcard subdomain or equivalent routing to the same Laravel app
- Wildcard SSL for `*.bazaar.hatchers.ai`

Example shops:

- `store1.bazaar.hatchers.ai`
- `brandx.bazaar.hatchers.ai`

## Main shared-hosting challenges

- Wildcard SSL is usually the first blocker. Standard single-domain SSL is not enough for per-shop subdomains.
- Shared hosting resource limits can become a bottleneck as more stores and traffic are added.
- Long-running queue workers, websocket servers, and other persistent processes are usually a poor fit for shared hosting.
- The app currently resolves stores from the request host and `WEBSITE_HOST`, so wildcard subdomain routing should be tested carefully after DNS is live.
- Custom apex domains for merchants are more complex than platform subdomains because DNS, SSL, and per-domain mapping all need to work together.

## Recommendation

For launch, `bazaar.hatchers.ai` on shared hosting is reasonable if traffic is modest.

For merchant subdomains, shared hosting can work only if Namecheap lets you use:

- wildcard DNS
- wildcard SSL
- one app entry point for all subdomains

If any of those are missing, a VPS is the safer path for multi-tenant storefront hosting.
