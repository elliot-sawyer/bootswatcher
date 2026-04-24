<?php
namespace Cashware\Bootswatcher;

use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\Cli\PolyOutput;

class BootswatchDownloader extends BuildTask
{
    public $title = 'Bootswatch Downloader';
    protected static string $commandName = 'bootswatcher-download';
    private static $bootswatch_themes = [
        'default' => 'Default',
        'cerulean' => 'Cerulean',
        'cosmo' => 'Cosmo',
        'cyborg' => 'Cyborg',
        'darkly' => 'Darkly',
        'flatly' => 'Flatly',
        'journal' => 'Journal',
        'litera' => 'Litera',
        'lumen' => 'Lumen',
        'lux' => 'Lux',
        'materia' => 'Materia',
        'minty' => 'Minty',
        'morph' => 'Morph',
        'pulse' => 'Pulse',
        'quartz' => 'Quartz',
        'sandstone' => 'Sandstone',
        'simplex' => 'Simplex',
        'sketchy' => 'Sketchy',
        'slate' => 'Slate',
        'solar' => 'Solar',
        'spacelab' => 'Spacelab',
        'superhero' => 'Superhero',
        'united' => 'United',
        'vapor' => 'Vapor',
        'yeti' => 'Yeti',
        'zephyr' => 'Zephyr',
    ];

    /**
     * Entry point for the build task — downloads all CSS themes and the Bootstrap JS bundle.
     */
    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $this->getCSS();
        $this->getJS();
        return 0;
    }

    /**
     * Download each Bootswatch theme's minified CSS into the dist/css directory.
     * Skips any file that already exists to avoid redundant network requests.
     */
    public function getCSS(): void
    {
        $fileFolder = $this->distPath('css');
        $this->ensureDir($fileFolder);

        foreach ($this->config()->bootswatch_themes as $theme => $name) {
            $client = new Client();
            if ($theme == 'default') {
                $url = 'https://bootswatch.com/_vendor/bootstrap/dist/css/bootstrap.min.css';
            } else {
                $url = sprintf("https://bootswatch.com/5/%s/bootstrap.min.css", $theme);
            }

            $filename = $fileFolder . DIRECTORY_SEPARATOR . $theme . '.min.css';

            if (file_exists($filename)) {
                continue;
            }

            DB::alteration_message('Downloading ' . $name . ' to ' . $filename);

            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:94.0) Gecko/20100101 Firefox/111.0'
                ]
            ]);

            if ($response && $response->getStatusCode() == 200) {
                $body = (string) $response->getBody();
                if ($body) {
                    file_put_contents($filename, $body);
                }
            }
        }
    }

    /**
     * Download the Bootstrap JS bundle into the dist/js directory.
     * Skips the download if the file already exists.
     */
    public function getJS(): void
    {
        $fileFolder = $this->distPath('js');
        $this->ensureDir($fileFolder);

        $filename = $fileFolder . DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js';

        if (file_exists($filename)) {
            return;
        }

        $url = 'https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js';

        DB::alteration_message('Downloading ' . $filename);

        $client = new Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:94.0) Gecko/20100101 Firefox/111.0'
            ]
        ]);

        if ($response && $response->getStatusCode() == 200) {
            $body = (string) $response->getBody();
            if ($body) {
                file_put_contents($filename, $body);
            }
        }
    }

    /**
     * Build the absolute path to a dist subdirectory within the bootswatcher theme.
     */
    private function distPath(string $type): string
    {
        return implode(DIRECTORY_SEPARATOR, [THEMES_PATH, 'bootswatcher', 'dist', $type]);
    }

    /**
     * Create a directory (and any parents) if it does not already exist.
     */
    private function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}
