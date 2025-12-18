<?php
// Bloquear acceso directo al directorio /api/
header('Content-Type: application/json');
http_response_code(403);
echo json_encode(['success' => false, 'error' => 'Acceso denegado. Utilice service.php para realizar peticiones.']);
