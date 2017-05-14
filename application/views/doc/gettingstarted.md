# Iniciando con RSPhp

## Instalación

Creamos una carpeta en un directorio accesible al servidor web, como [/var/www/](/var/www) o [C:\xampp\htdocs](C:\xampp\htdocs) si usas Xampp sobre Windows. En este caso usaremos "misitio" como el nombre de la carpeta, pero tu puedes usar la que gustes :).

Si utilizas Windows recomiendo utilizar Git Bash o MinSys para que te vayas acostumbrando a los comandos linux :).

```bash
  cd /var/www
  mkdir misitio
  cd misitio
```

Luego, lo siguiente que vamos a hacer es instalarlo:

```bash
  composer require espino316/rsphp
```
Luego, desde la línea de comandos:

```bash
  ./rsphp init --default
```
Esto creará la estructura de directorios y archivos necesarios de la aplicación:

* application
  * Controllers
  * Libraries
  * Models
  * Views
* config
* public
  * css
  * img
  * js
  * index.php
  * .htaccess
* .htaccess

A continuación, desde la línea de comandos podemos teclear:
```bash
  ./rsphp help
```

## Tu primer controlador
Esto nos mostrará la ayuda de la línea de comandos, cuando la necesitemos.

Lo primero que vamos a hacer ahora es crear un controlador. Desde la línea de comandos tecleamos:

```bash
  ./rsphp controller create --name=Default --doc="Controlador por defecto"
```

Esto creará DefaultController.php en el directorio application/Controllers

Abrimos el archivo y veremos:

```php
<?php

namespace Application\Controllers;

use RSPhp\Framework\Controller;

/**
 * Controlador por defecto
 */
class DefaultController extends Controller
{
    /**
     * Creates a new instance of DefaultController
     */
    function __construct()
    {
    } // end function constructs

    /**
     * The home %baseUrl/Default/
     */
    function index()
    {
    } // end function index

} // end class DefaultController
```

Como podemos observar, tenemos que el controlador se encuentra en el namespace *Application\Controllers* y que utilizamos la clase *RSPhp\Framework\Controller.*

La función inicial, por defecto es "index". Aqui colocaremos un mensaje:

```php
    /**
     * The home %baseUrl/Default/
     */
    function index()
    {
		echo "Hola mundo!";
    } // end function index
```

Guardamos y navegamos a [http://localhost/misitio](http://localhost/misitio/) y podremos ver el mensaje "Hola mundo!" en la pantalla. Bastante simple ¿No?

### Las funciones

Creando funciones nuevas podemos utilzar nuevas urls, el formato es el siguiente:

```bash
  http://misitio/nombrecontrolador/nombrefuncion
  ejem.
  http://misitio/mensajes/hola
```
En el ejemplo anterior no especificamos el nombre del controlador. Esto es por que es el controlador por defect y si navegamos a la home, entonces lo podemos omitir. Sin embargo, si utilizamos algun otro controlador con una función, hemos de especificarlo.

Pues bien, creemos otro controlador:

```bash
  ./rsphp controller create --name=Mensajes --doc="Controlador para probar mensajes"
```

Agreguémosle una función hola:

```php
    /**
     * Función para decir hola, url: %baseUrl/mensajes/hola
     */
    function hola()
    {
		echo "Hola!";
    } // end function index
```

Y naveguemos a [http://localhost/misitio/mensajes/hola](http://localhost/misitio/mensajes/hola).
Se nos mostrará el mensaje: "Hola!".

### Los parámetros

Para pasarle parámetros a la función, podemos especificarlos en el código y agregarlos directamente a la url, así:

```php
    /**
     * Función para decir hola, url: %baseUrl/mensajes/hola
     */
    function hola( $name )
    {
		echo "Hola! $name";
    } // end function index
```

Y naveguemos a [http://localhost/misitio/mensajes/hola/luis](http://localhost/misitio/mensajes/hola/luis).
Se nos mostrará el mensaje "Hola luis!".

Podemos especificar más parámetros de la misma forma:

```php
    /**
     * Función para decir hola, url: %baseUrl/mensajes/hola
     */
    function hola( $name, $age )
    {
		echo "Hola! $name, de $age años";
    } // end function index
```

Y naveguemos a [http://localhost/misitio/mensajes/hola/luis/33](http://localhost/misitio/mensajes/hola/luis/33).
Se nos mostrará el mensaje "Hola luis, de 33 años!".

Como podemos ver es bastante natural y simple.

## Las Vistas

Ahora procederemos a utilizar vistas. Las vistas se almacenarán en la carpeta *application/Views* y serán archivos * .php.

Vamos a crear una vista, estas se almacenarán en application/Views, así que allí crearemos el archivo sample.php.

```html
<html>
  <head><title>Ejemplo</title></head>
  <body>Este es un ejemplo</body>
</html>
```

Para utilizar las vistas, usaremos la clase View y la función load. Creemos otra función en el controlador "Mensajes" y añadamos la referencia a la clase. Queda de la siguiente manera, la clase completa:

```php
<?php

namespace Application\Controllers;

use RSPhp\Framework\Controller;
use RSPhp\Framework\View;

/**
 * Controlador de Mensajes
 */
class MensajesController extends Controller
{
    /**
     * Creates a new instance of MensajesController
     */
    function __construct()
    {
    } // end function constructs

    /**
     * The home %baseUrl/Mensajes/
     *
     * @return null
     */
    function index()
    {
    } // end function index

    /**
     * Dice "Hola"
     *
     * @param String $name El nombre a mostrar
     * @param String $age La edad a mostrar
     *
     * @return null
     */
    function hola( $name, $age )
    {
        echo "Hola $name, de $age años";
    } // end function hola

    /**
     * Muestra una vista
     *
     * @return null
     */
    function ejemplo() {
        View::load("sample");
    } // end function ejemplo

} // end class MensajesController

```
Esto cargará el archivo application/View/ejemplo.php y los escribirá, como si hubiesemos hecho un "echo".

### Variables en las Vistas

Para usar variables en las vistas utilizaremos la notación **$nombreVariable** como si estuvieramos usando php directamente. Las variables las pondremos en un arreglo, con la llave incluyendo el símbolo "$" y las pasaremos como parámetro en la función **View::load**, así

```html
<label>$myTitle</label>
```

```php
$data["$myTitle"] = "My asobroso artículo";
View::load( "miVista", $data );
```

Simple ¿no? Ahora vamos a hacer el ejemplo con el nombre y la edad del ejemplo anterior, creamos un archivo *hola.php* en el directorio *application/Views* que quedaría así:

```html
<html>
  <head><title>Hola</title></head>
  <body>
	<h1>Hola $name, de $age años.</h1>
  </body>
</html>
```

Ahora modificamos la función hola para que quede de la siguiente manera:

```php
/**
 * Dice "Hola"
 *
 * @param String $name El nombre a mostrar
 * @param String $age La edad a mostrar
 *
 * @return null
 */
function hola( $name, $age )
{
	$data["$name"] = $name;
	$data["$age"] = $age;
	View::load( "hola", $data );
} // end function hola
```

