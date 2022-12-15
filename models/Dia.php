<?php

    namespace Model;

    class Dia extends ActiveRecord {
        // BD
        protected static $tabla = 'dias';
        protected static $columnasDB = ['id', 'nombre'];
        
        // Declarando los atributos
        public $id;
        public $nombre;
    }