<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { UploadOutlined } from '@ant-design/icons-vue';
import { h } from 'vue';

const props = defineProps({
    company: Object,
});

const form = useForm({
    name: props.company.name,
    email: props.company.email,
    logo: null,
    website: props.company.website,
    _method: 'PUT',
});

const beforeUpload = (file) => {
    const isImage = file.type.startsWith('image/');
    if (!isImage) {
        return false;
    }
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
        return false;
    }
    form.logo = file;
    return false;
};

const handleFileChange = (info) => {
    const file = info.file;
    if (file.status !== 'uploading') {
        form.logo = file.originFileObj || file;
    }
};

const submit = () => {
    form.post(route('companies.update', props.company.id));
};
</script>

<template>
    <Head title="Edit Company" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Company</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <a-form
                            :model="form"
                            layout="vertical"
                            @finish="submit"
                        >
                            <!-- Name Field -->
                            <a-form-item
                                label="Company Name"
                                :validate-status="form.errors.name ? 'error' : ''"
                                :help="form.errors.name"
                                required
                            >
                                <a-input
                                    v-model:value="form.name"
                                    placeholder="Enter company name"
                                    size="large"
                                />
                            </a-form-item>

                            <!-- Email Field -->
                            <a-form-item
                                label="Email Address"
                                :validate-status="form.errors.email ? 'error' : ''"
                                :help="form.errors.email"
                            >
                                <a-input
                                    v-model:value="form.email"
                                    type="email"
                                    placeholder="company@example.com"
                                    size="large"
                                />
                                <div class="text-gray-500 text-sm mt-1">
                                    Email will be used for notifications when new employees are added
                                </div>
                            </a-form-item>

                            <!-- Current Logo -->
                            <a-form-item label="Current Logo" v-if="company.logo">
                                <img
                                    :src="`/storage/${company.logo}`"
                                    alt="Current Logo"
                                    class="h-24 w-24 object-cover rounded border"
                                />
                            </a-form-item>

                            <!-- Logo Upload -->
                            <a-form-item
                                label="Update Logo"
                                :validate-status="form.errors.logo ? 'error' : ''"
                                :help="form.errors.logo"
                            >
                                <a-upload
                                    :before-upload="beforeUpload"
                                    @change="handleFileChange"
                                    :max-count="1"
                                    accept="image/*"
                                    list-type="picture"
                                >
                                    <a-button :icon="h(UploadOutlined)">
                                        Upload New Logo (Max 2MB)
                                    </a-button>
                                </a-upload>
                                <div class="text-gray-500 text-sm mt-2">
                                    Leave empty to keep current logo
                                </div>
                            </a-form-item>

                            <!-- Website Field -->
                            <a-form-item
                                label="Website"
                                :validate-status="form.errors.website ? 'error' : ''"
                                :help="form.errors.website"
                            >
                                <a-input
                                    v-model:value="form.website"
                                    placeholder="https://company.com"
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
                                        Update Company
                                    </a-button>
                                    <a-button
                                        size="large"
                                        @click="$inertia.visit(route('companies.index'))"
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