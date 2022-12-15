<?php

    namespace Model;

    class EventosRegistros extends ActiveRecord {
        // BD
        protected static $tabla = 'eventos_registros';
        protected static $columnasDB = ['id', 'evento_id', 'registro_id'];
        
        // Declarando los atributos
        public $id;
        public $evento_id;
        public $registro_id;

        // Constructos
        public function __construct($args = [])
        {
            $this->id = $args['id'] ?? null;
            $this->evento_id = $args['evento_id'] ?? '';
            $this->registro_id = $args['registro_id'] ?? '';
        }

        
    }