<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined, EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';
import { h } from 'vue';

const props = defineProps({
    companies: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

// Search with debounce
let searchTimeout = null;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('companies.index'), { search: value }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
});

// Table columns
const columns = [
    {
        title: 'ID',
        dataIndex: 'id',
        key: 'id',
        width: 80,
    },
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    },
    {
        title: 'Logo',
        dataIndex: 'logo',
        key: 'logo',
        width: 100,
    },
    {
        title: 'Website',
        dataIndex: 'website',
        key: 'website',
    },
    {
        title: 'Action',
        key: 'action',
        width: 180,
        fixed: 'right',
    },
];

// Handle delete with confirmation
const handleDelete = (company) => {
    Modal.confirm({
        title: 'Delete Company',
        icon: h(ExclamationCircleOutlined),
        content: h('div', {}, [
            h('p', `Are you sure you want to delete "${company.name}"?`),
            h('p', { class: 'text-red-500 mt-2' }, 'This action cannot be undone. All employees associated with this company will also be deleted.'),
        ]),
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk() {
            router.delete(route('companies.destroy', company.id), {
                preserveScroll: true,
            });
        },
    });
};

// Handle pagination
const handleTableChange = (pagination) => {
    router.get(route('companies.index'), {
        page: pagination.current,
        search: search.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Companies" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Company Management</h2>
                <Link :href="route('companies.create')">
                    <a-button type="primary" class="bg-orange-500 hover:bg-orange-600 border-orange-500" :icon="h(PlusOutlined)">
                        Add Company
                    </a-button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Search Bar -->
                        <div class="mb-4">
                            <a-input-search
                                v-model:value="search"
                                placeholder="Search companies by name or email..."
                                style="max-width: 400px"
                                allow-clear
                            />
                        </div>

                        <!-- Table -->
                        <a-table
                            :columns="columns"
                            :data-source="companies.data"
                            :pagination="{
                                current: companies.current_page,
                                pageSize: companies.per_page,
                                total: companies.total,
                                showSizeChanger: false,
                                showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} companies`,
                            }"
                            :scroll="{ x: 1000 }"
                            @change="handleTableChange"
                            :row-key="record => record.id"
                        >
                            <template #bodyCell="{ column, record }">
                                <!-- Logo Column -->
                                <template v-if="column.key === 'logo'">
                                    <img
                                        v-if="record.logo"
                                        :src="`/storage/${record.logo}`"
                                        alt="Company Logo"
                                        class="h-10 w-10 object-cover rounded"
                                    />
                                    <span v-else class="text-gray-400 text-sm">No logo</span>
                                </template>

                                <!-- Website Column -->
                                <template v-else-if="column.key === 'website'">
                                    <a
                                      v-if="record.website"
                                      :href="record.website"
                                      target="_blank"
                                      class="text-blue-600 hover:underline"
                                    >
                                      {{ record.website }}
                                    </a>
                                    <span v-else class="text-gray-400 text-sm">-</span>
                                </template>

                                <!-- Email Column -->
                                <template v-else-if="column.key === 'email'">
                                    <span v-if="record.email">{{ record.email }}</span>
                                    <span v-else class="text-gray-400 text-sm">-</span>
                                </template>

                                <!-- Action Buttons -->
                                <template v-else-if="column.key === 'action'">
                                    <a-space>
                                        <Link :href="route('companies.edit', record.id)">
                                            <a-button 
                                                type="primary" 
                                                size="small" 
                                                class="bg-orange-500 hover:bg-orange-600 border-orange-500"
                                                :icon="h(EditOutlined)"
                                            >
                                                Edit
                                            </a-button>
                                        </Link>
                                        <a-button
                                            type="primary"
                                            danger
                                            size="small"
                                            class="bg-red-500 hover:bg-red-600 border-red-500"
                                            :icon="h(DeleteOutlined)"
                                            @click="handleDelete(record)"
                                        >
                                            Delete
                                        </a-button>
                                    </a-space>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>