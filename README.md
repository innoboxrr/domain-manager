# Domain Manager

**Custom domains for multi-tenant Laravel applications.**

Let every tenant bring their own domain. Domain Manager handles the registration, verification, routing and SSL bookkeeping so your application only has to answer one question: which tenant is this request for?

```php
$tenant->domains()->attach('app.cliente.com');
// Verification, routing and tenant resolution are handled from here.
```

---

## What it solves

Multi-tenant SaaS hits the same wall every time: tenants want their own domain, and suddenly you are hand-editing DNS, tracking verification state in a spreadsheet, and writing middleware that guesses the tenant from the Host header.

| | |
|---|---|
| **Domain lifecycle** | Attach, verify, activate and retire domains per tenant. |
| **Automatic resolution** | Middleware resolves the tenant from the incoming host — no guesswork in controllers. |
| **Verification** | DNS and file-based ownership checks with retry handling. |
| **Primary + aliases** | One canonical domain per tenant, unlimited aliases redirecting to it. |

---

## Install

```bash
composer require innoboxrr/domain-manager
php artisan vendor:publish --tag=domain-manager-config
php artisan migrate
```

Add the resolution middleware to the group that serves tenant traffic, and point the wildcard DNS at your application.

---

## Built by

[Innobox R&R](https://github.com/innoboxrr) — extracted from production multi-tenant systems. Part of a catalogue of 52 open-source packages on Packagist and npm.

**[innobox.systems](https://innobox.systems)**
