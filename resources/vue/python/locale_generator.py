import os
import json
from translation import translate_string

def generate_locale_json(strings, lang, translate, locales_dir):
    """Genera un archivo JSON de locales para un idioma específico."""
    locale_path = os.path.join(locales_dir, f"{lang}.json")

    # Cargar traducciones existentes si el archivo ya existe
    if os.path.exists(locale_path):
        with open(locale_path, 'r', encoding='utf-8') as file:
            translations = json.load(file)
    else:
        translations = {}

    # Añadir o traducir cadenas
    for string in strings:
        if string not in translations:
            translations[string] = translate_string(string, lang) if translate else ""

    # Guardar el archivo actualizado
    os.makedirs(locales_dir, exist_ok=True)
    with open(locale_path, 'w', encoding='utf-8') as file:
        json.dump(translations, file, ensure_ascii=False, indent=4)

    print(f"[INFO] Archivo de locales generado: {locale_path}")
