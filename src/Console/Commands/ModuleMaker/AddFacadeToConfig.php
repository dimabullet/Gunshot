<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits\FileChangeHelpers;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddFacadeToConfig extends Command
{
    use FileChangeHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:config:facade:add {facade}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind facade to the app config file';

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var string
     */
    protected $configFile = 'app.php';

    /**
     * @var string
     */
    protected $searchStart = '// Facades';

    /**
     * @var string
     */
    protected $searchEnd = '// End Facades';

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
        $file = $this->getConfigFile();
        $facadesArray = $this->getArrayFromFile($file);
        $facadesArray = $this->addNewRowToArray($facadesArray);
        $facadesArray = $this->alphabetiseArray($facadesArray);
        $facadesArray = $this->convertArrayBackToString($facadesArray);
        $file = $this->replaceTextInFile($file, $facadesArray);
        $this->saveFile($file, $this->getConfigFilePath());
    }

    /**
     * @return array|string|null
     */
    private function getFacadeName()
    {
        return basename($this->argument('facade'));
    }

    /**
     * @return array|string|null
     */
    private function getFacadeNamespace()
    {
        return 'App\\Modules\\'.str_replace('/', '\\', $this->argument('facade'));
    }

    /**
     * @return string
     */
    public function getNewRow()
    {
        return "'".$this->getFacadeName()."' => ".$this->getFacadeNamespace().'::class,';
    }

    /**
     * @param  array  $facades
     * @return string
     */
    protected function convertArrayBackToString(array $facades): string
    {
        $facades = implode(PHP_EOL, $facades);
        $facades = str_replace(PHP_EOL, PHP_EOL."\t\t", $facades);

        return PHP_EOL."\t\t".$facades.PHP_EOL."\t\t";
    }
}
