-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2020 at 11:49 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `empleados`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)  BEGIN
        DECLARE nueva_existencia int;
        DECLARE nuevo_total decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        DECLARE actual_existencia int;
        DECLARE actual_precio decimal(10,2);


        SELECT precio,existencia INTO actual_precio,actual_existencia FROM producto WHERE codproducto = codigo;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        
        UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;
         
         SELECT nueva_existencia,nuevo_precio;
         
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_tem` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))  BEGIN
        DECLARE precio_actual decimal(10,2);

        SELECT precio INTO precio_actual FROM producto WHERE codproducto = codigo;
        
        INSERT INTO detalle_temp (token_user,codproducto,cantidad,precio_venta) 											VALUES(token_user,codigo,cantidad,precio_actual);
        
         SELECT tmp.correlativo, tmp.codproducto, p.descripcion,p.ean, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp 
        INNER JOIN producto p 
        ON tmp.codproducto = p.codproducto
        WHERE tmp.token_user = token_user;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_factura` (IN `no_factura` INT)  BEGIN
       DECLARE existe_factura int;
       DECLARE registros int;
       DECLARE a int;
       DECLARE cod_producto int;
       DECLARE cant_producto int;
       DECLARE nueva_existencia int;
       DECLARE existencia_actual int;
       
	   SET existe_factura = (SELECT COUNT(*) FROM factura WHERE nofactura = no_factura and estatus = 1);
       IF existe_factura > 0 THEN
       		CREATE TEMPORARY TABLE tbl_tmp (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                cod_prod BIGINT,
                cant_prod int);
       ELSE 
       	SELECT 0 factura;
       END IF; 
       SET a =1;
       
       SET registros = (SELECT COUNT(*) FROM detallefactura WHERE nofactura = no_factura );
       IF registros > 0 THEN
       
       INSERT INTO tbl_tmp(cod_prod,cant_prod) SELECT codproducto, cantidad  FROM detallefactura WHERE nofactura = no_factura;
       
       WHILE a <= registros DO
        SELECT cod_prod,cant_prod INTO cod_producto, cant_producto FROM tbl_tmp WHERE id = a;
        SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = cod_producto;
        
        SET nueva_existencia = existencia_actual + cant_producto;
        UPDATE producto SET existencia = nueva_existencia where codproducto = cod_producto;
        
        SET a=a+1;
        
        END WHILE;
        UPDATE factura  SET estatus = 4 WHERE nofactura = no_factura;
        DROP TABLE tbl_tmp;
        SELECT * FROM factura WHERE nofactura = no_factura;
       END IF;       
       
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_tem` (`id_detalle` INT, `token` VARCHAR(50))  BEGIN
        DELETE FROM detalle_temp WHERE correlativo = id_detalle;

        SELECT tmp.correlativo, tmp.codproducto, p.descripcion,p.ean, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp 
        INNER JOIN producto p 
        ON tmp.codproducto = p.codproducto
        WHERE tmp.token_user = token;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))  BEGIN
    
    	DECLARE factura int;
        DECLARE registros int;
        DECLARE total DECIMAL(10.2);
        DECLARE nueva_existencia int;
        DECLARE existencia_actual int;
        DECLARE tmp_cod_producto int;
        DECLARE tmp_cant_producto int;
        DECLARE a INT;
        SET a = 1;
       
        
	CREATE TEMPORARY TABLE tbl_tmp_tokenuser (id BIGINT NOT NULL AUTO_INCREMENT KEY,
                                             cod_prod BIGINT,
                                             cant_prod int); 
                                             
	SET registros =(SELECT COUNT(*) FROM detalle_temp WHERE token_user = token );
    
    IF registros > 0 THEN
    	INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto, cantidad FROM detalle_temp WHERE token_user = token;
        
        INSERT INTO factura(usuario,codcliente) VALUES (cod_usuario,cod_cliente);
        SET factura = LAST_INSERT_ID();
        
        INSERT INTO detallefactura (nofactura,codproducto,cantidad,precio_venta) SELECT (factura) as nofactura, codproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user = token;     
        
        WHILE a<= registros DO
        SELECT cod_prod,cant_prod INTO tmp_cod_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
        SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
        
        SET nueva_existencia = existencia_actual - tmp_cant_producto;
        UPDATE producto SET existencia = nueva_existencia where codproducto = tmp_cod_producto;
        
        SET a=a+1;
        
        END WHILE;
        
        SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
        UPDATE factura  SET totalfactura = total WHERE nofactura = factura;
        
        DELETE FROM detalle_temp WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM factura WHERE nofactura = factura; 
        
    ELSE
    	SELECT 0;
    END IF;
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `idcat` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `idmenu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`idcat`, `categoria`, `estatus`, `idmenu`) VALUES
(1, 'TV', 1, 2),
(2, 'REPRODUCTORES DE VIDEO', 1, 2),
(3, 'CAMARAS Y VIDEOCAMARAS', 0, 2),
(4, 'AUDIO', 1, 2),
(5, 'REFRIGERACION', 1, 5),
(6, 'LAVADO Y SECADO', 0, 5),
(7, 'COCCIÓN', 0, 5),
(8, 'VENTILACIÓN Y CALEFACCIÓN', 1, 5),
(9, 'PEQUEÑOS ELECTRODOMÉSTICOS', 1, 5),
(10, 'MAQUINAS DE COSER', 0, 5),
(11, 'BASE CAMAS Y COLCHONES', 0, 5),
(12, 'Usuarios', 1, 1),
(13, 'Productos', 1, 1),
(14, 'Clientes', 1, 1),
(15, 'Ventas', 1, 1),
(16, 'Procter & Gamble', 0, 6),
(17, 'Familia', 0, 6),
(18, 'Kellogg\'s', 0, 6),
(19, 'Gillette', 0, 6),
(20, 'Team', 0, 6),
(21, 'Cartones y Papeles', 0, 6),
(22, 'Incauca', 0, 6),
(23, 'Siegfried', 0, 6),
(24, 'MiDía', 1, 6),
(25, 'Brinsa', 0, 6),
(26, 'Solla', 0, 6),
(27, 'Reckitt Benckiser', 0, 6),
(28, 'GlaxoSmithCline', 0, 6),
(29, 'Cala', 0, 6),
(30, 'Ajinomoto', 0, 6),
(31, 'Salricas', 0, 6),
(34, 'Informes', 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nit` int(11) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `dateadd` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nit`, `nombre`, `telefono`, `direccion`, `correo`, `dateadd`, `usuario_id`, `estatus`) VALUES
(16, 1015467732, 'Maria Alejandra Mejia Chaparro', '3156339915', 'Parque Industrial San carlos 2', 'MariaA.Mejia@colcomercio.com.co', '2020-10-13 02:00:23', 24, 1),
(17, 1073166043, 'Yenny Milena Salamanca Solano', '3115459112', 'Parque Industrial San Carlos 2', 'yenny.salamanca@colcomercio.com.co', '2020-10-13 02:29:54', 25, 1),
(18, 1012321312, 'Carlos Andrés González Plazas', '3184505146', 'Parque Industrial San Carlos 2', 'Jesus.ortiza@colcomercio.com.co', '2020-10-16 02:45:34', 26, 1),
(19, 1214463067, 'edison julian diaz jimenez', '3118230447', 'cll 143 b # 145a-09', 'Juliandj6370@hotmail.com', '2020-10-17 00:32:58', 27, 1),
(20, 1073525680, 'Jesus David Ortiz Arevalo', '3183107697', 'Cra 7B # 18-12 Barrio Mexico-Funza', '', '2020-10-17 02:04:21', 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(20) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `email_emisor` varchar(200) NOT NULL,
  `direccion` text NOT NULL,
  `host` varchar(100) NOT NULL,
  `puerto` text NOT NULL,
  `password` varchar(100) NOT NULL,
  `asunto` text NOT NULL,
  `cuerpo` text NOT NULL,
  `iva` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`id`, `nit`, `nombre`, `razon_social`, `telefono`, `email_emisor`, `direccion`, `host`, `puerto`, `password`, `asunto`, `cuerpo`, `iva`) VALUES
(1, '890.900.94', 'Colombiana de Comercio', 'Colombiana de Comercio S.A, Corbeta S.A y/o\r\nAlkosto S.A', 18000914066, 'ortizarevalojesusdavid432@gmail.com', 'Parque Industrial San carlos II', 'smtp.gmail.com', '587', 'jdoamamjesus12345', 'Factura de compra', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) DEFAULT NULL,
  `codproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detallefactura`
--

INSERT INTO `detallefactura` (`correlativo`, `nofactura`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(30, 19, 292, 1, '112322'),
(31, 20, 22, 1, '1563'),
(32, 20, 292, 1, '112322'),
(34, 21, 30, 1, '3066'),
(35, 22, 30, 1, '3066'),
(36, 23, 30, 1, '3066'),
(37, 24, 38, 1, '2894'),
(38, 25, 32, 1, '2312'),
(39, 26, 32, 1, '2312'),
(40, 27, 232, 1, '79648');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(200) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detalle_temp`
--

INSERT INTO `detalle_temp` (`correlativo`, `token_user`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(112, '6f4922f45568161a8cdf4ad2299f6d23', 30, 1, '3066');

-- --------------------------------------------------------

--
-- Table structure for table `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `entradas`
--

INSERT INTO `entradas` (`correlativo`, `codproducto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(30, 22, '2020-09-15 09:49:23', 1000, '1563.00', 18),
(31, 23, '2020-09-15 10:19:16', 100, '1563.00', 18),
(32, 24, '2020-09-15 10:19:16', 100, '1563.00', 18),
(33, 25, '2020-09-15 10:19:16', 100, '1563.00', 18),
(34, 26, '2020-09-15 10:19:16', 100, '1724.00', 18),
(35, 27, '2020-09-15 10:19:16', 100, '2859.00', 18),
(36, 28, '2020-09-15 10:19:16', 100, '1850.00', 18),
(37, 29, '2020-09-15 10:19:16', 100, '1916.00', 18),
(38, 30, '2020-09-15 10:19:16', 100, '3066.00', 18),
(39, 31, '2020-09-15 10:19:16', 100, '2048.00', 18),
(40, 32, '2020-09-15 10:19:16', 100, '2312.00', 18),
(41, 33, '2020-09-15 11:22:26', 100, '992.00', 18),
(42, 34, '2020-09-15 11:22:26', 100, '992.00', 18),
(43, 35, '2020-09-15 11:22:26', 100, '2000.00', 18),
(44, 36, '2020-09-15 11:22:26', 100, '2000.00', 18),
(45, 37, '2020-09-15 11:22:26', 100, '2308.00', 18),
(46, 38, '2020-09-15 11:22:26', 100, '2894.00', 18),
(47, 39, '2020-09-15 11:22:26', 100, '5453.00', 18),
(48, 40, '2020-09-15 11:22:26', 100, '10711.00', 18),
(49, 41, '2020-09-15 11:22:26', 100, '15338.00', 18),
(50, 42, '2020-09-15 11:22:26', 100, '2122.00', 18),
(51, 43, '2020-09-15 11:22:26', 100, '4655.00', 18),
(52, 44, '2020-09-18 10:10:19', 100, '2342.00', 18),
(53, 45, '2020-09-18 10:10:19', 100, '4480.00', 18),
(54, 46, '2020-09-18 10:10:19', 100, '4124.00', 18),
(55, 47, '2020-09-18 10:10:19', 100, '1988.00', 18),
(56, 48, '2020-09-18 10:10:19', 100, '2569.00', 18),
(57, 49, '2020-09-18 10:10:19', 100, '1999.00', 18),
(58, 50, '2020-09-18 10:10:19', 100, '2854.00', 18),
(59, 51, '2020-09-18 10:10:19', 100, '3824.00', 18),
(60, 52, '2020-09-18 10:10:19', 100, '2144.00', 18),
(61, 53, '2020-09-18 10:10:19', 100, '3140.00', 18),
(62, 54, '2020-09-18 10:10:19', 100, '6525.00', 18),
(63, 55, '2020-09-18 10:10:19', 100, '3071.00', 18),
(64, 56, '2020-09-18 10:10:19', 100, '4419.00', 18),
(65, 57, '2020-09-18 10:10:19', 100, '2121.00', 18),
(66, 58, '2020-09-18 10:10:19', 100, '3602.00', 18),
(67, 59, '2020-09-18 10:10:19', 100, '113022.00', 18),
(68, 60, '2020-09-18 10:10:19', 100, '2148.00', 18),
(69, 61, '2020-09-18 10:10:19', 100, '48909.00', 18),
(70, 62, '2020-09-18 10:10:19', 100, '4041.00', 18),
(71, 63, '2020-09-18 10:10:19', 100, '4041.00', 18),
(72, 64, '2020-09-18 10:10:19', 100, '2725.00', 18),
(73, 65, '2020-09-18 10:10:19', 100, '3305.00', 18),
(74, 66, '2020-09-21 10:59:02', 100, '3278.00', 18),
(75, 67, '2020-09-21 10:59:02', 100, '2163.00', 18),
(76, 68, '2020-09-21 10:59:02', 100, '2163.00', 18),
(77, 69, '2020-09-21 10:59:02', 100, '1766.00', 18),
(78, 70, '2020-09-21 10:59:02', 100, '6635.00', 18),
(79, 71, '2020-09-21 10:59:02', 100, '1497.00', 18),
(80, 72, '2020-09-21 10:59:02', 100, '1801.00', 18),
(81, 73, '2020-09-21 10:59:02', 100, '1801.00', 18),
(82, 74, '2020-09-21 10:59:02', 100, '1797.00', 18),
(83, 75, '2020-09-21 10:59:02', 100, '1266.00', 18),
(84, 76, '2020-09-21 10:59:02', 100, '2576.00', 18),
(85, 77, '2020-09-21 10:59:02', 100, '2576.00', 18),
(86, 78, '2020-09-21 10:59:02', 100, '1416.00', 18),
(87, 79, '2020-09-21 10:59:02', 100, '987.00', 18),
(88, 80, '2020-09-21 10:59:02', 100, '1262.00', 18),
(89, 81, '2020-09-21 10:59:02', 100, '799.00', 18),
(90, 82, '2020-09-21 10:59:02', 100, '8784.00', 18),
(91, 83, '2020-09-21 10:59:02', 100, '934.00', 18),
(92, 84, '2020-09-21 10:59:02', 100, '820.00', 18),
(93, 85, '2020-09-21 10:59:02', 100, '1151.00', 18),
(94, 86, '2020-09-21 10:59:02', 100, '938.00', 18),
(95, 87, '2020-09-21 10:59:02', 100, '851.00', 18),
(96, 88, '2020-09-21 10:59:02', 100, '1911.00', 18),
(97, 89, '2020-09-21 10:59:02', 100, '1124.00', 18),
(98, 90, '2020-09-21 10:59:02', 100, '2815.00', 18),
(99, 91, '2020-09-21 10:59:02', 100, '2815.00', 18),
(100, 92, '2020-09-21 10:59:02', 100, '2225.00', 18),
(101, 93, '2020-09-21 10:59:02', 100, '2225.00', 18),
(102, 94, '2020-09-21 10:59:02', 100, '2036.00', 18),
(103, 95, '2020-09-21 10:59:02', 100, '2036.00', 18),
(104, 96, '2020-09-21 10:59:02', 100, '2417.00', 18),
(105, 97, '2020-09-21 10:59:02', 100, '1312.00', 18),
(106, 98, '2020-09-21 10:59:02', 100, '3304.00', 18),
(107, 99, '2020-09-21 10:59:02', 100, '1795.00', 18),
(108, 100, '2020-09-21 10:59:02', 100, '1498.00', 18),
(109, 101, '2020-09-21 10:59:02', 100, '1005.00', 18),
(299, 102, '2020-10-16 01:05:01', 1011, '84000.00', 18),
(300, 103, '2020-10-16 01:05:01', 340, '119000.00', 18),
(301, 104, '2020-10-16 01:05:01', 510, '149000.00', 18),
(302, 105, '2020-10-16 01:05:01', 1208, '139900.00', 18),
(303, 106, '2020-10-16 01:05:01', 760, '199900.00', 18),
(304, 107, '2020-10-16 01:05:01', 2541, '289900.00', 18),
(305, 108, '2020-10-16 01:05:01', 3001, '309900.00', 18),
(306, 109, '2020-10-16 01:05:01', 0, '389900.00', 18),
(307, 110, '2020-10-16 01:05:01', 0, '389900.00', 18),
(308, 111, '2020-10-16 01:05:01', 5, '559000.00', 18),
(309, 112, '2020-10-16 01:05:01', 76, '609900.00', 18),
(310, 113, '2020-10-16 01:05:01', 170, '609900.00', 18),
(311, 114, '2020-10-16 01:05:01', 231, '619000.00', 18),
(312, 115, '2020-10-16 01:05:01', 0, '819000.00', 18),
(313, 116, '2020-10-16 01:05:01', 596, '849900.00', 18),
(314, 117, '2020-10-16 01:05:01', 137, '639900.00', 18),
(315, 118, '2020-10-16 01:05:01', 157, '919900.00', 18),
(316, 119, '2020-10-16 01:05:01', 35, '399000.00', 18),
(317, 120, '2020-10-16 01:05:01', 654, '54900.00', 18),
(318, 121, '2020-10-16 01:05:01', 881, '189900.00', 18),
(319, 122, '2020-10-16 01:05:01', 0, '289900.00', 18),
(320, 123, '2020-10-16 01:05:01', 207, '306900.00', 18),
(321, 124, '2020-10-16 01:05:01', 148, '239990.00', 18),
(322, 125, '2020-10-16 01:05:01', 9, '469900.00', 18),
(323, 126, '2020-10-16 01:05:01', 0, '299000.00', 18),
(324, 127, '2020-10-16 01:05:01', 10, '399000.00', 18),
(325, 128, '2020-10-16 01:05:01', 0, '109900.00', 18),
(326, 129, '2020-10-16 01:05:01', 48, '25900.00', 18),
(327, 130, '2020-10-16 01:05:01', 0, '34900.00', 18),
(328, 131, '2020-10-16 01:05:01', 110, '59900.00', 18),
(329, 132, '2020-10-16 01:05:01', 182, '79900.00', 18),
(330, 133, '2020-10-16 01:05:01', 0, '199900.00', 18),
(331, 134, '2020-10-16 01:05:01', 3, '299900.00', 18),
(332, 135, '2020-10-16 01:05:01', 0, '409900.00', 18),
(333, 136, '2020-10-16 01:05:01', 229, '399900.00', 18),
(334, 137, '2020-10-16 01:05:01', 103, '599900.00', 18),
(335, 138, '2020-10-16 01:05:01', 495, '839900.00', 18),
(336, 139, '2020-10-16 01:05:01', 21, '699900.00', 18),
(337, 140, '2020-10-16 01:05:01', 0, '999900.00', 18),
(338, 141, '2020-10-16 01:05:01', 0, '1099900.00', 18),
(339, 142, '2020-10-16 01:05:01', 795, '1299900.00', 18),
(340, 143, '2020-10-16 01:05:01', 904, '999900.00', 18),
(341, 144, '2020-10-16 01:05:01', 1641, '1999900.00', 18),
(342, 145, '2020-10-16 01:05:01', 529, '1999900.00', 18),
(343, 146, '2020-10-16 01:05:01', 1264, '2299900.00', 18),
(344, 147, '2020-10-16 01:05:01', 452, '3599900.00', 18),
(345, 148, '2020-10-16 01:05:01', 0, '4999900.00', 18),
(346, 149, '2020-10-16 01:05:01', 262, '44900.00', 18),
(347, 150, '2020-10-16 01:05:01', 0, '54900.00', 18),
(348, 151, '2020-10-16 01:05:01', 0, '19900.00', 18),
(349, 152, '2020-10-16 01:05:01', 0, '24990.00', 18),
(350, 153, '2020-10-16 01:05:01', 138, '29990.00', 18),
(351, 154, '2020-10-16 01:05:01', 330, '7150.00', 18),
(352, 155, '2020-10-16 01:05:01', 109, '78000.00', 18),
(353, 156, '2020-10-16 01:05:01', 0, '49900.00', 18),
(354, 157, '2020-10-16 01:05:01', 210, '119900.00', 18),
(355, 158, '2020-10-16 01:05:01', 9, '169900.00', 18),
(356, 159, '2020-10-16 01:05:01', 0, '189900.00', 18),
(357, 160, '2020-10-16 01:05:01', 0, '1299900.00', 18),
(358, 161, '2020-10-16 01:05:01', 0, '299900.00', 18),
(359, 162, '2020-10-16 01:05:01', 0, '749900.00', 18),
(360, 163, '2020-10-16 01:05:01', 0, '1299900.00', 18),
(361, 164, '2020-10-16 01:05:01', 0, '79900.00', 18),
(362, 165, '2020-10-16 01:05:01', 0, '611109.00', 18),
(363, 166, '2020-10-16 01:05:01', 31, '683385.00', 18),
(364, 167, '2020-10-16 01:05:01', 0, '835662.00', 18),
(365, 168, '2020-10-16 01:05:01', 1, '1062135.00', 18),
(366, 169, '2020-10-16 01:05:01', 0, '1499269.00', 18),
(367, 170, '2020-10-16 01:05:01', 0, '1745890.00', 18),
(368, 171, '2020-10-16 01:05:01', 0, '1267764.00', 18),
(369, 172, '2020-10-16 01:05:01', 54, '1500111.00', 18),
(370, 173, '2020-10-16 01:05:01', 92, '1431911.00', 18),
(371, 174, '2020-10-16 01:05:01', 1, '825607.00', 18),
(372, 175, '2020-10-16 01:05:01', 1, '768390.00', 18),
(373, 176, '2020-10-16 01:05:01', 38, '1094669.00', 18),
(374, 177, '2020-10-16 01:05:01', 124, '856160.00', 18),
(375, 178, '2020-10-16 01:05:01', 2, '913503.00', 18),
(376, 179, '2020-10-16 01:05:01', 118, '1261743.00', 18),
(377, 180, '2020-10-16 01:05:01', 0, '1278773.00', 18),
(378, 181, '2020-10-16 01:05:01', 188, '1190062.00', 18),
(379, 182, '2020-10-16 01:05:01', 142, '1295868.00', 18),
(380, 183, '2020-10-16 01:05:01', 69, '1094007.00', 18),
(381, 184, '2020-10-16 01:05:01', 0, '303948.00', 18),
(382, 185, '2020-10-16 01:05:01', 40, '302453.00', 18),
(383, 186, '2020-10-16 01:05:01', 1, '490151.00', 18),
(384, 187, '2020-10-16 01:05:01', 119, '448706.00', 18),
(385, 188, '2020-10-16 01:05:01', 21, '518113.00', 18),
(386, 189, '2020-10-16 01:05:01', 0, '350928.00', 18),
(387, 190, '2020-10-16 01:05:01', 8, '449733.00', 18),
(388, 191, '2020-10-16 01:05:01', 4, '1712540.00', 18),
(389, 192, '2020-10-16 01:05:01', 0, '226549.00', 18),
(390, 193, '2020-10-16 01:05:01', 137, '360734.00', 18),
(391, 194, '2020-10-16 01:05:01', 6, '407372.00', 18),
(392, 198, '2020-10-16 01:05:01', 1, '59996.00', 18),
(393, 199, '2020-10-16 01:05:01', 0, '105225.00', 18),
(394, 200, '2020-10-16 01:05:01', 0, '48385.00', 18),
(395, 201, '2020-10-16 01:05:01', 459, '31750.00', 18),
(396, 202, '2020-10-16 01:05:01', 203, '37105.00', 18),
(397, 203, '2020-10-16 01:05:01', 14669, '38880.00', 18),
(398, 204, '2020-10-16 01:05:01', 137, '63563.00', 18),
(399, 205, '2020-10-16 01:05:01', 668, '64335.00', 18),
(400, 206, '2020-10-16 01:05:01', 0, '84555.00', 18),
(401, 207, '2020-10-16 01:05:01', 70, '100553.00', 18),
(402, 208, '2020-10-16 01:05:01', 518, '46980.00', 18),
(403, 209, '2020-10-16 01:05:01', 56, '50891.00', 18),
(404, 210, '2020-10-16 01:05:01', 234, '119746.00', 18),
(405, 211, '2020-10-16 01:05:01', 0, '173445.00', 18),
(406, 212, '2020-10-16 01:05:01', 24, '511805.00', 18),
(407, 213, '2020-10-16 01:05:01', 8, '70982.00', 18),
(408, 214, '2020-10-16 01:05:01', 945, '77936.00', 18),
(409, 215, '2020-10-16 01:05:01', 118, '86170.00', 18),
(410, 216, '2020-10-16 01:05:01', 1, '148499.00', 18),
(411, 217, '2020-10-16 01:05:01', 121, '119888.00', 18),
(412, 218, '2020-10-16 01:05:01', 0, '180792.00', 18),
(413, 219, '2020-10-16 01:05:01', 14, '254074.00', 18),
(414, 220, '2020-10-16 01:05:01', 61, '178059.00', 18),
(415, 221, '2020-10-16 01:05:01', 271, '178062.00', 18),
(416, 222, '2020-10-16 01:05:01', 1672, '202129.00', 18),
(417, 223, '2020-10-16 01:05:01', 0, '211076.00', 18),
(418, 224, '2020-10-16 01:05:01', 2, '247197.00', 18),
(419, 225, '2020-10-16 01:05:01', 369, '289878.00', 18),
(420, 226, '2020-10-16 01:05:01', 5142, '54186.00', 18),
(421, 227, '2020-10-16 01:05:01', 1, '51075.00', 18),
(422, 228, '2020-10-16 01:05:01', 0, '50910.00', 18),
(423, 229, '2020-10-16 01:05:01', 0, '57159.00', 18),
(424, 230, '2020-10-16 01:05:01', 283, '59665.00', 18),
(425, 231, '2020-10-16 01:05:01', 796, '65798.00', 18),
(426, 232, '2020-10-16 01:05:01', 473, '79648.00', 18),
(427, 233, '2020-10-16 01:05:01', 0, '129374.00', 18),
(428, 234, '2020-10-16 01:05:01', 1, '75779.00', 18),
(429, 235, '2020-10-16 01:05:01', 0, '83065.00', 18),
(430, 236, '2020-10-16 01:05:01', 0, '82114.00', 18),
(431, 237, '2020-10-16 01:05:01', 156, '112727.00', 18),
(432, 238, '2020-10-16 01:05:01', 423, '119805.00', 18),
(433, 239, '2020-10-16 01:05:01', 0, '120151.00', 18),
(434, 240, '2020-10-16 01:05:01', 616, '136623.00', 18),
(435, 241, '2020-10-16 01:05:01', 238, '230994.00', 18),
(436, 242, '2020-10-16 01:05:01', 1013, '51528.00', 18),
(437, 243, '2020-10-16 01:05:01', 5, '57002.00', 18),
(438, 244, '2020-10-16 01:05:01', 105, '63611.00', 18),
(439, 245, '2020-10-16 01:05:01', 1, '69380.00', 18),
(440, 246, '2020-10-16 01:05:01', 157, '76746.00', 18),
(441, 247, '2020-10-16 01:05:01', 23, '82070.00', 18),
(442, 248, '2020-10-16 01:05:01', 347, '83906.00', 18),
(443, 249, '2020-10-16 01:05:01', 57, '83909.00', 18),
(444, 250, '2020-10-16 01:05:01', 1448, '22311.00', 18),
(445, 251, '2020-10-16 01:05:01', 1969, '29268.00', 18),
(446, 252, '2020-10-16 01:05:01', 0, '30707.00', 18),
(447, 253, '2020-10-16 01:05:01', 0, '30992.00', 18),
(448, 254, '2020-10-16 01:05:01', 629, '77473.00', 18),
(449, 255, '2020-10-16 01:05:01', 3668, '30706.00', 18),
(450, 256, '2020-10-16 01:05:01', 0, '29285.00', 18),
(451, 257, '2020-10-16 01:05:01', 80, '30998.00', 18),
(452, 258, '2020-10-16 01:05:01', 108, '30798.00', 18),
(453, 259, '2020-10-16 01:05:01', 731, '34308.00', 18),
(454, 260, '2020-10-16 01:05:01', 810, '36939.00', 18),
(455, 261, '2020-10-16 01:05:01', 5, '67315.00', 18),
(456, 262, '2020-10-16 01:05:01', 51, '71119.00', 18),
(457, 263, '2020-10-16 01:05:01', 234, '209571.00', 18),
(458, 264, '2020-10-16 01:05:01', 2, '75938.00', 18),
(459, 265, '2020-10-16 01:05:01', 254, '79985.00', 18),
(460, 266, '2020-10-16 01:05:01', 210, '89009.00', 18),
(461, 267, '2020-10-16 01:05:01', 25, '109904.00', 18),
(462, 268, '2020-10-16 01:05:01', 73, '119740.00', 18),
(463, 269, '2020-10-16 01:05:01', 474, '77623.00', 18),
(464, 270, '2020-10-16 01:05:01', 765, '86566.00', 18),
(465, 271, '2020-10-16 01:05:01', 766, '86722.00', 18),
(466, 272, '2020-10-16 01:05:01', 25, '36118.00', 18),
(467, 273, '2020-10-16 01:05:01', 0, '117564.00', 18),
(468, 274, '2020-10-16 01:05:01', 2, '86911.00', 18),
(469, 275, '2020-10-16 01:05:01', 15, '36063.00', 18),
(470, 276, '2020-10-16 01:05:01', 0, '40503.00', 18),
(471, 277, '2020-10-16 01:05:01', 1371, '43604.00', 18),
(472, 278, '2020-10-16 01:05:01', 676, '76636.00', 18),
(473, 279, '2020-10-16 01:05:01', 3518, '43762.00', 18),
(474, 280, '2020-10-16 01:05:01', 1, '83899.00', 18),
(475, 281, '2020-10-16 01:05:01', 643, '136148.00', 18),
(476, 282, '2020-10-16 01:05:01', 681, '166581.00', 18),
(477, 283, '2020-10-16 01:05:01', 0, '245242.00', 18),
(478, 284, '2020-10-16 01:05:01', 0, '53626.00', 18),
(479, 285, '2020-10-16 01:05:01', 5835, '51327.00', 18),
(480, 286, '2020-10-16 01:05:01', 542, '61990.00', 18),
(481, 287, '2020-10-16 01:05:01', 552, '106916.00', 18),
(482, 288, '2020-10-16 01:05:01', 258, '112235.00', 18),
(483, 289, '2020-10-16 01:05:01', 566, '110529.00', 18),
(484, 290, '2020-10-16 01:05:01', 498, '109327.00', 18),
(485, 291, '2020-10-16 01:05:01', 1263, '118424.00', 18),
(486, 292, '2020-10-16 01:05:01', 849, '112322.00', 18),
(487, 293, '2020-10-16 01:05:01', 1244, '114096.00', 18),
(488, 294, '2020-10-16 01:05:01', 260, '66018.00', 18),
(489, 295, '2020-10-16 01:05:01', 3373, '66325.00', 18),
(490, 296, '2020-10-16 01:05:01', 0, '59751.00', 18),
(491, 297, '2020-10-16 01:05:01', 7, '133999.00', 18),
(492, 298, '2020-10-16 01:05:01', 1, '133683.00', 18),
(493, 299, '2020-10-16 01:05:01', 764, '151200.00', 18),
(494, 300, '2020-10-16 01:05:01', 44, '169032.00', 18),
(495, 301, '2020-10-16 01:05:01', 595, '236179.00', 18),
(496, 302, '2020-10-16 01:05:01', 653, '266930.00', 18),
(497, 303, '2020-10-16 01:05:01', 1332, '26698.00', 18),
(498, 304, '2020-10-16 01:05:01', 508, '26698.00', 18),
(499, 305, '2020-10-16 01:05:01', 1272, '77638.00', 18),
(500, 306, '2020-10-16 01:05:01', 1876, '76218.00', 18),
(501, 307, '2020-10-16 01:05:01', 404, '116350.00', 18),
(502, 308, '2020-10-16 01:05:01', 0, '5119.00', 18);

-- --------------------------------------------------------

--
-- Table structure for table `estados`
--

CREATE TABLE `estados` (
  `idestado` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estados`
--

INSERT INTO `estados` (`idestado`, `descripcion`) VALUES
(1, 'Solicitado'),
(2, 'Digitado'),
(3, 'Reservado'),
(4, 'Anulado'),
(5, 'Facturado');

-- --------------------------------------------------------

--
-- Table structure for table `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) DEFAULT NULL,
  `codcliente` int(11) DEFAULT NULL,
  `totalfactura` decimal(10,0) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `noPedido` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `factura`
--

INSERT INTO `factura` (`nofactura`, `fecha`, `usuario`, `codcliente`, `totalfactura`, `estatus`, `noPedido`) VALUES
(19, '2020-10-16 03:51:16', 18, 18, '112322', 3, 'PED0401'),
(20, '2020-10-16 07:06:36', 18, 18, '113885', 1, ''),
(21, '2020-10-17 00:57:25', 27, 19, '3066', 2, 'PEDPRUEBA'),
(22, '2020-10-17 02:08:49', 18, 20, '3066', 3, 'PED123456'),
(23, '2020-10-17 03:00:04', 18, 20, '3066', 4, 'PED10172020'),
(24, '2020-10-17 04:46:51', 27, 19, '2894', 3, 'PED10172020'),
(25, '2020-10-18 01:01:16', 18, 20, '2312', 4, 'PED010200'),
(26, '2020-10-18 01:08:31', 18, 20, '2312', 4, 'PED001002'),
(27, '2020-10-22 07:25:03', 24, 16, '79648', 3, 'PED02010201');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL,
  `menu` varchar(50) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`idmenu`, `menu`, `estatus`) VALUES
(1, 'Inicio', 1),
(2, 'ELECTRONICA', 1),
(3, 'INFORMATICA', 0),
(4, 'HOGAR', 0),
(5, 'ELECTROHOGAR', 1),
(6, 'MERCADO', 1),
(9, 'GERENCIA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `ean` varchar(15) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `idsub` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `idboton` varchar(20) NOT NULL DEFAULT 'add_product_ventas',
  `Promocion` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`codproducto`, `ean`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `idsub`, `foto`, `idboton`, `Promocion`) VALUES
(22, '7701023035699', 'Aromatica Cidron MiDia x 20', 12, '1563', 38800, '2020-09-15 09:49:23', 18, 1, 41, 'img_3fc2b38da2bca84f0d9df98418ea11ce.jpg', 'add_product_ventas', ''),
(23, '7701023035675', 'Aromatica Limoncillo MiDia x20', 12, '1563', 6516, '2020-09-15 09:49:23', 18, 1, 41, 'img_218d3530e779c1e6fd6b3b3eb3be6b00.jpg', 'add_product_ventas', ''),
(24, '7701023035682', 'Aromatica Manzanilla MiDia x20', 12, '1563', 89888, '2020-09-15 09:49:23', 18, 1, 41, 'img_b22d6c786041b04d7062788abc6e1c94.jpg', 'add_product_ventas', ''),
(25, '7701023035705', 'Aromatica Yerbabuena MiDia x20', 12, '1563', 46112, '2020-09-15 09:49:23', 18, 1, 41, 'img_f1067bbe358eeb062165ec366280ea19.jpg', 'add_product_ventas', ''),
(26, '7705946641067', 'Aromática FrutoRojos MiDíax20', 12, '1724', 79824, '2020-09-15 09:49:23', 18, 1, 41, 'img_2df7284d928107413fbb04681b61b83a.jpg', 'add_product_ventas', ''),
(27, '7705946175746', 'Frijol antioqueño MiDía 320 gr', 12, '2859', 4510, '2020-09-15 09:49:23', 18, 1, 41, 'img_cf44f5cc428543db19cf2ec926e5eaa6.jpg', 'add_product_ventas', ''),
(28, '7705946583749', 'Arvejas en lata MiDía 300gr.', 12, '1850', 3811, '2020-09-15 09:49:23', 18, 1, 41, 'img_51a42214cc0bf388adf4aa3b6a26c079.jpg', 'add_product_ventas', ''),
(29, '7705946583756', 'Arve/Zana en lata MiDía 300gr.', 12, '1916', 4210, '2020-09-15 09:49:23', 18, 1, 41, 'img_a0b1ba98a1164866f853b8e383523e9f.jpg', 'add_product_ventas', ''),
(30, '7705946583763', 'Maíz Tierno Lata MiDía 300gr.', 12, '3066', 1219, '2020-09-15 09:49:23', 18, 1, 41, 'img_218d3530e779c1e6fd6b3b3eb3be6b00.jpg', 'add_product_ventas', ''),
(31, '7705946575416', 'Arve,zana,maiz lata MiDía 300g', 12, '2048', 3920, '2020-09-15 09:49:23', 18, 1, 41, 'img_1b5c1624da72e54dd480504c32abe5ba.jpg', 'add_product_ventas', ''),
(32, '7705946575423', 'Maiz Tierno MiDía 190g.', 12, '2312', 12746, '2020-09-15 09:49:23', 18, 1, 41, 'img_6e64c3e48f4cfae13d603aa9d7ea75eb.jpg', 'add_product_ventas', ''),
(33, '7701023035835', 'Avena Hojuelas MiDia 200g', 12, '992', 196838, '2020-09-15 09:49:23', 18, 1, 41, 'img_6220c47fb20e5e0b59529a9cbfaddfc4.jpg', 'add_product_ventas', ''),
(34, '7701023968089', 'Avena Molida MiDia x 200g', 12, '992', 163336, '2020-09-15 09:49:23', 18, 1, 41, 'img_878c30f3992dd7f959df1a618a5cc833.jpg', 'add_product_ventas', ''),
(35, '7701023035859', 'Avena Fresa MiDia 250g', 12, '2000', 123424, '2020-09-15 09:49:23', 18, 1, 41, 'img_ad6095c7ef8b8089eb844e8ad79e0e07.jpg', 'add_product_ventas', ''),
(36, '7701023035842', 'Avena Vainilla MiDia 250g', 12, '2000', 125464, '2020-09-15 09:49:23', 18, 1, 41, 'img_360526a29b5643b3a417d959bd6f6a4a.jpg', 'add_product_ventas', ''),
(37, '7701023968072', 'Avena Hojuelas MiDia x 500g', 12, '2308', 117524, '2020-09-15 09:49:23', 18, 1, 41, 'img_8972c3d32f3740c8505233b568d6a8ca.jpg', 'add_product_ventas', ''),
(38, '7705946509091', 'Aceite Vegetal MiDía 500 ml    ', 12, '2894', 0, '2020-09-15 09:49:23', 18, 1, 41, 'img_4d1fed9803673a8a2a4a23049604a525.jpg', 'add_product_ventas', ''),
(39, '7705946363846', 'Aceite Vegetal MiDía 1.000 ml', 12, '5453', 45734, '2020-09-15 09:49:23', 18, 1, 41, 'img_1398abd4d6285802e19955570883f345.jpg', 'add_product_ventas', ''),
(40, '7705946509107', 'Aceite Vegetal MiDía 2000 ml', 12, '10711', 0, '2020-09-15 09:49:23', 18, 1, 41, 'img_1eebeed4a425aab94f20c1ff476481dc.jpg', 'add_product_ventas', ''),
(41, '7705946363853', 'Aceite Vegetal MiDía 3.000 ml', 12, '15338', 8419, '2020-09-15 09:49:23', 18, 1, 41, 'img_20746422ad318f70450691489c80ef73.jpg', 'add_product_ventas', ''),
(42, '7701023968102', 'Sardina Tomate Tina MiDia 155g', 12, '2122', 0, '2020-09-15 09:49:23', 18, 1, 41, 'img_ef98fdf2e8e3cf056aae3762a003bb41.jpg', 'add_product_ventas', ''),
(43, '7701023968119', 'Sardina Tomate Oval MiDia 425g', 12, '4655', 23752, '2020-09-15 09:49:23', 18, 1, 41, 'img_07cf7ef24391ce09bb17c163d8173722.jpg', 'add_product_ventas', ''),
(44, '7705946351744', 'Lenteja MiDía 460g', 12, '2342', 3492, '2020-09-18 09:49:23', 18, 1, 41, 'img_8d1296000a4ada7d24134025f1e4fae5.jpg', 'add_product_ventas', ''),
(45, '7705946152808', 'Frijo Carg Rojo MiDía x 500 Gr', 12, '4480', 3377, '2020-09-18 09:49:23', 18, 1, 41, 'img_53493d99ccaeba69616d5ca218f208c6.jpg', 'add_product_ventas', ''),
(46, '7705946015189', 'Frijol Bola Roja MiDía 500g', 12, '4124', 1853, '2020-09-18 09:49:23', 18, 1, 41, 'img_a29c8b10238c34ff30b08cf57b09f71a.jpg', 'add_product_ventas', ''),
(47, '7705946015172', 'Arveja Verde Seca MiDía 500g', 12, '1988', 0, '2020-09-18 09:49:23', 18, 1, 41, 'img_4a59a3dd9d07988e8dbf6ea713edcffa.jpg', 'add_product_ventas', ''),
(48, '7705946015165', 'Arveja Amarilla MiDía 500g', 12, '2569', 7570, '2020-09-18 09:49:23', 18, 1, 41, 'img_producto.png', 'add_product_ventas', ''),
(49, '7705946351775', 'Maiz Pira MiDía 460g', 12, '1999', 156, '2020-09-18 09:49:23', 18, 1, 41, 'img_a29c8b10238c34ff30b08cf57b09f71a.jpg', 'add_product_ventas', ''),
(50, '7705946015196', 'Garbanzo MiDía 500g', 12, '2854', 4987, '2020-09-18 09:49:23', 18, 1, 41, 'img_4a59a3dd9d07988e8dbf6ea713edcffa.jpg', 'add_product_ventas', ''),
(51, '7705946351805', 'Frijol lima Midía 460 gr', 12, '3824', 11676, '2020-09-18 09:49:23', 18, 1, 41, 'img_8d1296000a4ada7d24134025f1e4fae5.jpg', 'add_product_ventas', ''),
(52, '7705946317184', 'Alpiste Mi Día x 460g ', 12, '2144', 0, '2020-09-18 09:49:23', 18, 1, 41, 'img_ef25390867b6b750c75931708124cfdb.jpg', 'add_product_ventas', ''),
(53, '7701023036054', 'Cafe Molido MiDia 250g', 12, '3140', 39438, '2020-09-18 09:49:23', 18, 1, 41, 'img_d7c3e42bbc1114f0c9735fa813fb0c54.jpg', 'add_product_ventas', ''),
(54, '7701023968096', 'Café Molido MiDia 500', 12, '6525', 5855, '2020-09-18 09:49:23', 18, 1, 41, 'img_2ef3b96b77bd7cff964707c34902b6d7.jpg', 'add_product_ventas', ''),
(55, '7705946573504', 'Duraznos x Mitades MiDia x 410', 12, '3071', 0, '2020-09-18 09:49:23', 18, 1, 41, 'img_producto.png', 'add_product_ventas', ''),
(56, '7705946573511', 'Duraznos x Mitades MiDia x 820', 12, '4419', 225, '2020-09-18 09:49:23', 18, 1, 41, 'img_d03aba3302651380783546f78165bda8.jpg', 'add_product_ventas', ''),
(57, '7705946508780', 'Salchichas Viena MiDía 150gr', 12, '2121', 19233, '2020-09-18 09:49:23', 18, 1, 41, 'img_aca63c8ac0caed4211de2f580ee4c396.jpg', 'add_product_ventas', ''),
(58, '7705946257787', 'Galleta Sal MiDía 5 tacosx470g', 12, '3602', 56166, '2020-09-18 09:49:23', 18, 1, 41, 'img_db8a7b7edc077bd4d06f9befd8179ba5.jpg', 'add_product_ventas', ''),
(59, '7705946728867', 'KV ANIVERSARIO  GALLETA 5T + 5T ', 12, '113022', 0, '2020-09-18 09:49:23', 18, 1, 41, 'img_producto.png', 'add_product_ventas', ''),
(60, '7705946545983', 'Galleta Sal MiDía 3 tacosx282g', 12, '2148', 1530, '2020-09-18 09:49:23', 18, 1, 41, 'img_b9c488aadd479a1ecf9a4432d8d9287b.jpg', 'add_product_ventas', ''),
(61, '7705946728850', 'KV ANIVERSARIO  GALLETA 6T + 3T ', 12, '48909', 0, '2020-09-18 09:49:23', 18, 1, 41, 'img_producto.png', 'add_product_ventas', ''),
(62, '7705946418676', 'Atún Lomo Aceite MiDia x 160g', 12, '4041', 151575, '2020-09-18 09:49:23', 18, 1, 41, 'img_d3df984f055098af96323ae2fb008278.jpg', 'add_product_ventas', ''),
(63, '7705946418669', 'Atún Lomo Agua MiDia x 160g', 12, '4041', 106554, '2020-09-18 09:49:23', 18, 1, 41, 'img_afc1e873e1ec7bd162cb56481cb1df66.jpg', 'add_product_ventas', ''),
(64, '7705946418683', 'Atun Rallado MiDia x 160g', 12, '2725', 221184, '2020-09-18 09:49:23', 18, 1, 41, 'img_8bbdf5666958a98139f77a79d2122db5.jpg', 'add_product_ventas', ''),
(65, '7705946418690', 'Ensalada con Atun MiDia 160 gr', 12, '3305', 114747, '2020-09-18 09:49:23', 18, 1, 41, 'img_d5d4e49842016af19adddc97cab7d545.jpg', 'add_product_ventas', ''),
(66, '7705946719247', 'Jabón BarraLiq Azul MiDía950ml', 12, '3278', 28392, '2020-09-18 09:49:23', 18, 1, 42, 'img_producto.png', 'add_product_ventas', ''),
(67, '7705946641036', 'Jabon Liq Frut Roj MiDía 500ml', 12, '2163', 32723, '2020-09-18 09:49:23', 18, 1, 42, 'img_cefde7d1de1e71e018a42db888ff64bc.jpg', 'add_product_ventas', ''),
(68, '7705946641050', 'Jabon Liq Avena MiDía 500ml', 12, '2163', 41173, '2020-09-18 09:49:23', 18, 1, 42, 'img_41be48287a0f585e5216151ffbcc885e.jpg', 'add_product_ventas', ''),
(69, '7705946610896', 'Limpiador Bicarlim MiDía 960ml', 12, '1766', 64509, '2020-09-18 09:49:23', 18, 1, 42, 'img_443876220c03bc714d42931f5848c830.jpg', 'add_product_ventas', ''),
(70, '7705946576482', 'Crem Corporal Mag MiDía 800ml.', 12, '6635', 1899, '2020-09-18 09:49:23', 18, 1, 42, 'img_c41141c895d7dc6fa02dace87c5dcad8.jpg', 'add_product_ventas', ''),
(71, '7705946315913', 'Crem Lavaloza MiDía Limon 500g', 12, '1497', 1857760, '2020-09-18 09:49:23', 18, 1, 42, 'img_30513640fc8c1a2061e627741f9ea45c.jpg', 'add_product_ventas', ''),
(72, '7705946314800', 'Blanqueador MiDía 1800ml', 12, '1801', 170116, '2020-09-18 09:49:23', 18, 1, 42, 'img_36493e7e8213989e1413210c614be178.jpg', 'add_product_ventas', ''),
(73, '7705946660235', 'Blanqueador MiDía 1800ml B', 12, '1801', 99683, '2020-09-18 09:49:23', 18, 1, 42, 'img_cf978a626e9851eae7d1181e01997bc4.jpg', 'add_product_ventas', ''),
(74, '7705946314824', 'Ropa Color MiDía 1000ml', 12, '1797', 26847, '2020-09-18 09:49:23', 18, 1, 42, 'img_6c8b0bd961bc216b6aa60f07a8079b51.jpg', 'add_product_ventas', ''),
(75, '7705946421577', 'Suavizan Primav DP MiDia 400ml', 12, '1266', 140392, '2020-09-19 09:49:22', 18, 1, 42, 'img_a30cb6673433d261ceb6c2c5b95bef8b.jpg', 'add_product_ventas', ''),
(76, '7705946421560', 'Suavizante primav Midía 1000ml', 12, '2576', 8318, '2020-09-20 09:49:22', 18, 1, 42, 'img_7aaf54f70cf28312e5486d36e2d8f5d0.jpg', 'add_product_ventas', ''),
(77, '7705946684293', 'Suavizant Primav MiDía 1000mlB', 12, '2576', 24338, '2020-09-20 09:49:22', 18, 1, 42, 'img_producto.png', 'add_product_ventas', ''),
(78, '7705946421553', 'Limpiapisos Lavan MiDía 1000ml', 12, '1416', 786080, '2020-09-20 09:49:22', 18, 1, 42, 'img_a30cb6673433d261ceb6c2c5b95bef8b.jpg', 'add_product_ventas', ''),
(79, '7701023035712', 'Bolsa Basura MiDia 51x76 x 6u', 12, '987', 5248, '2020-09-20 09:49:22', 18, 1, 42, 'img_e4eca3f1669d018924e1067bc6c1a50b.jpg', 'add_product_ventas', ''),
(80, '7701023035729', 'Bolsa Basura MiDia 65x80 x 6', 12, '1262', 4807, '2020-09-20 09:49:22', 18, 1, 42, 'img_b303ec32efd755d651d6eaa0306d70a7.jpg', 'add_product_ventas', ''),
(81, '7705946641579', 'Bolsa Basura Blanc MiDía 43x48', 12, '799', 2767, '2020-09-20 09:49:22', 18, 1, 42, 'img_0a6a2ea028f931f2a626e5b6f4b59ca7.jpg', 'add_product_ventas', ''),
(82, '7705946463034', 'Dsp Bolsa 65x80 MiDíax60Und', 12, '8784', 2333, '2020-09-20 09:49:22', 18, 1, 42, 'img_36493e7e8213989e1413210c614be178.jpg', 'add_product_ventas', ''),
(83, '7701023035934', 'Esponja Doble Uso MiDia x 2u', 12, '934', 70, '2020-09-20 09:49:22', 18, 1, 42, 'img_c41141c895d7dc6fa02dace87c5dcad8.jpg', 'add_product_ventas', ''),
(84, '7701023035910', 'Esponja Fuerte MiDia x 1u', 12, '820', 7364, '2020-09-20 09:49:22', 18, 1, 42, 'img_43d6d2d028dad3e890b502105de778b5.jpg', 'add_product_ventas', ''),
(85, '7701023035927', 'Esponja Malla MiDia x 2u', 12, '1151', 4398, '2020-09-20 09:49:22', 18, 1, 42, 'img_ea9e6d1d952525bb5a3f9a0f908bd4f6.jpg', 'add_product_ventas', ''),
(86, '7705946486828', 'Esponja Oro/Plata MiDía x 1und', 12, '938', 5848, '2020-09-20 09:49:22', 18, 1, 42, 'img_8f26801a6e31b8b5d1299ba3de68e445.jpg', 'add_product_ventas', ''),
(87, '7705946486842', 'Esponja Espiral Acero MiDía', 12, '851', 2013, '2020-09-20 09:49:22', 18, 1, 42, 'img_342eb0f14adeb4de989aa89fef09f990.jpg', 'add_product_ventas', ''),
(88, '7701023035903', 'Esponjillas MiDia x 12u', 12, '1911', 4119, '2020-09-20 09:49:22', 18, 1, 42, 'img_41be48287a0f585e5216151ffbcc885e.jpg', 'add_product_ventas', ''),
(89, '7705946486835', 'Paño Multi MiDíax1Und 38x40cm', 12, '1124', 4341, '2020-09-20 09:49:22', 18, 1, 42, 'img_af8573ba3e4ad1de71830989ac7b6ec6.jpg', 'add_product_ventas', ''),
(90, '7701023035972', 'Guantes Corrugados MiDia T/8', 12, '2815', 2513, '2020-09-20 09:49:22', 18, 1, 42, 'img_3681ed1647c0c05c51a9c0086df8c363.jpg', 'add_product_ventas', ''),
(91, '7701023035965', 'Guantes Corrugados MiDia T/7', 12, '2815', 1948, '2020-09-20 09:49:22', 18, 1, 42, 'img_aa2055d735ffdd8139c0027110fe14b5.jpg', 'add_product_ventas', ''),
(92, '7701023035958', 'Guantes Domesticos MiDia T/8', 12, '2225', 4427, '2020-09-20 09:49:22', 18, 1, 42, 'img_76a3ee8eb02194fd4aa514e890c34c2f.jpg', 'add_product_ventas', ''),
(93, '7701023035941', 'Guantes Domesticos MiDia T/7', 12, '2225', 2710, '2020-09-20 09:49:22', 18, 1, 42, 'img_698d39262c51f930e7ecbe42b3ee2f70.jpg', 'add_product_ventas', ''),
(94, '7705946486804', 'Guante Negr ind MiDía T7 7-1/2', 12, '2036', 3561, '2020-09-20 09:49:22', 18, 1, 42, 'img_93d651fe57b1eefdbdf17c67c0c535bc.jpg', 'add_product_ventas', ''),
(95, '7705946486811', 'Guante Negr ind MiDía T8 8-1/2', 12, '2036', 2726, '2020-09-20 09:49:22', 18, 1, 42, 'img_65cc0074c71f0235a11416d6c4ed0814.jpg', 'add_product_ventas', ''),
(96, '7701023036009', 'Limpiavidrios MiDia 500cc', 12, '2417', 7617, '2020-09-20 09:49:22', 18, 1, 42, 'img_57507af0dbaa348751512ea71bce5e88.jpg', 'add_product_ventas', ''),
(97, '7701023036016', 'Limpiavidrios Rpto MiDia 500g', 12, '1312', 5242, '2020-09-20 09:49:22', 18, 1, 42, 'img_bf863e8432aea500b8468d0c8028f078.jpg', 'add_product_ventas', ''),
(98, '7701023036023', 'Desengrasa Multi MiDia 500cc', 12, '3304', 9360, '2020-09-20 09:49:22', 18, 1, 42, 'img_339fa66992572e0b42ba4003aee2753f.jpg', 'add_product_ventas', ''),
(99, '7701023036030', 'Desengr Multi Rpto MiDia 500cc', 12, '1795', 22048, '2020-09-20 09:49:22', 18, 1, 42, 'img_bf863e8432aea500b8468d0c8028f078.jpg', 'add_product_ventas', ''),
(100, '7705946532686', 'Papel Aluminio Rpto MiDía x 7m', 12, '1498', 0, '2020-09-20 09:49:22', 18, 1, 42, 'img_975528eabf16f219dc4e6a4619246cda.jpg', 'add_product_ventas', NULL),
(101, '7701023035743', 'Pelicula Extensible MiDia x20m', 12, '1005', 14696, '2020-09-20 09:49:22', 18, 1, 42, 'img_3c5f9c0335c7ccc123b965bb84ca7599.jpg', 'add_product_ventas', 'NEW'),
(102, '7701023226967', 'DVD Kalley K-DVD102', 13, '84000', 3272, '2020-10-15 09:49:22', 18, 1, 8, 'img_7701023226967e0c967731be7b2fb55c33708a1940726.jpg', 'add_product_ventas', NULL),
(103, '7701023262835', 'DVD  Kalley 2.0 K-DVD103P', 13, '119000', 305, '2020-10-15 09:49:22', 18, 1, 8, 'img_1993a248299487bc9f14a56d9e700050.jpg', 'add_product_ventas', NULL),
(104, '7701023049948', 'DVD  Kalley 2.0 HDMI K-DVD104P', 13, '149000', 445, '2020-10-15 09:49:22', 18, 1, 8, 'img_7701023049948ad38afe0878ab0cf74a24a234597211a.jpg', 'add_product_ventas', NULL),
(105, '7705946675321', 'Parlante KALLEY BSK K-BSK8W', 13, '139900', 828, '2020-10-15 09:49:22', 18, 1, 20, 'img_c50f03a460f7c12a1ed32890ceba6578.jpg', 'add_product_ventas', NULL),
(106, '7705946675338', 'Parlante KALLEY BSK K-BSK15W', 13, '199900', 598, '2020-10-15 09:49:22', 18, 1, 20, 'img_3d35663bf847ac7d26e8e3b4b27dc6d8.jpg', 'add_product_ventas', NULL),
(107, '7705946643108', 'Parlante KALLEY K-SPK30BL2 NG', 13, '289900', 2942, '2020-10-15 09:49:22', 18, 1, 20, 'img_f23a5d4d8e4816c122a7209cdf5c2bae.jpg', 'add_product_ventas', NULL),
(108, '7705946643092', 'Parlante KALLEY K-SPK50BL2 NG', 13, '309900', 2646, '2020-10-15 09:49:22', 18, 1, 20, 'img_da4d6f85ff4c7b22e54de14035c7d9b6.jpg', 'add_product_ventas', NULL),
(109, '7705946484398', 'Parlante Kalley SPK50B Azul 50 W RMS - BATERIA - Bluetooth', 13, '389900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_e9c6a81e2eaa4c1c2b8804548ff1f2e1.jpg', 'add_product_ventas', NULL),
(110, '7705946471183', 'Parlante Kalley SPK50B Rojo 50 W RMS - BATERIA - Bluetooth', 13, '389900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_6dde18c4474909ccdf4ddddcce084c69.jpg', 'add_product_ventas', NULL),
(111, '7705946251525', 'Parlante Kalley K-SPK200LED BT', 13, '559000', 5, '2020-10-15 09:49:22', 18, 1, 20, 'img_6f5a56806d6197ce8342f2634c612b58.jpg', 'add_product_ventas', NULL),
(112, '7705946471190', 'Cmb Parla +Tripo Kalley SPK200', 13, '609900', 74, '2020-10-15 09:49:22', 18, 1, 20, 'img_7705946471190ab618acd6327a6ff114870968f712e8c.jpg', 'add_product_ventas', NULL),
(113, '7705946661188', 'Parlante KALLEY SPK200TLED Ng', 13, '609900', 163, '2020-10-15 09:49:22', 18, 1, 20, 'img_3de7b659d9053463043a893ac4cfa40b.jpg', 'add_product_ventas', NULL),
(114, '7705946251518', 'Parlante Kalley K-SPK70BLED BT', 13, '619000', 155, '2020-10-15 09:49:22', 18, 1, 20, 'img_e195f84b9dbfaf866bec63302b395794.jpg', 'add_product_ventas', NULL),
(115, '7705946251532', 'Parlante Kalley K-SPK300LED BT', 13, '819000', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_b7837692812caf2d938789323c8d3c33.jpg', 'add_product_ventas', NULL),
(116, '7705946665223', 'Parlante KALLEY SPK300LLED Ng', 13, '849900', 587, '2020-10-15 09:49:22', 18, 1, 20, 'img_9013cd25f4ea3b4c6e40b7af67a7e13c.jpg', 'add_product_ventas', NULL),
(117, '7705946636599', 'Parlante KALLEY K-SPK300W 2L02', 13, '639900', 403, '2020-10-15 09:49:22', 18, 1, 20, 'img_a8ea9392fb584d83ce30ab97e3edaf17.jpg', 'add_product_ventas', NULL),
(118, '7705946616904', 'Parlante KALLEY K-SPK500W 2L02', 13, '919900', 250, '2020-10-15 09:49:22', 18, 1, 20, 'img_1ee6a94089f52dc30ecf93fe69285410.jpg', 'add_product_ventas', NULL),
(119, '7705946261449', 'Parlante Outdoor KY K-AP30BTOV', 13, '399000', 35, '2020-10-15 09:49:22', 18, 1, 20, 'img_7705946261449de65e8a314e3e0a36a7ad711b6659783.jpg', 'add_product_ventas', NULL),
(120, '7701023148078', 'Tripode Base Parlant Kalley 50', 13, '54900', 483, '2020-10-15 09:49:22', 18, 1, 3, 'img_91d2eaaad0de5fcacb1f36f7e015316a.jpg', 'add_product_ventas', NULL),
(121, '7705946546003', 'Reproduc Kalley CD/BT K-ARCDBT', 13, '189900', 858, '2020-10-15 09:49:22', 18, 1, 21, 'img_12dbdb6a1c52e382bfbf04226f1d6bbf.jpg', 'add_product_ventas', NULL),
(122, '7705946583732', 'Torre sonido en madera Kalley', 13, '289900', 0, '2020-10-15 09:49:22', 18, 1, 21, 'img_814d41f89676d8a49ba55ab86fb945bc.jpg', 'add_product_ventas', NULL),
(123, '7701023049955', 'Teatro Casa Kalley KHTR140 5.1', 13, '306900', 160, '2020-10-15 09:49:22', 18, 1, 21, 'img_1d8ad8d86f8dfd8bf5dfdfbbccced7ed.jpg', 'add_product_ventas', NULL),
(124, '7705946041638', 'Equipo Micro Kalley K-EM40BT', 13, '239990', 137, '2020-10-15 09:49:22', 18, 1, 21, 'img_7705946041638e0c967731be7b2fb55c33708a1940726.jpg', 'add_product_ventas', NULL),
(125, '7705946490979', 'Equipo micro Kalley K-AMC60T2', 13, '469900', 1, '2020-10-15 09:49:22', 18, 1, 21, 'img_7705946490979204cb4c6b8889a6a8bc3274b0c5b854b.jpg', 'add_product_ventas', NULL),
(126, '7705946220118', 'Reproductor ALL IN 1  Kalley K-ARA30BT  Bluetooth', 13, '299000', 0, '2020-10-15 09:49:22', 18, 1, 21, 'img_33c252cd682c1b3f2a082da90ab49fe8.jpg', 'add_product_ventas', NULL),
(127, '7705946212298', 'Equipo Mini KALLEY K-EM200XBT', 13, '399000', 10, '2020-10-15 09:49:22', 18, 1, 21, 'img_7705946212298681cefd36dbfee6d2f92974a1cfc074f.jpg', 'add_product_ventas', NULL),
(128, '7705946458238', 'Decodificador DVB-T2 Kalley V3', 13, '109900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_77059464582381602706e525d9607c42c9fbf7ca06dd0.jpg', 'add_product_ventas', NULL),
(129, '7705946241519', 'Antena Kall DVB Pasiva Plan Ng', 13, '25900', 43, '2020-10-15 09:49:22', 18, 1, 3, 'img_77059462415196a049e5d021fb01b07183e8683ef7187.jpg', 'add_product_ventas', NULL),
(130, '7705946322522', 'Antena Kalley Pasiva Barra PBA', 13, '34900', 0, '2020-10-15 09:49:22', 18, 1, 3, 'img_770594632252239dae802c843481e5dae87f8328ff212.jpg', 'add_product_ventas', NULL),
(131, '7705946322539', 'Antena Kalley Activa Barra', 13, '59900', 109, '2020-10-15 09:49:22', 18, 1, 3, 'img_7705946322539b713a2ee9f6d4733d4d54a46eb71aef5.jpg', 'add_product_ventas', NULL),
(132, '7705946322546', 'Antena Kalley Activa Exterior', 13, '79900', 181, '2020-10-15 09:49:22', 18, 1, 3, 'img_77059463225466d9d4274bf1725d32478a6ee1afe0fc5.jpg', 'add_product_ventas', NULL),
(133, '7705946340830', 'Barra sonido Kalley ABS 40W', 13, '199900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_7705946340830cf12aa7fff80e068c8ccd3beba3c46b7.jpg', 'add_product_ventas', NULL),
(134, '7705946340847', 'Barra sonido Kalley ABS 80W', 13, '299900', 1, '2020-10-15 09:49:22', 18, 1, 20, 'img_77059463408472e5e59b355f35cc3c82ab923fdbaa6c3.jpg', 'add_product_ventas', NULL),
(135, '7705946340823', 'Barra sonido Kalley ABSD 120W', 13, '409900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_770594634082348f9a843497ff4017190399be1d0cb82.jpg', 'add_product_ventas', NULL),
(136, '7705946377713', 'Tv22\"55cm Kalley LED22FHDF T2', 13, '399900', 72, '2020-10-15 09:49:22', 18, 1, 1, 'img_e519922ad993dac2d132677da090c11f.jpg', 'add_product_ventas', NULL),
(137, '7705946372206', 'Tv32\"81cm Kalley LED32HDF T2', 13, '599900', 816, '2020-10-15 09:49:22', 18, 1, 1, 'img_343efb6338402657b98e9c6bca6209e7.jpg', 'add_product_ventas', NULL),
(138, '7705946463560', 'Tv32\"81cm KALLEY LED32HDSFBT', 13, '839900', 1792, '2020-10-15 09:49:22', 18, 1, 1, 'img_42f8540a6f0cc3c8ae70b3583478fe1c.jpg', 'add_product_ventas', NULL),
(139, '7705946475211', 'TV KALLEY 32\" LED32HDSNBT HD', 13, '699900', 20, '2020-10-15 09:49:22', 18, 1, 1, 'img_32bfde1900b714704ab6dd5ff095a797.jpg', 'add_product_ventas', NULL),
(140, '7705946463553', 'Tv 40 FHD  T2  , Resolucion FHD  , 2 HDMI ( 1 ARC / 1CEC )  2 USB  / 16 W De Potencia / PVR Grabado', 13, '999900', 0, '2020-10-15 09:49:22', 18, 1, 1, 'img_2787f8ba6533ec729a0081c767b3236c.jpg', 'add_product_ventas', NULL),
(141, '7705946463577', 'Tv 40 Smart T2  , Resolucion FHD  , Bluetooth ,  2 HDMI ( 1 ARC / 1CEC )  2 USB  , Certificacion Ne', 13, '1099900', 0, '2020-10-15 09:49:22', 18, 1, 1, 'img_0031ca4a3c92f0f97cc96580bd120a50.jpg', 'add_product_ventas', NULL),
(142, '7705946475075', 'TV KALLEY 43\" LED43FHDSF2B FHD', 13, '1299900', 1947, '2020-10-15 09:49:22', 18, 1, 1, 'img_d08ef130b4f1ad0bffad36c036cc7ead.jpg', 'add_product_ventas', NULL),
(143, '7705946475051', 'TV43\" 108CMKALLEY LED43FHDSNBT', 13, '999900', 827, '2020-10-15 09:49:22', 18, 1, 1, 'img_bbf27c346fdbfd3131bf93120a34723d.jpg', 'add_product_ventas', NULL),
(144, '7705946463607', 'Tv50\"126cm KALLEY LED50UHDSFBT', 13, '1999900', 10816, '2020-10-15 09:49:22', 18, 1, 1, 'img_5af20bc5199c45c4a2a971cc4c412fc7.jpg', 'add_product_ventas', NULL),
(145, '7705946475204', 'TV KALLEY 50\" LED50UHDSNBT 4K', 13, '1999900', 493, '2020-10-15 09:49:22', 18, 1, 1, 'img_ac442f1c6343754a58042ccaeb12c5e5.jpg', 'add_product_ventas', NULL),
(146, '7705946421256', 'Tv55\"139cm KALLEY LED55UHDSFBT', 13, '2299900', 1219, '2020-10-15 09:49:22', 18, 1, 1, 'img_e3c0e01faec1fdd60b8808cc8e791f2d.jpg', 'add_product_ventas', NULL),
(147, '7705946436076', 'Tv65\"164cm KALLEY LED65UHDSFBT', 13, '3599900', 436, '2020-10-15 09:49:22', 18, 1, 1, 'img_affa7f440539934f6f3ccf7a8d02a9bb.jpg', 'add_product_ventas', NULL),
(148, '7705946372343', 'TV65 164cm KalleyLED65UHDSVIn', 13, '4999900', 0, '2020-10-15 09:49:22', 18, 1, 1, 'img_c18075c4de830cbc6367d2c9a5f789ac.jpg', 'add_product_ventas', NULL),
(149, '7705946318273', 'Base Kal Fija Vidrio DVD/Cons', 13, '44900', 262, '2020-10-15 09:49:22', 18, 1, 2, 'img_7705946318273f8637826062324588b6fd47863beb0df.jpg', 'add_product_ventas', NULL),
(150, '7705946318280', 'Base Fija brazo Flex DVD/Cons', 13, '54900', 0, '2020-10-15 09:49:22', 18, 1, 2, 'img_77059463182809852a74a7d7c0624496d64d30e4cc722.jpg', 'add_product_ventas', NULL),
(151, '7701023413329', 'Base Kalley Fija 13', 13, '19900', 9, '2020-10-15 09:49:22', 18, 1, 2, 'img_7701023413329f8637826062324588b6fd47863beb0df.jpg', 'add_product_ventas', NULL),
(152, '7705946171069', 'Base Fija   Kalley 23 a 42 ( Vesa 75 X 75  Hasta 200 X 200  - 30 Kg)', 13, '24990', 0, '2020-10-15 09:49:22', 18, 1, 2, 'img_770594617106933278c7461e4726a9fce930f727334a4.jpg', 'add_product_ventas', NULL),
(153, '7701023776424', 'Base Kalley Fija 32', 13, '29990', 31, '2020-10-15 09:49:22', 18, 1, 2, 'img_77010237764246208484d47ba2424ee9987b0730e954c.jpg', 'add_product_ventas', NULL),
(154, '7705946250344', 'Base Kall Fija Univ 13', 13, '7150', 2322, '2020-10-15 09:49:22', 18, 1, 2, 'img_7705946250344f692d9a780e6428d355825a29f512103.jpg', 'add_product_ventas', NULL),
(155, '7705946250337', 'Base Kall Fija 37', 13, '78000', 86, '2020-10-15 09:49:22', 18, 1, 2, 'img_7705946250337d827970c4dd4feb6d0ed33f656f57182.jpg', 'add_product_ventas', NULL),
(156, '7705946171076', 'Base Kalley BrazoFlex 13', 13, '49900', 116, '2020-10-15 09:49:22', 18, 1, 2, 'img_7705946171076e2b2c472a8e55f2aeb7fc56fc1159221.jpg', 'add_product_ventas', NULL),
(157, '7705946171083', 'Base Kalley BrazoFlex 23', 13, '119900', 445, '2020-10-15 09:49:22', 18, 1, 2, 'img_7705946171083d827970c4dd4feb6d0ed33f656f57182.jpg', 'add_product_ventas', NULL),
(158, '7705946250320', 'Base Kall Braz Flex 37', 13, '169900', 65, '2020-10-15 09:49:22', 18, 1, 2, 'img_77059462503209a45b1af2955cc5cc001ddda12c9888c.jpg', 'add_product_ventas', NULL),
(159, '7705946250313', 'Base Kall BFlexHid 23 A 55Ng', 13, '189900', 0, '2020-10-15 09:49:22', 18, 1, 2, 'img_77059462503139429411fe432f39cec4febba58b1a014.jpg', 'add_product_ventas', NULL),
(160, '7705946463584', 'Tv 43 Smart T2  , Resolucion FHD  ,Bluetooth ,  2 HDMI ( 1 ARC / 1CEC )  2 USB  , Certificacion Nex', 13, '1299900', 0, '2020-10-15 09:49:22', 18, 1, 1, 'img_32bfde1900b714704ab6dd5ff095a797.jpg', 'add_product_ventas', NULL),
(161, '7705946049177', 'Parlante Kalley K-SPK50BLED BT  50 W RMS - BATERIA - Bluetooth', 13, '299900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_e9c6a81e2eaa4c1c2b8804548ff1f2e1.jpg', 'add_product_ventas', NULL),
(162, '7705946251556', 'Parlan Kalley K-SPK300W2LED BT 300W R.M.S. subw. 10x2 led', 13, '749900', 0, '2020-10-15 09:49:22', 18, 1, 20, 'img_3d6268dc6b133b799fe20f3949cd569b.jpg', 'add_product_ventas', NULL),
(163, '7705946463539', 'TV43 109cmKALLEYLED43FHDSNIn', 13, '1299900', 0, '2020-10-15 09:49:22', 18, 1, 1, 'img_0f37b452d94c2f724ce4860052fdf985.jpg', 'add_product_ventas', NULL),
(164, '7705946341684', 'Decodificador Kalley DVB-T2', 13, '79900', 0, '2020-10-15 09:49:22', 18, 1, 7, 'img_77059463416843b1f1ab65824853985fb182755d90355.jpg', 'add_product_ventas', NULL),
(165, '7705946691123', 'Congelador Horizontal de placa fría y puerta sólida Kalley K-CH99L2  / 1 puerta/ Función Dual (enfrí', 13, '611109', 0, '2020-10-15 09:49:22', 18, 1, 44, 'img_77059466911232083e0367234245cceee8bc138c31d13.jpg', 'add_product_ventas', NULL),
(166, '7705946691130', 'Cong H KALLLEY D 142Lt 142L2\"B', 13, '683385', 31, '2020-10-15 09:49:22', 18, 1, 44, 'img_770594669113081602cbb308d7539453bfcf53d234d62.jpg', 'add_product_ventas', NULL),
(167, '7705946644556', 'Congelador Horizontal Kalley K-CH198L3-BCongelador Horizontal de placa fría y puerta sólida Kalley K', 13, '835662', 0, '2020-10-15 09:49:22', 18, 1, 44, 'img_77059466445566c9cbee592bb33b72a136a81b6d53de9.jpg', 'add_product_ventas', NULL),
(168, '7705946319034', 'Congelador Horizontal Kalley de placa fría y puerta sólida K-CH295L 02/1 puerta/ Función Dual (enfrí', 13, '1062135', 0, '2020-10-15 09:49:22', 18, 1, 44, 'img_7705946319034086acd7f5326c9979d869ce83a6ab269.jpg', 'add_product_ventas', NULL),
(169, '7705946319041', 'Congelador Horizontal de placa fría y puerta sólida Kalley K-CH418L / 2 puertas/ Función Dual (enfrí', 13, '1499269', 0, '2020-10-15 09:49:22', 18, 1, 44, 'img_77059463190411602706e525d9607c42c9fbf7ca06dd0.jpg', 'add_product_ventas', NULL),
(170, '7705946319058', 'Congelador Horizontal de placa fría y puerta sólida Kalley K-CH515L / 2 Puertas/ Función Dual (enfrí', 13, '1745890', 0, '2020-10-15 09:49:22', 18, 1, 44, 'img_7705946319058681cefd36dbfee6d2f92974a1cfc074f.jpg', 'add_product_ventas', NULL),
(171, '7705946237130', 'Nevera Tipo Vitrina (Enfriador vertical de placa fría y puerta de cristal), Capaidad 211 Lts, Temper', 13, '1267764', 0, '2020-10-15 09:49:22', 18, 1, 45, 'img_0ae4b9185b10fbd78b645b7f3f9144b6.jpg', 'add_product_ventas', NULL),
(172, '7705946237147', 'Nev Kalley Vtr 309Lt KSC309L\"B', 13, '1500111', 7, '2020-10-15 09:49:22', 18, 1, 45, 'img_a2d9e40ae39fb196e33c091247d3cfb8.jpg', 'add_product_ventas', NULL),
(173, '7705946354325', 'Vitr Horiz Ky 254Lt K-SCH254\"B', 13, '1431911', 66, '2020-10-15 09:49:22', 18, 1, 45, 'img_77059463543258af2d5d5fd71128f193f37c987a8e4b9.jpg', 'add_product_ventas', NULL),
(174, '7705946266246', 'A/C Kalley Conv 9BTU 110V\"B', 13, '825607', 1, '2020-10-15 09:49:22', 18, 1, 46, 'img_77059462662462f591f28e4ccf8e55e11b71dc5994c12.jpg', 'add_product_ventas', NULL),
(175, '7705946337731', 'A/C Kalley Conv 9BTU 220V\"B', 13, '768390', 1, '2020-10-15 09:49:22', 18, 1, 46, 'img_7705946337731750e9ea9e4f2af8d366725a6f75aad61.jpg', 'add_product_ventas', NULL),
(176, '7705946452540', 'A/C Kalley Inv 9BTU 220V\"B', 13, '1094669', 36, '2020-10-15 09:49:22', 18, 1, 46, 'img_77059464525406a049e5d021fb01b07183e8683ef7187.jpg', 'add_product_ventas', NULL),
(177, '7705946266253', 'A/C Kalley Conv 12BTU 110V\"B', 13, '856160', 101, '2020-10-15 09:49:22', 18, 1, 46, 'img_7705946266253dd5201615d3db57b5b50366102196933.jpg', 'add_product_ventas', NULL),
(178, '7705946266260', 'A/C Kalley Conv 12BTU 220V\"B', 13, '913503', 2, '2020-10-15 09:49:22', 18, 1, 46, 'img_770594626626079f057a2861790ccf33ccbaf0970195d.jpg', 'add_product_ventas', NULL),
(179, '7705946399944', 'A/C Kalley Inv 12 BTU 115V\"BWV', 13, '1261743', 108, '2020-10-15 09:49:22', 18, 1, 46, 'img_77059463999443514d8fead950818cebbc22e43693c62.jpg', 'add_product_ventas', NULL),
(180, '7705946452564', 'A/C Kalley InvERTER 12BTU 220V B', 13, '1278773', 0, '2020-10-15 09:49:22', 18, 1, 46, 'img_77059464525642f591f28e4ccf8e55e11b71dc5994c12.jpg', 'add_product_ventas', NULL),
(181, '7705946452557', 'A/C Kalley Inv 12BTU 115V\"B', 13, '1190062', 185, '2020-10-15 09:49:22', 18, 1, 46, 'img_77059464525575295e629a56b7120611df2683f24e5a8.jpg', 'add_product_ventas', NULL),
(182, '7705946399951', 'A/C Kalley Inv 12 BTU 220V\"BWV', 13, '1295868', 136, '2020-10-15 09:49:22', 18, 1, 46, 'img_7705946399951b920794cc8b9de1a3dc822daba8cfff7.jpg', 'add_product_ventas', NULL),
(183, '7705946320856', 'A/C Kalley Port 14BTU K14P02\"B', 13, '1094007', 69, '2020-10-15 09:49:22', 18, 1, 46, 'img_7705946320856e12c6893a7d4cc9bc3244c2fa19c413a.jpg', 'add_product_ventas', NULL),
(184, '7705946211611', 'Dispensador con compartimiento Kalley K-WD15C  / Botellon superior / Agua fria y caliente / Color Bl', 13, '303948', 0, '2020-10-15 09:49:22', 18, 1, 47, 'img_77059462116118af2d5d5fd71128f193f37c987a8e4b9.jpg', 'add_product_ventas', NULL),
(185, '7701023397544', 'Dis Agua MsKps Kalley K-WD5K\"G', 13, '302453', 7, '2020-10-15 09:49:22', 18, 1, 47, 'img_7701023397544338b0ea0b18cff03c773b4376111b01c.jpg', 'add_product_ventas', NULL),
(186, '7701023397551', 'Disp Agua Nev Kalley K-WD15KR', 13, '490151', 1, '2020-10-15 09:49:22', 18, 1, 47, 'img_77010233975510d84ee94de566313401de4154ebce8a2.jpg', 'add_product_ventas', NULL),
(187, '7701023127523', 'Dis Agua Filt Kalley KWDLL15\"B', 13, '448706', 79, '2020-10-15 09:49:22', 18, 1, 47, 'img_77010231275233d6ea9f58a8e8a5e95004eef32897c52.jpg', 'add_product_ventas', NULL),
(188, '7705946684903', 'Disp Agua KALLEY K-WD15B2\"G', 13, '518113', 21, '2020-10-15 09:49:22', 18, 1, 47, 'img_77059466849032cc1c47a4584019cf67eacd27ab515bc.jpg', 'add_product_ventas', NULL),
(189, '7705946609104', 'Minibar KALLEY 45 LT K-MB45G02', 13, '350928', 240, '2020-10-15 09:49:22', 18, 1, 48, 'img_343764334ac24882d680d863329ccf7e.jpg', 'add_product_ventas', NULL),
(190, '7705946256308', 'MiniBar Kalley 121 Lt KMB121\"G', 13, '449733', 1, '2020-10-15 09:49:22', 18, 1, 48, 'img_35f629b06ac3b941e7958ce8bd310dfd.jpg', 'add_product_ventas', NULL),
(191, '7705946583558', 'Lav/Sec KALLEY 12KG digital', 13, '1712540', 4, '2020-10-15 09:49:22', 18, 1, 49, 'img_eeecbbe50eb4089afb81906861760995.jpg', 'add_product_ventas', NULL),
(192, '7701023261807', 'Lavadora Manual Kalley K-BLV1S06MB01 / Capacidad de lavado 6 kg / 13.2 lbs /  320 W de potencia / Co', 13, '226549', 0, '2020-10-15 09:49:22', 18, 1, 49, 'img_25916072d7af75acb337d83618aed1e4.jpg', 'add_product_ventas', NULL),
(193, '7705946201926', 'Lav Kalley 5Kg K-LAVSA5B\"B', 13, '360734', 136, '2020-10-15 09:49:22', 18, 1, 49, 'img_$ean25916072d7af75acb337d83618aed1e4.jpg', 'add_product_ventas', NULL),
(194, '7705946374064', 'Lav Kalley 7Kg K-LAVSA7B\"B', 13, '407372', 210, '2020-10-15 09:49:22', 18, 1, 49, 'img_d3234c3b80dfdf21f50c35399686887a.jpg', 'add_product_ventas', NULL),
(198, '7701023041713', 'Batidora Kalley K-MBAM30B01, 5 velocidades,Función turbo para tener gran rendimiento ,Incluye gancho', 13, '59996', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_77010230417132f022857d4939947b2ca027f92196a32.jpg', 'add_product_ventas', NULL),
(199, '7705946492232', 'Batidora Kalley K-MBME300,5 velocidades + turbo, Funcion 2 en 1:  Batidora de mano y batidora de mes', 13, '105225', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_770594649223270ff92a14be0b2ab169a128af5d10df4.jpg', 'add_product_ventas', NULL),
(200, '7705946492249', 'Tostadora de Pan Kalley Negra  K-MTP750SS, 7 niveles de tostado,Capacidad para 2 panes,Botón de func', 13, '48385', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_641eaa1401a4101d72de44cd3ca76bb9.jpg', 'add_product_ventas', NULL),
(201, '7705946458504', 'Cafetera Kalley K-CMP1502', 13, '31750', 220, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946458504f692d9a780e6428d355825a29f512103.jpg', 'add_product_ventas', NULL),
(202, '7705946259965', 'Cafetera Kalley K-MCM4N 4T', 13, '37105', 149, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946259965ff83e196b34b68238fdc9320797d6593.jpg', 'add_product_ventas', NULL),
(203, '7701023335461', 'Cafetera Kalley K-CM100K', 13, '38880', 14326, '2020-10-15 09:49:22', 18, 1, 50, 'img_7701023335461ad38afe0878ab0cf74a24a234597211a.jpg', 'add_product_ventas', NULL),
(204, '7701023046640', 'Cafetera Kalley K-CM500K', 13, '63563', 82, '2020-10-15 09:49:22', 18, 1, 50, 'img_7701023046640d7d2b98616c01affbd5a61fbc029bed7.jpg', 'add_product_ventas', NULL),
(205, '7705946151818', 'Cafetera Kalley K-MCD900N', 13, '64335', 530, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946151818ea8e961d81b3fae9ec8536b26ee5926e.jpg', 'add_product_ventas', NULL),
(206, '7705946379120', 'Cafetera Térmica Kalley K-CM750T', 13, '84555', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946379120f2c47fbfe3ca4f8302cd77b72b04bcab.jpg', 'add_product_ventas', NULL),
(207, '7705946646123', 'Chocomix KALLEY 418ML K-MCHCA', 13, '100553', 30, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946646123ff3bfb769bddd907c25711167f12dbf7.jpg', 'add_product_ventas', NULL),
(208, '7701023375870', 'Hervidor Agua Kalley K-HA170', 13, '46980', 565, '2020-10-15 09:49:22', 18, 1, 50, 'img_7701023375870eb573cc9de494a01c299751914598435.jpg', 'add_product_ventas', NULL),
(209, '7705946379137', 'Hervidor acero Kalley K-HA150', 13, '50891', 40, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946379137904b4bc934c14b26136a7c5a331731ec.jpg', 'add_product_ventas', NULL),
(210, '7705946330169', 'Aspiradora 2en1 Kalley K-VC21N', 13, '119746', 201, '2020-10-15 09:49:22', 18, 1, 51, 'img_77059463301694b2c357a0312419a3ce4f3db4445b212.jpg', 'add_product_ventas', NULL),
(211, '7705946652742', 'Aspiradora ciclónica K-VCC12   ASPIRADORA CICLÓNICA K-VCC12, Capacidad 1.5litros, Potencia 1200W, Fi', 13, '173445', 0, '2020-10-15 09:49:22', 18, 1, 51, 'img_77059466527422e5e59b355f35cc3c82ab923fdbaa6c3.jpg', 'add_product_ventas', NULL),
(212, '7705946442961', 'Aspiradora robotica Kalley', 13, '511805', 21, '2020-10-15 09:49:22', 18, 1, 51, 'img_7705946442961e2b2c472a8e55f2aeb7fc56fc1159221.jpg', 'add_product_ventas', NULL),
(213, '7701023194617', 'Horno Ele Kalley K-HE09B', 13, '70982', 8, '2020-10-15 09:49:22', 18, 1, 50, 'img_$eaneed7e49c610fc2d1aa6b6930f1722129.jpg', 'add_product_ventas', NULL),
(214, '7701023041720', 'Horno Ele Kalley K-MHE8009N01', 13, '77936', 843, '2020-10-15 09:49:22', 18, 1, 50, 'img_afa8795e044dbbdc2e5e08a107265960.jpg', 'add_product_ventas', NULL),
(215, '7705946314909', 'Horno Ele Kalley K-HE09SS', 13, '86170', 105, '2020-10-15 09:49:22', 18, 1, 50, 'img_0f4bf4824bbe21e6b35031912cb4682f.jpg', 'add_product_ventas', NULL),
(216, '7705946017022', 'Horno Ele Kalley K-MHE18N', 13, '148499', 1148, '2020-10-15 09:49:22', 18, 1, 50, 'img_788f3ec1823d122b33bd47762c33bcfc.jpg', 'add_product_ventas', NULL),
(217, '7701023328234', 'Horno Halo Kalley K-HH1200', 13, '119888', 48, '2020-10-15 09:49:22', 18, 1, 50, 'img_a41063243a19454a80891da7403111db.jpg', 'add_product_ventas', NULL),
(218, '7705946485654', 'Horno Tostador Kalley K-MHE26N, Capacidad: 26 litro,Potencia: 1500W,Temporizador de 90 minutos,Selec', 13, '180792', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_ac49444a0725f45c65344871c2735856.jpg', 'add_product_ventas', NULL),
(219, '7705946313858', 'Horno Electrico Kalley K-MHE46N, Capacidad: 46 litro,Potencia: 1600W,Selector de temperatura desde  ', 13, '254074', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_eb573cc9de494a01c299751914598435.jpg', 'add_product_ventas', NULL),
(220, '7705946550789', 'Horno Micro KALLEY K-MWB07\"BAF', 13, '178059', 2, '2020-10-15 09:49:22', 18, 1, 50, 'img_a6f413428082b8cc5ffd9dec93bf8d25.jpg', 'add_product_ventas', NULL),
(221, '7705946550796', 'Horno Micro KALLEY K-MWB07\"NAF', 13, '178062', 1, '2020-10-15 09:49:22', 18, 1, 50, 'img_6d78039380a69240cbad4db2ef7b77fb.jpg', 'add_product_ventas', NULL),
(222, '7705946173858', 'Horno Micro Kalley K-MW07N', 13, '202129', 1578, '2020-10-15 09:49:22', 18, 1, 50, 'img_349f24d3504ffab5c7317471b72f6069.jpg', 'add_product_ventas', NULL),
(223, '7705946250238', 'Horno Micro Kalley K-MW07DUO, Capacidad 20 Lts, 700 W, 6 Opciones pre programadas de coccion, funcio', 13, '211076', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_ac0dae0b757b67b9abccd16d18973a24.jpg', 'add_product_ventas', NULL),
(224, '7705946162807', 'HORNO MICROONDAS K-MW09G CON DORADOR; Microondas 0.9 pies cúbicos (25 lts), 800W de potencia, color ', 13, '247197', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_2ff3fa2aa7e9188b9c5f39d9805aa165.jpg', 'add_product_ventas', NULL),
(225, '7705946244428', 'Horno Micro Kalley 1.1 K-MW11G', 13, '289878', 2416, '2020-10-15 09:49:22', 18, 1, 50, 'img_83777df5bb2593da186f694e2478cd8c.jpg', 'add_product_ventas', NULL),
(226, '7701023190701', 'Licuadora Kalley K-LPP40S', 13, '54186', 3294, '2020-10-15 09:49:22', 18, 1, 50, 'img_abc98e2f505698ecbf896bab8e0e1e4a.jpg', 'add_product_ventas', NULL),
(227, '7705946670241', 'Licua. Básica Blanca K-ML400B', 13, '51075', 1, '2020-10-15 09:49:22', 18, 1, 50, 'img_producto.png', 'add_product_ventas', NULL),
(228, '7705946670234', 'Licuadora Kalley Básica Negra K-ML400N, Capacidad 1.5litros, Potencia 400W, Vaso plástico + tapa con', 13, '50910', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_producto.png', 'add_product_ventas', NULL),
(229, '7701023307338', 'Licuadora Kalley KY K-LI400PBR, 6 velocidades + turbo,Microswitch de seguridad;Vaso plástico, Capaci', 13, '57159', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_47990f3fe0549b81fb5009a95a7e05d0.jpg', 'add_product_ventas', NULL),
(230, '7701023115827', 'Licuadora Kalley K-LPV40, 2 velocidades + pulso, Vaso de vidrio,  Incluye picatodo,Capacidad 1,5 Lit', 13, '59665', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_ac2f34ecc74e3330b01573ac82f5303b.jpg', 'add_product_ventas', NULL),
(231, '7705946397421', 'Licuadora Kalley K-MLP5PSS', 13, '65798', 679, '2020-10-15 09:49:22', 18, 1, 50, 'img_4bc02f3db3dece09f40827453646e5cf.jpg', 'add_product_ventas', NULL),
(232, '7705946642323', 'Licuadora Pers KALLEY K-MLP5', 13, '79648', 465, '2020-10-15 09:49:22', 18, 1, 50, 'img_9775c4bb649be5140d2e5047516d5732.jpg', 'add_product_ventas', NULL),
(233, '7705946475334', 'kit virtual Licuadora Personal KALLEY K-MLP5, Potencia de 50 W capacidad de 120g de fruta y 180g de ', 13, '129374', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_producto.png', 'add_product_ventas', NULL),
(234, '7705946397414', 'Licuadora Kalley K-MLV6BSS', 13, '75779', 1446, '2020-10-15 09:49:22', 18, 1, 50, 'img_4b4ac7c7e1f7fc05c7aefcd7d5d747ad.jpg', 'add_product_ventas', NULL),
(235, '7705946298841', 'Licuadora Kalley K-MLIV600N, 2 velocidades, Cuchilla de 4 aspas en acero inoxidable,Tapa plástica co', 13, '83065', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_06bc37f760a4aee33e6252a17f1eaa3c.jpg', 'add_product_ventas', NULL),
(236, '7705946298858', 'Licuadora Kalley K-MLIV600R,  2 velocidades, Cuchilla de 4 aspas en acero inoxidable,Tapa plástica c', 13, '82114', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_fd0fde6d3b2b9deacd0c58d3da311d55.jpg', 'add_product_ventas', NULL),
(237, '7701023046091', 'Licua Mano Kalley K-MLIM50N01', 13, '112727', 134, '2020-10-15 09:49:22', 18, 1, 50, 'img_770102304609177ecd24bac2c73f54e6a1d6549969d4f.jpg', 'add_product_ventas', NULL),
(238, '7701023282819', 'Licuadora Kalley K-B15MAV Ace', 13, '119805', 329, '2020-10-15 09:49:22', 18, 1, 50, 'img_fca31ef9884db612535e2f6a94386f60.jpg', 'add_product_ventas', NULL),
(239, '7705946406499', 'Licuadora Kalley K-MLV700TP, Perilla: 6 velocidades + smoothie + ice crush + MIX + pulso, Incluye va', 13, '120151', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_480df141f64b859fad598aa69de81504.jpg', 'add_product_ventas', NULL),
(240, '7705946204552', 'Licua Personal KalleyK-MVL700', 13, '136623', 599, '2020-10-15 09:49:22', 18, 1, 50, 'img_9dc77fe3b6e72a1f2b5b367dfc84613f.jpg', 'add_product_ventas', NULL),
(241, '7705946216289', 'Licuadora Kalley K-MLAP1500 AP', 13, '230994', 231, '2020-10-15 09:49:22', 18, 1, 50, 'img_16e5844fea60e5a9cde065861a0e0629.jpg', 'add_product_ventas', NULL),
(242, '7705946670227', 'Olla Arroz KALLEY K-RC3B2 0.6L', 13, '51528', 898, '2020-10-15 09:49:22', 18, 1, 50, 'img_producto.png', 'add_product_ventas', NULL),
(243, '7701023046961', 'Olla Arroz Kalley K-RCW10 1.0L', 13, '57002', 5, '2020-10-15 09:49:22', 18, 1, 50, 'img_5f67463697d5773972be82a1e8f5061e.jpg', 'add_product_ventas', NULL),
(244, '7701023770835', 'Olla Arroz Kalley K-RCW14 1.4L', 13, '63611', 36, '2020-10-15 09:49:22', 18, 1, 50, 'img_90b6f80ea938e43acb51e0fd6ee1e570.jpg', 'add_product_ventas', NULL),
(245, '7701023770842', 'Olla Arroz Kalley K-RCW18 1.8L', 13, '69380', 1, '2020-10-15 09:49:22', 18, 1, 50, 'img_463ef4c0ea98031cc3539957234d7725.jpg', 'add_product_ventas', NULL),
(246, '7701023770859', 'Olla Arroz Kalley K-RCS14 1.4L', 13, '76746', 112, '2020-10-15 09:49:22', 18, 1, 50, 'img_16877250862421d8d5435ba1f1ee13ec.jpg', 'add_product_ventas', NULL),
(247, '7701023770866', 'Olla Arroz Kalley K-RCS18 1.8L', 13, '82070', 23, '2020-10-15 09:49:22', 18, 1, 50, 'img_3845d8fd0507fe2f4e17afeb567963a1.jpg', 'add_product_ventas', NULL),
(248, '7701023354783', 'Olla Arroz Kalley K-DRC14 Dg R', 13, '83906', 335, '2020-10-15 09:49:22', 18, 1, 50, 'img_c01ea0e1930813c03b0b51760fece1ac.jpg', 'add_product_ventas', NULL),
(249, '7701023354752', 'Olla Arroz Kalley K-DRC14 Dg N', 13, '83909', 42, '2020-10-15 09:49:22', 18, 1, 50, 'img_da6766f3fedd50ea10320c72fea75ca1.jpg', 'add_product_ventas', NULL),
(250, '7705946441223', 'Plancha seca Kalley K-MPS100P', 13, '22311', 3201, '2020-10-15 09:49:22', 18, 1, 52, 'img_feb560d61ffe217745d726778badc530.jpg', 'add_product_ventas', NULL),
(251, '7705946046664', 'Plancha Seca KY  K-MPLS100', 13, '29268', 1836, '2020-10-15 09:49:22', 18, 1, 52, 'img_aec15b02e8a7c971c7b60e99e57c0b28.jpg', 'add_product_ventas', NULL),
(252, '7701023119757', 'Plancha Vapor Kalley K-PB100AN', 13, '30707', 2814, '2020-10-15 09:49:22', 18, 1, 52, 'img_62e9ea2e50767b1063e9037b1da9f4d5.jpg', 'add_product_ventas', NULL),
(253, '7701023178945', 'Plancha Vapor Kalley K-PB100AA', 13, '30992', 3114, '2020-10-15 09:49:22', 18, 1, 52, 'img_492b59a8975458c74303c63ee98427a5.jpg', 'add_product_ventas', NULL),
(254, '7705946558150', 'Plancha Vapor KALLEY K-MPV1500', 13, '77473', 617, '2020-10-15 09:49:22', 18, 1, 52, 'img_e3c878935fe4814729724bd754f40f37.jpg', 'add_product_ventas', NULL),
(255, '7701023114356', 'Sanduchera Kalley K-SM101', 13, '30706', 2378, '2020-10-15 09:49:22', 18, 1, 50, 'img_1fe9adf2f8e14a6e065d3d0efd1135d3.jpg', 'add_product_ventas', NULL),
(256, '7705946369985', 'Sanduchera Kalley K-SM102 B', 13, '29285', 2055, '2020-10-15 09:49:22', 18, 1, 50, 'img_98af3ffc43dd56e0c31f48635406950e.jpg', 'add_product_ventas', NULL),
(257, '7705946371032', 'Sanduchera Kalley K-SMP500 N', 13, '30998', 341, '2020-10-15 09:49:22', 18, 1, 50, 'img_3b5473c2b3f2dcfbcdb4c8414ab79834.jpg', 'add_product_ventas', NULL),
(258, '7701023114363', 'Panini Kalley K-SMP200N', 13, '30798', 16, '2020-10-15 09:49:22', 18, 1, 50, 'img_87aedfd0998762064f5609510169bb75.jpg', 'add_product_ventas', NULL),
(259, '7705946369992', 'Sanduchera Kalley K-SM400N N', 13, '34308', 1403, '2020-10-15 09:49:22', 18, 1, 50, 'img_9865a50227cc49a651469168c72c9cb1.jpg', 'add_product_ventas', NULL),
(260, '7701023822220', 'Sanduchera Kalley K-SM300N N', 13, '36939', 640, '2020-10-15 09:49:22', 18, 1, 50, 'img_384c85cf51ecf545461dadde272c5cf5.jpg', 'add_product_ventas', NULL),
(261, '7705946489881', 'Sanduchera 3en1 KALLEY K-SM31', 13, '67315', 2663, '2020-10-15 09:49:22', 18, 1, 50, 'img_102120a0b24e166910a62b695ffc8812.jpg', 'add_product_ventas', NULL),
(262, '7705946545037', 'Raclette 800W KALLEY K-MRTT29', 13, '71119', 40, '2020-10-15 09:49:22', 18, 1, 50, 'img_7705946545037c22eb49ce4d0a3a102ca4ea58040a80d.jpg', 'add_product_ventas', NULL),
(263, '7705946460118', 'Air fryer Kalley K-MAF25', 13, '209571', 141, '2020-10-15 09:49:22', 18, 1, 50, 'img_6cf942d211e87deedc98886133bbbb13.jpg', 'add_product_ventas', NULL),
(264, '7701023598217', 'Asad Grill Liso Kalley K-AGL15, Selector de temperatura,  Bandeja para recolección de grasas, Incluy', 13, '75938', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_bd283bb5f2ba0707b242ef2d510d7ff3.jpg', 'add_product_ventas', NULL),
(265, '7701023756587', 'Asador Grill Kalley K-SG100', 13, '79985', 135, '2020-10-15 09:49:22', 18, 1, 50, 'img_af5c696c98afa14cc3cfba677a120ac3.jpg', 'add_product_ventas', NULL),
(266, '7701023598231', 'Sarten Elect Kalley K-SEC15V', 13, '89009', 173, '2020-10-15 09:49:22', 18, 1, 50, 'img_10b531d5a0b54e1f1830fd2b98214b9f.jpg', 'add_product_ventas', NULL),
(267, '7701023598200', 'Asador Pani Kalley K-SG150', 13, '109904', 11, '2020-10-15 09:49:22', 18, 1, 50, 'img_f45daaf0e244cd02f27a7f34045985f1.jpg', 'add_product_ventas', NULL),
(268, '7701023183543', 'Sarten Elect Kalley K-SER120C', 13, '119740', 63, '2020-10-15 09:49:22', 18, 1, 50, 'img_755e0c9878c3397f60784a1a9ade2155.jpg', 'add_product_ventas', NULL),
(269, '7701023131384', 'Secador Kalley K-SCBION1 Bion', 13, '77623', 455, '2020-10-15 09:49:22', 18, 1, 53, 'img_c083b662c1d5ead35ccc05806b91b8fa.jpg', 'add_product_ventas', NULL),
(270, '7705946032995', 'Plancha Alis Kalley K-PABI5 Bi', 13, '86566', 763, '2020-10-15 09:49:22', 18, 1, 53, 'img_e05b94631533396c3e79573f420cc408.jpg', 'add_product_ventas', NULL),
(271, '7705946033008', 'Plancha Alis Kalley K-PABI6 Bi', 13, '86722', 766, '2020-10-15 09:49:22', 18, 1, 53, 'img_7da892c111b00db892f9ebfa6932a224.jpg', 'add_product_ventas', NULL),
(272, '7701023507998', 'Afeitadora Kalley KSMF1MaxFlex', 13, '36118', 6, '2020-10-15 09:49:22', 18, 1, 53, 'img_a041635e775da80898898375e1cf51ca.jpg', 'add_product_ventas', NULL),
(273, '7705946484381', 'Aeitadora 4D Kalley K-A4DH, Multifuncionalidad y comodidad en un solo producto! La Afeitadora 4D KAL', 13, '117564', 0, '2020-10-15 09:49:22', 18, 1, 53, 'img_2960511c72624167841917d1b7183b35.jpg', 'add_product_ventas', NULL),
(274, '7701023151610', 'Afeitadora Kalley K-AMFS MFWet', 13, '86911', 2, '2020-10-15 09:49:22', 18, 1, 53, 'img_898cc12eabf9d65fb075de6d8698fda5.jpg', 'add_product_ventas', NULL),
(275, '7701023151603', 'Afeitadora Kalley KASDWSensiti', 13, '36063', 15, '2020-10-15 09:49:22', 18, 1, 53, 'img_b180646f1716cb60711a003c4a197987.jpg', 'add_product_ventas', NULL),
(276, '7701023835923', 'Exprimidor Kalley K-J150', 13, '40503', 2675, '2020-10-15 09:49:22', 18, 1, 50, 'img_fbafa27ac09eb7ec18da0310223f7913.jpg', 'add_product_ventas', NULL),
(277, '7701023822244', 'Exprimidor Kalley K-J200 N', 13, '43604', 1348, '2020-10-15 09:49:22', 18, 1, 50, 'img_fdb6e4e4483a4d80b7c277105ff0b465.jpg', 'add_product_ventas', NULL),
(278, '7701023583213', 'Exprimidor Kalley K-EJ85A', 13, '76636', 616, '2020-10-15 09:49:22', 18, 1, 50, 'img_3d89946d4114b2743e08ff831bae20c0.jpg', 'add_product_ventas', NULL),
(279, '7701023046084', 'Procesador A Kalley K-MPA1004B', 13, '43762', 3163, '2020-10-15 09:49:22', 18, 1, 50, 'img_1d12d3f68a400223a2b28e538f1faaa3.jpg', 'add_product_ventas', NULL),
(280, '7701023922791', 'Procesador A Kalley K-PA250', 13, '83899', 1, '2020-10-15 09:49:22', 18, 1, 50, 'img_2119c15c0cd69d4ce9d54b64c704ba58.jpg', 'add_product_ventas', NULL),
(281, '7705946460156', 'Procesador A Kalley K-MPA500N', 13, '136148', 554, '2020-10-15 09:49:22', 18, 1, 50, 'img_90013416783f7a2836b98ba765653fc5.jpg', 'add_product_ventas', NULL),
(282, '7705946328210', 'Extractor Kalley K-JE750', 13, '166581', 4536, '2020-10-15 09:49:22', 18, 1, 50, 'img_d38230560a9d6fcc540eec8dfe3a1c47.jpg', 'add_product_ventas', NULL),
(283, '7701023109758', 'Extractor de Jugos Kalley K-SJ150V, Extracción total de fruta,  Motor silencioso y reversible,  Incl', 13, '245242', 0, '2020-10-15 09:49:22', 18, 1, 50, 'img_6ae6955333a9f91aeef613267f3bb3aa.jpg', 'add_product_ventas', NULL),
(284, '7701023127547', 'Calentador de Ambiente Kalley K-CA18, 2 niveles de calor: alto y bajo,  Dispositivo de seguridad por', 13, '53626', 0, '2020-10-15 09:49:22', 18, 1, 54, 'img_8fc25a085bb9e1ab463c7d22da2878da.jpg', 'add_product_ventas', NULL),
(285, '7705946003087', 'Ventila Kalley K-VM8N02', 13, '51327', 4405, '2020-10-15 09:49:22', 18, 1, 55, 'img_ffb5b458c56c552fc53cbeca37741f04.jpg', 'add_product_ventas', NULL),
(286, '7705946435994', 'Ventilador Mesa Kalley K-VM40N', 13, '61990', 523, '2020-10-15 09:49:22', 18, 1, 55, 'img_e583a89399bd243a2d468ed73b874af9.jpg', 'add_product_ventas', NULL),
(287, '7701023419239', 'Ventila Orbi Kalley K-VORB16', 13, '106916', 161, '2020-10-15 09:49:22', 18, 1, 55, 'img_911c51655c76db5147899608afa3fa36.jpg', 'add_product_ventas', NULL),
(288, '7705946417440', 'Ventila Kalley K-VP100P\"N', 13, '112235', 251, '2020-10-15 09:49:22', 18, 1, 55, 'img_6b0da3a2d69a795b3bfece43c9d7b4b8.jpg', 'add_product_ventas', NULL);
INSERT INTO `producto` (`codproducto`, `ean`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `idsub`, `foto`, `idboton`, `Promocion`) VALUES
(289, '7701023151177', 'Ventila Pared Kalley K-VPAR16C', 13, '110529', 316, '2020-10-15 09:49:22', 18, 1, 55, 'img_6c2cb70db39fd6b2f3d9053c09b952d7.jpg', 'add_product_ventas', NULL),
(290, '7701023045926', 'Ventila Kalley  K-VMCU7B Cyclo', 13, '109327', 480, '2020-10-15 09:49:22', 18, 1, 55, 'img_560bb46b374603287c5b3af1d42e527b.jpg', 'add_product_ventas', NULL),
(291, '7705946049054', 'Ventila Pedes Kalley K-VP16N', 13, '118424', 1211, '2020-10-15 09:49:22', 18, 1, 55, 'img_277efab3c31296e955c04a30f5a4137e.jpg', 'add_product_ventas', NULL),
(292, '7701023638081', 'Ventila Torre Kalley K-TF60', 13, '112322', 826, '2020-10-15 09:49:22', 18, 1, 55, 'img_7e4b6fb612ae38021a17d430845f179d.jpg', 'add_product_ventas', NULL),
(293, '7705946366250', 'Ventila Torre Kalley K-TF60N', 13, '114096', 878, '2020-10-15 09:49:22', 18, 1, 55, 'img_7e06db3db7b0445790cd88c2c613a4e4.jpg', 'add_product_ventas', NULL),
(294, '7705946599658', 'Ventilador 3N1 A Kalley K-V40A', 13, '66018', 226, '2020-10-15 09:49:22', 18, 1, 55, 'img_c5d2deefc58789797727663dec458b1e.jpg', 'add_product_ventas', NULL),
(295, '7705946599641', 'Ventilador 3N1 A Kalley K-V40N', 13, '66325', 3307, '2020-10-15 09:49:22', 18, 1, 55, 'img_46246c375eccb226ddc077493f4589e9.jpg', 'add_product_ventas', NULL),
(296, '7705946599634', 'Ventilador Mesa Recargable Kalley K-VM6B   Número de velocidades 4 y 3 Aspas, Angulo de inclinación ', 13, '59751', 0, '2020-10-15 09:49:22', 18, 1, 55, 'img_c2ee834def7ce0f369b641a0e6784f1f.jpg', 'add_product_ventas', NULL),
(297, '7705946307406', 'Ventila 3en1 Kalley  K-V31N02', 13, '133999', 1, '2020-10-15 09:49:22', 18, 1, 55, 'img_e2094ef2af37d9c3d4e4b656c9461b29.jpg', 'add_product_ventas', NULL),
(298, '7705946379144', 'Ventilador 3en1Kalley  K-V31B02,5 aspas de 18 pulgadas, pedestal, mesa o pared,Giratorio y con direc', 13, '133683', 0, '2020-10-15 09:49:22', 18, 1, 55, 'img_d8a9b375c296ce37c388c8154b10d7aa.jpg', 'add_product_ventas', NULL),
(299, '7705946379175', 'Ventilador Kalley Torre K-TF45', 13, '151200', 511, '2020-10-15 09:49:22', 18, 1, 55, 'img_193d79830bc21b67f8010fdd7a341b72.jpg', 'add_product_ventas', NULL),
(300, '7701023127554', 'Ventila Kalley K-VP20HS Alta P', 13, '169032', 4, '2020-10-15 09:49:22', 18, 1, 55, 'img_5e60c748ba2cff2c9a70323ff6567333.jpg', 'add_product_ventas', NULL),
(301, '7705946255226', 'Ventila Pared Kalley K-VAP26W', 13, '236179', 1572, '2020-10-15 09:49:22', 18, 1, 55, 'img_03438af459f3bf5e9c7f583c403d4193.jpg', 'add_product_ventas', NULL),
(302, '7705946255219', 'Ventila Pedes Kalley K-VAP26P', 13, '266930', 1446, '2020-10-15 09:49:22', 18, 1, 55, 'img_2dbe26fb4f18a25498a946edea03b072.jpg', 'add_product_ventas', NULL),
(303, '7705946531054', 'Lonchera eléc KALLEY K-MLE60A2', 13, '26698', 1258, '2020-10-15 09:49:22', 18, 1, 50, 'img_818a2dbb3f90b756a94f221bcc811fb0.jpg', 'add_product_ventas', NULL),
(304, '7705946531047', 'Lonchera eléc KALLEY K-MLE60G2', 13, '26698', 487, '2020-10-15 09:49:22', 18, 1, 50, 'img_00ba00a197a8e149c1664e05c0a5e589.jpg', 'add_product_ventas', NULL),
(305, '7701023387569', 'Crispetera Kalley K-PM1200', 13, '77638', 1191, '2020-10-15 09:49:22', 18, 1, 50, 'img_4436ba6481d4f8880ca5904c672a0392.jpg', 'add_product_ventas', NULL),
(306, '7701023946926', 'Fuente Choc Kalley K-FCH190 S', 13, '76218', 1828, '2020-10-15 09:49:22', 18, 1, 50, 'img_ade627a732c239e0982b0b0f4b035caf.jpg', 'add_product_ventas', NULL),
(307, '7701023387576', 'Vaporizador Kalley K-VA800N3', 13, '116350', 386, '2020-10-15 09:49:22', 18, 1, 50, 'img_02b8e2cfdde52f8a1b3c6d9c188eb506.jpg', 'add_product_ventas', NULL),
(308, '7701023157780', 'Repto aftdra  Kalley K-RCAS', 13, '5119', 0, '2020-10-15 09:49:22', 18, 1, 53, 'img_8d32813d2ca90bb3679b369b1147738c.jpg', 'add_product_ventas', NULL);

--
-- Triggers `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
		INSERT INTO entradas(codproducto,cantidad,precio,usuario_id)
		VALUES(new.codproducto,new.existencia,new.precio,new.usuario_id);
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(12, 'Mi Dia', 'Corbeta', 314376868, 'Parque industrial San Carlos 2', '2020-09-15 09:42:49', 18, 1),
(13, 'KALEY', 'CORBETA', 4376868, 'Parque industrial San Carlos 2\r\n', '2020-10-15 23:24:12', 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Table structure for table `subcategoria`
--

CREATE TABLE `subcategoria` (
  `idsub` int(11) NOT NULL,
  `subcategoria` varchar(50) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `enlace` varchar(200) NOT NULL DEFAULT '#',
  `idcat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subcategoria`
--

INSERT INTO `subcategoria` (`idsub`, `subcategoria`, `estatus`, `enlace`, `idcat`) VALUES
(1, 'TV', 1, 'electronica.php', 1),
(2, 'Base Para Pared', 1, 'electronica.php', 1),
(3, 'Accesorios', 1, 'electronica.php', 1),
(7, 'Blu-Ray', 1, 'electronica.php', 2),
(8, 'DVD', 1, 'electronica.php', 2),
(9, 'Teatro en Casa', 1, 'electronica.php', 2),
(10, 'Nuevo Usuario', 1, 'registro_usuario.php', 12),
(11, 'Lista Usuarios', 1, 'lista_usuarios.php', 12),
(12, 'Nuevo Producto', 1, 'registro_producto.php', 13),
(13, 'Lista Productos', 1, 'Lista_productos.php', 13),
(14, 'Nuevo Cliente', 1, 'registro_cliente.php', 14),
(15, 'Lista Clientes', 1, 'lista_clientes.php', 14),
(16, 'Nueva Venta', 1, 'nueva_venta.php', 15),
(17, 'Listado ventas', 1, 'ventas.php', 15),
(18, 'Camaras', 1, 'electronica.php', 3),
(19, 'Video Camaras', 1, 'electronica.php', 3),
(20, 'Audio Portables', 1, 'electronica.php', 4),
(21, 'Equipos de Sonido', 1, 'electronica.php', 4),
(22, 'Audífonos', 0, 'electronica.php', 4),
(24, 'Accesorios Cámaras', 1, 'electronica.php', 3),
(25, 'Ariel', 1, 'mercado.php', 16),
(26, 'Rindex y Ace', 1, 'mercado.php', 16),
(27, 'Head & Shoulders', 1, 'mercado.php', 16),
(28, 'Pantene', 1, 'mercado.php', 16),
(29, 'Downy', 1, 'mercado.php', 16),
(30, 'Jabón de Loza', 1, 'Jabon_de_Loza.php', 16),
(31, 'Familia', 1, 'Familia.php', 17),
(32, 'Nosotras', 1, 'Nosotras.php', 17),
(33, 'Pequeñín', 1, 'Pequeñín.php', 17),
(34, 'Fres Kids', 1, 'Fres_Kids.php', 17),
(35, 'Tena', 1, 'Tena.php', 17),
(36, 'Super Megas y Megas', 1, 'Super_Megas_y_Megas.php', 18),
(37, 'Paketicos y Cabales', 1, 'Paketicos_y_Cabales.php', 18),
(38, 'Familiar', 1, 'Familiar.php', 18),
(39, 'Barras', 1, 'Barras.php', 18),
(40, 'Pringles', 1, 'Pringles.php', 18),
(41, 'Alimentos', 1, 'mercado.php', 24),
(42, 'Aseo', 1, 'Mercado.php', 24),
(43, 'Informe Ventas', 1, 'Informe.php', 34),
(44, 'Congeladores', 1, 'electrohogar.php', 5),
(45, 'Vitrina', 1, 'electrohogar.php', 5),
(46, 'Aires Acondicionados', 1, 'electrohogar.php', 8),
(47, 'Dispensadores', 1, 'electrohogar.php', 5),
(48, 'Mini Bar', 1, 'electrohogar.php', 5),
(49, 'Lavadoras', 1, 'electrohogar.php', 6),
(50, 'Preparación Alimentos', 1, 'electrohogar.php', 9),
(51, 'Cuidado de Pisos', 1, 'electrohogar.php', 9),
(52, 'Planchas de Ropa', 1, 'electrohohar.php', 9),
(53, 'Cuidado Personal', 1, 'electrohogar.php', 9),
(54, 'Calefactores', 1, 'electrohogar.php', 8),
(55, 'Ventiladores', 1, 'electrohogar.php', 8);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `cedula` text DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idusuario`, `cedula`, `nombre`, `correo`, `usuario`, `clave`, `direccion`, `telefono`, `rol`, `estatus`) VALUES
(1, NULL, 'Jesus Ortiz', 'Davinchi89@hotmail.com', 'JesusO', '12345', 'Cra 7B # 18-12 Barrio Mexico-Funza', '3183107697', 1, 1),
(18, '1073525680', 'Jesus David Ortiz Arevalo', 'jesus.ortiza@colcomercio.com.co', 'Jesus', 'dba51e4d0b4cfd8a575f1fea1a6e0e0a', 'Cra 7B # 18-12 Barrio Mexico-Funza', '3183107697', 1, 1),
(22, NULL, 'Abel', 'info@abelosh.com', 'admin', '202cb962ac59075b964b07152d234b70', '', '757584854', 1, 0),
(24, '1015467732', 'Maria Alejandra Mejia Chaparro', 'MariaA.Mejia@colcomercio.com.co', 'MariaA', 'c83c3117839fc7cca0e33c3e901f53e1', 'Parque Industrial San carlos 2', '3156639915', 1, 1),
(25, '1073166043', 'Yenny Milena Salamanca Solano', 'yenny.salamanca@colcomercio.com.co', 'Milena', '57837aebaa7b6f652ec5df6cc14f12ba', 'Parque Industrial San Carlos 2', '3115459112', 1, 1),
(26, '1012321312', 'Carlos Andrés González Plazas', 'Carlos.Gonzalez@colcomercio.com.co', 'Carlos.Gonzalez', '16f00b25818fea43ce355d295429d566', 'Parque Industrial San Carlos 2', '3184505146', 3, 1),
(27, '1214463067', 'edison julian diaz jimenez', 'Juliandj6370@hotmail.com', '1214463067', 'e90fac3e98e44b8466373edfc81cd228', 'cll 143 b # 145a-09', '3118230447', 3, 1);

--
-- Triggers `usuario`
--
DELIMITER $$
CREATE TRIGGER `Registro_U_C` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
				INSERT INTO cliente(nit,nombre,telefono,direccion,correo,usuario_id)
					VALUES(new.cedula,new.nombre,new.telefono,new.direccion,new.correo,new.idusuario);
			END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idcat`),
  ADD UNIQUE KEY `categoria` (`categoria`),
  ADD KEY `idmenu` (`idmenu`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indexes for table `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `token_user` (`token_user`);

--
-- Indexes for table `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`);

--
-- Indexes for table `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idestado`);

--
-- Indexes for table `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `codcliente` (`codcliente`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idmenu`),
  ADD UNIQUE KEY `menu` (`menu`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idsub` (`idsub`);

--
-- Indexes for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indexes for table `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD PRIMARY KEY (`idsub`),
  ADD UNIQUE KEY `subcategoria` (`subcategoria`),
  ADD KEY `idcat` (`idcat`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idcat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=503;

--
-- AUTO_INCREMENT for table `estados`
--
ALTER TABLE `estados`
  MODIFY `idestado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `idsub` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);

--
-- Constraints for table `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`),
  ADD CONSTRAINT `detallefactura_ibfk_3` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `factura_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`codproveedor`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`idsub`) REFERENCES `subcategoria` (`idsub`);

--
-- Constraints for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD CONSTRAINT `subcategoria_ibfk_1` FOREIGN KEY (`idcat`) REFERENCES `categorias` (`idcat`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
