CREATE TABLE provincias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);
CREATE TABLE ciudades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    provincia_id INT,
    FOREIGN KEY (provincia_id) REFERENCES provincias(id)
);
CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    ciudad_id INT,
    FOREIGN KEY (ciudad_id) REFERENCES ciudades(id)
);
CREATE TABLE barrios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    municipio_id INT,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id)
);
CREATE TABLE tipos_inmueble (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);
CREATE TABLE caracteristicas_inmueble (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);
CREATE TABLE inmuebles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_inmueble_id INT,
    provincia_id INT,
    ciudad_id INT,
    municipio_id INT,
    barrio_id INT,
    direccion VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2),
    superficie DECIMAL(10, 2),
    habitaciones INT,
    baños INT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_inmueble_id) REFERENCES tipos_inmueble(id),
    FOREIGN KEY (provincia_id) REFERENCES provincias(id),
    FOREIGN KEY (ciudad_id) REFERENCES ciudades(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (barrio_id) REFERENCES barrios(id)
);
CREATE TABLE inmueble_caracteristicas (
    inmueble_id INT,
    caracteristica_id INT,
    PRIMARY KEY (inmueble_id, caracteristica_id),
    FOREIGN KEY (inmueble_id) REFERENCES inmuebles(id),
    FOREIGN KEY (caracteristica_id) REFERENCES caracteristicas_inmueble(id)
);
