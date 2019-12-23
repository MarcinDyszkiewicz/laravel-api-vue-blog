<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    /**
     * @var Generator
     */
    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * @param  string  $name
     * @return array|null
     * @throws \Exception
     */
    public function getJsonStructureFile(string $name): ?array
    {
        $filePath = $this->getJsonStructureFilePath($name);

        if (!file_exists($filePath)) {
            throw new \Exception('Json structure file not found');
        }

        return require $filePath;
    }

    /**
     * Normalize the given event name.
     *
     * @param  string  $name
     * @return string
     */
    private function getJsonStructureFilePath(string $name)
    {
        $basePath =  __DIR__ . '/JsonStructure/';
        $delimiter = '.';
        $extension = '.php';

        if (strpos($name, $delimiter) === false) {
            return str_replace('/', '.', $name);
        }

        [$namespace, $name] = explode($delimiter, $name);

        return $basePath . $namespace . '/' . $name . $extension;
    }
}
