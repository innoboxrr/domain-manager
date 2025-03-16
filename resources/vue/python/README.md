# Locale Generator

## Descripción
Locale Generator es un script para gestionar la localización de proyectos. Permite:
- Extraer cadenas de texto de archivos específicos.
- Traducir cadenas automáticamente usando la API de Google Translate.
- Generar archivos JSON organizados por idioma.

## Requisitos
- Python 3.7 o superior
- Clave de la API de Google Translate
- Librerías externas: `dotenv`, `requests`

## Instalación
1. Clona el repositorio:
   ```bash
   git clone https://github.com/usuario/locale-generator.git
   cd locale-generator
   ```

2. Instala las dependencias:
   ```bash
   pip install -r requirements.txt
   ```

3. Crea un archivo `.env` en la raíz del proyecto:
   ```env
   GOOGLE_TRANSLATE_KEY=tu-clave-api
   ```

## Uso
Ejecuta el script desde la línea de comandos:
```bash
python locale_generate.py es fr -t -f ./project_files
```

### Opciones
- `es fr`: Idiomas objetivo para las traducciones.
- `-t`: Traducción automática.
- `-f`: Especifica el archivo o directorio a analizar.

## Ejemplo de Salida
Archivos JSON generados en la carpeta `locales/`:
```json
{
    "Welcome to the Domain Manager": "Bienvenido al administrador de dominio",
    "Learn Python easily": "Aprende Python fácilmente"
}
```

## Estructura del Proyecto
```
locale-generator/
│
├── locales/                     # Carpeta para archivos JSON generados
├── scripts/                     # Módulos organizados
│   ├── extract_strings.py       # Extracción de cadenas
│   ├── translate.py             # Traducción con la API de Google
│   ├── generate_json.py         # Generación de archivos JSON
│   ├── config.py                # Configuración de variables
│
├── locale_generate.py           # Script principal
├── .env                         # Configuración del entorno
└── README.md                    # Documentación
```

## Contribución
Si encuentras errores o tienes ideas para mejorar, abre un issue o envía un pull request.

## Licencia
Este proyecto está bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.

