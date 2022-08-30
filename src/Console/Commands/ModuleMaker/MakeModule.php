<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {entity} {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module folder for an entity';

    /**
     * @var string
     */
    protected $rootDirectory = '';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        $this->rootDirectory = app_path('Modules');
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->makeDirectory($this->getModuleDirectory());

        if ($this->confirm('Would you like to create a view folder? [yes|no]')) {
            $this->makeViewFolder();
        }

        if ($this->confirm('Would you like to create a repository? [yes|no]')) {
            $searchable = $this->confirm('Would you like to make the repository searchable? [yes|no]');
            $this->makeRepository($searchable);
        }

        if ($this->confirm('Would you like to create a facade? [yes|no]')) {
            $this->makeFacade();
        }

        if ($this->confirm('Would you like to create a transformer? [yes|no]')) {
            $this->makeTransformer();
        }

        if ($this->confirm('Would you like to create a filter? [yes|no]')) {
            $this->makeFilter();
        }

        if ($this->confirm('Would you like to create any events/listeners? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the event to be called?');
                $this->makeEvent(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another event? [yes|no]');
            }
        }

        if ($this->confirm('Would you like to create any jobs? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the job to be called?');
                $this->makeJob(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another job? [yes|no]');
            }
        }

        if ($this->confirm('Would you like to create any notifications? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the notification to be called?');
                $this->makeNotification(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another notification? [yes|no]');
            }
        }

        if ($this->confirm('Would you like to create any value objects? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the value object to be called?');
                $this->makeValueObject(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another value object? [yes|no]');
            }
        }

        if ($this->confirm('Would you like to create any requests? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the requests to be called?');
                $this->makeRequest(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another request? [yes|no]');
            }
        }

        if ($this->confirm('Would you like to create any controllers? [yes|no]')) {
            $continue = true;
            while ($continue == true) {
                $name = $this->ask('What would you like the controller to be called?');
                $this->makeController(Str::Studly($name));
                $continue = $this->confirm('Would you like to create another controller? [yes|no]');
            }
        }

        $this->info('Module Created');
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * @return string
     */
    protected function getModuleDirectory($subdirectory = null)
    {
        $moduleDirectory = $this->rootDirectory.'/'.$this->argument('directory');
        if ($subdirectory) {
            $moduleDirectory = $moduleDirectory.'/'.$subdirectory;
        }

        return $moduleDirectory;
    }

    /**
     * @return void
     */
    protected function makeViewFolder()
    {
        $this->makeDirectory($this->getModuleDirectory('Views'));

        if (config('gunshot.template_engine') === 'inertia') {
            return;
        }

        $this->makeDirectory($this->getModuleDirectory('Views/'.$this->getViewFolderName()));

        Artisan::call('module:config:view:add', [
            'directory' => $this->getModuleDirectory('Views'),
        ]);
    }

    /**
     * @return void
     */
    protected function makeRepository($isSearchable)
    {
        Artisan::call('make:module:contract', [
            'name' => $this->getContractName(),
            'directory' => $this->getContractDirectory(),
            'entity' => $this->getEntityName(),
        ]);

        Artisan::call('make:module:repository', [
            'name' => $this->getRepositoryName(),
            'directory' => $this->getRepositoryDirectory(),
            'contract' => $this->getContractDirectory().'/'.$this->getContractName(),
            'entity' => $this->getEntityName(),
            'isSearchable' => $isSearchable,
        ]);

        Artisan::call('module:config:repository:bind', [
            'repository' => $this->getRepositoryPath(),
            'contract' => $this->getContractPath(),
            'entity' => $this->getEntityName(),
            'isSearchable' => $isSearchable,
        ]);
    }

    /**
     * @return void
     */
    protected function makeFacade()
    {
        Artisan::call('make:module:facade', [
            'name' => $this->getEntityName(),
            'directory' => $this->getFacadesDirectory(),
            'contract' => $this->getContractPath(),
        ]);

        Artisan::call('module:config:facade:add', [
            'facade' => $this->getFacadesPath(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeTransformer()
    {
        Artisan::call('make:module:transformer', [
            'name' => $this->getTransformerName(),
            'directory' => $this->getTransformerDirectory(),
            'entity' => $this->getEntityName(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeFilter()
    {
        Artisan::call('make:module:contract', [
            'name' => $this->getFilterContractName(),
            'directory' => $this->getContractDirectory(),
            'entity' => $this->getEntityName(),
        ]);

        Artisan::call('make:module:filter', [
            'name' => $this->getFilterName(),
            'directory' => $this->getFilterDirectory(),
            'entity' => $this->getEntityName(),
            'contract' => $this->getFilterContractPath(),
            'repository' => $this->getRepositoryPath(),
            'transformer' => $this->getTransformerPath(),
        ]);

        Artisan::call('module:config:filter:bind', [
            'filter' => $this->getFilterPath(),
            'repository' => $this->getRepositoryPath(),
            'contract' => $this->getFilterContractPath(),
            'entity' => $this->getEntityName(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeEvent($name)
    {
        Artisan::call('make:module:event', [
            'name' => $name,
            'directory' => $this->getEventDirectory(),
            'contract' => $this->getContractPath(),
            'entity' => $this->getEntityName(),
        ]);

        $listenerName = $name.'Listener';

        Artisan::call('make:module:listener', [
            'name' => $listenerName,
            'directory' => $this->getListenerDirectory(),
            'event' => $this->getEventDirectory().'/'.$name,
        ]);

        Artisan::call('module:config:event:bind', [
            'event' => $this->getEventDirectory().'/'.$name,
            'listener' => $this->getListenerDirectory().'/'.$listenerName,
        ]);
    }

    /**
     * @return void
     */
    protected function makeJob($name)
    {
        Artisan::call('make:module:job', [
            'name' => $name,
            'directory' => $this->getJobDirectory(),
            'contract' => $this->getContractPath(),
            'entity' => $this->getEntityName(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeNotification($name)
    {
        Artisan::call('make:module:notification', [
            'name' => $name,
            'directory' => $this->getNotificationDirectory(),
            'entity' => $this->getEntityName(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeValueObject($name)
    {
        Artisan::call('make:module:value-object', [
            'name' => $name,
            'directory' => $this->getValueObjectDirectory(),
            'entity' => $this->getEntityName(),
        ]);
    }

    /**
     * @return void
     */
    protected function makeRequest($name)
    {
        Artisan::call('make:module:request', [
            'name' => $name,
            'directory' => $this->getRequestDirectory(),
        ]);
    }

//
    /**
     * @return void
     */
    protected function makeController($name)
    {
        Artisan::call('make:module:controller', [
            'name' => $name,
            'directory' => $this->getControllerDirectory(),
        ]);
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return Str::Studly($this->argument('entity'));
    }

    /**
     * @return string
     */
    protected function getViewFolderName(): string
    {
        return Str::Snake($this->argument('entity'));
    }

    /**
     * @return string
     */
    protected function getContractName(): string
    {
        return $this->argument('entity').'Contract';
    }

    /**
     * @return string
     */
    protected function getContractDirectory(): string
    {
        return $this->argument('directory').'/Contracts';
    }

    /**
     * @return string
     */
    protected function getContractPath()
    {
        return $this->getContractDirectory().'/'.$this->getContractName();
    }

    /**
     * @return string
     */
    protected function getFilterContractName(): string
    {
        return $this->argument('entity').'FilterContract';
    }

    /**
     * @return string
     */
    protected function getFilterContractPath()
    {
        return $this->getContractDirectory().'/'.$this->getFilterContractName();
    }

    /**
     * @return string
     */
    protected function getRepositoryName(): string
    {
        return $this->argument('entity').'Repository';
    }

    /**
     * @return string
     */
    protected function getRepositoryDirectory(): string
    {
        return $this->argument('directory').'/Repositories';
    }

    /**
     * @return string
     */
    protected function getRepositoryPath()
    {
        return $this->getRepositoryDirectory().'/'.$this->getRepositoryName();
    }

    /**
     * @return string
     */
    protected function getTransformerName(): string
    {
        return $this->argument('entity').'Transformer';
    }

    /**
     * @return string
     */
    protected function getTransformerDirectory(): string
    {
        return $this->argument('directory').'/Transformers';
    }

    /**
     * @return string
     */
    protected function getTransformerPath()
    {
        return $this->getTransformerDirectory().'/'.$this->getTransformerName();
    }

    /**
     * @return string
     */
    protected function getFacadesDirectory(): string
    {
        return $this->argument('directory').'/Facades';
    }

    /**
     * @return string
     */
    protected function getFacadesPath(): string
    {
        return $this->getFacadesDirectory().'/'.$this->getEntityName();
    }

    /**
     * @return string
     */
    protected function getFilterName(): string
    {
        return $this->argument('entity').'Filter';
    }

    /**
     * @return string
     */
    protected function getFilterDirectory(): string
    {
        return $this->argument('directory').'/Filters';
    }

    /**
     * @return string
     */
    protected function getFilterPath()
    {
        return $this->getFilterDirectory().'/'.$this->getFilterName();
    }

    /**
     * @return string
     */
    protected function getEventDirectory(): string
    {
        return $this->argument('directory').'/Events';
    }

    /**
     * @return string
     */
    protected function getListenerDirectory(): string
    {
        return $this->argument('directory').'/Listener';
    }

    /**
     * @return string
     */
    protected function getJobDirectory(): string
    {
        return $this->argument('directory').'/Jobs';
    }

    /**
     * @return string
     */
    protected function getValueObjectDirectory(): string
    {
        return $this->argument('directory').'/ValueObjects';
    }

    /**
     * @return string
     */
    protected function getRequestDirectory(): string
    {
        return $this->argument('directory').'/Http/Requests';
    }

    /**
     * @return string
     */
    protected function getControllerDirectory(): string
    {
        return $this->argument('directory').'/Http/Controllers';
    }

    /**
     * @return string
     */
    protected function getNotificationDirectory(): string
    {
        return $this->argument('directory').'/Notifications';
    }
}
