## Overview

Bootswatcher is a Bootstrap-5 based Silverstripe module that wraps over 25 different themes from [Bootswatch](https://bootswatch.com). Bootswatch offers a number of free, easy-to-install, and customisable themes fully compatible with Bootstrap 5 out of the box. This module adds a theme picker to your SiteConfig, downloads all Bootswatch CSS files on `dev/build`, and applies the selected theme automatically.

## Requirements

- Silverstripe CMS 5 or 6
- PHP 8.0+

## Installation

```bash
composer require elliotsawyer/bootswatcher
composer require elliotsawyer/bootswatcher-theme
```

Then run a `dev/build` to download the Bootswatch CSS files and register the `Theme` field on `SiteConfig`:

```bash
vendor/bin/sake dev/build flush=all
```

## Configuration

Add the `bootswatcher` theme to your theme list in `app/_config/theme.yml`:

```yaml
---
Name: mytheme
---
SilverStripe\View\SSViewer:
  themes:
    - '$public'
    - 'bootswatcher'
    - '$default'
```

The `SiteConfigTheme` extension is applied automatically by the module's config. Log in to the CMS, go to **Settings → Theme**, and select your preferred Bootswatch theme from the dropdown.

## Available Themes

cerulean, cosmo, cyborg, darkly, flatly, journal, litera, lumen, lux, materia, minty, morph, pulse, quartz, sandstone, simplex, sketchy, slate, solar, spacelab, superhero, united, vapor, yeti, zephyr

## License

BSD 3-Clause
