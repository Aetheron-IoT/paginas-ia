require('dotenv').config();
const express = require('express');
const cors = require('cors');
const path = require('path');
const { GoogleGenAI } = require('@google/genai');

const app = express();

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.use(express.static(__dirname));
app.use(cors());

if (!process.env.GEMINI_API_KEY) {
  console.error('Falta GEMINI_API_KEY en el archivo .env');
}

const ai = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

function limpiarBloqueMarkdown(texto) {
  let limpio = texto.trim();
  limpio = limpio.replace(/^```[a-zA-Z]*\s*/, '');
  limpio = limpio.replace(/```\s*$/, '');
  return limpio.trim();
}

app.post('/generar-sitio', async (req, res) => {
  const { nombre, desc } = req.body;

  if (!nombre || !desc) {
    return res.status(400).json({ error: 'Faltan los campos "nombre" o "desc"' });
  }

  try {
    const prompt = `Crea un sitio web con Bootstrap para "${nombre}" que hace "${desc}". Devuelve solo el código HTML.`;

    const result = await ai.models.generateContent({
      model: 'gemini-3.5-flash',
      contents: prompt,
    });

    const htmlLimpio = limpiarBloqueMarkdown(result.text);

    res.json({ html: htmlLimpio });
  } catch (error) {
    console.error('Error al generar contenido con Gemini:', error);
    res.status(500).json({ error: error.message || 'Error al generar' });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor corriendo en el puerto ${PORT}`);
});