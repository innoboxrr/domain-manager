<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Encabezado -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">
                {{__domain('Billings Management')}}
            </h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Sección de Métodos de Pago -->
            <section class="mb-10">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Métodos de Pago</h2>
                    <button type="button" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none">
                        Agregar Método de Pago
                    </button>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="method in paymentMethods" :key="method.id" class="relative rounded-lg border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-credit-card text-gray-500"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ method.name }}</p>
                                <p class="text-sm text-gray-500">{{ method.details }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-end space-x-3">
                            <button type="button" class="text-sm font-medium text-blue-600 hover:underline">Editar</button>
                            <button type="button" class="text-sm font-medium text-red-600 hover:underline">Eliminar</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Historial de Pagos -->
            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Historial de Pagos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="record in paymentHistory" :key="record.id" class="hover:bg-gray-50 transition-colors">
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
        </main>
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
