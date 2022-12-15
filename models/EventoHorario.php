<?php

    namespace Model;

    class EventoHorario extends ActiveRecord {
        // BD
        protected static $tabla = 'eventos';
        protected static $columnasDB = ['id', 'categoria_id', 'dia_id', 'hora_id'];
        
        // Declarando los atributos
        public $id;
        public $categoria_id;
        public $dia_id;
        public $hora_id;

        
    }