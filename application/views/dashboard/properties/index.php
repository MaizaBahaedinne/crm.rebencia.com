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
                                <div class="card mb-3 property-card" data-prop-id="<?php echo $property->property_id ?? $property->ID ?? ''; ?>" data-lat="<?php echo htmlspecialchars($property->metas->houzez_geolocation_lat ?? ''); ?>" data-lng="<?php echo htmlspecialchars($property->metas->houzez_geolocation_long ?? ''); ?>">
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
                                                <p class="mb-1 text-muted small">
                                                    <?php echo htmlspecialchars($property->metas->fave_property_map_address ?? $property->metas->fave_property_address ?? ($property->zone_nom ?? 'Adresse non fournie')); ?>
                                                </p>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <strong><?php echo !empty($property->metas->fave_property_price) ? number_format($property->metas->fave_property_price,0,',',' ') . ' TND' : '—'; ?></strong>
                                                    <small class="text-muted">·</small>
                                                    <small class="text-muted"><?php echo !empty($property->metas->fave_property_size) ? $property->metas->fave_property_size . ' m²' : (isset($property->surface_habitable) ? $property->surface_habitable . ' m²' : '—'); ?></small>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2C2JbT8Vf6k1qYqv1qjM+Y6lNfG6kG8f2GZlYhk=" crossorigin=""/>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-QVj8Q+Zx6k6/tjqT+6pWq2w1L6Q1Zx9s1g6Qk3x0b2k=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filtersForm = document.getElementById('filtersForm');
    if (filtersForm) filtersForm.addEventListener('change', function() { this.submit(); });

    // Prepare properties for map
    const listEl = document.getElementById('propertiesList');
    const propertyCards = document.querySelectorAll('#propertiesList .property-card');
    const markers = {};

    // Initialize map
    const map = L.map('map', { scrollWheelZoom: false }).setView([36.8, 10.2], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

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
