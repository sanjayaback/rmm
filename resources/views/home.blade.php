@extends('layouts.app')
@section('title','Interactive Map Search')

@push('head')
<style>
    #map-wrapper { position: relative; width: 100%; height: calc(100vh - 64px); overflow: hidden; background: #E5E7EB; }
    @media (min-width: 768px) { #map-wrapper { height: calc(100vh - 80px); } }

    #map { width: 100%; height: 100%; position: absolute; inset: 0; z-index: 1; }

    /* Top Centered Responsive Filter Bar */
    #filter-bar-container {
        position: absolute;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 400;
        width: max-content;
        max-width: 94vw;
    }

    #filter-bar {
        display: flex;
        gap: 6px;
        align-items: center;
        overflow-x: auto;
        padding: 6px 12px;
        scrollbar-width: none;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(20px);
        border: 1px solid #E5E7EB;
        border-radius: 999px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    #filter-bar::-webkit-scrollbar { display: none; }

    select.filter-pill-airbnb {
        appearance: none;
        padding-right: 28px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2300796B' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    /* Bottom Floating Card Carousel Overlay */
    #card-strip {
        position: absolute;
        bottom: 12px;
        left: 0;
        right: 0;
        z-index: 400;
        pointer-events: none;
    }
    @media (max-width: 768px) { #card-strip { bottom: 58px; } }

    #cards-scroll {
        display: flex;
        gap: 12px;
        padding: 8px 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        pointer-events: auto;
    }
    #cards-scroll::-webkit-scrollbar { display: none; }

    /* Horizontal Compact Map Card */
    .map-card {
        width: 280px;
        min-width: 280px;
        max-width: 280px;
        cursor: pointer;
        scroll-snap-align: start;
        transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #E5E7EB;
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        flex-shrink: 0;
    }
    @media (min-width: 640px) { .map-card { width: 300px; min-width: 300px; max-width: 300px; } }
    .map-card:hover { transform: translateY(-4px); border-color: #00796B !important; box-shadow: 0 14px 30px rgba(0,0,0,0.14); }
    .map-card.active { border-color: #00796B !important; box-shadow: 0 0 25px rgba(0, 121, 107, 0.4) !important; transform: scale(1.02); }

    /* Map Price Marker Pin */
    .price-pin-teal {
        background: #00796B;
        color: #FFFFFF;
        font-weight: 900;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        box-shadow: 0 4px 14px rgba(0, 121, 107, 0.45), 0 0 0 2px #FFFFFF;
        white-space: nowrap;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        transition: transform .2s ease;
    }
    .price-pin-teal:hover {
        transform: scale(1.15);
        background: #00695C;
        box-shadow: 0 6px 20px rgba(0, 121, 107, 0.65), 0 0 0 2px #FFFFFF;
    }
</style>
@endpush

@section('content')
<div id="map-wrapper">
    <div id="map"></div>

    <!-- Top Centered Category Pill Filter Bar (With Counter Pill & Live Search Input) -->
    <div id="filter-bar-container">
        <div id="filter-bar">
            <!-- Room Counter Pill -->
            <div class="bg-teal-50 border border-teal-200 text-[#00796B] font-extrabold text-[11px] px-3 py-1.5 rounded-full shrink-0 flex items-center gap-1 font-heading">
                <span id="count-num">0</span> rooms
            </div>

            <select id="city-filter" class="filter-pill-airbnb">
                <option value="">All Worldwide Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
            <button class="filter-pill-airbnb" data-type="single">🛏 Single</button>
            <button class="filter-pill-airbnb" data-type="double">🛋 Double</button>
            <button class="filter-pill-airbnb" data-type="apartment">🏢 Apartment</button>
            <button class="filter-pill-airbnb" data-type="hostel">🏡 Hostel</button>
            <button class="filter-pill-airbnb" data-type="">All Types</button>
        </div>
    </div>

    <!-- Bottom Floating Card Carousel -->
    <div id="card-strip">
        <div id="cards-scroll"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ALL = @json(json_decode($listingsJson));
let map, markers = [], activeType = '';

function initMap() {
    map = L.map('map', {
        center: [{{ config('roomrent.map_default_lat') }}, {{ config('roomrent.map_default_lng') }}],
        zoom: {{ config('roomrent.map_default_zoom') }},
        zoomControl: false,
    });

    // CartoDB Voyager clean light vector map tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://carto.com/">CARTO</a> &copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    L.control.zoom({ position: 'bottomright' }).addTo(map);
    render(ALL);
}

function render(listings) {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    const countEl = document.getElementById('count-num');
    if (countEl) countEl.textContent = listings.length;
    const strip = document.getElementById('cards-scroll');
    strip.innerHTML = '';

    if (!listings.length) {
        strip.innerHTML = '<div class="bg-white border border-gray-200 rounded-2xl p-3 px-4 text-gray-500 text-xs font-semibold shadow-md">No available rooms found for selected filters.</div>';
        return;
    }

    const boundsGroup = L.featureGroup();

    listings.forEach((l, i) => {
        const formattedPrice = Number(l.price).toLocaleString();
        const icon = L.divIcon({
            html: `<div class="price-pin-teal">Rs. ${formattedPrice}</div>`,
            iconSize: [76, 26], iconAnchor: [38, 13], popupAnchor: [0, -14], className: '',
        });
        const m = L.marker([l.approx_lat, l.approx_lng], { icon })
            .addTo(map)
            .bindPopup(popup(l), { maxWidth: 260 });
        m.on('click', () => focusCard(i));
        markers.push(m);
        boundsGroup.addLayer(m);

        const card = document.createElement('div');
        card.className = 'map-card';
        card.id = 'mc-' + i;
        card.innerHTML = cardHtml(l);
        card.onclick = () => {
            map.flyTo([l.approx_lat, l.approx_lng], 15, { duration: 0.8 });
            markers[i].openPopup();
            focusCard(i);
        };
        strip.appendChild(card);
    });

    if (listings.length > 0) {
        map.fitBounds(boundsGroup.getBounds(), { paddingBottomRight: [20, 160], paddingTopLeft: [20, 80], maxZoom: 15 });
    }
}

function popup(l) {
    return `<div style="font-family:'Plus Jakarta Sans',sans-serif;padding:6px 2px">
        <div style="font-weight:800;font-size:14px;color:#1F2937;margin-bottom:2px;font-family:'Outfit',sans-serif;">${l.title}</div>
        <div style="color:#00796B;font-weight:900;font-size:16px;font-family:'Outfit',sans-serif;">Rs. ${Number(l.price).toLocaleString()}<span style="color:#6B7280;font-size:11px;font-weight:400"> / mo</span></div>
        <div style="color:#6B7280;font-size:11px;margin-top:2px">📍 ${l.area}, ${l.city}</div>
        <a href="/listings/${l.id}" style="display:block;margin-top:10px;background:#00796B;color:#fff;text-align:center;padding:8px 12px;border-radius:12px;font-size:11px;font-weight:800;text-decoration:none;box-shadow:0 4px 12px rgba(0,121,107,0.3)">View Details →</a>
    </div>`;
}

function cardHtml(l) {
    const img = l.image_url || '/images/room-placeholder.jpg';
    const formattedPrice = Number(l.price).toLocaleString();
    return `<div style="display:flex;align-items:center;gap:12px;padding:10px">
        <div style="width:84px;height:84px;border-radius:14px;overflow:hidden;position:relative;flex-shrink:0;background:#F3F4F6;border:1px solid #E5E7EB">
            <img src="${img}" style="width:100%;height:100%;object-fit:cover" onerror="this.onerror=null;this.src='/images/room-placeholder.jpg'">
            <div style="position:absolute;top:4px;left:4px;background:rgba(255,255,255,0.92);backdrop-filter:blur(4px);color:#00796B;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;border:1px solid #B2DFDB;text-transform:capitalize;font-family:'Outfit',sans-serif">${l.room_type}</div>
        </div>
        <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-between;height:84px">
            <div>
                <div style="font-weight:800;font-size:13px;color:#1F2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:'Outfit',sans-serif">${l.title}</div>
                <div style="color:#6B7280;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px">📍 ${l.area}, ${l.city}</div>
                <div style="color:#9CA3AF;font-size:10px;margin-top:2px">🛏 ${l.bedrooms} bd • 🚿 ${l.bathrooms} bath</div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:4px;padding-top:4px;border-top:1px solid #F3F4F6">
                <div style="color:#00796B;font-weight:900;font-size:14px;font-family:'Outfit',sans-serif">Rs. ${formattedPrice}<span style="color:#9CA3AF;font-size:10px;font-weight:400">/mo</span></div>
                <a href="/listings/${l.id}" onclick="event.stopPropagation()"
                   style="background:#E0F2F1;border:1px solid #B2DFDB;color:#00796B;text-align:center;padding:4px 10px;border-radius:10px;font-size:10px;font-weight:800;text-decoration:none;white-space:nowrap">
                    Unlock →
                </a>
            </div>
        </div>
    </div>`;
}

function focusCard(i) {
    document.querySelectorAll('.map-card').forEach(c => c.classList.remove('active'));
    const c = document.getElementById('mc-' + i);
    if (c) { c.classList.add('active'); c.scrollIntoView({ behavior:'smooth', inline:'start' }); }
}

function applyFilters() {
    const city = document.getElementById('city-filter').value;
    const textSearch = (document.getElementById('nav-global-search')?.value || '').toLowerCase().trim();

    let filtered = ALL;

    if (city) {
        filtered = filtered.filter(l => l.city === city);
    }
    if (activeType) {
        filtered = filtered.filter(l => l.room_type === activeType);
    }
    if (textSearch) {
        filtered = filtered.filter(l =>
            (l.title && l.title.toLowerCase().includes(textSearch)) ||
            (l.area && l.area.toLowerCase().includes(textSearch)) ||
            (l.city && l.city.toLowerCase().includes(textSearch)) ||
            (l.description && l.description.toLowerCase().includes(textSearch))
        );
    }

    render(filtered);
}

document.getElementById('city-filter').addEventListener('change', applyFilters);
document.getElementById('nav-global-search')?.addEventListener('input', applyFilters);
if (document.getElementById('nav-global-search')?.value) {
    applyFilters();
}

document.querySelectorAll('[data-type]').forEach(btn => {
    btn.addEventListener('click', () => {
        activeType = btn.dataset.type;
        document.querySelectorAll('[data-type]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyFilters();
    });
});

document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush
