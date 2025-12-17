# Counselling Session Booking Form

A modern, mobile-responsive Laravel application for booking counselling sessions with real-time slot availability.

## Features

✅ **Modern UI** - Beautiful, professional design with smooth animations
✅ **Mobile Responsive** - Works perfectly on all devices
✅ **Real-time Updates** - Instant slot availability checking
✅ **Race Condition Protection** - Prevents double bookings
✅ **Auto-close** - Automatically closes after December 23, 2025
✅ **Admin Dashboard** - View all bookings with password protection
✅ **SQLite Database** - No external database required

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Alpine.js 3.15, Tailwind CSS 3.4
- **Database**: SQLite
- **Fonts**: Plus Jakarta Sans, Playfair Display
- **Hosting**: Railway

## Available Dates & Time Slots

- **December 19, 2025**: 9 AM, 10 AM, 11 AM, 2 PM, 3 PM, 4 PM
- **December 22, 2025**: 9 AM, 10 AM, 11 AM, 2 PM, 3 PM, 4 PM
- **December 23, 2025**: 9 AM, 10 AM, 11 AM, 2 PM, 3 PM, 4 PM

**Total Capacity**: 18 bookings (one per time slot)

## Quick Setup

### 1. Clone Repository

```bash
git clone https://github.com/YOUR-USERNAME/counselling-form.git
cd counselling-form
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate
```

### 5. Run Locally

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Deploying to Railway

### Step 1: Push to GitHub

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/counselling-form.git
git push -u origin main
```

### Step 2: Deploy on Railway

1. Go to [railway.app](https://railway.app)
2. Sign in with GitHub
3. Click **"New Project"**
4. Click **"Deploy from GitHub repo"**
5. Select your `counselling-form` repository
6. Railway will auto-detect Laravel

### Step 3: Configure Environment Variables

In Railway dashboard, add these variables:

```env
APP_NAME="Counselling Form"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx  # Railway auto-generates this
APP_URL=https://your-app.up.railway.app
DB_CONNECTION=sqlite
ADMIN_PASSWORD=your-secure-password
```

### Step 4: Deploy

Railway will automatically:
- Install dependencies
- Run migrations
- Start the application

Your app will be live at: `https://your-app.up.railway.app`

## Routes

### Public Routes
- `/` - Booking form
- `/api/available-slots?date=YYYY-MM-DD` - Get available slots for a date

### Admin Routes
- `/admin` - Admin login page
- `/admin/bookings` (POST) - View all bookings (requires password)

## Admin Access

**Default Password**: `admin123` (change this in `.env` file!)

To change admin password:
1. Update `ADMIN_PASSWORD` in `.env`
2. On Railway, update the environment variable

## Database Schema

### Bookings Table

| Column     | Type      | Description                |
|------------|-----------|----------------------------|
| id         | INTEGER   | Primary key                |
| name       | VARCHAR   | Attendee name              |
| date       | DATE      | Session date               |
| time_slot  | VARCHAR   | Session time (HH:MM)       |
| created_at | TIMESTAMP | Booking timestamp          |
| updated_at | TIMESTAMP | Last update timestamp      |

**Unique Constraint**: `(date, time_slot)` - Prevents double bookings

## Features Breakdown

### 1. Booking Form
- Name input validation
- Date selection dropdown
- Dynamic time slot buttons
- Real-time availability checking
- Loading states and animations
- Success confirmation screen

### 2. Admin Dashboard
- Password-protected access
- View all bookings grouped by date
- Statistics overview (total bookings, remaining slots, capacity percentage)
- Print-friendly layout
- Responsive table design

### 3. Auto-close Logic
- Form automatically closes after December 23, 2025
- Shows "Registration Closed" page
- No manual intervention needed

### 4. Race Condition Prevention
- Database transaction with row-level locking
- Prevents simultaneous bookings of the same slot
- Returns friendly error if slot is taken

## Customization

### Change Available Dates

Edit `app/Models/Booking.php`:

```php
public static function getAvailableDates(): array
{
    return [
        '2025-12-19' => ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'],
        // Add more dates here
    ];
}
```

### Change Closing Date

Edit `app/Models/Booking.php`:

```php
public static function isRegistrationOpen(): bool
{
    return now()->lt('2025-12-24'); // Change date here
}
```

### Change Design Colors

All Tailwind classes are in the Blade templates:
- `resources/views/booking-form.blade.php`
- `resources/views/admin-bookings.blade.php`
- `resources/views/admin-login.blade.php`
- `resources/views/closed.blade.php`

## Troubleshooting

### SQLite Database Not Found

```bash
touch database/database.sqlite
php artisan migrate
```

### Permission Issues on Railway

Railway automatically sets up permissions. If issues occur:
1. Check Railway logs
2. Ensure `database/` directory exists
3. Verify `DB_CONNECTION=sqlite` in environment variables

### Slots Not Updating

Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

### Admin Password Not Working

1. Check `.env` file has `ADMIN_PASSWORD` set
2. On Railway, verify environment variable is set
3. Restart the application

## Project Structure

```
counselling-form/
├── app/
│   ├── Http/Controllers/
│   │   └── BookingController.php
│   └── Models/
│       └── Booking.php
├── database/
│   ├── migrations/
│   │   └── 2024_01_01_000001_create_bookings_table.php
│   └── database.sqlite
├── resources/views/
│   ├── booking-form.blade.php
│   ├── admin-login.blade.php
│   ├── admin-bookings.blade.php
│   └── closed.blade.php
├── routes/
│   └── web.php
├── .env.example
├── composer.json
├── Procfile
└── railway.json
```

## Security Notes

- **Change default admin password** in production
- Form uses CSRF protection
- SQL injection protected by Eloquent ORM
- Race conditions prevented with database locks
- Input validation on all fields

## Support

For issues or questions:
1. Check Railway logs for errors
2. Review Laravel logs in `storage/logs/`
3. Verify environment variables are set correctly

## License

MIT License - Free to use and modify

---

**Built with Laravel 11 + Alpine.js + Tailwind CSS**
