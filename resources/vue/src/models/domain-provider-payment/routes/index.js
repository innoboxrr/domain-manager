import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-provider-payment',
		name: "AdminDomainProviderPayments",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainProviderPayments',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainProviderPayment",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainProviderPayments',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainProviderPayment",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainProviderPayments',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainProviderPayment",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainProviderPayments',
							middleware: [
								middleware.auth
							]
						}
					},
				]
			},
		]
	},
]