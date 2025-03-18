<template>
    <TransitionRoot :show="visible" as="template">
        <Dialog as="div" class="fixed inset-0 z-50 overflow-y-auto" @close="$emit('close')">
            <div class="flex min-h-screen items-center justify-center px-4 text-center">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-black opacity-30" />
                </TransitionChild>

                <!-- Modal Content -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to="opacity-100 translate-y-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100 translate-y-0 sm:scale-100"
                    leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="inline-block transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <DialogTitle class="text-lg font-medium text-gray-900">Completar Compra</DialogTitle>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Se ha seleccionado el dominio <span class="font-semibold text-gray-900">{{ domain.name }}</span> por un precio de <span class="font-semibold text-gray-900">${{ domain.price }}</span>.
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                Por favor, revise los detalles y confirme su compra.
                            </p>
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="button" @click="$emit('close')" class="inline-flex rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </button>
                            <button type="button" @click="confirmPurchase" class="inline-flex rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                                Confirmar Compra
                            </button>
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { Dialog, DialogTitle, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue'

export default {
    name: 'CompletePurchaseModal',
    components: {
        Dialog,
        DialogTitle,
        DialogOverlay,
        TransitionChild,
        TransitionRoot,
    },
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        domain: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        confirmPurchase() {
            // Aquí iría la lógica de compra
            alert(`Compra confirmada para el dominio: ${this.domain.name}`);
            this.$emit('close');
        },
    },
}
</script>
