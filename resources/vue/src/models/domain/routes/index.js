import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain',
		name: "AdminDomains",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'Domains',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomain",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear Domains',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomain",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver Domains',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomain",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar Domains',
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