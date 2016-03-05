# Codeigniter Middlewares

This library enables you to quickly add any middleware to your codeigniter application and in too few lines you get everything up and running smoothly.
Tested on CodeIgniter 3.0.4, should work on 2.2+ as well

### Quick Integration Guide

* Copy MY_Controller.php to application/core
* In your controller extend MY_Controller instead of CI_Controller
* Create your middleware class in middleware directory and add function run()
* Create a function middleware() and return array of middlewares to run
* That's it.


##### Create your middlewares directory
* Create new **middlewares** directory in application if not exists.

##### Create your class in application/middlewares

```php
<?php
// src: application/middlewares/AdminAuthMiddleware.php

// Extends nothing, it's upto you what you want to extend. Completely generic.
class AdminAuthMiddleware {
    // Get injected controller and ci references
    protected $controller;
    protected $ci;
    
    // Some custom and example data related to this class
    public $roles = array();
    
    // All middlewares will pass controller and ci class objects as references to constructor
    // It's upto you, that what you do with them
    // Obviously it's not required :)
    
    public function __construct($controller, $ci)
    {
        $this->controller = $controller;
        $this->ci = $ci;
    }
    
    // This function is required, and is entry point to this class
    public function run(){
        // you can reference to current controller called class
        $this->controller->some_your_method();
        
        // you can run db queries
        $categories = $this->ci->db->get('categories');
        
        // you can get reference to models, libraries
        $users = $this->controller->user->list();
        $this->controller->load->library('session');
        
        // you can get session references
        $email = $this->ci->session->userdata('email');
    
        // you can modify the class and add your methods to this class
        $this->roles = array('somehting', 'view', 'edit');
        
        // you can get reference to called function and class name on request
        $this->controller->router->method; // returns method name, eg. index
        $this->controller->router->class; // returns from which class (controller class) this function has been called
        
        // and also you can terminate the request, if you dont want it to pass on
        show_error('You are not allowed to perform this operation');
    }
}
```

##### Extend MY_Controller class
```php
<?php
// src: application/controllers/Home.php

class Home extends MY_Controller 
{
    // only create if you want to use, not compulsory.
    // or return parent::middleware(); if you want to keep.
    // or return empty array() and no middleware will run.
    protected function middleware()
    {
        /**
         * Return the list of middlewares you want to be applied,
         * Here is list of some valid options
         *
         * admin_auth                    // As used below, simplest, will be applied to all
         * someother|except:index,list   // This will be only applied to posts()
         * yet_another_one|only:index    // This will be only applied to index()
         **/
        return array('admin_auth', 'someother|except:index,list', 'yet_another_one|only:index');
    }
    
    // Middlewares applied according to above code: admin_auth, yet_another_one
    public function index()
    {
        // you can also use the middleware class's object later if you wish.
        var_dump($this->middlewares['admin_auth']);
        
        $this->load->view('index');
    }
    
    // Middlewares applied according to above code: admin_auth, someother
    public function posts()
    {
        $this->load->view('posts_view');
    }
    
    // Middlewares applied according to above code: admin_auth
    public function list()
    {
        $this->load->view('something');
    }
}
```

#### Notes:

>
> Class name require **Middleware** as suffix, also cannot contain, underscores or hyphens
> Here is list of some valid conventions:
>

* admin => AdminMiddleware
* Admin => AdminMiddleware
* SomeThing => SomeThingMiddleware
* some_lazy_name => SomeLazyNameMiddleware
* some_OtHer_Crazy_name => SomeOtHerCrazyNameMiddleware
* hell_Yeah => HellYeahMiddleware

On the left side is name of middleware, and on the right side is class name and .php filename for the class.
Above list explains, how your middleware name would resolve to a class name.

That's all, I hope documentation and code was helpful. Cheers!