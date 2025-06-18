<?php
declare(strict_types=1);

try {
    // Configurar las variables de entorno
    $url = 'https://fuvewmtivcczpqarsjkw.supabase.co/rest/v1/niveles_prioridad?select=*';
    $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk';

    // Inicializar cURL
    $ch = curl_init();
    
    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $key,
        'Authorization: Bearer ' . $key,
        'Content-Type: application/json'
    ]);

    // Ejecutar la petición
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Verificar errores
    if(curl_errno($ch)) {
        throw new Exception('Error en la conexión: ' . curl_error($ch));
    }
    
    // Cerrar cURL
    curl_close($ch);

    // Procesar respuesta
    if ($httpCode !== 200) {
        throw new Exception('Error HTTP: ' . $httpCode);
    }

    $data = json_decode($response, true);
    echo "✅ Conexión exitosa a Supabase!\n";
    echo "✅ Datos obtenidos: " . json_encode($data) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
