## Overview

Bootswatcher is a Silverstripe installer that wraps over 25 different [Bootswatch](https://bootswatch.com) themes, all built on Bootstrap 5. It automatically downloads the CSS for each theme on `dev/build` and exposes a theme picker in the CMS Settings area.

## Packages

This project is split into two Composer packages:

| Package | Type | Description |
|---|---|---|
| [`elliotsawyer/bootswatcher`](https://github.com/elliot-sawyer/bootswatcher) | `silverstripe-recipe` | Installer — includes the CMS extension and build task that downloads Bootswatch themes |
| [`elliotsawyer/bootswatcher-theme`](https://github.com/elliot-sawyer/bootswatcher-theme) | `silverstripe-theme` | Theme — Bootstrap 5 templates, detectable on [ssmods.com](https://ssmods.com) |

The theme package can be installed standalone and ships with the default Bootstrap stylesheet. Install the full installer to get CMS-based theme switching.

## Installation

```bash
composer create-project elliotsawyer/bootswatcher my-app
```

This installs Silverstripe 6 with the Bootswatch theme picker ready to go. On `dev/build`, all Bootswatch theme stylesheets and the Bootstrap JS bundle are downloaded automatically into the theme's `dist/` directory.

### Installing the theme only

```bash
composer require elliotsawyer/bootswatcher-theme
```

The theme ships with the default Bootstrap stylesheet and works out of the box. Run `composer require elliotsawyer/bootswatcher` afterwards to add CMS-based theme switching.

## Styleguide

Bootswatcher exposes a styleguide route at `/dev/styleguide` that renders a full Bootstrap component kitchen sink — buttons, typography, tables, forms, navs, badges, alerts, modals, and more — styled with the currently active Bootswatch theme.

Access is restricted to authenticated CMS users (`CMS_ACCESS_LeftAndMain`). Unauthenticated visitors are redirected to the login page.

This is useful for:
- Previewing how a theme renders standard Bootstrap components before committing to it
- QA-ing template changes against multiple themes
- Giving designers a reference page without needing a populated site

## Silverstripe version support

| Bootswatcher | Silverstripe |
|---|---|
| `1.x` | 4 |
| `2.x` | 5 |
| `3.x` | 6 |

## Theme package version support

| bootswatcher-theme | Silverstripe |
|---|---|
| `1.x` | 6 |
