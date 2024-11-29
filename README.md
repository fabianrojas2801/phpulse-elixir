
# PHPulseDeck

**PHPulseDeck** es un proyecto educativo orientado al backend API RESTful desarrollado en PHP, diseñado para la administración eficiente de datos y la creación de interfaces de gestión. Este proyecto facilita operaciones CRUD para varias entidades en una interfaz organizada, intuitiva y atractiva.

## Características

- **API RESTful**: CRUD completo para entidades como Clientes, Usuarios, Pedidos, Mensajes, Logs, y Productos.
- **Interfaz Modular**: Soporte para agregar nuevas entidades de manera dinámica con formularios de creación y edición en modal.
- **Dashboard Intuitivo**: Diseño responsivo en Bootstrap con un layout organizado en columnas para la administración rápida de datos.
- **Gestión de Relaciones**: Administración eficiente de entidades relacionadas, con funcionalidad de búsqueda de identificadores.
- **Búsqueda y Filtrado**: Búsqueda en tiempo real para identificar relaciones entre entidades (por ejemplo, asociar usuarios a mensajes).
- **Fácil Integración**: Pensado como un punto de partida adaptable para aplicaciones más complejas de backend en PHP.

## Tecnologías

- **PHP**: Backend para las operaciones CRUD.
- **Bootstrap**: Diseño y estilización de la interfaz.
- **SCSS**: Pendiente de subir cliente HTML con scss.
- **ANDROID**: Pendiente de subir cliente Android.
- **JavaScript y Fetch API**: Comunicación asincrónica con el backend para una experiencia de usuario sin interrupciones.
- **MySQL**: Base de datos relacional para almacenar y manejar la información de las entidades.
- **API FOTOS NASA**: Tabla photos con las fotos de la API pública de la NASA
- **ANGULAR**: Servicio para pruebas de angular hacia la API




## Instalación

1. Clona el repositorio en tu máquina local:
   ```bash
   git clone https://github.com/CarlosBasulto/PHPulse
   cd PHPulseDeck
   ```

2. Importa la estructura de la base de datos `SQL` en tu servidor MySQL local:
   - Crea una base de datos llamada `phpulsedeck`.
   - Importa el archivo `database.sql` incluido en este proyecto.

3. Configura el acceso a la base de datos en el archivo `DB.php`:
   ```php
   $this->pdo = new PDO('mysql:host=localhost;dbname=phpulsedeck', 'usuario', 'contraseña');
   ```

4. Inicia el servidor local (por ejemplo, usando XAMPP o MAMP) y asegúrate de que el proyecto esté en el directorio `htdocs`.

5. Accede a `http://localhost/PHPulseDeck/` en tu navegador para empezar a usar la aplicación.

## Uso

1. **Cargar Datos**: Usa los botones de "Cargar" para cada entidad (Clientes, Usuarios, etc.) para obtener y ver los registros existentes.
2. **Crear Nuevos Registros**: Usa el botón "Nuevo" para agregar registros a cualquier entidad en el dashboard.
3. **Editar y Eliminar**: Cada entidad se puede editar o eliminar desde el panel principal. Las ediciones se realizan en un modal con los datos pre-cargados.
4. **Búsqueda Relacionada**: Los campos de búsqueda para relaciones (como Cliente ID y Usuario ID) te permiten seleccionar los datos correctos para asociarlos a la entidad.

## Estructura del Proyecto

- **`index.php`**: Punto de entrada de la interfaz principal.
- **`DB.php`**: Configuración de la base de datos y funciones de CRUD para las entidades.
- **`api.php`**: Lógica para manejar los métodos `GET`, `POST`, `PUT`, y `DELETE` en el backend.
- **`modal-crear` y `form-crear`**: Modales dinámicos que permiten la creación y edición de registros.
- **Carpetas con clientes**: Se crean directorios de clientes para consumir la API, Android, HTML con bootraps y html con scss
  
## Scripts SQL

### Creación de la Base de Datos

```sql
-- Crear tabla de productos como ejemplo adicional
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
);
```

### Inserts de Ejemplo

```sql
INSERT INTO productos (nombre, descripcion, precio, stock) VALUES 
('Producto A', 'Descripción del producto A', 10.99, 100),
('Producto B', 'Descripción del producto B', 15.50, 50),
('Producto C', 'Descripción del producto C', 20.00, 25);
```

## Contribuciones

¡Las contribuciones son bienvenidas! Si quieres mejorar el proyecto, por favor sigue los siguientes pasos:

1. Haz un fork del proyecto.
2. Crea una nueva rama para tu funcionalidad (`git checkout -b feature/nueva-funcionalidad`).
3. Haz commit de tus cambios (`git commit -am 'Añadir nueva funcionalidad'`).
4. Haz push a la rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request.

## Licencia

Este proyecto está bajo la licencia MIT. Para más información, consulta el archivo `LICENSE` en el repositorio.
