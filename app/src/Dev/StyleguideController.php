<?php
namespace Cashware\Bootswatcher\Dev;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

class StyleguideController extends Controller
{
    private static $url_segment = 'dev/styleguide';

    private static $allowed_actions = ['index'];

    public function index(HTTPRequest $request): HTTPResponse|string
    {
        if (!Permission::check('CMS_ACCESS_LeftAndMain')) {
            return Security::permissionFailure($this);
        }

        $config = SiteConfig::current_site_config();
        $config->BootswatchTheme();

        Requirements::themedCSS('dist/css/default.min');

        return $this->renderWith([
            'Cashware/Bootswatcher/Dev/Styleguide',
            'Page',
        ]);
    }

    public function getTitle(): string
    {
        return 'Styleguide';
    }
}
