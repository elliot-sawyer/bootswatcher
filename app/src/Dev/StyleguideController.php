<?php
namespace Cashware\Bootswatcher\Dev;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class StyleguideController extends Controller
{
    private static $url_segment = 'dev/styleguide';

    private static $allowed_actions = ['index'];

    public function index(HTTPRequest $request): HTTPResponse|string
    {
        if (!Permission::check('CMS_ACCESS_LeftAndMain')) {
            return Security::permissionFailure($this);
        }

        return $this->renderWith('Cashware/Bootswatcher/Dev/Styleguide');
    }

    public function getTitle(): string
    {
        return 'Styleguide';
    }
}
