# Prueba Técnica Backend - API de Productos

API RESTful desarrollada en **Laravel 13** con tipado estricto (**PHP 8.3**) para la gestión de productos y precios multimoneda, aplicando principios SOLID y arquitectura desacoplada.

---

## 🌐 Demostración en Vivo (Live Demo)

Para facilitar la evaluación sin necesidad de configuración local previa, el proyecto se encuentra desplegado y funcional en producción:

- **Documentación Interactiva (Swagger / OpenAPI):** [https://api-productos.apps.jhonnycarpenito.com/docs/api](https://api-productos.apps.jhonnycarpenito.com/docs/api)
- **URL Base de la API:** `https://api-productos.apps.jhonnycarpenito.com/api`

> **Credenciales de prueba preconfiguradas:**
> - **Email:** `admin@test.com`
> - **Password:** `password`
>
> *Puedes autenticarte directamente en el endpoint `POST /api/login` o usar el botón **Authorize** de la interfaz interactiva.*

---

## 💱 Divisas y Tipos de Cambio del Sistema

El sistema implementa soporte para precios multimoneda y cuenta con las siguientes divisas y tipos de cambio de referencia (con respecto a `USD` como moneda base):

| ID (`currency_id`) | Código / Símbolo | Nombre de la Divisa | Tipo de Cambio (Ref. USD) |
| :---: | :---: | :--- | :---: |
| **1** | `USD` | Dólar Estadounidense | `1.0000` (Moneda Base) |
| **2** | `EUR` | Euro | `0.9200` |
| **3** | `VES` | Bolívar Venezolano | `45.5000` |
| **4** | `COP` | Peso Colombiano | `3900.0000` |
| **5** | `GBP` | Libra Esterlina | `0.7900` |

> **Nota para evaluación:** Al consumir endpoints como `POST /api/products`, `PUT /api/products/{id}` o `POST /api/products/{id}/prices`, utiliza el número correspondiente de la columna **ID (`currency_id`)**.

---

## 🛠️ Instalación y Configuración Local

En caso de requerir ejecutar el proyecto de forma local:

### Requisitos del Sistema
- PHP 8.3+
- Composer
- SQLite

### Pasos de Instalación

1. **Clonar el repositorio y acceder a la carpeta:**
   ```bash
   git clone https://github.com/jhonnyCarpenito/api-productos.git
   cd api-productos
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   ```

3. **Configurar las variables de entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Preparar la base de datos y ejecutar los seeders:**
   *(Creará el usuario administrador, 5 divisas base y 5 productos reales con sus respectivos precios).*
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```

5. **Iniciar el servidor local de desarrollo:**
   ```bash
   php artisan serve
   ```
   Accede a la documentación local en: [http://localhost:8000/docs/api](http://localhost:8000/docs/api)

---

## 📖 Documentación de la API (OpenAPI / Scramble)

La API cuenta con documentación automatizada bajo la especificación OpenAPI 3.1 generada mediante `dedoc/scramble`.

### Autenticación con Laravel Sanctum
Todos los endpoints (a excepción de `POST /api/login`) se encuentran protegidos mediante middleware `auth:sanctum`.

1. El seeder (`php artisan migrate:fresh --seed`) genera e imprime en la consola un **Token Bearer** listo para usar.
2. También puedes obtener un nuevo token enviando una petición `POST /api/login` con:
   ```json
   {
     "email": "admin@test.com",
     "password": "password"
   }
   ```
3. En la interfaz de Swagger, haz clic en el botón superior derecho **Authorize**, pega el token generado y guárdalo para enviar peticiones autenticadas.

---

## 🏛️ Decisiones de Arquitectura

Para evitar el antipatrón de *Fat Controllers* (controladores sobrecargados) y la sobreingeniería de crear un servicio monolítico (*God Object*), se implementaron los siguientes patrones:

- **Thin Controllers:** `ProductController` y `ProductPriceController` se limitan exclusivamente a recibir las peticiones HTTP, delegar la lógica y retornar las respuestas estandarizadas.
- **Form Requests:** Las reglas de validación y autorización se aislaron en clases dedicadas (`StoreProductRequest`, `UpdateProductRequest`), asegurando una entrada de datos limpia y tipada antes de llegar al controlador.
- **Action Pattern (SRP):** Las operaciones de escritura complejas se encapsularon en acciones de responsabilidad única. `CreateProductAction` coordina la persistencia del producto y la vinculación de su precio inicial dentro de una transacción atómica (`DB::transaction`), garantizando integridad referencial.
- **API Resources (Wrapper Pattern):** Se utilizó `ProductResource` para transformar la salida JSON hacia el cliente. El cálculo dinámico del campo `total_cost` (`tax_cost + manufacturing_cost`) se calcula al vuelo en esta capa, evitando redundancias en la base de datos.
- **Restricción de Unicidad en Precios:** La relación producto-precio valida a nivel de aplicación (`Rule::unique`) y base de datos una restricción compuesta `['product_id', 'currency_id']`, retornando errores 422 controlados en lugar de excepciones 500 no gestionadas.