<?php

    namespace Model;

    class Paquete extends ActiveRecord {
        // BD
        protected static $tabla = 'paquetes';
        protected static $columnasDB = ['id', 'nombre'];
        
        // Declarando los atributos
        public $id;
        public $nombre;
    }