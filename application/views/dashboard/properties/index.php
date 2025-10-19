<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Propriétés</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Propriétés</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form method="GET" id="filtersForm" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Recherche</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Titre, adresse..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="type_bien" class="form-label">Type</label>
                                    <select class="form-select" id="type_bien" name="type_bien">
                                        <option value="">Tous types</option>
                                        <?php if (isset($property_types)) : ?>
                                            <?php foreach ($property_types as $type): ?>
                                                <option value="<?= $type->slug ?>" <?php echo ($filters['type_bien'] ?? '') == $type->slug ? 'selected' : ''; ?>><?= $type->name ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="S+1" <?php echo ($filters['type_bien'] ?? '') == 'S+1' ? 'selected' : ''; ?>>Studio</option>
                                            <option value="S+2" <?php echo ($filters['type_bien'] ?? '') == 'S+2' ? 'selected' : ''; ?>>S+2</option>
                                            <option value="S+3" <?php echo ($filters['type_bien'] ?? '') == 'S+3' ? 'selected' : ''; ?>>S+3</option>
                                            <option value="S+4" <?php echo ($filters['type_bien'] ?? '') == 'S+4' ? 'selected' : ''; ?>>S+4</option>
                                            <option value="S+5" <?php echo ($filters['type_bien'] ?? '') == 'S+5' ? 'selected' : ''; ?>>S+5+</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="statut_houzez" class="form-label">Statut</label>
                                    <select class="form-select" id="statut_houzez" name="status">
                                        <option value="">Tous statuts</option>
                                        <?php if (isset($property_statuses)) : ?>
                                            <?php foreach ($property_statuses as $status): ?>
                                                <option value="<?= $status->slug ?>" <?php echo ($filters['status'] ?? '') == $status->slug ? 'selected' : ''; ?>><?= $status->name ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="for-sale" <?php echo ($filters['status'] ?? '') == 'for-sale' ? 'selected' : ''; ?>>À vendre</option>
                                            <option value="for-rent" <?php echo ($filters['status'] ?? '') == 'for-rent' ? 'selected' : ''; ?>>À louer</option>
                                            <option value="sold" <?php echo ($filters['status'] ?? '') == 'sold' ? 'selected' : ''; ?>>Vendu</option>
                                            <option value="rented" <?php echo ($filters['status'] ?? '') == 'rented' ? 'selected' : ''; ?>>Loué</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="prix_min" class="form-label">Prix min</label>
                                    <input type="number" class="form-control" id="prix_min" name="prix_min" 
                                           placeholder="0" value="<?php echo htmlspecialchars($filters['prix_min'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="prix_max" class="form-label">Prix max</label>
                                    <input type="number" class="form-control" id="prix_max" name="prix_max" 
                                           placeholder="∞" value="<?php echo htmlspecialchars($filters['prix_max'] ?? ''); ?>">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced metas filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">Chambres (min)</label>
                                    <input type="number" id="filter_bedrooms" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">S.d.e. (min)</label>
                                    <input type="number" id="filter_bathrooms" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Surface min (m²)</label>
                                    <input type="number" id="filter_size_min" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Surface max (m²)</label>
                                    <input type="number" id="filter_size_max" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Terrain min (m²)</label>
                                    <input type="number" id="filter_land_min" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Garage (min)</label>
                                    <input type="number" id="filter_garage" class="form-control" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Année (min)</label>
                                    <input type="number" id="filter_year" class="form-control" min="1900" max="2100">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Featured</label>
                                    <select id="filter_featured" class="form-select">
                                        <option value="">Tous</option>
                                        <option value="1">Oui</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Classe énergie</label>
                                    <input type="text" id="filter_energy" class="form-control" placeholder="B, A, C...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Code postal</label>
                                    <input type="text" id="filter_zip" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Header -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0 me-3"><?php echo count($properties); ?> propriété(s) trouvée(s)</h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                            <label class="btn btn-outline-primary" for="gridView">
                                <i class="ri-layout-grid-line"></i>
                            </label>
                            <input type="radio" class="btn-check" name="viewMode" id="listView">
                            <label class="btn btn-outline-primary" for="listView">
                                <i class="ri-list-check"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-sort-desc"></i> Trier par
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'date_desc'])); ?>">Plus récent</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'date_asc'])); ?>">Plus ancien</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'price_asc'])); ?>">Prix croissant</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'price_desc'])); ?>">Prix décroissant</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Half-map layout: map (left) + list (right) -->
            <div class="row">
                <div class="col-lg-6">
                    <div id="map" style="width:100%;height:80vh;border-radius:8px;overflow:hidden;"></div>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><?php echo count($properties); ?> propriété(s)</h6>
                        <small class="text-muted">Cliquer sur une carte ou sur un marqueur pour centrer</small>
                    </div>

                    <div id="propertiesList" style="max-height:75vh; overflow:auto;">
                        <?php if (!empty($properties)) : ?>
                            <?php foreach ($properties as $property) : ?>
                                <?php
                                    // prepare property id and coordinates with multiple fallbacks
                                    $pid = $property->property_id ?? $property->ID ?? '';
                                    $lat = '';
                                    $lng = '';

                                    // metas may be object or array
                                    if (!empty($property->metas)) {
                                        if (is_array($property->metas)) {
                                            $lat = $property->metas['houzez_geolocation_lat'] ?? $property->metas['houzez_geolocation_lat'] ?? ($property->metas['fave_property_location'] ?? '');
                                            $lng = $property->metas['houzez_geolocation_long'] ?? $property->metas['houzez_geolocation_long'] ?? '';
                                        } elseif (is_object($property->metas)) {
                                            $lat = $property->metas->houzez_geolocation_lat ?? $property->metas->houzez_geolocation_lat ?? ($property->metas->fave_property_location ?? '');
                                            $lng = $property->metas->houzez_geolocation_long ?? $property->metas->houzez_geolocation_long ?? '';
                                        }
                                    }

                                    // If fave_property_location exists as "lat,lng,..." parse it
                                    if ((empty($lat) || empty($lng)) && !empty($property->metas)) {
                                        $loc = '';
                                        if (is_array($property->metas)) $loc = $property->metas['fave_property_location'] ?? '';
                                        if (empty($loc) && is_object($property->metas)) $loc = $property->metas->fave_property_location ?? '';
                                        if ($loc) {
                                            $parts = preg_split('/\s*,\s*/', $loc);
                                            if (count($parts) >= 2) {
                                                $lat = $parts[0];
                                                $lng = $parts[1];
                                            }
                                        }
                                    }

                                    // Last-chance fallbacks
                                    $lat = trim((string)$lat);
                                    $lng = trim((string)$lng);
                                ?>
                                <div class="card mb-3 property-card" data-prop-id="<?php echo $pid; ?>" data-lat="<?php echo htmlspecialchars($lat); ?>" data-lng="<?php echo htmlspecialchars($lng); ?>">
                                    <div class="row g-0">
                                        <div class="col-5">
                                            <?php if (!empty($property->images['thumbnail'])) : ?>
                                                <img src="<?php echo $property->images['thumbnail']; ?>" class="img-fluid rounded-start" style="height:100%;object-fit:cover;" alt="<?php echo htmlspecialchars($property->property_title ?? $property->post_title ?? 'Propriété'); ?>">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height:100%;min-height:120px;"><i class="ri-home-line fs-2 text-muted"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-7">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1"><a href="<?php echo base_url('properties/view/' . ($property->property_id ?? $property->ID ?? '')); ?>" class="text-decoration-none"><?php echo htmlspecialchars($property->property_title ?? $property->post_title ?? 'Propriété sans titre'); ?></a></h6>
                                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($property->property_date ?? $property->post_date ?? '')); ?></small>
                                                </div>
                                                <?php
                                                    // helper to read metas whether array or object
                                                    $get_meta = function($k) use ($property) {
                                                        if (empty($property->metas)) return null;
                                                        if (is_array($property->metas)) return $property->metas[$k] ?? null;
                                                        if (is_object($property->metas)) return $property->metas->{$k} ?? null;
                                                        return null;
                                                    };

                                                    $address = $get_meta('fave_property_map_address') ?? $get_meta('fave_property_address') ?? ($property->zone_nom ?? 'Adresse non fournie');
                                                    $price = $get_meta('fave_property_price') ?? $get_meta('fave_property_price');
                                                    $size = $get_meta('fave_property_size') ?? ($property->surface_habitable ?? null);
                                                    $bedrooms = $get_meta('fave_property_bedrooms');
                                                    $bathrooms = $get_meta('fave_property_bathrooms');
                                                    $land = $get_meta('fave_property_land');
                                                    $garage = $get_meta('fave_property_garage');
                                                    $year = $get_meta('fave_property_year');
                                                    $typeName = $property->type->name ?? $property->type ?? '';
                                                    $statusName = $property->status->name ?? $property->status ?? '';
                                                ?>

                                                <p class="mb-1 text-muted small"><?php echo htmlspecialchars($address); ?></p>

                                                <div class="d-flex gap-2 align-items-center mb-1">
                                                    <strong><?php echo $price !== null && $price !== '' ? number_format((float)$price,0,',',' ') . ' TND' : '—'; ?></strong>
                                                    <small class="text-muted">·</small>
                                                    <small class="text-muted"><?php echo $size ? htmlspecialchars($size . ' m²') : '—'; ?></small>
                                                </div>

                                                <div class="d-flex gap-2 align-items-center mb-2 small text-muted">
                                                    <?php if ($bedrooms) : ?><span class="badge bg-light text-dark"><?= htmlspecialchars($bedrooms) ?> ch.</span><?php endif; ?>
                                                    <?php if ($bathrooms) : ?><span class="badge bg-light text-dark"><?= htmlspecialchars($bathrooms) ?> s.d.e.</span><?php endif; ?>
                                                    <?php if ($land) : ?><span class="badge bg-light text-dark"><?= htmlspecialchars($land) ?> m² terrain</span><?php endif; ?>
                                                    <?php if ($garage) : ?><span class="badge bg-light text-dark"><?= htmlspecialchars($garage) ?> gar.</span><?php endif; ?>
                                                    <?php if ($year) : ?><span class="badge bg-light text-dark">Année <?= htmlspecialchars($year) ?></span><?php endif; ?>
                                                </div>

                                                <div class="mb-2">
                                                    <?php if (!empty($typeName)): ?><span class="badge bg-primary me-1"><?= htmlspecialchars($typeName) ?></span><?php endif; ?>
                                                    <?php if (!empty($statusName)): ?><span class="badge bg-success"><?= htmlspecialchars($statusName) ?></span><?php endif; ?>
                                                </div>
                                                <div class="mt-2 d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($property->agent_photo ?? ''); ?>" alt="" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                                                    <div>
                                                        <div class="small fw-medium"><?php echo htmlspecialchars($property->agent_name ?? 'Agent'); ?></div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($property->agency_name ?? 'Agence'); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <h5>Aucune propriété trouvée</h5>
                                <p class="text-muted">Essayez d'élargir vos filtres.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Single reusable Gallery Modal -->
            <div class="modal fade" id="propertyGalleryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="propertyGalleryTitle">Galerie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body" id="propertyGalleryBody">
                            <div class="row g-2" id="propertyGalleryItems"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Client-side filtering: use server filters if user submits; otherwise react on change
    const filtersForm = document.getElementById('filtersForm');
    // Properties JSON (for client-side filtering)
    const propertiesData = <?php echo json_encode($properties); ?>;

    const submitOnServer = false; // change to true to fallback to server-side
    if (filtersForm) {
        filtersForm.addEventListener('submit', function(e){
            if (!submitOnServer) { e.preventDefault(); applyFiltersJS(); }
        });
        // react on change
        filtersForm.addEventListener('change', function(e){ if (!submitOnServer) applyFiltersJS(); });
    }

    // Prepare properties for map
    const listEl = document.getElementById('propertiesList');
    const propertyCards = document.querySelectorAll('#propertiesList .property-card');
    const markers = {};

    // Initialize map
    const map = L.map('map', { scrollWheelZoom: false }).setView([36.8, 10.2], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Force redraw in case container size wasn't available on init (common in flex layouts)
    setTimeout(function() { try { map.invalidateSize(); } catch(e) { console.warn('Leaflet invalidateSize failed', e); } }, 200);
    window.addEventListener('resize', function(){ try { map.invalidateSize(); } catch(e){} });

    // Add markers
    propertyCards.forEach(card => {
        const lat = card.getAttribute('data-lat');
        const lng = card.getAttribute('data-lng');
        const pid = card.getAttribute('data-prop-id');
        if (!lat || !lng) return;
        const title = card.querySelector('h6') ? card.querySelector('h6').innerText : 'Propriété';

        const marker = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map).bindPopup(title);
        markers[pid] = marker;

        // Clicking marker highlights card
        marker.on('click', function() {
            document.querySelectorAll('#propertiesList .property-card').forEach(c=>c.classList.remove('border-primary'));
            const target = document.querySelector('#propertiesList .property-card[data-prop-id="'+pid+'"]');
            if (target) {
                target.classList.add('border', 'border-primary');
                target.scrollIntoView({behavior:'smooth', block:'center'});
            }
        });
    });

    // Apply filters JS: hide/show list cards and markers
    function applyFiltersJS() {
        const q = (document.getElementById('search') ? document.getElementById('search').value.trim().toLowerCase() : '');
        const type = (document.getElementById('type_bien') ? document.getElementById('type_bien').value : '');
        const status = (document.getElementById('statut_houzez') ? document.getElementById('statut_houzez').value : '');
        const prixMin = parseFloat(document.getElementById('prix_min') ? document.getElementById('prix_min').value : '') || null;
        const prixMax = parseFloat(document.getElementById('prix_max') ? document.getElementById('prix_max').value : '') || null;

        // advanced filters
        const f_bed = parseInt(document.getElementById('filter_bedrooms') ? document.getElementById('filter_bedrooms').value : '') || null;
        const f_bath = parseInt(document.getElementById('filter_bathrooms') ? document.getElementById('filter_bathrooms').value : '') || null;
        const f_size_min = parseFloat(document.getElementById('filter_size_min') ? document.getElementById('filter_size_min').value : '') || null;
        const f_size_max = parseFloat(document.getElementById('filter_size_max') ? document.getElementById('filter_size_max').value : '') || null;
        const f_land = parseFloat(document.getElementById('filter_land_min') ? document.getElementById('filter_land_min').value : '') || null;
        const f_garage = parseInt(document.getElementById('filter_garage') ? document.getElementById('filter_garage').value : '') || null;
        const f_year = parseInt(document.getElementById('filter_year') ? document.getElementById('filter_year').value : '') || null;
        const f_featured = (document.getElementById('filter_featured') ? document.getElementById('filter_featured').value : '');
        const f_energy = (document.getElementById('filter_energy') ? document.getElementById('filter_energy').value.trim().toLowerCase() : '');
        const f_zip = (document.getElementById('filter_zip') ? document.getElementById('filter_zip').value.trim() : '');

        const visibleLatLngs = [];

        propertyCards.forEach(card => {
            const pid = card.getAttribute('data-prop-id');
            const prop = propertiesData.find(p => (p.property_id == pid || p.ID == pid));
            if (!prop) return;

            // helper to read metas safely
            const getMeta = (k) => {
                if (!prop.metas) return null;
                if (Array.isArray(prop.metas)) return prop.metas[k] ?? null;
                if (typeof prop.metas === 'object') return prop.metas[k] ?? prop.metas[k] ?? null;
                return null;
            };

            // match filters
            let match = true;
            // search in title/address
            if (q) {
                const hay = ((prop.property_title||prop.post_title||'') + ' ' + ((getMeta('fave_property_map_address')||getMeta('fave_property_address')) || '')).toLowerCase();
                if (hay.indexOf(q) === -1) match = false;
            }
            // type filter
            if (type) {
                const t = (prop.type && (prop.type.slug||prop.type.name)) ? (prop.type.slug||prop.type.name) : '';
                if (String(t) !== String(type)) match = false;
            }
            // status
            if (status) {
                const s = (prop.status && (prop.status.slug||prop.status.name)) ? (prop.status.slug||prop.status.name) : '';
                if (String(s) !== String(status)) match = false;
            }

            const price = parseFloat(getMeta('fave_property_price')) || null;
            if (prixMin !== null && price !== null && price < prixMin) match = false;
            if (prixMax !== null && price !== null && price > prixMax) match = false;

            // bedrooms
            const bedrooms = parseInt(getMeta('fave_property_bedrooms')) || null;
            if (f_bed !== null && bedrooms !== null && bedrooms < f_bed) match = false;
            // bathrooms
            const bathrooms = parseInt(getMeta('fave_property_bathrooms')) || null;
            if (f_bath !== null && bathrooms !== null && bathrooms < f_bath) match = false;
            // size
            const size = parseFloat(getMeta('fave_property_size')) || null;
            if (f_size_min !== null && size !== null && size < f_size_min) match = false;
            if (f_size_max !== null && size !== null && size > f_size_max) match = false;
            // land
            const land = parseFloat(getMeta('fave_property_land')) || null;
            if (f_land !== null && land !== null && land < f_land) match = false;
            // garage
            const garage = parseInt(getMeta('fave_property_garage')) || null;
            if (f_garage !== null && garage !== null && garage < f_garage) match = false;
            // year
            const year = parseInt(getMeta('fave_property_year')) || null;
            if (f_year !== null && year !== null && year < f_year) match = false;
            // featured
            const featured = getMeta('fave_featured');
            if (f_featured !== '' && String(featured) !== String(f_featured)) match = false;
            // energy
            const energy = (getMeta('fave_energy_class') || '').toLowerCase();
            if (f_energy && energy.indexOf(f_energy) === -1) match = false;
            // zip
            const zip = (getMeta('fave_property_zip') || '');
            if (f_zip && zip.indexOf(f_zip) === -1) match = false;

            // toggle card and marker
            if (match) {
                card.style.display = '';
                if (markers[pid]) {
                    try { markers[pid].addTo(map); visibleLatLngs.push(markers[pid].getLatLng()); } catch(e){}
                }
            } else {
                card.style.display = 'none';
                if (markers[pid] && map.hasLayer(markers[pid])) { try { map.removeLayer(markers[pid]); } catch(e){} }
            }
        });

        if (visibleLatLngs.length > 0) {
            const group = L.featureGroup(visibleLatLngs.map(ll=>L.marker([ll.lat,ll.lng])));
            map.fitBounds(group.getBounds().pad(0.15));
        }
    }

    // Clicking card centres map on marker
    document.querySelectorAll('#propertiesList .property-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const pid = this.getAttribute('data-prop-id');
            if (markers[pid]) {
                map.setView(markers[pid].getLatLng(), 15);
                markers[pid].openPopup();
            }
        });
    });

    // Gallery modal handling (single reusable modal)
    const galleryModal = document.getElementById('propertyGalleryModal');
    const galleryTitle = document.getElementById('propertyGalleryTitle');
    const galleryBody = document.getElementById('propertyGalleryItems');

    document.querySelectorAll('.property-card').forEach(card => {
        const pid = card.getAttribute('data-prop-id');
        const galleryBtn = card.querySelector('[data-bs-target]');
        if (galleryBtn) {
            galleryBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const prop = <?php echo json_encode($properties); ?>.find(p => (p.property_id == pid || p.ID == pid));
                if (!prop) return;
                galleryTitle.innerText = (prop.property_title || prop.post_title || 'Galerie');
                galleryBody.innerHTML = '';
                const gallery = (prop.images && prop.images.gallery) ? prop.images.gallery.slice() : [];
                if (prop.images && prop.images.thumbnail) gallery.unshift(prop.images.thumbnail);
                if (gallery.length === 0) galleryBody.innerHTML = '<div class="col-12 text-center text-muted">Aucune image.</div>';
                gallery.forEach(img => {
                    const col = document.createElement('div'); col.className='col-6 mb-2';
                    col.innerHTML = '<a href="'+img+'" target="_blank"><img src="'+img+'" class="img-fluid rounded"></a>';
                    galleryBody.appendChild(col);
                });
                var modal = new bootstrap.Modal(galleryModal);
                modal.show();
            });
        }
    });
});
</script>

<style>
/* Half map styles */
#propertiesList { padding-right:8px; }
.property-card { cursor:pointer; }
.property-card.border-primary { box-shadow: 0 6px 18px rgba(0,123,255,.12); }
@media (max-width: 991px) { #map { height: 50vh !important; } }
</style>
