# Prueba Tecnica Backend - API de Productos

API RESTful desarrollada en Laravel 13 con tipado estricto (PHP 8.3) para la gestion de productos y sus precios multimoneda, cumpliendo con principios de diseno solido y arquitectura escalable.

## Requisitos del Entorno
- PHP 8.3+
- Composer
- SQLite (Configurado por defecto)

## Instalacion y Configuracion

1. Clonar el repositorio y entrar al directorio:
   ```bash
   git clone <tu-repositorio> api-productos
   cd api-productos
   ```
2. Instalar dependencias:
   ```bash
   composer install
   ```
3. Configurar el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Preparar la base de datos y sembrar datos de prueba (creara el usuario admin, divisas base y 5 productos reales):
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```
5. Iniciar el servidor local:
   ```bash
   php artisan serve
   ```

## Documentacion de la API (Swagger / OpenAPI)

La API esta completamente documentada bajo el estandar OpenAPI utilizando dedoc/scramble. 
Una vez levantado el servidor, accede a la interfaz interactiva en:
http://localhost:8000/docs/api

### Autenticacion para Pruebas (Sanctum)
Todos los endpoints (excepto el login) estan protegidos con Laravel Sanctum. Para interactuar con la API desde la interfaz de Swagger:

1. El seeder (php artisan migrate:fresh --seed) imprimira en tu terminal un Token Bearer de acceso directo.
2. Tambien puedes obtener un nuevo token utilizando el endpoint publico POST /api/login con estas credenciales preconfiguradas:
   - Email: admin@test.com
   - Password: password
3. En la interfaz de Swagger, haz clic en el boton "Authorize" (esquina superior derecha), pega el token y guardalo para enviar peticiones a los endpoints protegidos.

## Decisiones de Arquitectura

Para evitar el anti-patron de "Controladores Gordos" (Fat Controllers) y la sobreingenieria de crear un servicio monolitico central (ProductService), se implemento la siguiente estructura:

* Thin Controllers (Controladores Delgados): Los controladores (ProductController y ProductPriceController) se limitan exclusivamente a orquestar las peticiones HTTP y devolver respuestas.
* Form Requests: Toda la logica de autorizacion y reglas de validacion complejas se extrajo a clases especializadas (Ej. StoreProductRequest, UpdateProductRequest).
* Action Pattern (SRP): Las operaciones de escritura complejas se aislaron. Se creo CreateProductAction para orquestar la creacion del producto y su precio base garantizando la atomicidad mediante Database Transactions (DB::transaction). Esto asegura la integridad referencial; si la asignacion del precio falla, el producto no se crea.
* API Resources (El Patron Wrapper): Se utilizo ProductResource para encapsular y transformar la capa de presentacion hacia el cliente. El calculo dinamico del campo requerido total_cost (suma de tax_cost + manufacturing_cost) se realiza aqui, evitando almacenar datos calculables y redundantes en la base de datos.