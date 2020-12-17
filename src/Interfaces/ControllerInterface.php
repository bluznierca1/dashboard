<?php

namespace Dashboard\Interfaces;

interface ControllerInterface {

    public function renderView( array $data ): void;

}