# Logistic App — Sistema de Gestión de Inventario Farmacéutico

Sistema web desarrollado con Laravel 11 para la gestión de inventario, prescripción y entrega de medicamentos en un entorno hospitalario.

## Características Principales
- **Roles y Permisos**: Admin, Médico, Farmacia, Bodega.
- **Inventario**: Control de stock en dos ubicaciones (Bodega y Farmacia).
- **Flujos de Trabajo**:
  - Emisión de Recetas (Médico).
  - Transferencias de Stock (Bodega -> Farmacia).
  - Entrega de Medicamentos (Farmacia -> Paciente).
- **Seguridad**: Autenticación y protección de rutas.

## Requisitos
- PHP 8.2 o superior
- Composer
- MySQL 8.0 o superior
- Servidor Web (Nginx/Apache) o `php artisan serve`

## Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone <repo-url>
   cd logistic-app
   ```

2. **Instalar dependencias**:
   ```bash
   composer install
   ```

3. **Configurar el entorno**:
   ```bash
   cp .env.example .env
   ```
   Configura las variables de base de datos en `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=logistic_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generar key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrar y sembrar base de datos**:
   ```bash
   php artisan migrate --seed
   ```

## Credenciales de Acceso (Seeders)

| Rol | Email | Contraseña |
|---|---|---|
| **Admin** | `admin@logistic.com` | `password` |
| **Médico** | `medico@logistic.com` | `password` |
| **Farmacia** | `farmacia@logistic.com` | `password` |
| **Bodega** | `bodega@logistic.com` | `password` |

## Ejecución

### Servidor Local
```bash
php artisan serve
```
Acceder a: `http://127.0.0.1:8000`

### Pruebas (Tests)
Para ejecutar la suite de pruebas (Feature Tests):
```bash
php artisan test
```

## Documentación
La documentación completa se encuentra en el directorio `docs/`:
- **Reportes**: `docs/reports/`
- **Diagramas**: `docs/diagrams/`
- **Endpoints**: `postman/endpoints.md`

## Autor
Alexander - TFG Ingeniería 2026
