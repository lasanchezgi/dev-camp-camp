<?php

    namespace Model;

    class Hora extends ActiveRecord {
        // BD
        protected static $tabla = 'horas';
        protected static $columnasDB = ['id', 'hora'];
        
        // Declarando los atributos
        public $id;
        public $hora;
    }