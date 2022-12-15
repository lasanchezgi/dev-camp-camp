<?php

    namespace Controllers;

    use Classes\Paginacion;
    use MVC\Router;
    use Model\Ponente;
    use Intervention\Image\ImageManagerStatic as Image;


    class PonentesController {

        public static function index(Router $router) {

            if(!is_admin()) {
                header('Location: /login');
            }

            $pagina_actual = $_GET['page'];
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

            if(!$pagina_actual || $pagina_actual < 1) {
                header('Location: /admin/ponentes?page=1');
            }
            
            // Uno define el valor
            $registros_por_pagina = 5;
            $total_registros = Ponente::total();
            $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros);

            if($paginacion->total_paginas() < $pagina_actual) {
                header('Location: /admin/ponentes?page=1');
            }

            $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());

            $router->render('admin/ponentes/index', [
                'titulo' => 'Ponentes / Conferencistas',
                'ponentes' => $ponentes,
                'paginacion' => $paginacion->paginacion()
            ]);
        }

        public static function crear(Router $router) {

            if(!is_admin()) {
                header('Location: /login');
            }

            $alertas = [];

            $ponente = new Ponente;

            if($_SERVER['REQUEST_METHOD'] === 'POST') {

                if(!is_admin()) {
                    header('Location: /login');
                }

                // Leer imagen
                if(!empty($_FILES['imagen']['tmp_name'])) {
                    // Carpeta donde se guardan las imagenes
                    $carpeta_imagenes = '../public/img/speakers';

                    // Crear la carpeta si NO existe
                    if(!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                    $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                    $nombre_imagen = md5(uniqid(rand(),true));

                    // Agregar la imagen al POST
                    $_POST['imagen'] = $nombre_imagen;
                }

                //Organizando las redes de un arreglo a un string
                $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

                // Sincronizar
                $ponente->sincronizar($_POST);

                // Validar
                $alertas = $ponente->validar();

                // Guardar registro del ponente
                if(empty($alertas)) {
                    // Guardar las imagenes
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . "webp");

                    // Guardar en la BD
                    $resultado = $ponente->guardar();
                    if($resultado) {
                        header('Location: /admin/ponentes');
                    }
                }
            }

            // Redes del ponente
            $redes = json_decode($ponente->redes);

            $router->render('admin/ponentes/crear', [
                'titulo' => 'Registrar ponente',
                'alertas' => $alertas,
                'ponente' => $ponente,
                'redes' => $redes
            ]);
        }

        public static function editar(Router $router) {

            if(!is_admin()) {
                header('Location: /login');
            }

            $alertas = [];

            // Validar ID
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id) {
                header('Location: /admin/ponentes');
            }

            // Obtener ponente a editar
            $ponente = Ponente::find($id);

            // Si no hay ponente se redirecciona al administrador
            if(!$ponente) {
                header('Location: /admin/ponentes');
            }

            // Para ver la imagen actual
            $ponente->imagen_actual = $ponente->imagen;

            // Redes del ponente
            $redes = json_decode($ponente->redes);

            // Actualizar
            if($_SERVER['REQUEST_METHOD'] === 'POST') {

                if(!is_admin()) {
                    header('Location: /login');
                }

                // Comprobar si hay una nueva imagen
                if(!empty($_FILES['imagen']['tmp_name'])) {
                    // Carpeta donde se guardan las imagenes
                    $carpeta_imagenes = '../public/img/speakers';

                    // Crear la carpeta si NO existe
                    if(!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                    $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                    $nombre_imagen = md5(uniqid(rand(),true));

                    // Agregar la imagen al POST
                    $_POST['imagen'] = $nombre_imagen;
                } else {
                    $_POST['imagen'] = $ponente->imagen_actual;
                }

                //Organizando las redes de un arreglo a un string
                $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

                // Sincronizar el POST actual
                $ponente->sincronizar($_POST);

                $alertas = $ponente->validar();

                // Si no hay errores, se guarda la actualización
                if(empty($alertar)) {
                    if(isset($nombre_imagen)) {
                        $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                        $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);
                    }

                    $resultado = $ponente->guardar();
                    if($resultado) {
                        header('Location: /admin/ponentes');
                    }
                }

            }

            $router->render('admin/ponentes/editar', [
                'titulo' => 'Editar ponente',
                'alertas' => $alertas,
                'ponente' => $ponente,
                'redes' => $redes
            ]);
        }

        public static function eliminar() {

            if($_SERVER['REQUEST_METHOD'] === 'POST') {

                if(!is_admin()) {
                    header('Location: /login');
                }
                
                $id = $_POST['id'];
                $ponente = Ponente::find($id);

                if(!isset($ponente)) {
                    header('Location: /admin/ponentes');
                }

                $resultado = $ponente->eliminar();

                if($resultado) {
                    header('Location: /admin/ponentes');
                }
            }
        }
    }