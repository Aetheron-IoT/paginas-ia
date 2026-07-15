async function generarHTML() {
    const nombre = document.getElementById('nombreNegocio').value;
    const desc = document.getElementById('descripcionNegocio').value;
    const frame = document.getElementById('frameContenedor');

    frame.innerHTML = "Generando...";

    const response = await fetch('/generar-sitio', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, desc })
    });

    const data = await response.json();
    frame.innerHTML = data.html;
    frame.setAttribute('data-html', data.html);
}

function descargarHTML() {
    const contenido = document.getElementById('frameContenedor').getAttribute('data-html');
    const blob = new Blob([contenido], { type: 'text/html' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'sitio.html';
    a.click();
}