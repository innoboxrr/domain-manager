import requests
import os
from dotenv import load_dotenv

# Cargar variables de entorno
load_dotenv()
GOOGLE_TRANSLATE_KEY = os.getenv("GOOGLE_TRANSLATE_KEY")
GOOGLE_TRANSLATE_URL = "https://translation.googleapis.com/language/translate/v2"

def translate_string(text, target_lang):
    """Traduce una cadena usando la API de Google Translate."""
    if not GOOGLE_TRANSLATE_KEY:
        raise ValueError("Google Translate API key no encontrada en .env")

    if not text.strip():  # Evitar traducir cadenas vacías
        return text

    response = requests.post(
        GOOGLE_TRANSLATE_URL,
        params={"key": GOOGLE_TRANSLATE_KEY},
        json={"q": text, "target": target_lang}
    )

    if response.status_code == 200:
        result = response.json()
        return result['data']['translations'][0]['translatedText']
    else:
        raise Exception(f"Error en la API de traducción: {response.text}")
