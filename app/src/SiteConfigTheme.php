<?php
namespace Cashware\Bootswatcher;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\TaskRunner;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DB;
use SilverStripe\View\Requirements;

class SiteConfigTheme extends DataExtension
{
    private static $db = [
      'Theme' => 'Enum("default,cerulean,cosmo,cyborg,darkly,flatly,journal,litera,lumen,lux,materia,minty,morph,pulse,quartz,sandstone,simplex,sketchy,slate,solar,spacelab,superhero,united,vapor,yeti,zephyr","default")'
    ];
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Theme', [
            DropdownField::create('Theme', 'Bootswatch Theme')
                ->setSource(BootswatchDownloader::config()->bootswatch_themes)
        ]);
    }

    public function BootswatchTheme()
    {
        Requirements::themedCSS("dist/css/".$this->owner->Theme.'.min');
    }

    public function requireDefaultRecords()
    {
        $task = Injector::inst()->create(BootswatchDownloader::class);
        $task->run([]);
    }

    public function onBeforeWrite()
    {
        $themes = array_keys(BootswatchDownloader::config()->bootswatch_themes);
        shuffle($themes);
        $theme = array_shift($themes);

        if($this->owner->Theme == 'default') {
            $this->owner->Theme = $theme;
        }
    }
}
