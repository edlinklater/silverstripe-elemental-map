<div class="edgarindustries__elementalmap__block__content <% if $Style %>$CssStyle<% end_if %>">
    <div id="elemental-map-{$ID}" style="height: {$Height}px; <% if $Width %>width: {$Width}px<% end_if %>"></div>
</div>

<% require css('edgarindustries/silverstripe-elemental-map:client/leaflet.css') %>
<% require javascript('edgarindustries/silverstripe-elemental-map:client/leaflet.js') %>

<script>
    var map{$ID} = L.map('elemental-map-{$ID}').setView([{$DefaultLatitude}, {$DefaultLongitude}], {$DefaultZoom});

    L.tileLayer('{$TileUrl.RAW}', {$LeafletParams.RAW}).addTo(map{$ID});

    <% if $Markers %>
        <% loop $Markers %>
            var map{$Up.ID}marker{$ID} = L.marker([{$Latitude}, {$Longitude}]).addTo(map{$Up.ID});

            <% if $Description %>
                map{$Up.ID}marker{$ID}.bindPopup('{$PopupContent}');
            <% end_if %>
        <% end_loop %>
    <% end_if %>
</script>
