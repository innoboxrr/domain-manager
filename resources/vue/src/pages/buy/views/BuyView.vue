<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header (sin cambios) -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">
                {{ __domain('Buy') }}
            </h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Sección de Buscador -->
            <div class="mb-8">
                <div class="flex">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Buscar dominio, ej. example.com"
                        class="w-full rounded-l-md border border-gray-300 px-4 py-2 focus:outline-none"
                    />
                    <button 
                        @click="searchDomains"
                        class="rounded-r-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none"
                    >
                        Buscar
                    </button>
                </div>
            </div>

            <!-- Sección de Resultados -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Resultados de Búsqueda</h2>
                <div v-if="loading" class="text-center text-gray-500">Cargando...</div>
                <div v-else>
                    <div v-if="domains.length === 0" class="text-center text-gray-500">
                        No se encontraron dominios
                    </div>
                    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div 
                            v-for="domain in domains" 
                            :key="domain.name" 
                            class="rounded-lg border border-gray-200 bg-white p-6 shadow hover:shadow-lg transition"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">{{ domain.name }}</h3>
                                <span class="text-lg font-semibold text-green-600">${{ domain.price }}</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ domain.available ? 'Disponible' : 'No Disponible' }}
                            </p>
                            <div class="mt-4">
                                <button 
                                    :disabled="!domain.available"
                                    @click="buyDomain(domain)"
                                    class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ domain.available ? 'Comprar Ahora' : 'Agotado' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal para Completar Compra -->
        <CompletePurchaseModal 
            :visible="showPurchaseModal" 
            :domain="selectedDomain" 
            @close="showPurchaseModal = false" 
        />
    </div>
</template>

<script>
import CompletePurchaseModal from '../components/CompletePurchaseModal.vue'
export default {
    name: 'BuyDomainsView',
    components: {
        CompletePurchaseModal,
    },
    data() {
        return {
            searchQuery: '',
            loading: false,
            domains: [],
            showPurchaseModal: false,
            selectedDomain: {},
        }
    },
    methods: {
        searchDomains() {
            this.loading = true;
            // Simulación de llamada a API
            setTimeout(() => {
                if (this.searchQuery.trim() === '') {
                    this.domains = [];
                } else {
                    // Simulación de resultados dummy
                    this.domains = [
                        { name: `${this.searchQuery}.com`, price: 9.99, available: true },
                        { name: `${this.searchQuery}.net`, price: 12.99, available: false },
                        { name: `${this.searchQuery}.org`, price: 8.99, available: true },
                        // Puedes agregar más resultados dummy si lo deseas
                    ];
                }
                this.loading = false;
            }, 1000);
        },
        buyDomain(domain) {
            this.selectedDomain = domain;
            this.showPurchaseModal = true;
        },
    },
}
</script>
