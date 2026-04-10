<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct()
    {
        Booking::syncProductionSlots();
    }

    /**
     * Show the booking form
     */
    public function index()
    {
        if (!Booking::isRegistrationOpen()) {
            return view('closed');
        }

        $availableDates = Booking::getAvailableDates();
        $totalBookings = Booking::getTotalBookings();
        $totalSlots = Booking::getTotalSlots();
        $remainingSlots = max($totalSlots - $totalBookings, 0);

        return view('booking-form', compact('availableDates', 'remainingSlots', 'totalSlots'));
    }

    /**
     * Get available slots for a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', Rule::in(array_keys(Booking::getAvailableDates()))],
        ]);

        $allSlots = Booking::getAvailableDates()[$request->date] ?? [];
        $bookedSlots = Booking::getBookedSlots($request->date);

        $availableSlots = array_map(function ($slot) use ($bookedSlots) {
            return [
                'time' => $slot,
                'available' => !in_array($slot, $bookedSlots),
            ];
        }, $allSlots);

        return response()->json([
            'slots' => $availableSlots,
        ]);
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        if (!Booking::isRegistrationOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration has closed.',
            ], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => ['required', 'date', Rule::in(array_keys(Booking::getAvailableDates()))],
            'time_slot' => 'required|string',
        ]);

        try {
            // Use transaction to prevent race conditions
            DB::beginTransaction();

            // Check if slot is still available
            $exists = Booking::where('date', $request->date)
                ->where('time_slot', $request->time_slot)
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, this time slot has just been taken. Please select another slot.',
                ], 422);
            }

            // Check if we've reached the maximum bookings for the active dates
            if (Booking::getTotalBookings() >= Booking::getTotalSlots()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, all time slots are now fully booked.',
                ], 422);
            }

            $booking = Booking::create([
                'name' => $request->name,
                'date' => $request->date,
                'time_slot' => $request->time_slot,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your booking has been confirmed!',
                'booking' => [
                    'name' => $booking->name,
                    'date' => $booking->date->format('d F Y'),
                    'time' => $booking->time_slot,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Show admin login page
     */
    public function adminLogin()
    {
        return view('admin-login');
    }

    /**
     * Verify admin password and show bookings
     */
    public function adminBookings(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if ($request->password !== config('app.admin_password', env('ADMIN_PASSWORD'))) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        $availableDates = array_keys(Booking::getAvailableDates());

        $bookings = Booking::whereIn('date', $availableDates)
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get()
            ->groupBy(function ($booking) {
                return $booking->date->format('Y-m-d');
            });

        $totalBookings = Booking::getTotalBookings();
        $totalSlots = Booking::getTotalSlots();
        $remainingSlots = max($totalSlots - $totalBookings, 0);

        return view('admin-bookings', compact('bookings', 'totalBookings', 'remainingSlots', 'totalSlots'));
    }
}
