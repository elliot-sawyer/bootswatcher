<?php
namespace Cashware\Bootswatcher;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
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
}
