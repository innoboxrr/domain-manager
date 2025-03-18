<template>
    <div>
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">Gestión de Pagos</h1>
        </header>
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <!-- Sección de Métodos de Pago -->
            <section class="mb-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Métodos de Pago</h2>
                    <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none">
                        Agregar Método de Pago
                    </button>
                </div>
                <div class="mt-4">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="method in paymentMethods" :key="method.id" class="flex items-center justify-between py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <!-- Ícono representativo (ej. ícono de tarjeta de crédito) -->
                                    <svg class="h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ method.name }}</p>
                                    <p class="text-sm text-gray-500">{{ method.details }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" class="text-sm text-blue-600 hover:underline">Editar</button>
                                <button type="button" class="text-sm text-red-600 hover:underline">Eliminar</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Sección de Historial de Pagos -->
            <section>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Historial de Pagos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="record in paymentHistory" :key="record.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.method }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.amount }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="record.status === 'Exitoso' ? 'text-green-600' : 'text-red-600'">
                                    {{ record.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BillingView',
    data() {
        return {
            paymentMethods: [
                { id: 1, name: 'Tarjeta de Crédito', details: '**** **** **** 1234' },
                { id: 2, name: 'PayPal', details: 'usuario@correo.com' },
            ],
            paymentHistory: [
                { id: 1, date: '2025-03-01', method: 'Tarjeta de Crédito', amount: '$50.00', status: 'Exitoso' },
                { id: 2, date: '2025-02-15', method: 'PayPal', amount: '$30.00', status: 'Exitoso' },
                { id: 3, date: '2025-02-01', method: 'Tarjeta de Crédito', amount: '$45.00', status: 'Fallido' },
            ],
        }
    },
}
</script>
