<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dayang Counselling Session Registration</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .slot-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .slot-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3);
        }
        
        .slot-btn:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Floating decoration -->
    <div class="fixed top-10 right-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
    <div class="fixed bottom-10 left-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 1s;"></div>
    
    <div class="relative max-w-3xl mx-auto" 
         x-data="{
            name: '',
            selectedDate: '',
            selectedSlot: '',
            availableSlots: [],
            loading: false,
            submitted: false,
            confirmation: null,
            error: null,
            remainingSlots: {{ $remainingSlots }},
            
            async fetchSlots() {
                if (!this.selectedDate) {
                    this.availableSlots = [];
                    return;
                }
                
                this.loading = true;
                this.selectedSlot = '';
                
                try {
                    const response = await fetch(`/api/available-slots?date=${this.selectedDate}`);
                    const data = await response.json();
                    this.availableSlots = data.slots;
                } catch (error) {
                    console.error('Error fetching slots:', error);
                    this.error = 'Failed to load available time slots. Please try again.';
                } finally {
                    this.loading = false;
                }
            },
            
            async submitBooking() {
                if (!this.name || !this.selectedDate || !this.selectedSlot) {
                    this.error = 'Please fill in all fields';
                    return;
                }
                
                this.loading = true;
                this.error = null;
                
                try {
                    const response = await fetch('/book', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.name,
                            date: this.selectedDate,
                            time_slot: this.selectedSlot
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.confirmation = data.booking;
                        this.submitted = true;
                        this.remainingSlots--;
                    } else {
                        this.error = data.message;
                    }
                } catch (error) {
                    console.error('Error submitting booking:', error);
                    this.error = 'An error occurred. Please try again.';
                } finally {
                    this.loading = false;
                }
            },
            
            formatDate(dateStr) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const date = new Date(dateStr + 'T00:00:00');
                return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
            },
            
            formatTime(time) {
                const [hour, minute] = time.split(':');
                const h = parseInt(hour);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const hour12 = h % 12 || 12;
                return `${hour12}:${minute} ${ampm}`;
            }
         }"
         x-init="$watch('selectedDate', value => fetchSlots())"
    >
        
        <!-- Success Message -->
        <div x-show="submitted" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="glass-effect rounded-3xl shadow-2xl p-8 sm:p-12 text-center animate-fade-in-up"
             style="display: none;">
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Booking Confirmed!
            </h2>
            
            <p class="text-lg text-gray-600 mb-8">
                Your counselling session has been successfully scheduled.
            </p>
            
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 mb-8">
                <div class="space-y-3 text-left">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium" x-text="confirmation?.name"></span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium" x-text="confirmation?.date"></span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium" x-text="confirmation?.time"></span>
                    </div>
                </div>
            </div>
            
            <p class="text-sm text-gray-500">
                Please arrive 5 minutes early for your session.
            </p>
        </div>
        
        <!-- Booking Form -->
        <div x-show="!submitted" 
             class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 sm:px-12 sm:py-12">
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">
                   Dayang Counselling Session
                </h1>
                <p class="text-indigo-100 text-lg">
                    Late Attendance Registration
                </p>
                
                <div class="mt-6 inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                    <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-white font-medium">
                        <span x-text="remainingSlots"></span> slots remaining
                    </span>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="px-8 py-10 sm:px-12 sm:py-12">
                
                <!-- Error Message -->
                <div x-show="error" 
                     x-transition
                     class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start"
                     style="display: none;">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-700" x-text="error"></p>
                </div>
                
                <form @submit.prevent="submitBooking" class="space-y-8">
                    
                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            Your Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            x-model="name"
                            required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-500 transition-all duration-200"
                            placeholder="Enter your full name"
                        >
                    </div>
                    
                    <!-- Date Selection -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-semibold text-gray-700">
                            Select Date <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="date"
                            x-model="selectedDate"
                            required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:border-indigo-500 transition-all duration-200 appearance-none cursor-pointer"
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%207l5%205%205-5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%222%22%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem;"
                        >
                            <option value="">Choose a date</option>
                            @foreach($availableDates as $date => $slots)
                                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d F Y (l)') }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Time Slot Selection -->
                    <div x-show="selectedDate" 
                         x-transition
                         class="space-y-3"
                         style="display: none;">
                        <label class="block text-sm font-semibold text-gray-700">
                            Select Time Slot <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Loading State -->
                        <div x-show="loading" class="text-center py-8">
                            <div class="inline-block w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                        </div>
                        
                        <!-- Time Slots Grid -->
                        <div x-show="!loading && availableSlots.length > 0" 
                             class="grid grid-cols-2 sm:grid-cols-3 gap-3"
                             style="display: none;">
                            <template x-for="slot in availableSlots" :key="slot.time">
                                <button 
                                    type="button"
                                    @click="selectedSlot = slot.time"
                                    :disabled="!slot.available"
                                    :class="{
                                        'bg-gradient-to-br from-indigo-600 to-purple-600 text-white shadow-lg': selectedSlot === slot.time && slot.available,
                                        'bg-white text-gray-700 border-2 border-gray-200 hover:border-indigo-300': selectedSlot !== slot.time && slot.available,
                                        'bg-gray-100 text-gray-400 cursor-not-allowed': !slot.available
                                    }"
                                    class="slot-btn px-6 py-4 rounded-xl font-semibold text-center transition-all duration-200"
                                >
                                    <div x-text="formatTime(slot.time)" class="text-lg"></div>
                                    <div x-show="!slot.available" class="text-xs mt-1">Booked</div>
                                </button>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit"
                            :disabled="loading || !name || !selectedDate || !selectedSlot"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-5 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span x-show="!loading">Confirm Booking</span>
                            <span x-show="loading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                    
                </form>
                
                <!-- Info Footer -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex items-start text-sm text-gray-600">
                        <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>
                            This is a mandatory counselling session for late attendance. Please ensure you attend on your selected date and time.
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
    
</body>
</html>
