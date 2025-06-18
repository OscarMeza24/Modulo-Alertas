<?php
require_once __DIR__ . '/vendor/autoload.php';
use SafeAlert\Services\AlertaService;
use SafeAlert\Alertas\ReporteService;
use SafeAlert\Database;

// Configurar headers para CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Constante para el tipo de contenido JSON
const CONTENT_TYPE_JSON = 'Content-Type: application/json';

//Mensaje De medulo no encontrado
$inputPhp = 'php://input'; 

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener la ruta
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Router principal
switch ($path) {
    case '/':
    case '/dashboard':
        header(CONTENT_TYPE_JSON);
        echo json_encode(['message' => 'Bienvenido al módulo de Alertas']);
        break;
    
    case '/api/health':
        header(CONTENT_TYPE_JSON);
        echo json_encode(['status' => 'ok', 'module' => 'alertas', 'port' => 3003]);
        break;
    
    case '/api/alertas':
        handleAlertasAPI($inputPhp);
        break;
    
    case '/api/alertas/generar':
        handleGenerarAlertas($inputPhp);
        break;
    
    case '/api/alertas/historial':
        handleHistorialAlertas($inputPhp);
        break;
    
    case '/api/alertas/notificaciones':
        handleNotificaciones($inputPhp);
        break;
    
    case '/api/reportes':
        handleReportesAPI($inputPhp);
        break;
    
    case '/api/reportes/generar':
        handleGenerarReporte($inputPhp);
        break;
    
    case '/api/productos':
        handleProductosAPI();
        break;
    
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint no encontrado']);
        break;
}

function handleAlertasAPI($inputPhp) {
    $alertaService = new AlertaService();
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $alertas = $alertaService->obtenerAlertasActivas();
            header(CONTENT_TYPE_JSON);
            echo json_encode(['alertas' => $alertas]);
            break;
        
        case 'POST':
            $input = json_decode(file_get_contents($inputPhp), true);
            try {
                $alerta = $alertaService->crearAlerta($input);
                header(CONTENT_TYPE_JSON);
                echo json_encode(['alerta' => $alerta]);
            } catch (\Exception $e) {
                http_response_code(400);
                header(CONTENT_TYPE_JSON);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
            
        case 'PATCH':
            $input = json_decode(file_get_contents($inputPhp), true);
            $resultado = $alertaService->actualizarAlerta(
                $input['alerta_id'],
                $input['estado'],
                $input['comentario'] ?? '',
                $input['usuario'] ?? 'sistema'
            );
            header(CONTENT_TYPE_JSON);
            echo json_encode(['success' => $resultado]);
            break;
        
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
}

function handleGenerarAlertas() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }
    
    $alertaService = new AlertaService();
    $alertasGeneradas = $alertaService->generarAlertasInteligentes();
    
    header('Content-Type: application/json');
    echo json_encode([
        'alertas_generadas' => count($alertasGeneradas),
        'alertas' => $alertasGeneradas
    ]);
}

function handleHistorialAlertas() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }

    $alertaService = new AlertaService();
    $historial = $alertaService->obtenerHistorialAlertas();

    header(CONTENT_TYPE_JSON);
    echo json_encode(['historial' => $historial]);
}

function handleNotificaciones() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }

    $alertaService = new AlertaService();
    $notificaciones = $alertaService->obtenerNotificacionesPendientes();

    header(CONTENT_TYPE_JSON);
    echo json_encode(['notificaciones' => $notificaciones]);
}

function handleReportesAPI() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }

    $reporteService = new ReporteService();
    $reportes = $reporteService->obtenerReportes();

    header(CONTENT_TYPE_JSON);
    echo json_encode(['reportes' => $reportes]);
}

function handleProductosAPI() {
    $db = Database::getInstance();
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $productos = $db->obtenerProductos();
            header(CONTENT_TYPE_JSON);
            echo json_encode(['productos' => $productos]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
}

function handleGenerarReporte($inputPhp) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }
    
    $input = json_decode(file_get_contents($inputPhp), true);
    $reporteService = new ReporteService();
    
    $reporte = $reporteService->generarReporte(
        $input['fecha_inicio'],
        $input['fecha_fin'],
        $input['tipo_reporte'] ?? 'desperdicio'
    );
    
    header(CONTENT_TYPE_JSON);
    echo json_encode(['reporte' => $reporte]);
}

