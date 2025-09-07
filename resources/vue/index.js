import domainRoutes from './src/routes';
import { TranslatePlugin, TitlePlugin } from './src/plugins';

export const routes = domainRoutes;

export default {
    install(app, options = {}) {
        app.use(TranslatePlugin, options.translateOptions || {});
        app.use(TitlePlugin);
    }
};
