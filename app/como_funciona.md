# Estructura
Esta compuesta por: 
* `.htaccess`: configuración para que todas las peticiones vayan al archivo **index.php**
* `load_core.php`: Carga todas los archivos necesarios (conexiones, controladores, modelos, providers)
* `index.php`: Recibe las peticiones y crea un nuevo objeto desde la ruta. 
> Ej. la ruta es https://tu-dominio.com/app/user/create 
> lo que se hace es crear el Objeto UserController y se buscar el método create. 
  - **NOTA.** userController.php (controlador) y user.php (modelo) deben estar en el array `$entidades` en **load_core.php**.
* `config`: Contiene las conexiones a base de datos.
* `controllers`: Contiene los archivos controladores del tipo **entidad**Controller.php. Cada entidad debe estar en el array `$entidades` en **load_core.php**. 
* `models`: En los modelos son archivos que se encargan de manejar la estructura de cada entidad, como atributos y funciones.  
* `providers`: Tiene como prioridad manejar procesos lógicos reutilizables en cualquier parte de la estructura.

# Agregar nueva entidad.
1. Ir al archivo **load_core.php** en al array `$entidades` agregar el nombre de la entidad. Ej: `customer`
2. Para que tu entidad se convierta en un endpoint de la **APP** ve a la carpeta **controllers** crea el archivo con la entidad agregada en el paso 1. Ej: `customerController.php`.
3. Para que este endpoint funcione crea un metodo en el controlador creado. Ej.
```php
class CustomerController{
  public function saludar(){
    echo 'Hola mundo';
  }
}
```
4. Para integrar el modelo a tu controlador, debes crear el archivo con la entidad agregada en **$entidades** del paso 1. Ej: `customer.php`.
> Puedes crear la estructura de la clase y agregar metodos para usarlo desde el controlador.

Ej.
```php
class Customer{
  public int $id;
  public string $name;
  
  public function __contruct($id, $name){
    $this->id = $id;
    $this->name = $name;
  }
  public function saludar(){
    echo 'Hola ' . $this->name;
  }
}
```
Y desde el controlador podriamos saludar.
```php
class CustomerController{
  public function saludar(){
    $customer = new Customer(1, 'Pedrito');
    $customer->saludar();
  }
}
```
