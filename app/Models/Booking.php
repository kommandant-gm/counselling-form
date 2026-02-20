<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Get all available dates with their time slots
     */
    public static function getAvailableDates(): array
    {
        $slots = self::getDefaultSlots();

        return [
            '2026-02-23' => $slots,
            '2026-02-24' => $slots,
            '2026-02-25' => $slots,
            '2026-02-26' => $slots,
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
        return now()->lt('2026-02-27');
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
        return ['09:00', '10:00', '11:00', '14:00'];
    }
}
