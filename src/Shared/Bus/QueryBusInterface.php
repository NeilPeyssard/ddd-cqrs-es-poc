<?php

namespace Shared\Bus;

interface QueryBusInterface
{
    public function dispatch($message): mixed;
}
