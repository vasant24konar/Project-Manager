# FruGo × Product Manager — Laravel 11 Enterprise Assignment

A single Laravel 11 application that delivers two integrated products:

- **FruGo** — a customer-facing fresh-produce shop with passwordless OTP login, product browsing, cart, and order management with live status tabs.
- **Product Manager** — an admin/manager dashboard for product approval workflows, full-text search, rich-text descriptions, and multi-role access control.

> **Tagline:** _Get fresh fruits at better prices from trusted local vendors delivered to your doorstep._

---

## Quick Start (Docker — recommended)

```bash
# Clone the repo
git clone <repo-url>
cd <project-folder>

# Full bootstrap (builds containers, runs migrations + seeds)
bash deploy.sh
# or
make deploy
```

| Role | Email | Password | Notes |
|------|-------|----------|-------|
| Admin | admin@example.com | Admin@1234 | Full access |
| Product Manager | manager@example.com | Manager@1234 | Products only |
| Customer | Use OTP login | — | Any email, auto-created |

- Shop: [http://localhost:8080](http://localhost:8080)
- MailHog (OTP inbox): [http://localhost:8025](http://localhost:8025)

---

## Manual Setup (without Docker)

```bash
cp .env.example .env
# Edit .env: set DB_*, APP_URL, MAIL_*

composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## Makefile Commands

| Command | Description |
|---------|-------------|
| `make up` | Start containers |
| `make down` | Stop containers |
| `make deploy` | Full first-run bootstrap |
| `make migrate` | Run pending migrations |
| `make fresh` | Drop all tables and re-seed |
| `make shell` | Shell into the app container |
| `make test` | Run PHPUnit |
| `make lint` | Run Laravel Pint (PSR-12) |
| `make cache-clear` | Clear all Laravel caches |

---

## Vercel Deployment

> The app runs on Vercel via the community [`vercel-php`](https://github.com/juicyfx/vercel-php) runtime (PHP 8.2).

### Prerequisites

1. External MySQL — [PlanetScale](https://planetscale.com) (free tier) or [Railway](https://railway.app).
2. Transactional email — [Mailgun](https://mailgun.com), [Resend](https://resend.com), or similar.

### Steps

```bash
npm i -g vercel          # install Vercel CLI
vercel login
vercel --prod            # first deploy (follow prompts)
```

### Required Vercel environment variables

Set these in **Vercel → Project → Settings → Environment Variables**:

| Variable | Value |
|----------|-------|
| `APP_KEY` | `base64:...` (run `php artisan key:generate --show` locally) |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your-project.vercel.app` |
| `DB_HOST` | PlanetScale / Railway host |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | your database name |
| `DB_USERNAME` | your db user |
| `DB_PASSWORD` | your db password |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_STORE` | `array` |
| `LOG_CHANNEL` | `stderr` |
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | your SMTP host |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | your SMTP user |
| `MAIL_PASSWORD` | your SMTP password |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` |
| `MAIL_FROM_NAME` | `FruGo` |

After the first deploy, run migrations against your external DB:

```bash
vercel env pull .env.vercel.local          # pull env locally
php artisan migrate --seed --env=vercel    # or point DB vars to PlanetScale and run locally
```

---

## Architecture Decisions

### Why Laravel 11?

Laravel 11 ships a streamlined `bootstrap/app.php` — a single file for middleware aliases, providers, and exception handling, eliminating the old `Kernel.php` proliferation. Its first-class OTP/email primitives, Eloquent, and Blade templating made it the natural choice for both the admin dashboard and the customer shop.

### Repository + Service Pattern

```
Controller → Service → Repository → Eloquent Model
```

Controllers are HTTP adapters only — they validate requests and return responses. Zero query logic lives in a controller.

`ProductRepositoryInterface` decouples the Eloquent implementation from business logic; swapping to an external API or a test double requires changing one binding in `RepositoryServiceProvider`, not the service or controller.

`ProductService` owns business rules: ownership checks (`canModify`), `created_by` stamping, and approval-state transitions.

### Multi-Role RBAC

The `User` model carries a `role` column (`admin` | `product_manager` | `customer`). The custom `CheckRole` middleware is aliased as `role` in `bootstrap/app.php` and applied to route groups:

```php
Route::middleware(['auth', 'role:admin,product_manager'])->group(...)
```

Ownership checks live in `ProductService::canModify()`, not in controllers — keeping controllers thin and the rule testable in isolation.

### OTP Passwordless Authentication

Customers log in via a 6-digit time-limited OTP sent to their email (`OtpCode` model, 10-minute expiry). `User::firstOrCreate()` auto-registers new customers on first login — no separate sign-up form. The OTP session is stored server-side (`session('otp_email')`), so the verify step cannot be reached without first requesting a code.

In development, the OTP is displayed on-screen in a yellow dashed box (guarded by `APP_DEBUG`) so you can test without an SMTP server.

### Product Approval Workflow

Products created by `product_manager` users enter a `pending` state. Admins approve or reject from the dashboard. Only `approved` products appear in the FruGo shop. This keeps untrusted content off the storefront without requiring a separate application.

### Form Requests

Every mutating endpoint has a dedicated `FormRequest`. Authorisation (`authorize()`) and validation rules are co-located. `ProductRequest::prepareForValidation()` sanitises rich-text HTML before validation — a defence-in-depth measure against stored XSS.

### PSR-4 / PSR-12

`composer.json` maps `App\` → `app/` per PSR-4. All classes carry `declare(strict_types=1)`. Laravel Pint enforces PSR-12 style (`make lint`).

---

## Challenges & Solutions

### Carousel Navigation Arrows Appearing on Shop Pages

**Challenge:** The Owl Carousel testimonial and vegetable carousels rendered large `<` `>` navigation arrows that overlapped product listings.

**Solution:** Both carousel instances set `nav: false` in `public/js/main.js`. A CSS safety net (`display: none !important` on `.owl-carousel .owl-nav`) in the shop layout catches any future carousel that opts in to nav.

### Sticky Navbar vs. Scrolling Green Topbar

**Challenge:** A fixed-top navbar covered page content with a gap; the green brand topbar needed to scroll away while the white nav stayed pinned.

**Solution:** The green topbar sits in normal page flow (scrolls up with content). The white navbar uses `position: sticky; top: 0` so it sticks to the viewport once the topbar has scrolled out. A `$(window).scroll` shadow enhancement gives depth feedback without interfering with positioning.

### OTP Email Not Delivered (Local Development)

**Challenge:** `MAIL_MAILER=log` wrote OTPs to `laravel.log` — invisible to the user.

**Solution:** Added MailHog as a Docker service (`pm_mailhog`). All local mail is captured at `http://localhost:8025`. Additionally, in `APP_DEBUG` mode the OTP is printed directly on the verify page so development can proceed even without checking MailHog.

### Custom Bootstrap 5 Pagination

**Challenge:** Laravel's default Bootstrap 5 pagination view used Bootstrap Icons (`bi-chevron-left`) that rendered at an inherited large font size, producing huge arrows.

**Solution:** Published `resources/views/vendor/pagination/bootstrap-5.blade.php` and replaced Bootstrap Icons with FontAwesome `fa-chevron-left/right` at `0.75rem`. Called `Paginator::useBootstrapFive()` in `AppServiceProvider::boot()` to activate the custom view.

### Rich-Text Description XSS

**Challenge:** HTML from a Quill rich-text editor is user-supplied markup — it must be stored but not weaponised for XSS.

**Solution (two layers):**
1. `ProductRequest::prepareForValidation()` calls `strip_tags()` with an explicit allow-list of safe formatting tags; script tags, event attributes, and iframes are stripped.
2. In views, `{{ }}` HTML-encodes all output by default; `{!! !!}` is used only for the pre-cleaned description field.

### CSRF on PATCH/DELETE Forms

HTML forms only support GET and POST. Laravel's `@method('DELETE')` renders a hidden `_method` field; combined with `@csrf`, every state-changing form is both method-spoofed and CSRF-protected with a single Blade directive.

### Order Status Filtering

**Challenge:** Orders page showed only pending orders with no way to filter.

**Solution:** Added `?status=` query parameter to `OrderController::index()`. The controller clones the base query five times (all / pending / processing / completed / cancelled) to compute per-tab counts without a GROUP BY. Pagination preserves the active filter via `->appends(['status' => $status])`.

---

## Security

| Threat | Mitigation |
|--------|------------|
| **SQL Injection** | All queries use Eloquent's parameterised query builder. Raw SQL is never constructed from user input. |
| **Stored XSS** | Rich-text HTML is strip-tagged on ingest. Blade `{{ }}` HTML-encodes all output; `{!! !!}` only used after sanitisation. |
| **Reflected XSS** | Blade's `{{ }}` encodes all reflected values (search terms, flash messages, etc.). |
| **CSRF** | `@csrf` on every POST/PUT/PATCH/DELETE form. `VerifyCsrfToken` middleware rejects requests without a valid token. |
| **Brute force (login)** | `RateLimiter` in `LoginRequest` — 5 attempts per email/IP, then lockout. |
| **OTP replay** | OTP records are deleted immediately on successful verification. Codes expire after 10 minutes. |
| **Password storage** | Bcrypt via Laravel's `hashed` cast (12 rounds). |
| **Session fixation** | `$request->session()->regenerate()` on login; `invalidate()` + `regenerateToken()` on logout. |
| **Privilege escalation** | Role checked in middleware for route access and in `ProductService::canModify()` for ownership. `abort(403)` on failure. |
| **Sensitive file exposure** | Nginx config denies access to `/.*` (dotfiles). Docker image does not expose `.env`. |
| **Security headers** | Nginx adds `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`. |

---

## Performance

- **Eager loading** — `ProductRepository::paginate()` calls `->with('creator:id,name')` to avoid N+1 queries on the index page.
- **`withQueryString()`** — pagination links preserve `?search=` and `?status=` parameters automatically.
- **Spinner** — a CSS overlay hides the page until JavaScript has loaded, eliminating flash-of-unstyled-content.
- **OPcache** — compiled into the Docker image; caches byte-compiled PHP opcodes in shared memory.
- **Production caches** — `deploy.sh` runs `config:cache`, `route:cache`, and `view:cache` after every deployment.
- **Clone-based count queries** — the orders controller clones the base Eloquent Builder (not re-queries the DB from scratch) to compute per-tab counts efficiently.
- **FULLTEXT index** — `products(title, description)` supports keyword search without full table scans.

---

## Future Improvements

| Area | What to add |
|------|-------------|
| **Image uploads** | Product gallery using Laravel file storage + S3 driver; resizing via `spatie/laravel-medialibrary`. |
| **Full-text search** | Replace `LIKE` with Laravel Scout + Meilisearch for relevance-ranked, typo-tolerant search. |
| **Push notifications** | Broadcast order-status changes to customers via Reverb / Pusher WebSockets. |
| **API layer** | Versioned JSON API with Sanctum tokens to serve a React/Vue frontend or mobile app. |
| **Audit trail** | `spatie/laravel-activitylog` — record who changed what and when (enterprise compliance). |
| **Test suite** | Feature tests for OTP auth, CRUD, approval workflow, search, and order filtering using an in-memory SQLite DB. |
| **CI/CD pipeline** | GitHub Actions: Pint lint → PHPUnit → Docker build → push image → rolling Kubernetes deploy. |
| **Queue & events** | Move OTP emails and order notifications to queued `Mailable`/`Notification` classes so HTTP responses stay fast. |
| **Multi-tenancy** | Scope all data to a `Vendor` model enforced by a global Eloquent scope for SaaS use. |
| **Content-Security-Policy** | Strict `CSP` header via middleware to eliminate inline-script XSS risk. |
| **Delivery tracking** | Integrate with a logistics API (Shiprocket, Delhivery) to provide real-time delivery updates. |

---

## Project Structure

```
.
├── api/
│   └── index.php                           ← Vercel PHP runtime entry point
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   └── OtpLoginController.php  ← OTP request / verify / resend
│   │   │   ├── Admin/UserController.php    ← user management (admin only)
│   │   │   ├── Products/                   ← CRUD + approval
│   │   │   └── Shop/
│   │   │       ├── ShopController.php      ← product listing + search
│   │   │       ├── CartController.php      ← session-based cart
│   │   │       └── OrderController.php     ← order history + status filter
│   │   ├── Middleware/CheckRole.php        ← RBAC enforcement
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php       ← rate-limited auth
│   │       └── ProductRequest.php          ← validation + XSS sanitisation
│   ├── Models/
│   │   ├── User.php                        ← role constants, OTP relation
│   │   ├── Product.php                     ← search scope, soft deletes, approval
│   │   ├── OtpCode.php                     ← time-limited OTP records
│   │   ├── Order.php
│   │   └── OrderItem.php
│   ├── Mail/OtpMail.php                    ← Mailable for OTP delivery
│   ├── Repositories/
│   │   ├── Contracts/ProductRepositoryInterface.php
│   │   └── ProductRepository.php
│   ├── Services/ProductService.php         ← business rules
│   └── Providers/
│       ├── AppServiceProvider.php          ← Bootstrap5 pagination, password rules
│       └── RepositoryServiceProvider.php   ← interface → implementation binding
├── bootstrap/app.php                       ← middleware, providers, Vercel storage override
├── database/migrations/
├── database/seeders/
├── resources/views/
│   ├── layouts/shop.blade.php              ← sticky nav, FruGo branding
│   ├── auth/
│   │   ├── otp-request.blade.php
│   │   └── otp-verify.blade.php           ← dev-mode OTP display
│   ├── shop/{index,show,cart,checkout,orders}.blade.php
│   ├── products/{index,create,edit,show}.blade.php
│   └── vendor/pagination/bootstrap-5.blade.php ← custom circular pagination
├── routes/web.php
├── docker/nginx/default.conf
├── docker-compose.yml                      ← app + nginx + mysql + mailhog
├── Dockerfile
├── Makefile
├── deploy.sh
└── vercel.json                             ← Vercel routing + PHP runtime config
```
