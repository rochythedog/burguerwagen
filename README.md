# BurguerWagen

**BurguerWagen** es un proyecto web desarrollado como práctica final, que simula una aplicación real de pedidos online para una hamburguesería.  
El objetivo del proyecto ha sido aplicar de forma práctica los conocimientos adquiridos sobre **PHP, bases de datos, MVC, APIs, JavaScript y despliegue web**, creando una aplicación funcional y realista.

---

## Idea del proyecto

La idea principal era desarrollar una web donde un usuario pudiera:

- Ver productos organizados por categorías  
- Consultar precios y detalles  
- Realizar pedidos online  

Y, por otro lado, disponer de un **panel de administrador** desde el cual gestionar los pedidos y la actividad del sistema.

A lo largo del desarrollo, la estructura del proyecto fue cambiando bastante. En un inicio se plantearon algunas partes de una manera que posteriormente se entendió que no era la más correcta, por lo que se realizaron refactorizaciones y cambios en la arquitectura. Este proceso ha sido clave para entender mejor cómo se desarrolla una aplicación web real.

---

## Estructura del proyecto

El proyecto sigue una **arquitectura MVC (Modelo – Vista – Controlador)** con un único punto de entrada (`index.php`), que se encarga de gestionar todas las peticiones.

burguerwagen/
│
├── config/ → Configuración del proyecto y base de datos
├── controllers/ → Controladores (lógica de la aplicación)
├── models/ → Modelos y DAOs (acceso a base de datos)
├── views/ → Vistas (HTML + Bootstrap)
├── api/ → API REST (JSON)
├── public/
│ ├── css/ → Estilos
│ ├── js/ → JavaScript (panel admin y fetch)
│ └── img/ → Imágenes de productos
│
├── index.php → Front Controller
└── README.md


Los **modelos, vistas y controladores parten de una estructura común**, con vistas principales (listados) y vistas de detalle, lo que deja claro el funcionamiento general del proyecto.

---

## 🗄️ Base de datos

La base de datos está desarrollada en **MySQL** y contiene las siguientes tablas principales:

- `usuarios`
- `categorias`
- `productos`
- `pedidos`
- `lineas_pedido`
- `direcciones`
- `ofertas`
- `producto_oferta`
- `logs`

Se utilizan **claves foráneas**, relaciones entre tablas y reglas como `ON DELETE CASCADE` para garantizar la integridad de los datos.

---

## Modelos y DAOs

Durante el desarrollo, el proyecto evolucionó hasta implementar una separación clara entre:

- **Modelos** (representación de los datos)
- **DAOs (Data Access Objects)** encargados del acceso a la base de datos

Ejemplos:
- `User` / `UserDAO`
- `Order` / `OrderDAO`
- `Product` / `ProductDAO`

Esto mejora la organización del código, facilita el mantenimiento y permite entender mejor la lógica del proyecto.

---

## API REST propia

El proyecto incluye una **API REST desarrollada en PHP**, que devuelve datos en formato **JSON** y es **accesible directamente desde el navegador**.

La API se utiliza principalmente para:
- Obtener pedidos
- Cambiar el estado de los pedidos
- Eliminar pedidos
- Gestionar datos sin recargar la página

Esta API se consume mediante **JavaScript (Fetch API)**, especialmente en el panel de administración.

---

## API externa de divisas

Además de la API propia, se ha implementado una **API externa de conversión de divisas**, que permite mostrar los precios de los productos en diferentes monedas.

Esto simula un entorno más realista y demuestra la integración de servicios externos dentro del proyecto.

---

## Panel de administración

El panel de administrador permite:

- Ver pedidos en tiempo real
- Cambiar el estado de los pedidos
- Eliminar pedidos
- Registrar acciones en la tabla `logs`

Está desarrollado utilizando:
- JavaScript
- Fetch API
- API REST en PHP
- Bootstrap

Todo el panel funciona sin recargar la página, ofreciendo una experiencia más dinámica.  
El acceso está protegido mediante **sesiones** y comprobación de rol (`admin`).

---

## Frontend y diseño

- HTML + Bootstrap
- Diseño responsive
- Interfaz clara y sencilla
- Imágenes gestionadas desde `public/img`

El enfoque principal ha sido el correcto funcionamiento de la aplicación más que un diseño complejo.

---

## Proceso y aprendizaje

Durante el desarrollo del proyecto:

- La estructura fue cambiando conforme se entendía mejor el funcionamiento real de una aplicación web
- Se resolvieron errores reales relacionados con bases de datos, APIs y hosting
- Se refactorizó el código para mejorar su organización
- Se adaptó el proyecto para funcionar en un servidor externo (InfinityFree)

Este proceso ha permitido comprender el desarrollo web de una forma más práctica y realista.

---

## Tecnologías utilizadas

- PHP
- MySQL / MariaDB
- HTML / CSS
- Bootstrap
- JavaScript
- Fetch API
- APIs REST
- Git / GitHub
- InfinityFree (hosting)

---

## Autor

**Desarrollado por:**  
**Roger Malgrat Gonzalez**
