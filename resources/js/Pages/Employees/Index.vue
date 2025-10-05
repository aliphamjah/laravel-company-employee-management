<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, h } from 'vue';
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined, EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';

const props = defineProps({
    employees: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

let searchTimeout = null;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('employees.index'), { search: value }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
});

const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id', width: 80 },
    { title: 'Full Name', key: 'full_name', width: 200 },
    { title: 'Company', key: 'company', width: 200 },
    { title: 'Email', dataIndex: 'email', key: 'email' },
    { title: 'Phone', dataIndex: 'phone', key: 'phone' },
    { title: 'Action', key: 'action', width: 150, fixed: 'right' },
];

const handleDelete = (employee) => {
    Modal.confirm({
        title: 'Delete Employee',
        icon: h(ExclamationCircleOutlined),
        content: `Are you sure you want to delete "${employee.first_name} ${employee.last_name}"?`,
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk() {
            router.delete(route('employees.destroy', employee.id));
        },
    });
};

const showCompanyModal = (company) => {
    Modal.info({
        title: company.name,
        content: h('div', { class: 'space-y-2' }, [
            h('p', {}, [
                h('strong', 'Email: '),
                h('span', company.email || '-'),
            ]),
            h('p', {}, [
                h('strong', 'Website: '),
                company.website 
                    ? h('a', { 
                        href: company.website, 
                        target: '_blank',
                        class: 'text-blue-600 hover:underline'
                    }, company.website)
                    : h('span', '-'),
            ]),
        ]),
        width: 500,
    });
};

const handleTableChange = (pagination) => {
    router.get(route('employees.index'), {
        page: pagination.current,
        search: search.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
                <Link :href="route('employees.create')">
                    <a-button type="primary" :icon="h(PlusOutlined)">Add Employee</a-button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4">
                            <a-input-search
                                v-model:value="search"
                                placeholder="Search employees..."
                                style="max-width: 400px"
                                allow-clear
                            />
                        </div>

                        <a-table
                            :columns="columns"
                            :data-source="employees.data"
                            :pagination="{
                                current: employees.current_page,
                                pageSize: employees.per_page,
                                total: employees.total,
                                showSizeChanger: false,
                                showTotal: (total, range) => `${range[0]}-${range[1]} of ${total}`,
                            }"
                            :scroll="{ x: 1000 }"
                            @change="handleTableChange"
                            :row-key="record => record.id"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'full_name'">
                                    <span class="font-medium">
                                        {{ record.first_name }} {{ record.last_name }}
                                    </span>
                                </template>

                                <template v-else-if="column.key === 'company'"
                                    href="javascript:void(0)"
                                    @click="showCompanyModal(record.company)"
                                    class="text-blue-600 hover:underline"
                                >
                                    <span class="font-medium">
                                        {{ record.company.name }}
                                    </span>
                                </template>

                                <template v-else-if="column.key === 'email'">
                                    <span v-if="record.email">{{ record.email }}</span>
                                    <span v-else class="text-gray-400">-</span>
                                </template>

                                <template v-else-if="column.key === 'phone'">
                                    <span v-if="record.phone">{{ record.phone }}</span>
                                    <span v-else class="text-gray-400">-</span>
                                </template>

                                <template v-else-if="column.key === 'action'">
                                    <a-space>
                                        <Link :href="route('employees.edit', record.id)">
                                            <a-button type="primary" size="small" :icon="h(EditOutlined)">Edit</a-button>
                                        </Link>
                                        <a-button
                                            type="primary"
                                            danger
                                            size="small"
                                            :icon="h(DeleteOutlined)"
                                            @click="handleDelete(record)"
                                        >Delete</a-button>
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