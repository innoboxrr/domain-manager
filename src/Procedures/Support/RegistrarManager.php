<?php

namespace Innoboxrr\DomainManager\Procedures\Support;

use Illuminate\Support\Manager;
use Innoboxrr\DomainManager\Procedures\Contracts\Registrar;
use Innoboxrr\DomainManager\Procedures\Registrars\AwsRoute53Registrar;
use Innoboxrr\DomainManager\Procedures\Registrars\NamecheapRegistrar;

class RegistrarManager extends Manager
{
    public function getDefaultDriver()
    {
        return 'aws';
    }

    public function createAwsDriver(): Registrar
    {
        return new AwsRoute53Registrar;
    }

    public function createNamecheapDriver(): Registrar
    {
        return new NamecheapRegistrar;
    }
}
