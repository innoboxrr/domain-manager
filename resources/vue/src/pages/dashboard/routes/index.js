export default [
	{
		path: 'dashboard',
		name: "DomainManagerDashboard",
		component: () => import("./../views/DashboardView.vue"),
		meta: {
			title: "Domain Manager Dashboard",
		},
	}
];