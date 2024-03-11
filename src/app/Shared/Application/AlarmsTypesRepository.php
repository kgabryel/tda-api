<?php

namespace App\Shared\Application;

interface AlarmsTypesRepository
{
    public function getAlarmsTypes(string ...$ids): AlarmsTypesCollection;
}
