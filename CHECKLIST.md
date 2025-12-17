# Deployment & Testing Checklist ✅

Use this checklist to ensure everything is working before going live.

---

## Pre-Deployment Checklist

### 1. GitHub Setup
- [ ] Created GitHub repository
- [ ] Repository is named `counselling-form` (or your preferred name)
- [ ] All files are committed and pushed
- [ ] Repository is accessible (public or private with Railway access)

### 2. Code Review
- [ ] Admin password changed from default (`admin123`)
- [ ] Dates are correct in `app/Models/Booking.php`
- [ ] Time slots are correct
- [ ] Closing date is correct (currently Dec 23, 2025)
- [ ] Form title is appropriate for your company

### 3. Railway Configuration
- [ ] Railway account created and linked to GitHub
- [ ] Project created and connected to repository
- [ ] Environment variables set:
  - [ ] `APP_NAME`
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `DB_CONNECTION=sqlite`
  - [ ] `ADMIN_PASSWORD` (strong password)
- [ ] Domain generated or custom domain configured

---

## Post-Deployment Testing

### Phase 1: Basic Access
- [ ] Site loads without errors
- [ ] HTTPS is working (Railway provides SSL automatically)
- [ ] Mobile view looks good
- [ ] Desktop view looks good

### Phase 2: Booking Form Testing

#### Form Display
- [ ] Title displays correctly
- [ ] "X slots remaining" shows "18 slots remaining"
- [ ] Date dropdown shows all 3 dates
- [ ] Dates are formatted correctly

#### Date Selection
- [ ] Select December 19, 2025
  - [ ] Time slots appear
  - [ ] All 6 slots show as available
  - [ ] Slots are clickable
  - [ ] Selected slot highlights

#### Booking Submission
- [ ] Fill in name: "Test User 1"
- [ ] Select date: December 19, 2025
- [ ] Select time: 9:00 AM
- [ ] Click "Confirm Booking"
- [ ] Success message appears
- [ ] Confirmation shows correct name, date, and time
- [ ] "Slots remaining" decreased to 17

#### Duplicate Booking Prevention
- [ ] Try to book the same slot (Dec 19, 9 AM)
- [ ] Slot should show as "Booked"
- [ ] Slot button should be disabled
- [ ] Cannot select the booked slot

#### Multiple Bookings
- [ ] Create another booking with different slot
- [ ] Success message appears
- [ ] "Slots remaining" decreased to 16

#### Form Validation
- [ ] Try to submit without name
  - [ ] Error message appears
- [ ] Try to submit without date
  - [ ] Error message appears
- [ ] Try to submit without time slot
  - [ ] Error message appears

---

### Phase 3: Admin Dashboard Testing

#### Login
- [ ] Visit `/admin`
- [ ] Admin login page loads
- [ ] Enter incorrect password
  - [ ] Error message appears
- [ ] Enter correct password
  - [ ] Redirects to bookings dashboard

#### Dashboard Display
- [ ] Dashboard loads successfully
- [ ] Statistics card shows:
  - [ ] Total Bookings (should be 2 if you followed above tests)
  - [ ] Remaining Slots (should be 16)
  - [ ] Capacity percentage (should be ~11.1%)

#### Bookings Table
- [ ] Bookings are grouped by date
- [ ] Each booking shows:
  - [ ] Time slot
  - [ ] Name
  - [ ] Booked timestamp
- [ ] Data matches your test bookings

#### Print Functionality
- [ ] Click "Print" button
- [ ] Print preview appears
- [ ] Layout looks good for printing
- [ ] Cancel print (don't actually print)

#### Navigation
- [ ] Click "Back to Login"
- [ ] Returns to login page

---

### Phase 4: Edge Cases & Race Conditions

#### Simultaneous Bookings (Advanced)
- [ ] Open form in two browser tabs/windows
- [ ] In both tabs, select same date and time
- [ ] Try to submit both simultaneously
- [ ] Only one should succeed
- [ ] Other should show "slot has just been taken" error

#### Maximum Capacity
- [ ] Book all remaining slots (if testing thoroughly)
- [ ] After 18th booking, form should show "all slots fully booked"
- [ ] 19th booking attempt should fail

#### Browser Compatibility
- [ ] Test on Chrome
- [ ] Test on Firefox
- [ ] Test on Safari (if available)
- [ ] Test on mobile browser

---

### Phase 5: Mobile Responsiveness

#### Mobile View (use browser dev tools or actual mobile)
- [ ] Form fits screen without horizontal scroll
- [ ] Text is readable
- [ ] Buttons are easily tappable
- [ ] Date dropdown works
- [ ] Time slot buttons are large enough
- [ ] Success screen displays well
- [ ] Admin dashboard is readable
- [ ] Admin table scrolls horizontally if needed

---

### Phase 6: Closing Date Logic

**Note**: Only test this if you want to verify the auto-close feature

#### Test Closing (Optional - requires date change)
- [ ] Change closing date to current date in `app/Models/Booking.php`
- [ ] Deploy changes
- [ ] Visit form
- [ ] Should show "Registration Closed" page
- [ ] Change date back to Dec 23, 2025
- [ ] Deploy again

---

## Production Readiness Checklist

### Security
- [ ] Admin password is strong and unique
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` is set
- [ ] HTTPS is enabled (Railway does this automatically)

### Performance
- [ ] Page loads in under 3 seconds
- [ ] Slot loading is instant
- [ ] Form submission is smooth
- [ ] No console errors in browser

### User Experience
- [ ] Clear instructions on form
- [ ] Success message is encouraging
- [ ] Error messages are helpful
- [ ] Mobile experience is smooth
- [ ] Print layout is clean

### Data Integrity
- [ ] Cannot double-book a slot
- [ ] All bookings are saved correctly
- [ ] Admin can view all bookings
- [ ] Timestamps are correct

---

## Go-Live Checklist

### Before Sharing URL
- [ ] All tests above passed
- [ ] Admin password is secure
- [ ] You can access admin dashboard
- [ ] Railway domain is noted down
- [ ] Backup plan in place (if needed)

### When Sharing
- [ ] Share correct URL: `https://your-app.railway.app/`
- [ ] Not admin URL: `https://your-app.railway.app/admin`
- [ ] Provide deadline (Dec 23, 2025)
- [ ] Mention total slots available (18)
- [ ] Note that sessions are first-come, first-served

### Monitoring (First Day)
- [ ] Check admin dashboard regularly
- [ ] Monitor Railway logs for errors
- [ ] Test a real booking yourself
- [ ] Confirm emails/communications work (if any)

---

## Common Issues & Quick Fixes

### Issue: "500 Server Error"
**Check**: Railway logs for PHP errors
**Fix**: Usually missing environment variable

### Issue: Slots not loading
**Check**: Browser console for JavaScript errors
**Fix**: Clear cache, hard refresh (Ctrl+Shift+R)

### Issue: Admin password not working
**Check**: Railway environment variables
**Fix**: Verify `ADMIN_PASSWORD` is set correctly, no extra spaces

### Issue: Form not responsive on mobile
**Check**: Browser device emulation
**Fix**: Tailwind CSS CDN loaded? Check view source

### Issue: Double bookings possible
**Check**: Database migration ran?
**Fix**: Ensure unique constraint exists on (date, time_slot)

---

## Success Criteria

Your deployment is successful when:

✅ **Functional**
- Form loads and accepts bookings
- Admin can view bookings
- Race conditions are prevented
- Form closes after deadline

✅ **Accessible**
- Works on mobile and desktop
- All browsers supported
- HTTPS enabled
- Fast load times

✅ **Secure**
- Admin password required
- No console errors exposing data
- CSRF protection active
- Input validation working

✅ **User-Friendly**
- Clear instructions
- Good error messages
- Smooth interactions
- Professional appearance

---

## Final Pre-Launch Steps

1. [ ] Complete all tests above
2. [ ] Take screenshots of:
   - [ ] Booking form
   - [ ] Success message
   - [ ] Admin dashboard
3. [ ] Write down admin credentials in a secure place
4. [ ] Prepare announcement message with URL
5. [ ] Set calendar reminder for Dec 23 (check if all slots filled)
6. [ ] Bookmark admin URL for easy access

---

## After Launch

### Daily Checks (Recommended)
- Check admin dashboard for new bookings
- Monitor remaining slots
- Look for any unusual patterns

### If Issues Arise
1. Check Railway logs first
2. Review admin dashboard
3. Test form yourself
4. Check GitHub for any accidental changes

### When Full (18 bookings)
- No action needed, form handles it automatically
- You can still access admin to view bookings
- Form will show "all slots fully booked"

### After Dec 23, 2025
- Form automatically closes
- Shows "Registration Closed" message
- Admin dashboard still accessible
- Export/print bookings if needed

---

## Contact Info

**For Support**:
- Railway Dashboard: [railway.app](https://railway.app)
- Railway Logs: Project → Deployments → Logs
- GitHub Repo: Your repository URL
- Laravel Docs: [laravel.com/docs](https://laravel.com/docs)

---

**Ready to go live!** 🚀

Once you've completed this checklist, your counselling form is production-ready and can handle your 18 bookings smoothly!

**Good luck with your counselling sessions!** 🎯
