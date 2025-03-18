import { defineStore } from 'pinia'
import { HomeIcon, GlobeAltIcon, ServerIcon, ClockIcon, CreditCardIcon, Cog6ToothIcon } from '@heroicons/vue/24/outline'

export const useGlobalStore = defineStore('domain-global', {
    state: () => ({
        sidebarOpen: false,
        navigation: [
            { name: 'Inicio', route: { name: 'DomainManagerDashboard' }, icon: HomeIcon, current: true },
            // { name: 'Comparar Dominios', route: { name: 'CompararDominios' }, icon: GlobeAltIcon, current: false },
            //{ name: 'Gestionar DNS', route: { name: 'GestionarDNS' }, icon: ServerIcon, current: false },
            //{ name: 'Renovaciones', route: { name: 'Renovaciones' }, icon: ClockIcon, current: false },
            { name: 'Pagos & Suscripciones', route: { name: 'DomainManagerBillings' }, icon: CreditCardIcon, current: false },
            //{ name: 'Configuración', route: { name: 'Configuracion' }, icon: Cog6ToothIcon, current: false },
        ],
        domainsList: [
            //{ id: 1, name: 'example.com', route: { name: 'DomainDetail', params: { domain: 'example.com' } }, initial: 'E', current: true },
            //{ id: 2, name: 'testdomain.net', route: { name: 'DomainDetail', params: { domain: 'testdomain.net' } }, initial: 'T', current: false },
            //{ id: 3, name: 'mywebsite.org', route: { name: 'DomainDetail', params: { domain: 'mywebsite.org' } }, initial: 'M', current: false },
        ],
        activityItems: [
            {
                user: {
                    name: 'Michael Foster',
                    imageUrl: 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                },
                action: 'Renovó el dominio example.com',
                date: 'Hace 1h',
                dateTime: '2023-01-23T11:00',
            },
        ],
    }),
    actions: {
        // Aquí puedes agregar acciones si lo requieres
    },
})
