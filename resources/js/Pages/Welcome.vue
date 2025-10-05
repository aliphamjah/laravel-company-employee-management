<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome" />
    
    <div class="min-h-screen bg-gray-50">
        <!-- Simple Header -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">G</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">Company</span>
                    </div>
                    
                    <div v-if="canLogin" class="flex items-center space-x-3">
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition"
                        >
                            Sign In
                        </Link>
                        <Link
                            :href="route('login')"
                            class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium transition shadow-sm"
                        >
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-4xl mx-auto my-auto">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Company & Employee<br/>
                    <span class="text-orange-500">Management System</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                    Simple, powerful tools to manage your companies and employees efficiently.
                </p>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 hover:border-orange-300 transition">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Company Management</h3>
                    <p class="text-gray-600">
                        Manage company profiles, logos, and contact information.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 hover:border-orange-300 transition">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Employee Records</h3>
                    <p class="text-gray-600">
                        Track employee information with instant company linking.
                    </p>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>© 2024 CompanyHub. Built with Laravel 12 & Vue 3.</p>
            </div>
        </footer>
    </div>
</template>