<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;

Class ServicioController{

    public static function index(Router $router){

        session_start();
        isAdmin();
        $servicios = Servicio::all();
      
        $router->render('servicios/index', ['nombre'=>$_SESSION['nombre'], 'servicios' => $servicios]);
    }

    public static function crear(Router $router){
        
        session_start();
        isAdmin();
        $servicio = new Servicio;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){

                $servicio->guardar();
                header('Location: /servicios');

            }

        }

        $router->render('servicios/crear', ['nombre'=>$_SESSION['nombre'], 'alertas'=>$alertas, 'servicio'=>$servicio]);
    }

    public static function actualizar(Router $router){

        session_start();
        isAdmin();
        $servicio = new Servicio;
        $alertas = [];
        
        $servicio = Servicio::find($_GET['id']);

        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->sincronizar($_POST);
            
            $alertas = $servicio->validar();

            if(empty($alertas)){

                
                $servicio->guardar();
                header('Location: /servicios');

            }

           
        }

        $router->render('servicios/actualizar', ['nombre'=>$_SESSION['nombre'], 'servicio' => $servicio, 'alertas'=>$alertas]);
    }
    

    public static function eliminar(Router $router){

        session_start();
        
        isAdmin();
        $servicio = Servicio::find($_POST['id']);

        

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->eliminar();
            header('Location: /servicios');
            
        }

    }
}