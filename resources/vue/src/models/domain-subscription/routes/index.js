import * as middleware from '@router/middleware'

export default [
	{
		path: 'domain-subscription',
		name: "AdminDomainSubscriptions",
		component: () => import ("./../views/AdminView.vue"),
		meta: {
			title: 'DomainSubscriptions',
			middleware: [
				middleware.auth
			]
		},
		children: [
			{
				path: 'create',
				name: "AdminCreateDomainSubscription",
				component: () => import ("./../views/CreateView.vue"),
				meta: {
					title: 'Crear DomainSubscriptions',
					middleware: [
						middleware.auth
					]
				}
			},
			{
				path: ':id',
				name: "AdminShowDomainSubscription",
				component: () => import ("./../views/ShowView.vue"),
				meta: {
					title: 'Ver DomainSubscriptions',
					middleware: [
						middleware.auth
					]
				},
				children: [
					{
						path: 'edit',
						name: "AdminEditDomainSubscription",
						component: () => import ("./../views/EditView.vue"),
						meta: {
							title: 'Editar DomainSubscriptions',
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