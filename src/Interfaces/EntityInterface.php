<?php

namespace Dashboard\Interfaces;

interface EntityInterface {

    public function save( array $data = [] );
    public function findById( int $id = null );
    public function getAll();
    public function eraseAllRecords();

}