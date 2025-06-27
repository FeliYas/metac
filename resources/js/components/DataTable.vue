<script setup>
import { ref, computed, onMounted, inject } from 'vue';
import { router } from '@inertiajs/vue3';
import DeleteModal from '@/components/Modales/DeleteModal.vue';
import QuillEditor from '@/components/QuillEditor.vue'; // Import QuillEditor
import { Link, useForm } from '@inertiajs/vue3';
import CreateModal from '@/components/Modales/CreateModal.vue';
import EditModal from '@/components/Modales/EditModal.vue';

const props = defineProps({
    columns: Array,
    data: Array,
    deleteRoute: {
        type: Function,
        required: true
    },
    updateRoute: {
        type: Function,
        required: true
    },
    createRoute: {
        type: [String, Function], // createRoute puede ser string o función
        required: true
    },
    categorias: Array,
    toggleDestacadoRoute: {
        type: Function,
        required: true
    },
    toggleAutorizadoRoute: {
        type: Function,
        required: true
    },
    imgsRoute: {
        type: [String, Function],
        required: true
    }, productosRoute: {
        type: [String, Function],
        required: true
    }, aggProdRoute: {
        type: [String, Function],
        required: false
    }, productoId: Number,
    industriaId: {
        type: Number,
        required: false
    },
    recomendacion: String,
    productos: {
        type: Array,
        required: true,
        default: () => []
    },
});

// Inyectar el sistema de notificaciones global
const notification = inject('noti');

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showPassword = ref(false);
const showEditPassword = ref(false);
const currentItemId = ref(null);
const editFormData = ref({});
const createFormData = ref({});
const fileInputLabel = ref('Elija una imagen');
const editFileInputLabel = ref('Elija una nueva imagen');
const bannerInputLabel = ref('Elija un banner');
const editBannerInputLabel = ref('Elija un nuevo banner');
const portadaInputLabel = ref('Elija una portada');
const editPortadaInputLabel = ref('Elija una nueva portada');
const fichaInputLabel = ref('Elija un PDF');
const editFichaInputLabel = ref('Elija un nuevo PDF');
const recomendacion = props.recomendacion;
const showProductosModal = ref(false);
const selectedProducts = ref([]);
const searchQuery = ref('');
const isCreating = ref(false);
const isEditing = ref(false);
const isDeleting = ref(false);

// Para la vista previa de imágenes
const editImagePreview = ref('');
const editBannerPreview = ref('');
const editPortadaPreview = ref('');

// Form para enviar los datos
const form = useForm({
    industria_id: props.industriaId,
    productos: []
});

// Productos filtrados por búsqueda
const filteredProducts = computed(() => {
    if (!props.productos || props.productos.length === 0) return [];

    if (!searchQuery.value) return props.productos;

    return props.productos.filter(producto =>
        producto.titulo.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (producto.categoria && producto.categoria.titulo &&
            producto.categoria.titulo.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

// Productos ya relacionados (para no mostrarlos en el modal)
const productosDisponibles = computed(() => {
    // props.data contiene los productos ya relacionados
    const productosRelacionadosIds = (props.data || []).map(p => p.id);

    // Solo mostrar productos que NO están ya relacionados
    return filteredProducts.value.filter(producto =>
        !productosRelacionadosIds.includes(producto.id)
    );
});

// Funciones del modal de productos
const openProductosModal = () => {
    showProductosModal.value = true;
    selectedProducts.value = [];
    searchQuery.value = '';
};

const closeModal = () => {
    showProductosModal.value = false;
    selectedProducts.value = [];
    searchQuery.value = '';
};

const toggleProductSelection = (productId) => {
    const index = selectedProducts.value.indexOf(productId);
    if (index > -1) {
        selectedProducts.value.splice(index, 1);
    } else {
        selectedProducts.value.push(productId);
    }
};

const isProductSelected = (productId) => {
    return selectedProducts.value.includes(productId);
};

const selectAllProducts = () => {
    if (selectedProducts.value.length === productosDisponibles.value.length) {
        selectedProducts.value = [];
    } else {
        selectedProducts.value = productosDisponibles.value.map(p => p.id);
    }
};

const submitSelectedProducts = () => {
    if (selectedProducts.value.length === 0) {
        alert('Por favor selecciona al menos un producto');
        return;
    }
    form.productos = selectedProducts.value;
    form.post(props.aggProdRoute, {
        onSuccess: () => {
            closeModal();
            notification({ message: "Productos agregados correctamente", type: "success" });
            router.reload();
        },
        onError: (errors) => {
            console.error('Error al agregar productos:', errors);
            notification({ message: "Error al agregar productos", type: "error" });
        }
    });
};

const openCreateModal = () => {
    // Reset form data
    createFormData.value = {};
    props.columns.forEach(column => {
        if (column !== 'id' && column !== 'destacado' && column !== 'autorizado') {
            createFormData.value[column] = '';
        }
        if (column === 'role') {
            createFormData.value[column] = 'user'; //
        }
    });
    // Set industria_id in createFormData
    createFormData.value.industria_id = props.industriaId;
    showPassword.value = false;
    showCreateModal.value = true;
};
const closeCreateModal = () => {
    showCreateModal.value = false;
};
const openEditModal = (item) => {
    editFormData.value = { ...item };

    // Si hay una imagen, configurar la vista previa
    if (item.path) {
        editImagePreview.value = item.path;
    } else {
        editImagePreview.value = '';
    }
    if (item.banners) {
        editBannerPreview.value = item.banners;
    } else {
        editBannerPreview.value = '';
    }
    if (item.portada) {
        editPortadaPreview.value = item.portada;
    } else {
        editPortadaPreview.value = '';
    }

    showEditPassword.value = false;
    showEditModal.value = true;
};
const closeEditModal = () => {
    showEditModal.value = false;
};
const openDeleteModal = (id) => {
    currentItemId.value = id;
    showDeleteModal.value = true;
};
const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
const toggleEditPasswordVisibility = () => {
    showEditPassword.value = !showEditPassword.value;
};

const handleCreateFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileInputLabel.value = file.name;
        createFormData.value.path = file;
    } else {
        fileInputLabel.value = 'Elija una imagen';
    }
};
const handleEditFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editFileInputLabel.value = file.name;
        editFormData.value.path = file;
    } else {
        editFileInputLabel.value = 'Elija una nueva imagen';
    }
};
const handleCreateFichaChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileInputLabel.value = file.name;
        createFormData.value.fichatecnica = file;
    } else {
        fileInputLabel.value = 'Elija una ficha';
    }
};
const handleEditFichaChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editFileInputLabel.value = file.name;
        editFormData.value.fichatecnica = file;
    } else {
        editFileInputLabel.value = 'Elija una nueva ficha';
    }
};
const handleCreateFichaSeguridadChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileInputLabel.value = file.name;
        createFormData.value.fichaseguridad = file;
    } else {
        fileInputLabel.value = 'Elija una ficha';
    }
};
const handleEditFichaSeguridadChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editFileInputLabel.value = file.name;
        editFormData.value.fichaseguridad = file;
    } else {
        editFileInputLabel.value = 'Elija una nueva ficha';
    }
};
const openFicha = (fichaPath) => {
    if (fichaPath) {
        // Abrir en una nueva pestaña para descargar/ver el PDF
        window.open(fichaPath, '_blank');
    }
};
const handleCreateBannersChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        bannerInputLabel.value = file.name;
        createFormData.value.banners = file;
    } else {
        bannerInputLabel.value = 'Elija un banner';
    }
};
const handleEditBannersChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editBannerInputLabel.value = file.name;
        editFormData.value.banners = file;
    } else {
        editBannerInputLabel.value = 'Elija un nuevo banner';
    }
};
const handleCreatePortadaChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        portadaInputLabel.value = file.name;
        createFormData.value.portada = file;
    } else {
        portadaInputLabel.value = 'Elija un portada';
    }
};
const handleEditPortadaChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editPortadaInputLabel.value = file.name;
        editFormData.value.portada = file;
    } else {
        editPortadaInputLabel.value = 'Elija un nuevo portada';
    }
};
const getCategoriaName = (categoriaId, row) => {
    // Si recibimos el objeto row y tiene una relación categoria con un título, usamos esa directamente
    if (row && row.categoria && row.categoria.titulo) {
        return row.categoria.titulo.charAt(0).toUpperCase() + row.categoria.titulo.slice(1);
    }

    // Si recibimos el objeto row y tiene una relación subcategoria con un título, usamos esa directamente
    if (row && row.subcategoria && row.subcategoria.titulo) {
        return row.subcategoria.titulo.charAt(0).toUpperCase() + row.subcategoria.titulo.slice(1);
    }

    // Como respaldo, buscamos en el array de categorías (comportamiento anterior)
    if (!props.categorias || !categoriaId) return 'N/A';
    const categoria = props.categorias.find(cat => cat.id === categoriaId);
    return categoria ? categoria.titulo.charAt(0).toUpperCase() + categoria.titulo.slice(1) : 'N/A';
};
// Función para obtener el contador de productos relacionados
const getProductosCount = (row) => {
    // Si hay un valor de withCount precargado desde Laravel, lo usamos
    if (row.productos_count !== undefined) {
        return row.productos_count;
    }

    // Si hay productos cargados en el objeto como relación, contamos esos
    if (row.productos && Array.isArray(row.productos)) {
        return row.productos.length;
    }

    // Si no hay información, devolvemos 0
    return 0;
};
const submitCreateForm = () => {
    isCreating.value = true;
    // Crear FormData para enviar archivos
    const formData = new FormData();

    Object.keys(createFormData.value).forEach(key => {
        formData.append(key, createFormData.value[key]);
    });

    // Usar la ruta, que puede ser una string o una función
    const createUrl = typeof props.createRoute === 'function' ? props.createRoute() : props.createRoute;

    router.post(createUrl, formData, {
        onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Creado correctamente", type: "success" });
            }
            closeCreateModal();
        },
        onError: (errors) => {
            notification({ message: errors[0], type: "error" });
        },
        onFinish: () => {
            isCreating.value = false;
        }
    });
};
const submitEditForm = () => {
    isEditing.value = true;
    const formData = new FormData();

    Object.keys(editFormData.value).forEach(key => {
        if (key !== 'id') {
            // Si el campo es 'path' y no se seleccionó un archivo nuevo, no lo agregamos
            if (key === 'path' && typeof editFormData.value[key] === 'string') {
                return;
            }
            if (key === 'banners' && typeof editFormData.value[key] === 'string') {
                return;
            }
            if (key === 'portada' && typeof editFormData.value[key] === 'string') {
                return;
            }
            // Para fichas: si es string, null o undefined, no agregar al FormData
            if (key === 'fichatecnica' && (typeof editFormData.value[key] === 'string' || editFormData.value[key] === null || editFormData.value[key] === undefined)) {
                return;
            }
            if (key === 'fichaseguridad' && (typeof editFormData.value[key] === 'string' || editFormData.value[key] === null || editFormData.value[key] === undefined)) {
                return;
            }
            formData.append(key, editFormData.value[key]);
        }
    });

    formData.append('_method', 'PUT');

    // Usar directamente la función updateRoute
    const updateUrl = props.updateRoute(editFormData.value.id);

    router.post(updateUrl, formData, {
        onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Actualizado correctamente", type: "success" });
            }
            closeEditModal();
        },
        onError: (errors) => {
            notification({ message: errors[0], type: "error" });
        },
        onFinish: () => {
            isEditing.value = false;
        }
    });
};
const submitDeleteForm = () => {
    isDeleting.value = true;
    // Usar directamente la función deleteRoute
    const deleteUrl = props.deleteRoute(currentItemId.value);

    router.delete(deleteUrl, {
        onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Eliminado correctamente", type: "success" });
            }
            closeDeleteModal();
        },
        onFinish: () => {
            isDeleting.value = false;
        }
    });
};

const toggleDestacado = (id, isChecked) => {
    // Usar directamente la función toggleDestacadoRoute
    const toggleUrl = props.toggleDestacadoRoute(id);

    // Usar router.post para manejar CSRF automáticamente
    router.post(toggleUrl, {
        id: id,
        destacado: isChecked ? 1 : 0
    }, {
        preserveScroll: true,
        onSuccess: (page) => {
            notification({ message: page.props.flash.message, type: "success" });
        },
        onError: () => {
            notification({ message: "Error al actualizar el destacado", type: "error" });
        }
    });
};
const toggleAutorizado = (id, isChecked) => {
    // Usar directamente la función toggleAutorizadoRoute
    const toggleUrl = props.toggleAutorizadoRoute(id);

    // Usar router.post para manejar CSRF automáticamente
    router.post(toggleUrl, {
        id: id,
        autorizado: isChecked ? 1 : 0
    }, {
        preserveScroll: true,
        onSuccess: (page) => {
            notification({ message: page.props.flash.message, type: "success" });
        },
        onError: () => {
            notification({ message: "Error al actualizar el autorizado", type: "error" });
        }
    });
};
</script>

<template>
    <div class="py-4">
        <div v-if="createRoute || aggProdRoute" class="flex justify-start items-center mb-6 gap-3">
            <button v-if="createRoute" class="btn-primary flex items-center gap-2" @click="openCreateModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Agregar
            </button>

            <button v-if="aggProdRoute" class="btn-secondary flex items-center gap-2" @click="openProductosModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Agregar Productos
            </button>
        </div>

        <div class="grid-table-container">

            <!-- Encabezado de la grid -->
            <div class="grid-header">
                <template v-for="column in columns" :key="column">
                    <div v-if="column !== 'password' && column !== 'descripcion'" class="grid-header-cell">
                        <template v-if="column === 'categoria_id'">
                            Categoria
                        </template>
                        <template v-else-if="column === 'subcategoria_id'">
                            Subcategoria
                        </template>
                        <template v-else-if="column === 'fichatecnica'">
                            Ficha tecnica
                        </template>
                        <template v-else-if="column === 'fichaseguridad'">
                            Ficha de seguridad
                        </template>
                        <template v-else-if="column === 'path'">
                            Imagen
                        </template>
                        <template v-else-if="column === 'banners'">
                            Banner
                        </template>
                        <template v-else>
                            {{ column.charAt(0).toUpperCase() + column.slice(1) }}
                        </template>
                    </div>
                </template>
                <div class="grid-header-cell">
                    Acciones
                </div>
            </div>

            <!-- Contenido de la grid -->
            <div class="grid-body">

                <!-- Mensaje cuando no hay datos -->
                <div v-if="!data || data.length === 0" class="grid-no-data">
                    No hay datos disponibles
                </div>

                <!-- Filas de datos -->
                <template v-else>
                    <div v-for="row in data" :key="row.id" class="grid-row">
                        <template v-for="column in columns" :key="column">
                            <div v-if="column !== 'password' && column !== 'descripcion'" :class="'grid-cell'">
                                <!-- Celda de imagen -->
                                <template v-if="column === 'path'">
                                    <img v-if="row.path" :src="row.path" alt="Imagen del producto"
                                        class="w-full flex items-center justify-center h-25 object-contain">
                                    <div v-else class="grid-no-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                                <template v-else-if="column === 'banners'">
                                    <img v-if="row.banners" :src="row.banners" alt="Imagen del producto"
                                        class="w-full flex items-center justify-center h-25 object-cover">
                                    <div v-else class="grid-no-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                                <template v-else-if="column === 'portada'">
                                    <img v-if="row.portada" :src="row.portada" alt="Imagen del producto"
                                        class="w-full flex items-center justify-center h-25 object-contain">
                                    <div v-else class="grid-no-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                                <!-- Destacado toggle -->
                                <template v-else-if="column === 'destacado'">
                                    <div class="grid-cell">
                                        <div class="flex justify-center">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" :checked="row.destacado"
                                                    @change="toggleDestacado(row.id, $event.target.checked)">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#374977]">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                                <template v-else-if="column === 'autorizado'">
                                    <div class="grid-cell">
                                        <div class="flex justify-center">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" :checked="row.autorizado"
                                                    @change="toggleAutorizado(row.id, $event.target.checked)">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#374977]">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                                <!-- Celda de ficha -->
                                <template v-else-if="column === 'fichatecnica'">
                                    <div class="flex justify-center">
                                        <template v-if="row.fichatecnica">
                                            <button @click="openFicha(row.fichatecnica)"
                                                class="text-blue-900 hover:text-blue-500 flex items-center gap-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs">Ver PDF</span>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <span class="text-gray-400 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                <span class="text-xs">Sin ficha</span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <template v-else-if="column === 'fichaseguridad'">
                                    <div class="flex justify-center">
                                        <template v-if="row.fichaseguridad">
                                            <button @click="openFicha(row.fichaseguridad)"
                                                class="text-blue-900 hover:text-blue-500 flex items-center gap-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs">Ver PDF</span>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <span class="text-gray-400 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                <span class="text-xs">Sin ficha</span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <!-- Productos relacionados -->
                                <template v-else-if="column === 'productos relacionados'">
                                    <Link :href="productosRoute(row.id)"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-main-color bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 border border-main-color cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span>Productos</span>
                                    <span v-if="getProductosCount(row) > 0"
                                        class="ml-2 px-2 py-0.5 text-xs bg-main-color text-white rounded-full font-medium">
                                        {{ getProductosCount(row) }}
                                    </span>
                                    <span v-else
                                        class="ml-2 px-2 py-0.5 text-xs bg-gray-400 text-white rounded-full font-medium">
                                        0
                                    </span>
                                    </Link>
                                </template>
                                <template v-else-if="column === 'categoria_id'">
                                    {{ getCategoriaName(row[column], row) }}
                                </template>
                                <template v-else-if="column === 'subcategoria_id'">
                                    {{ getCategoriaName(row[column], row) }}
                                </template>
                                <template v-else>
                                    {{ row[column] ? row[column].charAt(0).toUpperCase() + row[column].slice(1) : '' }}
                                </template>
                            </div>
                        </template>

                        <!-- Celda de acciones -->
                        <div class="grid-cell grid-actions-cell">
                            <div class="grid-actions">
                                <button v-if="imgsRoute" @click="router.get(imgsRoute(row.id))"
                                    class="action-button view-button">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="#1e3a8a"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="9" cy="9" r="2" stroke="#1e3a8a" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M21 15L16 10L5 21" stroke="#1e3a8a" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button v-if="colocacionRoute" @click="router.get(colocacionRoute(row.id))"
                                    class="action-button list-button">
                                    <i class="fa-solid fa-list-ol text-2xl mt-1 text-green-700"></i>
                                </button>
                                <button v-if="updateRoute" @click="openEditModal(row)"
                                    class="action-button edit-button">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button v-if="deleteRoute" @click="openDeleteModal(row.id)"
                                    class="action-button delete-button">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

        <!-- Modal de Crear (componente) -->
        <CreateModal v-if="showCreateModal" :show="showCreateModal" :columns="columns" :categorias="categorias"
            :formData="createFormData" :fileInputLabel="fileInputLabel" :bannerInputLabel="bannerInputLabel"
            :portadaInputLabel="portadaInputLabel" :fichaInputLabel="fichaInputLabel" :recomendacion="recomendacion"
            :showPassword="showPassword" :loading="isCreating" @close="closeCreateModal" @submit="submitCreateForm"
            @file-change="handleCreateFileChange" @banner-change="handleCreateBannersChange"
            @portada-change="handleCreatePortadaChange" @ficha-change="handleCreateFichaChange"
            @fichaseguridad-change="handleCreateFichaSeguridadChange" @toggle-password="togglePasswordVisibility" />

        <!-- Modal de Editar (componente) -->
        <EditModal v-if="showEditModal" :show="showEditModal" :columns="columns" :categorias="categorias"
            :formData="editFormData" :fileInputLabel="editFileInputLabel" :bannerInputLabel="editBannerInputLabel"
            :portadaInputLabel="editPortadaInputLabel" :fichaInputLabel="editFichaInputLabel"
            :imagePreview="editImagePreview" :bannerPreview="editBannerPreview" :portadaPreview="editPortadaPreview"
            :recomendacion="recomendacion" :showPassword="showEditPassword" :loading="isEditing" @close="closeEditModal"
            @submit="submitEditForm" @file-change="handleEditFileChange" @banner-change="handleEditBannersChange"
            @portada-change="handleEditPortadaChange" @ficha-change="handleEditFichaChange"
            @fichaseguridad-change="handleEditFichaSeguridadChange" @toggle-password="toggleEditPasswordVisibility"
            @open-ficha="openFicha" /> <!-- Componente Modal de Eliminación -->
        <DeleteModal v-if="showDeleteModal" :show="showDeleteModal" :loading="isDeleting" @close="closeDeleteModal"
            @confirm="submitDeleteForm" title="¿Estás seguro de que querés eliminar este elemento?"
            message="Esta acción no se puede deshacer." />

        <!-- Modal de Productos -->
        <Transition name="modal">
            <div v-if="showProductosModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm" @click="closeModal"></div>
                <div class="relative w-full max-w-4xl z-50 bg-white rounded-lg shadow-lg max-h-[90vh]">
                    <!-- Header -->
                    <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                        <div class="flex items-center justify-between">
                            <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Agregar Productos a Industria
                            </h2>
                            <button type="button" @click="closeModal"
                                class="text-white hover:text-gray-200 transition-colors">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <!-- Barra de búsqueda -->
                        <div class="mb-6">
                            <div class="relative">
                                <input v-model="searchQuery" type="text" placeholder="Buscar productos..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-main-color focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Seleccionar todos -->
                        <div class="mb-4 flex items-center gap-2">
                            <input type="checkbox"
                                :checked="selectedProducts.length === productosDisponibles.length && productosDisponibles.length > 0"
                                @change="selectAllProducts"
                                class="h-4 w-4 text-main-color border-gray-300 rounded focus:ring-main-color">
                            <label class="text-sm font-medium text-gray-700">
                                Seleccionar todos ({{ productosDisponibles.length }} disponibles)
                            </label>
                        </div>

                        <!-- Lista de productos -->
                        <div v-if="productosDisponibles.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m-3 0h.01M21 13h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay productos disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ searchQuery ? 'No se encontraron productos con ese criterio de búsqueda.' :
                                    'Todos los productos ya están relacionados con esta industria.' }}
                            </p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="producto in productosDisponibles" :key="producto.id"
                                class="border border-gray-200 rounded-lg p-4 hover:border-main-color transition-colors cursor-pointer"
                                :class="{ 'ring-2 ring-main-color bg-blue-50': isProductSelected(producto.id) }"
                                @click="toggleProductSelection(producto.id)">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" :checked="isProductSelected(producto.id)" @click.stop
                                        @change="toggleProductSelection(producto.id)"
                                        class="mt-1 h-4 w-4 text-main-color border-gray-300 rounded focus:ring-main-color">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <img v-if="producto.path" :src="producto.path" :alt="producto.titulo"
                                                class="w-12 h-12 object-cover rounded">
                                            <div v-else
                                                class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ producto.titulo }}
                                        </h4>
                                        <p v-if="producto.categoria" class="text-xs text-gray-500 mt-1">
                                            {{ producto.categoria.titulo }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            {{ selectedProducts.length }} producto(s) seleccionado(s)
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="closeModal" class="btn-secondary px-4 py-2">
                                Cancelar
                            </button>
                            <button type="button" @click="submitSelectedProducts"
                                :disabled="selectedProducts.length === 0"
                                class="btn-primary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                Agregar {{ selectedProducts.length }} Producto(s)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

    </div>
</template>

<style scoped>
/* Estilos para la tabla grid */
.grid-table-container {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

/* Estilos para el encabezado */
.grid-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    background-color: rgb(229, 231, 235);
    /* bg-gray-200 */
    border-bottom: 1px solid #e2e8f0;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.75rem;
    color: var(--main-color, #333);
    letter-spacing: 0.05em;
}

.grid-header-cell {
    padding: 1rem 1.5rem;
    text-align: center;
}

/* Estilos para el cuerpo de la tabla */
.grid-body {
    background-color: rgb(243, 244, 246);
    /* bg-gray-100 */
}

/* Mensaje cuando no hay datos */
.grid-no-data {
    padding: 1rem;
    text-align: center;
    grid-column: 1 / -1;
    color: rgb(55, 65, 81);
    /* text-gray-700 */
    font-size: 0.875rem;
}

/* Filas de datos */
.grid-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    align-items: center;
    transition: background-color 0.2s;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.grid-row:hover {
    background-color: rgb(229, 231, 235);
    /* hover:bg-gray-200 */
}

.grid-row:last-child {
    border-bottom: none;
}

/* Celdas */
.grid-cell {
    padding: 1rem 1.5rem;
    text-align: center;
    font-size: 0.875rem;
    color: rgb(55, 65, 81);
    /* text-gray-700 */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    align-items: center;
}

/* Mostrar el título completo, permitiendo varias líneas y sin cortar */
.show-full-title {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
    display: block;
    word-break: break-word;
    text-align: center;
    /* Opcional: puedes ajustar el ancho máximo si quieres limitarlo */
    /* max-width: 350px; */
}

.grid-no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: rgb(156, 163, 175);
    /* text-gray-400 */
}

/* Celda de acciones */
.grid-actions-cell {
    padding: 0.5rem;
}

.grid-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.action-button {
    padding: 0.5rem;
    border-radius: 9999px;
    transition: all 0.2s;
    cursor: pointer;
    background-color: transparent;
    border: none;
}

.list-button:hover {
    background-color: rgba(34, 255, 0, 0.204);
    /* hover:bg-orange-100 */
}

.view-button:hover {
    background-color: rgba(15, 3, 248, 0.1);
    /* hover:bg-orange-100 */
}

.edit-button:hover {
    background-color: rgba(248, 105, 3, 0.1);
    /* hover:bg-orange-100 */
}

.delete-button {
    color: rgb(220, 38, 38);
    /* text-red-600 */
}

.delete-button:hover {
    background-color: rgba(248, 3, 3, 0.1);
    /* hover:bg-red-100 */
}

/* Ajuste para pantallas pequeñas */
@media (max-width: 768px) {
    .grid-header {
        display: none;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 1fr;
        padding: 1rem 0;
        gap: 0.5rem;
        position: relative;
    }

    .grid-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .grid-cell:before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .grid-actions-cell {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 0.75rem;
    }
}

/* Animaciones para modales */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

/* Utilidades para el modal de productos */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>