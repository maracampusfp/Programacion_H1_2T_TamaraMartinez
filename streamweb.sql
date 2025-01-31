/* TAMARA MARTÍNEZ VARGAS*/
DROP DATABASE IF EXISTS streamweb;
CREATE DATABASE streamweb;
USE streamweb;

CREATE TABLE clientes (
	nombre VARCHAR(45),
    apellido VARCHAR(45),
    correo VARCHAR(150) unique,
    edad INT,
    tipoPlanBase VARCHAR(30), -- Básico 9,99 €/ Estándar 13,99 €/ Premium 17,99 €
    packDeporte BOOLEAN, -- 0 FALSE / 1 TRUE
    packCine BOOLEAN, -- 0 FALSE / 1 TRUE
    packInfantil BOOLEAN, -- 0 FALSE / 1 TRUE
    suscripcion VARCHAR(45), -- MENSUAL / ANUAL
    precioTotal DECIMAL(10,2)
);

CREATE TABLE planesBase(
	nombrePlanBase VARCHAR(45), -- Básico 9,99 €/ Estándar 13,99 €/ Premium 17,99 €
    dispositivos INT, -- Cantidad de dispositivos según el plan base
    precioPlanBase DECIMAL(10,2) -- Básico 9,99 €/ Estándar 13,99 €/ Premium 17,99 €
);

CREATE TABLE packsAdicionales(
	nombrePackAdicional VARCHAR(45), -- Deporte / Cine / Infantil
    precioPackAdicional DECIMAL(10,2) -- 6.99 / 7.99 / 4.99
);

INSERT INTO clientes (nombre, apellido, correo, edad, tipoPlanBase, packDeporte, packCine, packInfantil, suscripcion, precioTotal) VALUES
    ('Mara', 'Martínez', 'maramartinez@gmail.com', 35, 'Básico', 0, 1, 0, 'Mensual', 17.98), -- 9.99 + 7.99
    ('Carlos', 'López', 'carloslopez@gmail.com', 37, 'Estándar', 1, 0, 1, 'Anual', 311.64), -- (13.99 + 6.99 + 4.99) * 12
    ('Ana', 'Gómez', 'anagomez@hotmail.com', 46, 'Premium', 0, 1, 0, 'Mensual', 25.98), -- 17.99 + 7.99
    ('Lucía', 'Fernández', 'luciafernandez@gmail.com', 42, 'Básico', 0, 0, 1, 'Mensual', 14.98), -- 9.99 + 4.99
    ('Javier', 'Pérez', 'javierperez@yahoo.com', 68, 'Estándar', 0, 1, 0, 'Mensual', 21.98), -- 13.99 + 7.99
    ('Sofía', 'Ramírez', 'sofiaramirez@hotmail.com', 45, 'Premium', 1, 1, 1, 'Anual', 359.76), -- (17.99 + 6.99 + 7.99 + 4.99) * 12
    ('David', 'Torres', 'davidtorres@gmail.com', 31, 'Básico', 0, 0, 0, 'Mensual', 9.99), -- Solo plan básico
    ('Elena', 'Ruiz', 'elena.ruiz@gmail.com', 38, 'Estándar', 0, 0, 1, 'Mensual', 18.98), -- 13.99 + 4.99
    ('Pablo', 'Hernández', 'pablohernandez@yahoo.com', 49, 'Premium', 0, 1, 1, 'Mensual', 30.97), -- 17.99 + 7.99 + 4.99
    ('Laura', 'Vargas', 'lauravargas@hotmail.com', 41, 'Básico', 1, 1, 0, 'Anual', 296.64), -- (9.99 + 6.99 + 7.99) * 12
    ('Mateo', 'Sánchez', 'mateosanchez@gmail.com', 16, 'Básico', 0, 0, 1, 'Mensual', 14.98); -- 9.99 + 4.99 (Cliente menor de edad, solo con pack infantil)
  
INSERT INTO planesBase(nombrePlanBase, dispositivos, precioPlanBase) VALUES
	('Básico', 1, 9.99),
    ('Estándar', 2, 13.99),
    ('Premium', 4, 17.99);

INSERT INTO packsAdicionales(nombrePackAdicional, precioPackAdicional) VALUES
	('Deporte', 6.99),
    ('Cine', 7.99),
    ('Infantil', 4.99);
    
    
    
    
    