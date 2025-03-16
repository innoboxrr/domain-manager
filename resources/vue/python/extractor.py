import os
import re

EXCLUDED_DIRS = {'docs', 'node_modules'}

def extract_strings(file_path):
    """Extrae cadenas que coincidan con __domain('') de un archivo."""
    with open(file_path, 'r', encoding='utf-8') as file:
        content = file.read()
    matches = re.findall(r"__domain\(['\"](.*?)['\"]\)", content)
    if matches:
        print(f"[INFO] Cadenas encontradas en {file_path}: {matches}")
    return matches

def extract_strings_from_directory(directory):
    """Recorre un directorio y extrae todas las cadenas __domain('') de los archivos."""
    strings = []
    print(f"[INFO] Analizando directorio: {directory}")
    for root, dirs, files in os.walk(directory):
        # Excluir directorios específicos
        dirs[:] = [d for d in dirs if d not in EXCLUDED_DIRS]
        for file in files:
            if file.endswith(('.js', '.vue', '.html', '.py')):
                file_path = os.path.join(root, file)
                strings.extend(extract_strings(file_path))
    return strings
