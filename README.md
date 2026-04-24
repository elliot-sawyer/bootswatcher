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
