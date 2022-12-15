<?php

    namespace Model;

    class Categoria extends ActiveRecord {
        // BD
        protected static $tabla = 'categorias';
        protected static $columnasDB = ['id', 'nombre'];
        
        // Declarando los atributos
        public $id;
        public $nombre;

        
    }