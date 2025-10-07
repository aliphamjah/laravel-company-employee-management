<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employee: Object,
    companies: Array,
});

const form = useForm({
    first_name: props.employee.first_name,
    last_name: props.employee.last_name,
    company_id: props.employee.company_id,
    email: props.employee.email,
    phone: props.employee.phone,
    _method: 'PUT',
});

const submit = () => {
    form.post(route('employees.update', props.employee.id));
};
</script>

<template>
    <Head title="Edit Employee" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Employee</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <a-form
                            :model="form"
                            layout="vertical"
                            @finish="submit"
                        >
                            <!-- First Name -->
                            <a-form-item
                                label="First Name"
                                :validate-status="form.errors.first_name ? 'error' : ''"
                                :help="form.errors.first_name"
                                required
                            >
                                <a-input
                                    v-model:value="form.first_name"
                                    placeholder="Enter first name"
                                    size="large"
                                />
                            </a-form-item>

                            <!-- Last Name -->
                            <a-form-item
                                label="Last Name"
                                :validate-status="form.errors.last_name ? 'error' : ''"
                                :help="form.errors.last_name"
                                required
                            >
                                <a-input
                                    v-model:value="form.last_name"
                                    placeholder="Enter last name"
                                    size="large"
                                />
                            </a-form-item>

                            <!-- Company Selection -->
                            <a-form-item
                                label="Company"
                                :validate-status="form.errors.company_id ? 'error' : ''"
                                :help="form.errors.company_id"
                                required
                            >
                                <a-select
                                    v-model:value="form.company_id"
                                    placeholder="Select a company"
                                    size="large"
                                    show-search
                                    :filter-option="(input, option) => {
                                        return option.label.toLowerCase().includes(input.toLowerCase());
                                    }"
                                >
                                    <a-select-option
                                        v-for="company in companies"
                                        :key="company.id"
                                        :value="company.id"
                                        :label="company.name"
                                    >
                                        {{ company.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>

                            <!-- Email -->
                            <a-form-item
                                label="Email Address"
                                :validate-status="form.errors.email ? 'error' : ''"
                                :help="form.errors.email"
                            >
                                <a-input
                                    v-model:value="form.email"
                                    type="email"
                                    placeholder="employee@example.com"
                                    size="large"
                                />
                            </a-form-item>

                            <!-- Phone -->
                            <a-form-item
                                label="Phone Number"
                                :validate-status="form.errors.phone ? 'error' : ''"
                                :help="form.errors.phone"
                            >
                                <a-input
                                    v-model:value="form.phone"
                                    placeholder="Enter phone number"
                                    size="large"
                                />
                            </a-form-item>

                            <!-- Submit Buttons -->
                            <a-form-item>
                                <a-space>
                                    <a-button
                                        type="primary"
                                        html-type="submit"
                                        size="large"
                                        class="bg-orange-500 hover:bg-orange-600 border-orange-500"
                                        :loading="form.processing"
                                    >
                                        Update Employee
                                    </a-button>
                                    <a-button
                                        size="large"
                                        @click="$inertia.visit(route('employees.index'))"
                                    >
                                        Cancel
                                    </a-button>
                                </a-space>
                            </a-form-item>
                        </a-form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>