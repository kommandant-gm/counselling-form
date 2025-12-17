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
        return [
            '2025-12-19' => ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'],
            '2025-12-22' => ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'],
            '2025-12-23' => ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'],
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
        return now()->lt('2025-12-24');
    }

    /**
     * Get total bookings count
     */
    public static function getTotalBookings(): int
    {
        return self::count();
    }
}
