<?php

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController{

    public static function login(Router $router){

        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){

                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //Verificar usuario activado y password
                    
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){

                        session_start();

                        //Lleno arreglo de sesion
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin ==="1"){
                        
                            $_SESSION['admin'] = $usuario->admin ?? null;    
                            header('Location: /admin');    

                        }else{
                        
                            header('Location: /cita');    

                        }

                        

                    }
                    
                } else{
                    Usuario::setAlerta('error', 'El usuario no existe');
                }

            }

          
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login',[ 'auth'=> $auth, 'alertas'=>$alertas ]);

    }

    public static function logout(){

        session_start();
        $_SESSION = [];
        header('Location: /');    

    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado ==='1'){
                    //Generar nuevo token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email
                    Usuario::setAlerta('exito', 'Hemos enviado un email para resetear tu contraseña');
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    
                } else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                    
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', ['alertas'=>$alertas ]);

    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = $_GET['token'];

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){

            Usuario::setAlerta('error','Token invalido');
            $error = true;

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $password = new Usuario($_POST);
            $alertas = $password-> validarPassword();

            if(empty($alertas)){
             
                $usuario->password= NULL;
                $usuario->password = $password->password;

                //Hashear Password
                $usuario->hashPAssword();
                $usuario->guardar();

                $alertas = Usuario::setAlerta('exito', 'Su contraseña ha sido reestablecida con exito');
                $error = true;
            }


        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', ['alertas'=>$alertas, 'error'=>$error ]);

    }

    public static function crear(Router $router){

        $usuario = new Usuario();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario ->sincronizar($_POST);
           
            $alertas = $usuario-> validarNuevaCuenta();
           
            if(empty($alertas)){

                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else{
                    //No esta registrado

                    //Hashear Password
                    $usuario->hashPAssword();

                    //Generar Toquen Unico
                    $usuario->crearToken();

                    //Enviar el mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }

                   
                }

            }
            
        }

        $router->render('auth/crear-cuenta',['usuario'=> $usuario, 'alertas'=>$alertas ]);

    }

    public static function mensaje(Router $router){

        

        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router){

        $alertas=[];

        $token = s($_GET['token']);

        
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){

            Usuario::setAlerta('error', 'Token No Valido');
            
        } else{

            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Confirmada!');
        }

        
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', ['alertas'=>$alertas ]);

    }

}