<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits\FileChangeHelpers;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddViewFolderToConfig extends Command
{
    use FileChangeHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:config:view:add {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind the view folder to the config file';

    /**
     * @var string
     */
    protected $configFile = 'view.php';

    /**
     * @var string
     */
    protected $searchStart = "'paths' => [";

    /**
     * @var string
     */
    protected $searchEnd = '],';

    /**
     * @var Filesystem
     */
    private $files;

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
        $viewFolders = $this->getArrayFromFile($file);
        $viewFolders = $this->addNewRowToArray($viewFolders);
        $viewFolders = $this->alphabetiseArray($viewFolders);
        $viewFolders = $this->convertArrayBackToString($viewFolders);
        $file = $this->replaceTextInFile($file, $viewFolders);
        $this->saveFile($file, $this->getConfigFilePath());
    }

    /**
     * @return string
     */
    public function getNewRow()
    {
        return "app_path('Modules/".$this->argument('directory')."'),";
    }
}
