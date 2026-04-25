<div class="bootswatch-picker" id="{$ID}_picker">
    <input type="hidden" name="$Name" id="$ID" value="$Value" />

    <button type="button"
            class="bootswatch-trigger"
            aria-haspopup="listbox"
            aria-expanded="false"
            aria-controls="{$ID}_list">
        <% if $CurrentThumbnail %>
            <img src="$CurrentThumbnail" alt="" class="bootswatch-thumb-sm" loading="lazy" onerror="this.style.display='none'" />
        <% end_if %>
        <span class="bootswatch-trigger-label">$CurrentLabel</span>
        <span class="bootswatch-trigger-caret" aria-hidden="true">&#9660;</span>
    </button>

    <ul class="bootswatch-options"
        id="{$ID}_list"
        role="listbox"
        aria-label="$Title"
        hidden>
        <% loop $ThemeOptions %>
            <li class="bootswatch-option<% if $Selected %> bootswatch-option--selected<% end_if %>"
                role="option"
                data-value="$Key"
                aria-selected="<% if $Selected %>true<% else %>false<% end_if %>"
                tabindex="-1">
                <% if $Thumbnail %>
                    <img src="$Thumbnail" alt="" class="bootswatch-thumb" loading="lazy" onerror="this.style.display='none'" />
                <% end_if %>
                <span class="bootswatch-option-label">$Label</span>
            </li>
        <% end_loop %>
    </ul>
</div>
