<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'titulo', 'subcategoria_id'];

// Definición de rutas
const aggProdRoute = route('relacionados.store');
const deleteRoute = (productoId) => route('relacionados.destroy', {
    industria: props.industria.id,
    producto: productoId
});

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    productosRelacionados: {
        type: Array,
        required: true
    },
    productos: {
        type: Array,
        required: true
    },
    industria: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Productos relacionados de {{ industria.titulo }}</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">

        <DataTable :columns="columns" :data="productosRelacionados" :aggProdRoute="aggProdRoute"
            :deleteRoute="deleteRoute" :industriaId="industria.id" :productos="productos" />
    </div>
</template>
