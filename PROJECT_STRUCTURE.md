# Project Structure

Complete file structure of the Counselling Form application.

```
counselling-form/
│
├── 📄 README.md                    # Complete documentation
├── 📄 QUICKSTART.md                # 10-minute setup guide
├── 📄 DEPLOYMENT.md                # Detailed Railway deployment guide
├── 📄 ARCHITECTURE.md              # System architecture overview
├── 📄 composer.json                # PHP dependencies
├── 📄 .env.example                 # Environment variables template
├── 📄 .gitignore                   # Git ignore rules
├── 📄 artisan                      # Laravel CLI tool
├── 📄 setup.sh                     # Quick setup script
├── 📄 Procfile                     # Railway deployment config
├── 📄 railway.json                 # Railway build settings
│
├── 📁 app/                         # Application core
│   ├── 📁 Http/
│   │   └── 📁 Controllers/
│   │       └── 📄 BookingController.php    # Main controller (all routes)
│   └── 📁 Models/
│       └── 📄 Booking.php                   # Database model
│
├── 📁 bootstrap/                   # Laravel bootstrap
│   └── 📄 app.php                 # Application bootstrap
│
├── 📁 config/                      # Configuration files
│   ├── 📄 app.php                 # App config (timezone, locale, admin password)
│   └── 📄 database.php            # Database config (SQLite setup)
│
├── 📁 database/                    # Database files
│   ├── 📄 database.sqlite         # SQLite database (auto-created)
│   └── 📁 migrations/
│       └── 📄 2024_01_01_000001_create_bookings_table.php  # Create bookings table
│
├── 📁 public/                      # Public web root
│   └── 📄 index.php               # Application entry point
│
├── 📁 resources/                   # Frontend resources
│   └── 📁 views/                  # Blade templates
│       ├── 📄 booking-form.blade.php       # Main booking form
│       ├── 📄 admin-login.blade.php        # Admin password login
│       ├── 📄 admin-bookings.blade.php     # Admin dashboard
│       └── 📄 closed.blade.php             # Registration closed page
│
├── 📁 routes/                      # Route definitions
│   ├── 📄 web.php                 # Web routes (all app routes)
│   └── 📄 console.php             # Artisan commands
│
└── 📁 storage/                     # App storage (logs, cache, sessions)
    ├── 📁 app/
    ├── 📁 framework/
    │   ├── 📁 cache/
    │   ├── 📁 sessions/
    │   └── 📁 views/
    └── 📁 logs/
```

---

## File Descriptions

### 📋 Documentation Files

| File | Purpose | When to Read |
|------|---------|-------------|
| `README.md` | Complete documentation, features, troubleshooting | First time setup, reference |
| `QUICKSTART.md` | Get started in 10 minutes | Quick deployment |
| `DEPLOYMENT.md` | Step-by-step Railway deployment | When deploying to production |
| `ARCHITECTURE.md` | System design, data flow, tech choices | Understanding how it works |

### ⚙️ Configuration Files

| File | Purpose | When to Edit |
|------|---------|------------|
| `.env.example` | Environment variables template | Reference for required variables |
| `composer.json` | PHP dependencies | Adding Laravel packages |
| `railway.json` | Railway deployment settings | Changing build/deploy commands |
| `Procfile` | Railway start command | Changing startup behavior |

### 🎯 Core Application Files

| File | Purpose | When to Edit |
|------|---------|------------|
| `routes/web.php` | All route definitions | Adding new routes |
| `app/Http/Controllers/BookingController.php` | All business logic | Modifying booking logic |
| `app/Models/Booking.php` | Database model | Changing dates, slots, logic |
| `config/database.php` | Database connection | Switching database type |
| `config/app.php` | App settings | Changing timezone, admin password |

### 🎨 Frontend Files

| File | Purpose | When to Edit |
|------|---------|------------|
| `resources/views/booking-form.blade.php` | Main booking UI | Changing form design |
| `resources/views/admin-bookings.blade.php` | Admin dashboard | Modifying admin view |
| `resources/views/admin-login.blade.php` | Admin login page | Changing login UI |
| `resources/views/closed.blade.php` | Closed registration page | Modifying closed message |

### 🗄️ Database Files

| File | Purpose | When to Edit |
|------|---------|------------|
| `database/database.sqlite` | SQLite database | Never (auto-managed) |
| `database/migrations/...create_bookings_table.php` | Database schema | Adding fields to bookings |

---

## Key Code Locations

### Add New Route
**File**: `routes/web.php`
```php
Route::get('/your-route', [YourController::class, 'method']);
```

### Change Available Dates/Times
**File**: `app/Models/Booking.php`
```php
public static function getAvailableDates(): array
{
    return [
        '2025-12-19' => ['09:00', '10:00', '11:00'],
        // Add more dates here
    ];
}
```

### Modify Booking Logic
**File**: `app/Http/Controllers/BookingController.php`
```php
public function store(Request $request)
{
    // Booking logic here
}
```

### Change Form Design
**File**: `resources/views/booking-form.blade.php`
- Line 1-120: HTML structure
- Alpine.js logic in `x-data` attribute
- Tailwind CSS classes for styling

### Change Admin Dashboard
**File**: `resources/views/admin-bookings.blade.php`
- Statistics cards
- Bookings table
- Print layout

---

## Important: Files NOT to Edit

❌ **Do not edit these files** (auto-generated or managed):
- `vendor/` folder (Composer packages)
- `storage/framework/` (Laravel cache)
- `storage/logs/` (Log files)
- `database/database.sqlite` (managed by migrations)
- `bootstrap/cache/` (Laravel cache)

---

## File Sizes (Approximate)

```
Total Project Size: ~100 MB (mostly vendor/)
Without vendor/:     ~50 KB

Breakdown:
- Documentation:     ~30 KB (README, guides)
- Application code:  ~15 KB (controllers, models)
- Views (Blade):     ~25 KB (HTML templates)
- Config:            ~10 KB (settings)
- Routes:            ~2 KB (route definitions)
```

---

## Dependencies

### Required (from composer.json)
```json
{
  "php": "^8.2",
  "laravel/framework": "^11.0"
}
```

### Frontend (CDN)
- Alpine.js 3.15.0
- Tailwind CSS 3.4
- Google Fonts (Plus Jakarta Sans, Playfair Display)

---

## Development Workflow

### Making Changes

1. **Edit relevant file** (see table above)
2. **Test locally** (if running local server)
3. **Commit changes**:
   ```bash
   git add .
   git commit -m "Description of changes"
   ```
4. **Push to GitHub**:
   ```bash
   git push
   ```
5. **Railway auto-deploys** (2-3 minutes)

### File Change Examples

#### Example 1: Change Admin Password
**File**: `.env` or Railway Variables
```env
ADMIN_PASSWORD=new-secure-password
```

#### Example 2: Add New Time Slot
**File**: `app/Models/Booking.php`
```php
'2025-12-19' => ['09:00', '10:00', '11:00', '12:00'], // Added 12:00
```

#### Example 3: Change Form Title
**File**: `resources/views/booking-form.blade.php`
```html
<h1 class="font-display text-3xl...">
    Your New Title Here  <!-- Change this -->
</h1>
```

---

## Folder Permissions (Railway)

Railway automatically handles permissions. On local development:

```bash
# Storage folders need write access
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Database needs write access
chmod 664 database/database.sqlite
```

---

## Environment-Specific Files

### Local Development
- Uses `.env` file
- SQLite database in `database/database.sqlite`
- Debug mode enabled

### Production (Railway)
- Uses Railway environment variables
- Same SQLite structure
- Debug mode disabled
- Caching enabled

---

## What Gets Deployed to Railway

✅ **Included in deployment**:
- All PHP files (app, config, routes)
- All Blade views (resources/views)
- Migration files
- Public assets

❌ **Excluded from deployment**:
- `vendor/` (rebuilt on Railway)
- `.env` (uses Railway variables)
- `storage/` contents (recreated)
- `.git/` folder

---

## Quick Reference

### Most Edited Files (90% of customization)
1. `app/Models/Booking.php` - Dates and time slots
2. `resources/views/booking-form.blade.php` - Form design
3. `resources/views/admin-bookings.blade.php` - Admin view
4. `.env` or Railway Variables - Configuration

### Rarely Edited Files
- `routes/web.php` - Only if adding features
- `app/Http/Controllers/BookingController.php` - Only if changing logic
- `config/` files - Usually fine as-is
- `database/migrations/` - Only if changing schema

---

## Where to Find What

**Need to change dates?**
→ `app/Models/Booking.php`

**Need to modify form UI?**
→ `resources/views/booking-form.blade.php`

**Need to change admin password?**
→ `.env` file or Railway Variables

**Need to add validation?**
→ `app/Http/Controllers/BookingController.php`

**Need to change database fields?**
→ Create new migration file

**Need to modify routes?**
→ `routes/web.php`

---

This structure is designed to be:
- ✅ **Simple** - Few files to manage
- ✅ **Organized** - Clear separation of concerns
- ✅ **Maintainable** - Easy to find and edit files
- ✅ **Scalable** - Can grow if needed

Ready to customize! 🚀
