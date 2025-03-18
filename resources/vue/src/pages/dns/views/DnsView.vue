<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">{{ __domain('DNS') }}</h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Selector de Dominio -->
            <div class="mb-6">
                <label for="domainSelect" class="block text-sm font-medium text-gray-700">Selecciona Dominio</label>
                <select
                    id="domainSelect"
                    v-model="selectedDomain"
                    @change="loadDNSRecords"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none"
                >
                    <option disabled value="">-- Selecciona un dominio --</option>
                    <option v-for="domain in domains" :key="domain.id" :value="domain">
                        {{ domain.name }}
                    </option>
                </select>
            </div>

            <!-- Gestión de Registros DNS -->
            <div v-if="selectedDomain" class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Registros DNS para {{ selectedDomain.name }}
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TTL</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Registros existentes -->
                            <tr
                                v-for="(record, index) in dnsRecords"
                                :key="index"
                                :class="{'bg-blue-50': record.editing, 'hover:bg-gray-50': !record.editing}"
                            >
                                <td class="px-4 py-2">
                                    <template v-if="record.editing">
                                        <select v-model="record.type" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none">
                                            <option disabled value="">Tipo</option>
                                            <option v-for="option in recordTypes" :key="option" :value="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <input type="text" v-model="record.type" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none" disabled />
                                    </template>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" v-model="record.name" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none" :disabled="!record.editing" />
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" v-model="record.value" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none" :disabled="!record.editing" />
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" v-model="record.ttl" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none" :disabled="!record.editing" />
                                </td>
                                <td class="px-4 py-2">
                                    <template v-if="record.type === 'MX'">
                                        <input type="number" v-model="record.priority" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none" :disabled="!record.editing" />
                                    </template>
                                    <template v-else>-</template>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <template v-if="record.editing">
                                        <button @click="saveRecord(index)" class="inline-flex items-center gap-1 rounded-md bg-green-600 px-3 py-1 text-xs font-medium text-white hover:bg-green-700">
                                            <CheckCircleIcon class="h-4 w-4" />
                                        </button>
                                        <button @click="cancelEdit(index)" class="ml-2 inline-flex items-center gap-1 rounded-md bg-gray-300 px-3 py-1 text-xs font-medium text-gray-800 hover:bg-gray-400">
                                            <XCircleIcon class="h-4 w-4" />
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button @click="editRecord(index)" class="inline-flex items-center gap-1 rounded-md bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700">
                                            <PencilSquareIcon class="h-4 w-4" />
                                        </button>
                                        <button @click="removeRecord(index)" class="ml-2 inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700">
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </template>
                                </td>
                            </tr>
                            <!-- Fila para agregar nuevo registro -->
                            <tr>
                                <td class="px-4 py-2">
                                    <select v-model="newRecord.type" class="w-full border border-dashed border-gray-300 rounded-md px-2 py-1 focus:outline-none">
                                        <option disabled value="">Tipo</option>
                                        <option v-for="option in recordTypes" :key="option" :value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input 
                                        type="text" 
                                        v-model="newRecord.name" 
                                        :placeholder="newRecord.type === 'MX' ? 'Mail server' : 'Nombre'" 
                                        class="w-full border border-dashed border-gray-300 rounded-md px-2 py-1 focus:outline-none"
                                        :disabled="!newRecord.type" 
                                    />
                                </td>
                                <td class="px-4 py-2">
                                    <input 
                                        type="text" 
                                        v-model="newRecord.value" 
                                        :placeholder="getPlaceholder(newRecord.type)" 
                                        class="w-full border border-dashed border-gray-300 rounded-md px-2 py-1 focus:outline-none"
                                        :disabled="!newRecord.type" 
                                    />
                                </td>
                                <td class="px-4 py-2">
                                    <input 
                                        type="number" 
                                        v-model="newRecord.ttl" 
                                        placeholder="TTL" 
                                        class="w-full border border-dashed border-gray-300 rounded-md px-2 py-1 focus:outline-none"
                                        :disabled="!newRecord.type" 
                                    />
                                </td>
                                <td class="px-4 py-2">
                                    <template v-if="newRecord.type === 'MX'">
                                        <input 
                                            type="number" 
                                            v-model="newRecord.priority" 
                                            placeholder="Prioridad" 
                                            class="w-full border border-dashed border-gray-300 rounded-md px-2 py-1 focus:outline-none"
                                        />
                                    </template>
                                    <template v-else>-</template>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <button @click="addRecord" class="text-blue-600 hover:underline text-sm">Agregar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Botón para Guardar Cambios -->
                <div class="mt-4 flex justify-end">
                    <button @click="saveDNSRecords" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Guardar Cambios
                    </button>
                </div>
            </div>
            <div v-else class="text-center text-gray-500">
                Selecciona un dominio para gestionar sus registros DNS.
            </div>
        </main>
    </div>
</template>

<script>
import { CheckCircleIcon, XCircleIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'

export default {
    name: 'DNSManagerView',
    components: {
        CheckCircleIcon,
        XCircleIcon,
        PencilSquareIcon,
        TrashIcon,
    },
    data() {
        return {
            // Lista de dominios dummy
            domains: [
                { id: 1, name: 'example.com' },
                { id: 2, name: 'testdomain.net' },
                { id: 3, name: 'mywebsite.org' },
            ],
            selectedDomain: '',
            dnsRecords: [],
            newRecord: {
                type: '',
                name: '',
                value: '',
                ttl: 3600,
                priority: null,
            },
            recordTypes: ['A', 'AAAA', 'CNAME', 'MX', 'TXT'],
            // Objeto para respaldar ediciones
            originalRecords: {},
        }
    },
    methods: {
        loadDNSRecords() {
            // Simula la carga de registros DNS para el dominio seleccionado.
            // En una implementación real, se realizaría una llamada a la API.
            this.dnsRecords = [
                { type: 'A', name: '@', value: '192.0.2.1', ttl: 3600, editing: false },
                { type: 'CNAME', name: 'www', value: 'example.com', ttl: 3600, editing: false },
                { type: 'MX', name: '@', value: 'mail.example.com', ttl: 3600, priority: 10, editing: false },
            ];
        },
        addRecord() {
            // Verificar campos obligatorios
            if (!this.newRecord.type || !this.newRecord.name || !this.newRecord.value || !this.newRecord.ttl) {
                alert('Por favor, complete todos los campos requeridos.');
                return;
            }
            if (this.newRecord.type === 'MX' && (this.newRecord.priority === null || this.newRecord.priority === '')) {
                alert('Por favor, ingrese la prioridad para el registro MX.');
                return;
            }
            const recordToAdd = { ...this.newRecord, editing: false };
            if (recordToAdd.type !== 'MX') {
                delete recordToAdd.priority;
            }
            this.dnsRecords.push(recordToAdd);
            this.newRecord = { type: '', name: '', value: '', ttl: 3600, priority: null };
        },
        removeRecord(index) {
            this.dnsRecords.splice(index, 1);
        },
        saveDNSRecords() {
            // Aquí se enviarían los registros actualizados a la API
            alert('Registros DNS actualizados para ' + this.selectedDomain.name);
        },
        getPlaceholder(type) {
            if (type === 'A') return 'Dirección IP (ej. 192.0.2.1)';
            if (type === 'AAAA') return 'Dirección IPv6';
            if (type === 'CNAME') return 'Alias (ej. example.com)';
            if (type === 'MX') return 'Servidor de correo';
            if (type === 'TXT') return 'Texto';
            return '';
        },
        editRecord(index) {
            const record = this.dnsRecords[index];
            // Guardar respaldo
            this.originalRecords[index] = { ...record };
            record.editing = true;
        },
        saveRecord(index) {
            this.dnsRecords[index].editing = false;
            // Eliminar respaldo
            delete this.originalRecords[index];
        },
        cancelEdit(index) {
            if (this.originalRecords[index]) {
                this.$set(this.dnsRecords, index, { ...this.originalRecords[index] });
                delete this.originalRecords[index];
            }
        },
    },
}
</script>
