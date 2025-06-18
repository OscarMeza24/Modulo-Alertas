<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use SafeAlert\Database;
use SafeAlert\Exception\SupabaseConnectionException;

class DatabaseTest extends TestCase
{
    private Database $database;
    
    protected function setUp(): void
    {
        $this->database = Database::getInstance();
    }

    public function testConnection(): void
    {
        // Test the connection by attempting a simple query
        try {
            $this->database->obtenerAlertas();
            $this->assertTrue(true, "Connection successful");
        } catch (SupabaseConnectionException $e) {
            $this->fail("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function testGetAlertas(): void
    {
        $alertas = $this->database->obtenerAlertas();
        $this->assertIsArray($alertas);
    }

    public function testCreateAlerta(): void
    {
        $alerta = [
            'descripcion' => 'Test alerta',
            'estado' => 'activa',
            'idusuario' => 1,
            'idniveprioridad' => 1
        ];

        $result = $this->database->crearAlerta($alerta);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result[0]);
    }

    public function testUpdateAlerta(): void
    {
        $alertas = $this->database->obtenerAlertas();
        if (empty($alertas)) {
            $this->markTestSkipped('No hay alertas para actualizar');
        }

        $alerta = $alertas[0];
        $updatedAlerta = $this->database->actualizarAlerta(
            $alerta['id'],
            ['estado' => 'resuelta']
        );

        $this->assertIsArray($updatedAlerta);
        $this->assertEquals('resuelta', $updatedAlerta[0]['estado']);
    }
}
