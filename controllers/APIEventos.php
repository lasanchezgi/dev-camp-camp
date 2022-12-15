<?php

    namespace Controllers;

    use Model\EventoHorario;

    class APIEventos {
        // No requiere routing, porque NO renderiza una vista, renderiza un JSON
        public static function index() {

            // Parametros
            $dia_id = $_GET['dia_id'];
            $categoria_id = $_GET['categoria_id'];

            // Aseguarse de que son números enteros
            $dia_id = filter_var($dia_id, FILTER_VALIDATE_INT);
            $categoria_id = filter_var($categoria_id, FILTER_VALIDATE_INT);

            // Deben estar presentes tanto el dia como la hora
            if(!$dia_id || !$categoria_id) {
                echo json_encode([]);
                return;
            }

            // Consultar la BD
            $eventos = EventoHorario::whereArray(['dia_id' => $dia_id, 'categoria_id' => $categoria_id]) ?? [];
            echo json_encode($eventos);
        }
    }