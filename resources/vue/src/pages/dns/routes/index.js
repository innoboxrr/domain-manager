export default [
	{
		path: 'dns',
		name: "DomainManagerDns",
		component: () => import("../views/DnsView.vue"),
		meta: {
			title: "Domain Manager DNS",
		},
	}
];