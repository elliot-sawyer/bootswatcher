<?php
namespace Cashware\Bootswatcher;

use Cashware\Bootswatcher\Forms\BootswatchThemeField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\View\Requirements;

class SiteConfigTheme extends Extension
{
    private static $db = [
      'Theme' => 'Enum("default,brite,cerulean,cosmo,cyborg,darkly,flatly,journal,litera,lumen,lux,materia,minty,morph,pulse,quartz,sandstone,simplex,sketchy,slate,solar,spacelab,superhero,united,vapor,yeti,zephyr","default")'
    ];

    /**
     * Add a Theme dropdown to the CMS Settings area.
     */
    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab('Root.Theme', [
            BootswatchThemeField::create('Theme', 'Bootswatch Theme')
                ->setSource(BootswatchDownloader::config()->bootswatch_themes)
        ]);
    }

    /**
     * Inject the selected Bootswatch theme CSS into the page via Requirements.
     */
    public function BootswatchTheme(): void
    {
        Requirements::themedCSS("dist/css/" . $this->owner->Theme . '.min');
    }

    /**
     * Download theme assets on dev/build so a fresh install is ready without manual task runs.
     */
    public function requireDefaultRecords(): void
    {
        $task = new BootswatchDownloader();
        $task->getCSS();
        $task->getJS();
        $task->getThumbnails();
    }

    /**
     * Assign a random theme on first save when the owner still has the default placeholder.
     */
    public function onBeforeWrite(): void
    {
        $themes = array_keys(BootswatchDownloader::config()->bootswatch_themes);
        shuffle($themes);
        $theme = array_shift($themes);

        if ($this->owner->Theme == 'default') {
            $this->owner->Theme = $theme;
        }
    }
}
