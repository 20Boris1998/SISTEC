<?php include '../includes/header.php'; ?>
<style>
    .peligro { background-color: #f8d7da; }   /* rojo claro */
    .cuidado { background-color: #fff3cd; }   /* amarillo claro */
    .info    { background-color: #e0f7fa; }   /* azul claro */

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }

    .btn-volver {
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        padding: 5px 10px;
        background-color: #ccc;
        border-radius: 5px;
        color: #000;
    }
</style>

<div class="container">
    <h2>📡 Alertas de Salida de Equipos</h2>
    <a href="dashboard.php" class="btn-volver">← Volver</a>

    <p>Esta lista se actualiza automáticamente con las alertas enviadas por el módulo RFID/NFC.</p>

    <table id="tabla-alertas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mensaje</th>
                <th>Laboratorio</th>
                <th>Nivel</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <!-- Se llenará con JavaScript -->
        </tbody>
    </table>
</div>

<script>
function cargarAlertas() {
    fetch('../php/alertas/listar.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabla-alertas tbody');
            tbody.innerHTML = ''; // Limpiar contenido actual

            if (data.success) {
                data.data.forEach(alerta => {
                    const fila = document.createElement('tr');

                    // Normalizar nivel (puede venir en mayúsculas)
                    const nivel = (alerta.nivel || 'info').toLowerCase();
                    const claseNivel = ['peligro', 'cuidado', 'info'].includes(nivel) ? nivel : 'info';

                    const mensaje = alerta.mensaje || 'Sin mensaje';
                    const ubicacion = alerta.ubicacion || 'No especificado';
                    const fecha = alerta.fecha || 'Desconocida';

                    fila.className = claseNivel;

                    fila.innerHTML = `
                        <td>${alerta.id}</td>
                        <td>${mensaje}</td>
                        <td>${ubicacion}</td>
                        <td>${nivel.charAt(0).toUpperCase() + nivel.slice(1)}</td>
                        <td>${fecha}</td>
                    `;

                    tbody.appendChild(fila);
                });
            } else {
                console.error('Error al cargar alertas:', data.error);
            }
        })
        .catch(error => console.error('Error al cargar alertas:', error));
}

cargarAlertas();
setInterval(cargarAlertas, 5000);
</script>

<?php include '../includes/footer.php'; ?>
