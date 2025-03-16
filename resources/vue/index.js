import DomainManagerApp from './src/DomainManagerApp.vue';
import domainRoutes from './src/routes';
import { createPinia } from 'pinia';
import { TranslatePlugin, TitlePlugin } from './src/plugins';

export const routes = domainRoutes;

export default {
    install(app, options = {}) {
        if (!options.pinia) {
            console.warn('Se recomienda proveer la instancia de Pinia desde la app principal');
            app.use(createPinia());
        } else {
            app.use(options.pinia);
        }
        app.use(TranslatePlugin, options.translateOptions || {});
        app.use(TitlePlugin);
        app.component('DomainManagerApp', DomainManagerApp);
    }
};
