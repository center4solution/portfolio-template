<section id="travel-map-section" class="py-6 sm:py-6 md:py-10 bg-gray-50 text-gray-800">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold">Where These Stories Happened</h2>
      <p class="text-gray-600 mt-2">A quiet map of the places behind my stories — each pin leads to a moment lived, captured, and remembered.</p>
      <div class="w-16 h-1 bg-emerald-400 mx-auto mt-4 rounded"></div>
    </div>
    <div id="travel-map" class="shadow-md border border-gray-200"></div>
  </div>
</section>

<style>
  #travel-map {
    height: 500px;
    border-radius: 12px;
    overflow: hidden;
    /* Leaflet panes use z-index 400-1000; keep them under the fixed header */
    position: relative;
    z-index: 0;
    isolation: isolate;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const mapContainer = document.getElementById("travel-map");

    if (!mapContainer || typeof L === "undefined") {
      return;
    }

    // Pins come from shared/app/data/places.php
    const travelLocations = <?= json_encode(array_map(fn($p) => ["name" => $p[0], "coords" => [$p[1], $p[2]], "videoUrl" => $p[3]], data("places")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    
    function getSafeHttpUrl(urlValue) {
      if (typeof urlValue !== "string") {
        return null;
      }

      try {
        const parsedUrl = new URL(urlValue);
        if (parsedUrl.protocol === "http:" || parsedUrl.protocol === "https:") {
          return parsedUrl.toString();
        }
      } catch (error) {
        return null;
      }

      return null;
    }

    function buildPopupContent(location) {
      const wrapper = document.createElement("div");
      wrapper.style.minWidth = "170px";
      wrapper.style.display = "flex";
      wrapper.style.flexDirection = "column";
      wrapper.style.gap = "6px";

      const title = document.createElement("div");
      title.textContent = location.name;
      title.style.fontWeight = "600";
      title.style.color = "#111827";
      wrapper.appendChild(title);

      const safeVideoUrl = getSafeHttpUrl(location.videoUrl);
      if (safeVideoUrl) {
        const watchLink = document.createElement("a");
        watchLink.textContent = "Watch Video";
        watchLink.href = safeVideoUrl;
        watchLink.target = "_blank";
        watchLink.rel = "noopener noreferrer";
        watchLink.style.color = "#2F80ED";
        watchLink.style.fontWeight = "500";
        watchLink.style.textDecoration = "none";
        wrapper.appendChild(watchLink);
      }

      return wrapper;
    }

    const travelMap = L.map("travel-map", {
      zoomControl: true,
      scrollWheelZoom: false
    }).setView([0, 0], 2);

    const tileLayerUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
    L.tileLayer(tileLayerUrl, {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19
    }).addTo(travelMap);

    const markerCoordinates = [];
    travelLocations.forEach(function (location) {
      markerCoordinates.push(location.coords);
      L.marker(location.coords).addTo(travelMap).bindPopup(buildPopupContent(location));
    });

    if (markerCoordinates.length === 0) {
      return;
    }

    const bounds = L.latLngBounds(markerCoordinates);
    travelMap.fitBounds(bounds, { padding: [30, 30] });

    // Smooth initial framing after fit for a cleaner entry animation.
    setTimeout(function () {
      travelMap.invalidateSize();
      travelMap.flyToBounds(bounds, {
        padding: [30, 30],
        duration: 1.5
      });
    }, 150);
  });
</script>
