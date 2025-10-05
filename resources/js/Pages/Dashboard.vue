<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const isAdmin = computed(() => page.props.auth.user.email === 'admin@grtech.com');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold mb-4">
                            Welcome, {{ $page.props.auth.user.name }}!
                        </h3>
                        
                        <div v-if="isAdmin" class="space-y-4">
                            <p class="text-lg">
                                You're logged in as <span class="font-semibold text-blue-600">Administrator</span>.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                <a 
                                    href="/companies" 
                                    class="block p-6 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition"
                                >
                                    <h4 class="text-xl font-semibold text-orange-700 mb-2">
                                        Manage Companies
                                    </h4>
                                    <p class="text-gray-600">
                                        Add, edit, or delete company information
                                    </p>
                                </a>
                                
                                <a 
                                    href="/employees" 
                                    class="block p-6 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition"
                                >
                                    <h4 class="text-xl font-semibold text-orange-700 mb-2">
                                        Manage Employees
                                    </h4>
                                    <p class="text-gray-600">
                                        Add, edit, or delete employee records
                                    </p>
                                </a>
                            </div>
                        </div>
                        
                        <div v-else class="space-y-4">
                            <p class="text-lg">
                                You're logged in as a <span class="font-semibold">Regular User</span>.
                            </p>
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-gray-700">
                                    You don't have permission to access Companies and Employees management.
                                    Please contact the administrator for access.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>