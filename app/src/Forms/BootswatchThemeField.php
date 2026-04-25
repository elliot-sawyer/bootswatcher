<?php
namespace Cashware\Bootswatcher\Forms;

use SilverStripe\Control\Director;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class BootswatchThemeField extends DropdownField
{
    /**
     * Inject the picker CSS/JS and delegate rendering to BootswatchThemeField.ss.
     */
    public function Field($properties = [])
    {
        Requirements::customCSS($this->pickerCSS(), 'bootswatch-theme-field-css');
        Requirements::customScript($this->pickerJS(), 'bootswatch-theme-field-js');
        return parent::Field($properties);
    }

    /**
     * Return options enriched with thumbnail URL and selection state for the template.
     */
    public function getThemeOptions(): ArrayList
    {
        $current = (string) $this->getValue();
        $options  = ArrayList::create();

        foreach ($this->getSource() as $key => $label) {
            $options->push(ArrayData::create([
                'Key'       => $key,
                'Label'     => $label,
                'Selected'  => (string) $key === $current,
                'Thumbnail' => $key !== 'default' ? $this->localThumbnailURL($key) : null,
            ]));
        }

        return $options;
    }

    /**
     * Human-readable label for the currently selected theme.
     */
    public function getCurrentLabel(): string
    {
        $source = $this->getSource();
        $value  = $this->getValue();
        return $source[$value] ?? '';
    }

    /**
     * Thumbnail URL for the currently selected theme, or null for the Bootstrap default.
     */
    public function getCurrentThumbnail(): ?string
    {
        $value = (string) $this->getValue();
        if (!$value || $value === 'default') {
            return null;
        }
        return $this->localThumbnailURL($value);
    }

    /**
     * Resolve a theme thumbnail to its _resources URL so images are served locally.
     * Thumbnails are downloaded by BootswatchDownloader::getThumbnails() on dev/build.
     */
    private function localThumbnailURL(string $theme): string
    {
        return Director::baseURL() . '_resources/themes/bootswatcher/dist/img/' . $theme . '.png';
    }

    private function pickerCSS(): string
    {
        return <<<'CSS'
.bootswatch-picker{position:relative;display:inline-block}
.bootswatch-trigger{display:flex;align-items:center;gap:8px;padding:6px 10px;background:#fff;border:1px solid #ced4da;border-radius:4px;cursor:pointer;min-width:220px;font-size:.9rem}
.bootswatch-trigger:focus{outline:2px solid #0d6efd;outline-offset:2px}
.bootswatch-trigger-caret{margin-left:auto;font-style:normal}
.bootswatch-thumb-sm{width:48px;height:auto;border-radius:2px;flex-shrink:0}
.bootswatch-options{position:absolute;top:calc(100% + 4px);left:0;z-index:1050;background:#fff;border:1px solid #ced4da;border-radius:4px;list-style:none;margin:0;padding:6px;display:grid;grid-template-columns:repeat(3,1fr);gap:6px;min-width:480px;max-height:420px;overflow-y:auto;box-shadow:0 4px 12px rgba(0,0,0,.15)}
.bootswatch-options[hidden]{display:none}
.bootswatch-option{display:flex;flex-direction:column;align-items:center;gap:4px;padding:6px;cursor:pointer;border:2px solid transparent;border-radius:4px;text-align:center}
.bootswatch-option:hover{background:#f0f4ff}
.bootswatch-option--selected{border-color:#0d6efd;background:#e7f1ff}
.bootswatch-option:focus{outline:2px solid #0d6efd;outline-offset:2px}
.bootswatch-thumb{width:100%;max-width:128px;height:auto;border:1px solid #dee2e6;border-radius:2px}
.bootswatch-option-label{font-size:.75rem;line-height:1.2}
CSS;
    }

    private function pickerJS(): string
    {
        return <<<'JS'
(function () {
    function init(picker) {
        var trigger = picker.querySelector('.bootswatch-trigger');
        var list    = picker.querySelector('.bootswatch-options');
        var input   = picker.querySelector('input[type="hidden"]');

        trigger.addEventListener('click', function () {
            toggle(trigger.getAttribute('aria-expanded') !== 'true');
        });

        list.querySelectorAll('.bootswatch-option').forEach(function (opt) {
            opt.addEventListener('click', function () { choose(opt); });
            opt.setAttribute('tabindex', '-1');
        });

        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(true); }
            if (e.key === 'Escape') toggle(false);
            if (e.key === 'ArrowDown') { e.preventDefault(); focusOption(0); }
        });

        list.addEventListener('keydown', function (e) {
            var opts = Array.from(list.querySelectorAll('.bootswatch-option'));
            var idx  = opts.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') { e.preventDefault(); if (idx < opts.length - 1) opts[idx + 1].focus(); }
            if (e.key === 'ArrowUp')   { e.preventDefault(); if (idx > 0) opts[idx - 1].focus(); else trigger.focus(); }
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); if (idx >= 0) choose(opts[idx]); }
            if (e.key === 'Escape') { toggle(false); trigger.focus(); }
        });

        document.addEventListener('click', function (e) {
            if (!picker.contains(e.target)) toggle(false);
        });

        function focusOption(idx) {
            var opts = list.querySelectorAll('.bootswatch-option');
            if (opts[idx]) { toggle(true); opts[idx].focus(); }
        }

        function toggle(open) {
            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) {
                list.removeAttribute('hidden');
                var sel = list.querySelector('.bootswatch-option--selected');
                if (sel) sel.scrollIntoView({ block: 'nearest' });
            } else {
                list.setAttribute('hidden', '');
            }
        }

        function choose(opt) {
            var value = opt.getAttribute('data-value');
            var label = opt.querySelector('.bootswatch-option-label').textContent.trim();
            var img   = opt.querySelector('.bootswatch-thumb');

            input.value = value;
            picker.querySelector('.bootswatch-trigger-label').textContent = label;

            var thumbSm = picker.querySelector('.bootswatch-thumb-sm');
            if (img) {
                if (!thumbSm) {
                    thumbSm = document.createElement('img');
                    thumbSm.className = 'bootswatch-thumb-sm';
                    thumbSm.alt = '';
                    thumbSm.loading = 'lazy';
                    trigger.insertBefore(thumbSm, picker.querySelector('.bootswatch-trigger-label'));
                }
                thumbSm.src = img.src;
                thumbSm.hidden = false;
            } else if (thumbSm) {
                thumbSm.hidden = true;
            }

            list.querySelectorAll('.bootswatch-option').forEach(function (o) {
                o.classList.toggle('bootswatch-option--selected', o === opt);
                o.setAttribute('aria-selected', o === opt ? 'true' : 'false');
            });

            toggle(false);
            trigger.focus();
        }
    }

    function setup() {
        document.querySelectorAll('.bootswatch-picker').forEach(init);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setup);
    } else {
        setup();
    }
})();
JS;
    }
}
