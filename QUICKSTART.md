# Quick Start Guide 🚀

Get your counselling form live in **10 minutes**!

---

## Option 1: Deploy Directly to Railway (Fastest ⚡)

### Step 1: Create GitHub Repo
```bash
cd counselling-form
git init
git branch -M main
git add .
git commit -m "Initial commit"
```

Go to GitHub → New Repository → Name it `counselling-form` → Create

```bash
git remote add origin https://github.com/YOUR-USERNAME/counselling-form.git
git push -u origin main
```

### Step 2: Deploy to Railway

1. Go to [railway.app](https://railway.app)
2. Login with GitHub
3. **New Project** → **Deploy from GitHub repo**
4. Select your `counselling-form` repo
5. Add these environment variables:
   ```
   APP_NAME=Counselling Form
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=sqlite
   ADMIN_PASSWORD=your-password-here
   ```
6. Generate domain in Settings → Domains

**Done!** ✅ Your form is live!

---

## Option 2: Run Locally First (Test Before Deploy)

### Requirements
- PHP 8.2+
- Composer
- Git

### Quick Setup
```bash
cd counselling-form
./setup.sh
```

Or manually:
```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Visit: `http://localhost:8000`
Admin: `http://localhost:8000/admin` (password: `admin123`)

---

## What You Get

### Public Form
- **URL**: `https://your-app.railway.app/`
- Beautiful, modern UI
- Real-time slot availability
- Mobile responsive
- Auto-closes after Dec 23, 2025

### Admin Dashboard
- **URL**: `https://your-app.railway.app/admin`
- View all bookings
- Grouped by date
- Statistics overview
- Print-friendly
- Password protected

---

## Key Files to Know

```
counselling-form/
├── .env.example              ← Environment config
├── README.md                 ← Full documentation
├── DEPLOYMENT.md             ← Detailed deploy guide
├── routes/web.php            ← All routes
├── app/
│   ├── Http/Controllers/
│   │   └── BookingController.php  ← Main logic
│   └── Models/
│       └── Booking.php       ← Database model
└── resources/views/
    ├── booking-form.blade.php     ← Main form
    ├── admin-bookings.blade.php   ← Admin view
    ├── admin-login.blade.php      ← Admin login
    └── closed.blade.php           ← Closed message
```

---

## Customization

### Change Dates
Edit `app/Models/Booking.php`:
```php
public static function getAvailableDates(): array
{
    return [
        '2025-12-19' => ['09:00', '10:00', ...],
        // Add more dates
    ];
}
```

### Change Admin Password
Railway Dashboard → Variables → `ADMIN_PASSWORD`

### Change Design Colors
Edit views in `resources/views/` - all styling uses Tailwind CSS

---

## Testing Checklist

- [ ] Form loads correctly
- [ ] Can select a date
- [ ] Time slots appear
- [ ] Can submit booking
- [ ] Success message shows
- [ ] Admin login works
- [ ] Admin dashboard shows booking
- [ ] Mobile responsive

---

## Troubleshooting

**Form not loading?**
→ Check Railway logs for errors

**Admin password not working?**
→ Verify `ADMIN_PASSWORD` in Railway variables

**Slots not updating?**
→ Clear browser cache and refresh

**Need help?**
→ Read [DEPLOYMENT.md](DEPLOYMENT.md) for detailed guide

---

## Support

- 📖 Full docs: [README.md](README.md)
- 🚀 Deploy guide: [DEPLOYMENT.md](DEPLOYMENT.md)
- 🐛 Issues: Check Railway logs first

---

**That's it!** Your counselling form is ready to go! 🎉

**Next:** Share your Railway URL with your team and start collecting bookings!
