<?php

namespace NanoContainer\Test;

class TestService
{
    public function __construct(
        public readonly string $service_name,
    ) {}
}
