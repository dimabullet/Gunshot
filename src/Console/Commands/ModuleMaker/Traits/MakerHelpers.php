<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits;

use Illuminate\Support\Str;

trait MakerHelpers
{
    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceContract(&$stub, $name)
    {
        $stub = str_replace(['DummyContractNamespace', '{{ class }}', '{{class}}'], $this->getContractNamespace(), $stub);
        $stub = str_replace(['DummyContract', '{{ class }}', '{{class}}'], $this->getContractName(), $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceEntity(&$stub, $name)
    {
        $stub = str_replace(['DummyEntityNamespace', '{{ class }}', '{{class}}'], $this->getEntityNamespace(), $stub);
        $stub = str_replace(['dummyEntityVariable', '{{ class }}', '{{class}}'], $this->getEntityVariableName(), $stub);
        $stub = str_replace(['dummyEntityIdVariable', '{{ class }}', '{{class}}'], $this->getEntityIdVariableName(), $stub);
        $stub = str_replace(['DummyEntity', '{{ class }}', '{{class}}'], $this->getEntityName(), $stub);
        $stub = str_replace(['dummyRepoName', '{{ class }}', '{{class}}'], $this->getEntityRepoName(), $stub);
        $stub = str_replace(['dummyEntityGetter', '{{ class }}', '{{class}}'], $this->getEntityGetter(), $stub);
        $stub = str_replace(['dummySaveEntity', '{{ class }}', '{{class}}'], $this->getSaveEntity(), $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceRepository(&$stub, $name)
    {
        $stub = str_replace(['DummyRepositoryNamespace', '{{ class }}', '{{class}}'], $this->getRepositoryNamespace(), $stub);
        $stub = str_replace(['DummyRepository', '{{ class }}', '{{class}}'], $this->getRepositoryName(), $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceTransformer(&$stub, $name)
    {
        $stub = str_replace(['DummyTransformerNamespace', '{{ class }}', '{{class}}'], $this->getTransformerNamespace(), $stub);
        $stub = str_replace(['DummyTransformer', '{{ class }}', '{{class}}'], $this->getTransformerName(), $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceEvent(&$stub, $name)
    {
        $stub = str_replace(['DummyEventNamespace', '{{ class }}', '{{class}}'], $this->getEventNamespace(), $stub);
        $stub = str_replace(['DummyEvent', '{{ class }}', '{{class}}'], $this->getEventName(), $stub);

        return $this;
    }

    /**
     * @return array|string|null
     */
    private function getContractNamespace()
    {
        return 'App\\Modules\\'.str_replace('/', '\\', $this->argument('contract'));
    }

    /**
     * @return array|string|null
     */
    private function getContractName()
    {
        return basename($this->argument('contract'));
    }

    /**
     * @return array|string|null
     */
    private function getEntityNamespace()
    {
        return 'App\\Entities\\'.$this->getEntityName();
    }

    /**
     * @return array|string|null
     */
    private function getEntityName()
    {
        return Str::Studly($this->argument('entity'));
    }

    /**
     * @return array|string|null
     */
    private function getEntityGetter()
    {
        return Str::camel('Get ' . $this->argument('entity'));
    }

    /**
     * @return array|string|null
     */
    private function getSaveEntity()
    {
        return Str::camel('Save ' . $this->argument('entity'));
    }

    /**
     * @return array|string|null
     */
    private function getEntityVariableName()
    {
        return Str::camel($this->argument('entity'));
    }

    /**
     * @return array|string|null
     */
    private function getEntityIdVariableName()
    {
        return Str::camel($this->argument('entity').'Id');
    }

    /**
     * @return array|string|null
     */
    private function getEntityRepoName()
    {
        return Str::camel($this->argument('entity')).'Repo';
    }

    /**
     * @return array|string|null
     */
    private function getRepositoryNamespace()
    {
        return 'App\\Modules\\'.str_replace('/', '\\', $this->argument('repository'));
    }

    /**
     * @return array|string|null
     */
    private function getRepositoryName()
    {
        return basename($this->argument('repository'));
    }

    /**
     * @return array|string|null
     */
    private function getTransformerNamespace()
    {
        return 'App\\Modules\\'.str_replace('/', '\\', $this->argument('transformer'));
    }

    /**
     * @return array|string|null
     */
    private function getTransformerName()
    {
        return basename($this->argument('transformer'));
    }

    /**
     * @return array|string|null
     */
    private function getEventNamespace()
    {
        return 'App\\Modules\\'.str_replace('/', '\\', $this->argument('event'));
    }

    /**
     * @return array|string|null
     */
    private function getEventName()
    {
        return basename($this->argument('event'));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Modules';

        if ($directory = $this->argument('directory')) {
            $directory = str_replace('/', '\\', $directory);
            $namespace = $namespace.'\\'.$directory;
        }

        return $namespace;
    }
}
