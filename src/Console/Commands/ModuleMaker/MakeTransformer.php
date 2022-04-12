<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits\MakerHelpers;
use Illuminate\Console\GeneratorCommand;

class MakeTransformer extends GeneratorCommand
{
    use MakerHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:transformer {name} {directory} {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a transformer for an entity';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Tranformer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Stubs/transformer.stub';
    }

    /**
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceEntity($stub, $name)->replaceClass($stub, $name);
    }
}
