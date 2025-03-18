export default [
	{
		path: 'buy',
		name: "DomainManagerBuy",
		component: () => import("../views/BuyView.vue"),
		meta: {
			title: "Domain Manager Buy",
		},
	}
];