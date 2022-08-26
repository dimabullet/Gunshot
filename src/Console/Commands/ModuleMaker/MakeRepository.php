<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits\MakerHelpers;
use Illuminate\Console\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    use MakerHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:repository {name} {directory} {contract} {entity} {isSearchable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository for an entity';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->argument('isSearchable')) {
            return __DIR__.'/Stubs/searchable-repository.stub';
        }

        return __DIR__.'/Stubs/repository.stub';
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

        return $this
            ->replaceNamespace($stub, $name)
            ->replaceContract($stub, $name)
            ->replaceEntity($stub, $name)
            ->replaceClass($stub, $name)
            ;
    }
}
