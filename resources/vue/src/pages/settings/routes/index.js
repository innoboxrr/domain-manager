export default [
	{
		path: 'settings',
		name: "DomainManagerSettings",
		component: () => import("./../views/SettingsView.vue"),
		meta: {
			title: "Domain Manager Settings",
		},
	}
];