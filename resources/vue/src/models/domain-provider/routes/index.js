import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-provider',
		name: "AdminDomainProviders",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainProviders',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainProvider",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainProviders',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainProvider",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainProviders',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainProvider",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainProviders',
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