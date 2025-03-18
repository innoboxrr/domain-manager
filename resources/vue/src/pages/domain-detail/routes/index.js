export default [
	{
		path: 'domain-detail/:domain',
		name: "DomainManagerDomainDetail",
		component: () => import("./../views/DomainDetailView.vue"),
		meta: {
			title: "Domain Manager Domain Detail",
		},
	}
];