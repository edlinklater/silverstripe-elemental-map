<div class="element_content__content <% if $Style %>$CssStyle<% end_if %>">
    <div id="elemental-map-{$ID}" style="height: {$Height}px"></div>
</div>

<% require css('edgarindustries/silverstripe-elemental-map:client/leaflet.css') %>
<% require javascript('edgarindustries/silverstripe-elemental-map:client/leaflet.js') %>
<% require javascript('edgarindustries/silverstripe-elemental-map:client/leaflet-providers.js') %>

<script>
    var map{$ID} = L.map('elemental-map-{$ID}').setView([{$DefaultLatitude}, {$DefaultLongitude}], {$DefaultZoom});

    <% if $ProviderLive %>
    L.TileLayer.Provider.providers.HERE.url = L.TileLayer.Provider.providers.HERE.url.replace('cit.api', 'api');
    <% end_if %>

    L.tileLayer.provider('{$ProviderDotted}', {$ProviderOptions.RAW}).addTo(map{$ID});

    <% if $Markers %>
        <% loop $Markers %>
        var map{$Up.ID}marker{$ID} = L.marker([{$Latitude}, {$Longitude}]).addTo(map{$Up.ID});
        <% end_loop %>
    <% end_if %>
</script>
