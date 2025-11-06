Perfecto ✅
Aquí tienes el **README.md completo**, con las rutas exactas y todo listo para **copiar y pegar tal cual**.

---


# 🧩 Prueba Técnica - Gestión de Proyectos y Tareas 

Este proyecto implementa una **API RESTful y una vista web** para la gestión de **usuarios, proyectos, tareas y tarifas por proyecto**.  
Desarrollado con **Symfony 7**, **Doctrine ORM**, **MySQL (XAMPP)** y documentado con **Swagger**.  
También incluye una vista web construida con **Twig + Bootstrap** para visualizar los datos desde la API.

---

## 🚀 Características principales

- CRUD completo para:
  - **Usuarios**
  - **Proyectos**
  - **Asignación de usuarios a proyectos** con tarifa (`UserProject`)
  - **Tareas** registradas por usuario y proyecto
- Documentación interactiva con **Swagger**
- Vista web con **Twig + Bootstrap** que lista las tareas desde la API
- Arquitectura limpia basada en entidades (`Entity`) y controladores (`Controller`)

---

## 🧰 Tecnologías utilizadas

- [Symfony 7](https://symfony.com/)
- [Doctrine ORM](https://www.doctrine-project.org/)
- [MySQL](https://www.mysql.com/)
- [Twig](https://twig.symfony.com/)
- [Swagger / NelmioApiDocBundle](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html)

---

## ⚙️ Requisitos previos

Asegúrate de tener instalados los siguientes componentes:

- PHP >= 8.2  
- Composer  
- MySQL (puedes usar **XAMPP** o **Laragon**)  
- Node.js (opcional, si deseas compilar assets)
- Symfony CLI (opcional)

---

## 📦 Instalación

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/josetenorio/proyecto-symfony.git
   cd prueba_tecnica


2. **Instalar dependencias**

   ```bash
   composer install
   ```

3. **Configurar el archivo `.env`**

   Edita el archivo `.env` y ajusta la conexión a tu base de datos MySQL:

   ```env
   DATABASE_URL="mysql://root:@127.0.0.1:3306/proyecto_symfony?serverVersion=8.0"
   ```

   *(Si tu usuario o contraseña son diferentes, cámbialos según tu configuración local.)*

4. **Crear la base de datos**

   ```bash
   php bin/console doctrine:database:create
   ```

5. **Ejecutar las migraciones**

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. **Levantar el servidor**

   ```bash
   symfony server:start
   ```

   o

   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

7. **Abrir el proyecto en el navegador**

   ```
   http://127.0.0.1:8000
   ```

---

## 📘 Documentación de la API (Swagger)

Una vez el servidor esté corriendo, accede a la documentación de la API desde:

👉 **[http://127.0.0.1:8000/api/doc](http://127.0.0.1:8000/api/doc)**

Ahí podrás ejecutar y probar todos los endpoints disponibles.

---

## 🔗 Endpoints principales

| Recurso          | Método | Endpoint             | Descripción                                           |
| ---------------- | ------ | -------------------- | ----------------------------------------------------- |
| Usuario          | `POST` | `/api/users`         | Crea un nuevo usuario                                 |
| Proyecto         | `POST` | `/api/projects`      | Crea un nuevo proyecto                                |
| Usuario-Proyecto | `POST` | `/api/user-projects` | Asigna un usuario a un proyecto con tarifa            |
| Tarea            | `POST` | `/api/tasks`         | Crea una nueva tarea                                  |
| Tarea            | `GET`  | `/api/tasks`         | Lista todas las tareas con usuario, proyecto y tarifa |

---

## 🖥️ Vista web

Además de la API, el proyecto incluye una **interfaz web** desarrollada con **Twig y Bootstrap**.

Accede a ella desde:

👉 **[http://127.0.0.1:8000/tasks/view](http://127.0.0.1:8000/tasks/view)**

Esta vista consume el endpoint `/api/tasks` y muestra las tareas en una tabla moderna y responsive.

---



## 📁 Estructura del proyecto

```
src/
 ├── Controller/       # Controladores de la API
 ├── Entity/           # Entidades (User, Project, Task, UserProject)
 ├── Repository/       # Repositorios personalizados
 └── Templates/        # Vistas Twig

config/
 ├── packages/
 ├── routes/
 └── services.yaml

public/
 └── index.php         # Punto de entrada de la aplicación
```

---

## 👨‍💻 Autor

**José Tenorio**
Desarrollador Full Stack
📧 Josevisbal2@gmail.com
📍 Bogota, Colombia

---


