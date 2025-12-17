# Architecture Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         User's Browser                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Alpine.js (Frontend Logic)                              │   │
│  │  - Form state management                                 │   │
│  │  - Real-time slot checking                               │   │
│  │  - Dynamic UI updates                                    │   │
│  └──────────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Tailwind CSS (Styling)                                  │   │
│  │  - Responsive design                                     │   │
│  │  - Modern animations                                     │   │
│  │  - Mobile-first approach                                 │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↕ HTTP/AJAX
┌─────────────────────────────────────────────────────────────────┐
│                    Laravel Application (Railway)                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Routes (web.php)                                        │   │
│  │  - GET  /              → Booking form                    │   │
│  │  - GET  /api/slots     → Available slots API            │   │
│  │  - POST /book          → Submit booking                  │   │
│  │  - GET  /admin         → Admin login                     │   │
│  │  - POST /admin/bookings → View bookings                  │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              ↕                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  BookingController                                       │   │
│  │  - index()         → Show booking form                   │   │
│  │  - getAvailableSlots() → Return available slots         │   │
│  │  - store()         → Save booking (with locks)           │   │
│  │  - adminLogin()    → Show admin login                    │   │
│  │  - adminBookings() → Show all bookings                   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              ↕                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Booking Model                                           │   │
│  │  - getAvailableDates()     → Return date/slot config    │   │
│  │  - getBookedSlots()        → Get booked slots for date  │   │
│  │  - isRegistrationOpen()    → Check if still open        │   │
│  │  - getTotalBookings()      → Count total bookings       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              ↕                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  SQLite Database (database.sqlite)                       │   │
│  │  ┌────────────────────────────────────────────────────┐  │   │
│  │  │ bookings                                           │  │   │
│  │  │ - id (primary key)                                 │  │   │
│  │  │ - name (varchar)                                   │  │   │
│  │  │ - date (date)                                      │  │   │
│  │  │ - time_slot (varchar)                              │  │   │
│  │  │ - created_at (timestamp)                           │  │   │
│  │  │ - updated_at (timestamp)                           │  │   │
│  │  │ - UNIQUE(date, time_slot) ← Prevents double-booking │  │   │
│  │  └────────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Data Flow

### Booking Flow
```
1. User visits form
   ↓
2. Browser loads Alpine.js + Form UI
   ↓
3. User selects date
   ↓
4. Alpine.js → AJAX → GET /api/available-slots?date=2025-12-19
   ↓
5. BookingController → getAvailableSlots()
   ↓
6. Query DB for booked slots on that date
   ↓
7. Return JSON: { slots: [{time: "09:00", available: true}, ...] }
   ↓
8. Alpine.js updates UI → Shows available/booked slots
   ↓
9. User selects slot and enters name
   ↓
10. User clicks "Confirm Booking"
    ↓
11. Alpine.js → AJAX → POST /book
    ↓
12. BookingController → store()
    ↓
13. Begin database transaction with row-level lock
    ↓
14. Check if slot still available
    ├─ Available → Save booking → Commit → Return success
    └─ Taken → Rollback → Return error
    ↓
15. Alpine.js receives response
    ├─ Success → Show confirmation screen
    └─ Error → Display error message
```

### Admin View Flow
```
1. Admin visits /admin
   ↓
2. Shows password form
   ↓
3. Admin enters password → POST /admin/bookings
   ↓
4. BookingController validates password
   ├─ Valid → Query all bookings → Return view
   └─ Invalid → Show error
   ↓
5. Display bookings grouped by date
   - Statistics (total, remaining, capacity)
   - Print-friendly layout
```

---

## Key Features Implementation

### 1. Real-time Availability
- **Frontend**: Alpine.js watches date selection
- **API Call**: Fetches current availability
- **Backend**: Queries DB for booked slots
- **Update**: Dynamically enables/disables buttons

### 2. Race Condition Prevention
```php
DB::transaction(function () {
    // Lock the row for reading
    $exists = Booking::where('date', $date)
        ->where('time_slot', $time)
        ->lockForUpdate()
        ->exists();
    
    if (!$exists) {
        // Safe to book - no one else can book this slot
        Booking::create([...]);
    }
});
```

### 3. Auto-close After December 23
```php
public static function isRegistrationOpen(): bool
{
    return now()->lt('2025-12-24');
}

// In controller:
if (!Booking::isRegistrationOpen()) {
    return view('closed');
}
```

### 4. Responsive Design
- **Mobile-first**: Tailwind breakpoints (sm:, md:, lg:)
- **Touch-friendly**: Large tap targets
- **Flexible layouts**: Grid → Single column on mobile

---

## Database Design

### Why SQLite?
- ✅ **Zero configuration** - No separate database server
- ✅ **Perfect for small apps** - Max 18 bookings only
- ✅ **Railway compatible** - Works out of the box
- ✅ **File-based** - Single database.sqlite file
- ✅ **ACID compliant** - Supports transactions

### Schema Decisions

**Unique Constraint on (date, time_slot)**
- Ensures no double-booking at database level
- Works even if application logic fails
- Fast lookups for availability checks

**Timestamps (created_at, updated_at)**
- Track when bookings were made
- Useful for admin reporting
- Automatic via Laravel

---

## Security Measures

1. **CSRF Protection**
   - Laravel's built-in CSRF middleware
   - Meta tag in HTML + header in AJAX

2. **SQL Injection Prevention**
   - Eloquent ORM (parameterized queries)
   - Never raw SQL with user input

3. **Admin Password Protection**
   - Environment variable (not in code)
   - Server-side validation
   - No JWT/sessions (simple password check)

4. **Input Validation**
   - Laravel validation rules
   - Frontend validation (Alpine.js)
   - Backend validation (BookingController)

5. **Rate Limiting**
   - Laravel's default rate limiting
   - Prevents spam submissions

---

## Technology Choices

### Why Alpine.js?
- ✅ Lightweight (15KB vs React's 100KB+)
- ✅ No build step needed
- ✅ Works with Blade templates
- ✅ Perfect for simple interactivity
- ✅ You're already familiar with it!

### Why Tailwind CSS?
- ✅ Rapid development
- ✅ Consistent design system
- ✅ Responsive utilities built-in
- ✅ CDN available (no compilation needed)
- ✅ Modern, professional look

### Why Laravel 11?
- ✅ Mature, battle-tested framework
- ✅ Excellent documentation
- ✅ Built-in database migrations
- ✅ Eloquent ORM (elegant, secure)
- ✅ Easy deployment

### Why Railway?
- ✅ GitHub integration (auto-deploy)
- ✅ Laravel auto-detection
- ✅ Free tier ($5/month credit)
- ✅ Zero configuration
- ✅ Built-in SSL/HTTPS

---

## Performance Considerations

### Database Queries
- **Indexed fields**: `(date, time_slot)` unique constraint creates index
- **Minimal queries**: One query per slot check
- **Eager loading**: Not needed (simple queries)

### Frontend Performance
- **CDN resources**: Alpine.js and Tailwind from CDN (fast, cached)
- **No build step**: Instant page loads
- **Lazy loading**: Slots load only when date selected
- **Optimized animations**: CSS-only (GPU accelerated)

### Scalability
- **Current capacity**: 18 bookings max
- **SQLite limit**: Handles thousands of reads/sec
- **Bottleneck**: None for this use case
- **If scaling needed**: Easy migration to MySQL/PostgreSQL

---

## Error Handling

### Frontend Errors
```javascript
try {
    const response = await fetch('/book', {...});
    const data = await response.json();
    
    if (data.success) {
        // Show success
    } else {
        this.error = data.message; // User-friendly message
    }
} catch (error) {
    this.error = 'An error occurred. Please try again.';
}
```

### Backend Errors
```php
try {
    DB::transaction(function () {
        // Booking logic
    });
} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'message' => 'An error occurred. Please try again.',
    ], 500);
}
```

### Database Constraints
- Unique constraint violation → Returns error
- Frontend shows: "Slot has just been taken"
- User can select another slot

---

## Maintenance & Updates

### Changing Dates
1. Edit `app/Models/Booking.php`
2. Update `getAvailableDates()` array
3. Git commit + push
4. Railway auto-deploys

### Changing Capacity
1. Modify max count check in `BookingController`
2. Update frontend `remainingSlots` logic
3. Deploy

### Backing Up Data
- Admin dashboard → Print → Save as PDF
- Or export SQLite file from Railway

---

## Future Enhancements (Not Implemented)

### Possible Additions:
1. **Email confirmation** - Send booking confirmation
2. **SMS reminders** - Remind users before session
3. **Cancellation feature** - Allow users to cancel
4. **Multi-language support** - i18n for different languages
5. **Calendar export** - iCal download for bookings
6. **Analytics dashboard** - Track booking patterns

### Why Not Included:
- Keeps it simple and focused
- Faster to deploy
- Lower maintenance
- Meets core requirements perfectly

---

## Monitoring & Debugging

### Railway Logs
- Real-time log streaming
- Error tracking
- Performance metrics

### Laravel Logs
- `storage/logs/laravel.log`
- Detailed error messages
- Stack traces

### Browser Console
- Network tab for AJAX calls
- Console for JavaScript errors
- Alpine.js devtools available

---

**This architecture is designed for:**
- ✅ **Simplicity** - Easy to understand and maintain
- ✅ **Reliability** - Race condition protection, ACID compliance
- ✅ **Performance** - Fast loads, instant interactions
- ✅ **Security** - Protected against common vulnerabilities
- ✅ **User Experience** - Beautiful, intuitive, responsive

Perfect for your counselling session booking needs! 🎯
