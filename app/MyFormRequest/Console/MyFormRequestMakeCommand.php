<?php

namespace App\MyFormRequest\Console;

use Illuminate\Foundation\Console\RequestMakeCommand;

class MyFormRequestMakeCommand extends RequestMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:my-form-request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/my-form-request.stub';
    }
}