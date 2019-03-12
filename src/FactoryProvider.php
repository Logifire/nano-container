<?php

namespace NanoContainer;

interface FactoryProvider
{
    public function register(ContainerFactory $factory): void;
}
