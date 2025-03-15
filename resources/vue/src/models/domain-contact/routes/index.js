import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-contact',
		name: "AdminDomainContacts",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainContacts',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainContact",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainContacts',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainContact",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainContacts',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainContact",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainContacts',
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