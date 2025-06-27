<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['nombre', 'apellido', 'usuario', 'email', 'telefono', 'cuit', 'password', 'autorizado'];

// Definición de rutas dinámicas
const deleteRoute = (id) => route('clientes.destroy', { id });
const autorizadoRoute = (id) => route('clientes.toggleAutorizado', { id });

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    clientes: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Clientes</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="clientes" :deleteRoute="deleteRoute" :toggleAutorizadoRoute="autorizadoRoute" />
    </div>
</template>
