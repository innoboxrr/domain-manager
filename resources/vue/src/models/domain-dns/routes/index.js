import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-dns',
		name: "AdminDomainDns",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainDns',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainDns",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainDns',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainDns",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainDns',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainDns",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainDns',
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