import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-tld',
		name: "AdminDomainTlds",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainTlds',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainTld",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainTlds',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainTld",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainTlds',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainTld",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainTlds',
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