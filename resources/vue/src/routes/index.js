import dynamicRouteImport from 'innoboxrr-vue-utils/src/router/route-import';

const appRoutes = dynamicRouteImport(import.meta.globEager('../**/routes/index.js'));

// Validar que las rutas dinámicas sean un arreglo
if (!Array.isArray(appRoutes)) {
    console.error('[DomainManagerApp] appRoutes is not an array.');
}

export default [{
	path: 'app',
	name: 'DomainManagerApp',
	redirect: { name: 'DomainManagerDashboard' },
	meta: {
		title: 'Domain Manager App',
	},
	children: [
		...appRoutes,
	],
}];
