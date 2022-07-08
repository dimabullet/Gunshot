<?php

namespace BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\Traits;

trait FileChangeHelpers
{
    /**
     * @var string
     */
    protected $importsSearchStart = '// Generated Imports';

    /**
     * @var string
     */
    protected $importsSearchEnd = '// End Generated Imports';

    /**
     * @return string
     */
    protected function getConfigFilePath(): string
    {
        return config_path($this->configFile);
    }

    /**
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getConfigFile(): string
    {
        return $this->files->get($this->getConfigFilePath());
    }

    /**
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getServiceProviderFile(): string
    {
        return $this->files->get($this->getServiceProviderPath());
    }

    /**
     * @return string
     */
    protected function getServiceProviderPath(): string
    {
        return app_path(sprintf('Providers/%s.php', $this->serviceProvider));
    }

    /**
     * @param  string  $file
     * @return array
     */
    protected function getArrayFromFile(string $file, $searchStart = null, $searchEnd = null): array
    {
        if (! $searchStart) {
            $searchStart = $this->searchStart;
        }

        if (! $searchEnd) {
            $searchEnd = $this->searchEnd;
        }

        $array = $this->getTextBetween($file, $searchStart, $searchEnd);

        // Remove Blank Space
        $array = trim($array);

        // Convert rows to array
        $array = explode(PHP_EOL, $array);

        return array_map('trim', $array);
    }

    /**
     * @param $string
     * @param $start
     * @param $end
     * @return false|string
     */
    public function getTextBetween($string, $start, $end)
    {
        $string = ' '.$string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

    /**
     * @param $string
     * @param $start
     * @param $end
     * @param $replacement
     * @return mixed
     */
    public function replaceTextBetween($string, $start, $end, $replacement)
    {
        $string = ' '.$string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr_replace($string, $replacement, $ini, $len);
    }

    /**
     * @param  array  $array
     * @return array
     */
    protected function addNewRowToArray(array $array): array
    {
        if (! in_array($this->getNewRow(), $array)) {
            $array[] = $this->getNewRow();
        }

        return $array;
    }

    /**
     * @param  array  $facades
     * @return array
     */
    protected function alphabetiseArray(array $facades): array
    {
        return collect($facades)->sort()->toArray();
    }

    /**
     * @param  string  $file
     * @param  string  $newString
     * @return array|string|string[]
     */
    protected function replaceTextInFile(string $file, string $newString, $searchStart = null, $searchEnd = null)
    {
        if (! $searchStart) {
            $searchStart = $this->searchStart;
        }

        if (! $searchEnd) {
            $searchEnd = $this->searchEnd;
        }

        $file = $this->replaceTextBetween($file, $searchStart, $searchEnd, $newString);

        return str_replace(' <?php', '<?php', $file);
    }

    /**
     * @param  string  $file
     * @param  string  $filePath
     * @return void
     */
    protected function saveFile(string $file, string $filePath): void
    {
        $this->files->put($filePath, $file);
    }

    /**
     * @param  array  $viewFolders
     * @return string
     */
    protected function convertArrayBackToString(array $array, $tabs = 2, $postTabs = 1): string
    {
        $array = implode(PHP_EOL, $array);
        $tabsString = str_repeat('    ', $tabs);
        $postTabs = str_repeat('    ', $postTabs);
        $array = str_replace(PHP_EOL, PHP_EOL.$tabsString, $array);

        return PHP_EOL.$tabsString.$array.PHP_EOL.$postTabs;
    }

    /**
     * @return array|string|null
     */
    private function getNamespace($file)
    {
        return sprintf('App\Modules\%s', str_replace('/', '\\', $file));
    }

    /**
     * @return array|string|null
     */
    private function getFilenameFromPath($file)
    {
        return basename($file);
    }

    /**
     * @param $import
     * @return string
     */
    private function importRow($import)
    {
        return sprintf('use %s;', $import);
    }

    /**
     * @param $file
     * @param $imports
     * @return mixed
     */
    private function addImports($file, $imports)
    {
        $importArray = $this->getArrayFromFile($file, $this->importsSearchStart, $this->importsSearchEnd);

        foreach ($imports as $import) {
            if (! in_array($this->importRow($import), $importArray)) {
                $importArray[] = $this->importRow($import);
            }
        }

        $importArray = $this->alphabetiseArray($importArray);
        $importArray = $this->convertArrayBackToString($importArray, 0, 0);

        return $this->replaceTextInFile($file, $importArray, $this->importsSearchStart, $this->importsSearchEnd);
    }
}
