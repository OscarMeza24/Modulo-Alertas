<?php
require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

try {
    $client = new Client([
        'base_uri' => 'https://fuvewmtivcczpqarsjkw.supabase.co/rest/v1/',
        'headers' => [
            'apikey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk',
            'Content-Type' => 'application/json'
        ]
    ]);
    
    $response = $client->request('GET', 'public?select=*');
    $statusCode = $response->getStatusCode();
    $body = $response->getBody()->getContents();
    
    if ($statusCode === 200) {
        echo "✅ Supabase connection works correctly!\n";
        echo "Debug: Response: " . $body . "\n";
    } else {
        echo "❌ Error: HTTP Status " . $statusCode . "\n";
        echo "Debug: Response: " . $body . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . "\n";
    echo "❌ Line: " . $e->getLine() . "\n";
}
