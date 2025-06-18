# Módulo de Alertas - SafeAlert Platform

Este módulo se encarga de la gestión de alertas y reportes en la plataforma SafeAlert.

## 🎯 Funcionalidades Principales

- 🔔 Sistema de alertas en tiempo real
- 📊 Generación de reportes estadísticos
- 🤖 Priorización de alertas con IA
- 📱 Interfaz web responsive
- 📈 Visualización de métricas

## 🚀 Instalación

1. Clonar el repositorio
2. Instalar dependencias:
```bash
composer install
```

3. Configurar variables de entorno:
```bash
cp .env.example .env
```

4. Iniciar el servidor:
```bash
php -S localhost:3003
```

## 📚 Estructura del Proyecto

```
modulo-alertas/
├── src/                 # Código fuente principal
│   ├── controllers/     # Controladores de rutas
│   ├── models/         # Modelos de datos
│   ├── services/       # Servicios de negocio
│   └── utils/          # Utilidades
├── config/             # Configuraciones
├── public/             # Archivos públicos
├── tests/              # Pruebas unitarias
└── views/              # Vistas
```

## 🛠️ API Endpoints

- `GET /api/health` - Estado del servicio
- `GET /api/alertas` - Obtener alertas
- `POST /api/alertas/generar` - Generar nuevas alertas
- `POST /api/reportes/generar` - Generar reportes

## 🔐 Variables de Entorno

- `SUPABASE_URL` - URL de Supabase
- `SUPABASE_KEY` - Key de Supabase
- `OPENAI_API_KEY` - Key de OpenAI
- `PORT` - Puerto del servidor

## 📝 Documentación Adicional

- [API Documentation](docs/api.md)
- [Testing Guide](docs/testing.md)
- [Deployment Guide](docs/deployment.md)
