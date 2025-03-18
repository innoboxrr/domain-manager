export default [
	{
		path: 'renewals',
		name: "DomainManagerRenewals",
		component: () => import("./../views/RenewalsView.vue"),
		meta: {
			title: "Domain Manager Renewals",
		},
	}
];