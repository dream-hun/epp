<?php

namespace DreamHun\EPP\Facades;

use Illuminate\Support\Facades\Facade;
use DreamHun\EPP\Client;

class EPP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
