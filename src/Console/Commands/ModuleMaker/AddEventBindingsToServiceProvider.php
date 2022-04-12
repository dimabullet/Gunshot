<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits\FileChangeHelpers;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddEventBindingsToServiceProvider extends Command
{
    use FileChangeHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:config:event:bind {event} {listener}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind event and listener to the AppServiceProvider';

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var String
     */
    protected $serviceProvider = 'EventServiceProvider';

    /**
     * @var String
     */
    protected $searchStart = '// Generated Events';

    /**
     * @var String
     */
    protected $searchEnd = '// End Generated Events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    public function handle()
    {
//        TODO - Currently this only binds one event to one listener, and does this on a single line
        $file = $this->getServiceProviderFile();
        $bindingArray = $this->getArrayFromFile($file);
        $bindingArray = $this->addNewRowToArray($bindingArray);
        $bindingArray = $this->alphabetiseArray($bindingArray);
        $bindingArray = $this->convertArrayBackToString($bindingArray, 2, 2);

        $file = $this->replaceTextInFile($file, $bindingArray);

        $file = $this->addImports($file, [
            $this->getNamespace($this->argument('event')),
            $this->getNamespace($this->argument('listener')),
        ]);

        $this->saveFile($file, $this->getServiceProviderPath());
    }

    /**
     * @return string
     */
    public function getNewRow()
    {
        return sprintf('%s::class => [ %s::class],',
            $this->getFilenameFromPath($this->argument('event')),
            $this->getFilenameFromPath($this->argument('listener'))
        );
    }
}
