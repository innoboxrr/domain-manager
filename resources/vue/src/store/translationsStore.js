import { defineStore } from 'pinia';

export const useTranslationsStore = defineStore('domain-translations', {
    state: () => ({
        currentLang: 'en', // Idioma por defecto
        translations: {}, // Traducciones cargadas
    }),
    actions: {
        setLang(lang) {
            this.currentLang = lang;
        },
        loadTranslations(translations) {
            this.translations = translations;
        },
        translate(string) {
            return this.translations[string] || string; // Devuelve la traducción o el string original
        },
    },
});
