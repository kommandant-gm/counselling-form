<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-md w-full glass-effect rounded-3xl shadow-2xl p-8 sm:p-12">
        
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 mb-4">
                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">
                Admin Access
            </h1>
            <p class="text-gray-600">
                Enter password to view bookings
            </p>
        </div>
        
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
        </div>
        @endif
        
        <form action="{{ route('admin.bookings') }}" method="POST">
            @csrf
            
            <div class="space-y-2 mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    Password
                </label>
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    required
                    autofocus
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-500 transition-all duration-200"
                    placeholder="Enter admin password"
                >
            </div>
            
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
            >
                Access Dashboard
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                ← Back to booking form
            </a>
        </div>
        
    </div>
    
</body>
</html>
