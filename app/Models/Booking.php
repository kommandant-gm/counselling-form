<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'date',
        'time_slot',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Keep production booking data aligned with the active schedule.
     */
    public static function syncProductionSlots(): void
    {
        if (!app()->environment('production')) {
            return;
        }

        // Always remove historical bookings from past dates.
        self::whereDate('date', '<', now()->toDateString())->delete();

        // Reset once when the configured schedule changes after deployment.
        $currentScheduleHash = md5(json_encode(self::getAvailableDates()));
        $markerPath = storage_path('app/booking-schedule-hash.txt');
        $lastScheduleHash = File::exists($markerPath)
            ? trim((string) File::get($markerPath))
            : null;

        if ($lastScheduleHash !== $currentScheduleHash) {
            self::query()->delete();

            File::ensureDirectoryExists(dirname($markerPath));
            File::put($markerPath, $currentScheduleHash);
        }
    }

    /**
     * Get all available dates with their time slots
     */
    public static function getAvailableDates(): array
    {
        $slots = self::getDefaultSlots();

        return [
            '2026-04-14' => $slots,
            '2026-04-15' => $slots,
            '2026-04-16' => $slots,
        ];
    }

    /**
     * Get booked slots for a specific date
     */
    public static function getBookedSlots(string $date): array
    {
        return self::whereDate('date', $date)
            ->pluck('time_slot')
            ->toArray();
    }

    /**
     * Check if registration is still open
     */
    public static function isRegistrationOpen(): bool
    {
        return now()->lt('2026-04-17');
    }

    /**
     * Get total bookings count
     */
    public static function getTotalBookings(): int
    {
        $dates = array_keys(self::getAvailableDates());

        return self::whereIn('date', $dates)->count();
    }

    /**
     * Get total available slots across all dates
     */
    public static function getTotalSlots(): int
    {
        return array_sum(array_map('count', self::getAvailableDates()));
    }

    private static function getDefaultSlots(): array
    {
        return ['09:00', '10:00', '11:00', '14:30'];
    }
}
