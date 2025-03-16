import os
import argparse
from extractor import extract_strings, extract_strings_from_directory
from locale_generator import generate_locale_json

# Ruta raíz del proyecto
BASE_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
SRC_DIR = os.path.join(BASE_DIR, 'src')  # Ruta al directorio src
LOCALES_DIR = os.path.join(SRC_DIR, 'locales')  # Guardar locales en src/locales

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Generar y gestionar archivos de locales para traducciones de Domain Manager.")
    parser.add_argument("languages", nargs='+', help="Idiomas objetivo (por ejemplo, es en fr)")
    parser.add_argument("-t", "--translate", action="store_true", help="Traducir automáticamente las cadenas con la API de Google Translate")
    parser.add_argument("-f", "--file", default=SRC_DIR, help="Archivo o directorio a buscar para cadenas __domain")

    args = parser.parse_args()

    # Extraer cadenas
    strings = []
    if os.path.isfile(args.file):
        print(f"[INFO] Analizando archivo: {args.file}")
        strings = extract_strings(args.file)
    elif os.path.isdir(args.file):
        strings = extract_strings_from_directory(args.file)
    else:
        print("[ERROR] Archivo o directorio no válido especificado.")
        exit(1)

    strings = list(set(strings))  # Eliminar duplicados
    if not strings:
        print("[WARNING] No se encontraron cadenas para traducir.")
        exit(0)

    # Generar archivos de locales para cada idioma
    for lang in args.languages:
        generate_locale_json(strings, lang, args.translate, LOCALES_DIR)
