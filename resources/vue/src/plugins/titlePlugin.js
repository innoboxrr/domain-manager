import { useGlobalStore } from '@domainStore/globalStore';

export default {
    install(app) {
        const getGlobalStore = () => {
            return useGlobalStore();
        }

        // Función para cambiar el título
        const setTitle = (newTitle) => {
            const globalStore = getGlobalStore();
            globalStore.title = newTitle;
            document.title = newTitle; // Actualizar el título del documento
        };

        // Registrar el método global
        app.config.globalProperties.$setTitle = setTitle;
        app.provide('$setTitle', setTitle);
    },
};
