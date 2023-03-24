<?php
namespace Cashware\Bootswatcher;

use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class BootswatchDownloader extends BuildTask
{
    public $title = 'Bootswatch Downloader';
    private static $segment = 'BootswatchDownloader';
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

    public function run($request) {
        foreach($this->config()->bootswatch_themes as $theme => $name) {
            $client = new Client();
            if($theme == 'default') {
                $url = 'https://bootswatch.com/_vendor/bootstrap/dist/css/bootstrap.min.css';
            } else {
                $url = sprintf("https://bootswatch.com/5/%s/bootstrap.min.css", $theme);
            }

            $fileFolder = THEMES_PATH
                . DIRECTORY_SEPARATOR
                . 'bootswatcher'
                . DIRECTORY_SEPARATOR
                . 'dist'
                . DIRECTORY_SEPARATOR
                . 'css';

            $filename = $fileFolder
                . DIRECTORY_SEPARATOR
                . $theme
                . '.min.css';

            if(file_exists($filename)) continue;
            
            DB::alteration_message('Downloading '.$name.' to '.$filename);

            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:94.0) Gecko/20100101 Firefox/94.0'
                ]
            ]);

            if($response && $response->getStatusCode() == 200) {
                $body = (string) $response->getBody();
                if($body) {
                    if(is_dir($fileFolder)) {
                        file_put_contents($filename, $body);
                    }
                }
            }
        }
    }
}
