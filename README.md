DATOS DEL PROGRAMADOR:
Naudys Reina
Correo electronico: naudys16@gmail.com ó ateamdev.naudysar@gmail.com
Skype: naudys_16_2009
Whatsapp: +584267730905

Prueba realizada en Laravel en su última versión
Se usó JQuery para el dinamismo sin recarga del sitio, y php para la lógica del back-end.
En las migraciones se pueden generar las tablas de la base de datos, e incluye seeds para los datos iniciales.

INSTRUCCIONES PARA EJECUTAR LA APLICACIÓN:

- Crear base de datos llamada prueba-empleados
- Ejecutar las migraciones con el comando php artisan migrate:fresh --seed
- Ejecutar el servidor con php artisan serve
- Ingresar al sistema con los datos:
    Administrador: 
        Usuario: admin@prueba.com 
        Contraseña: 12345
    Empleado:
        Usuario: employee@prueba.com
        Contraseña: 12345

TAREAS QUE PUEDE REALIZAR EL ADMINISTRADOR:

- El administrador puede visualizar los reportes de todos los empleados, puede editar o eliminarlos, pero no crear nuevos.
- El administrador puede ejecutar todas las operaciones con los usuarios (Crear, Editar, Ver y Eliminar)

TAREAS QUE PUEDE REALIZAR LOS EMPLEADOS:

- El empleado puede ver sus reportes y ejecutar todas las operaciones con reportes (Crear, Editar, Ver y Eliminar)
