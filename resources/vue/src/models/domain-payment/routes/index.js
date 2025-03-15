import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-payment',
		name: "AdminDomainPayments",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainPayments',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainPayment",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainPayments',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainPayment",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainPayments',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainPayment",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainPayments',
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