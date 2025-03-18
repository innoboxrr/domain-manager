export default [
	{
		path: 'billings',
		name: "DomainManagerBillings",
		component: () => import("./../views/BillingsManager.vue"),
		meta: {
			title: "Domain Manager Billings",
		},
	}
];