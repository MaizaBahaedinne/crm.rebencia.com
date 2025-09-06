<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">
                            Agents de <?php echo htmlspecialchars($agency->agency_name ?? 'l\'agence'); ?>
                        </h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/agency'); ?>">Agences</a></li>
                                <li class="breadcrumb-item active">Agents</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agency Info -->
            <?php if (!empty($agency)) : ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($agency->agency_name); ?></h5>
                                    <p class="text-muted mb-0">
                                        <i class="ri-mail-line me-1"></i><?php echo htmlspecialchars($agency->agency_email ?: 'Email non fourni'); ?>
                                        <?php if (!empty($agency->phone)) : ?>
                                        | <i class="ri-phone-line me-1"></i><?php echo htmlspecialchars($agency->phone); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="<?php echo base_url('agency/view/' . $agency->agency_id); ?>" class="btn btn-primary btn-sm">
                                        <i class="ri-eye-line me-1"></i>Voir agence
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Agents List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="searchAgentList" placeholder="Rechercher un agent...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-sm-auto ms-auto">
                                    <div class="d-flex gap-2">
                                        <button type="button" id="grid-view-button" class="btn btn-soft-info btn-icon fs-14 active">
                                            <i class="ri-grid-fill"></i>
                                        </button>
                                        <button type="button" id="list-view-button" class="btn btn-soft-info btn-icon fs-14">
                                            <i class="ri-list-unordered"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($agents)) : ?>
                                <!-- Grid View -->
                                <div id="agents-grid-view">
                                    <div class="row">
                                        <?php foreach ($agents as $agent) : ?>
                                            <div class="col-xl-3 col-lg-4 col-md-6 agent-item">
                                                <div class="card team-box h-100">
                                                    <div class="team-cover">
                                                        <img src="<?php echo base_url('assets/images/small/img-9.jpg'); ?>" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="card-body p-4">
                                                        <div class="row align-items-center team-row">
                                                            <div class="col team-settings">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <button type="button" class="btn btn-light btn-icon rounded-circle btn-sm favourite-btn">
                                                                                <i class="ri-heart-fill fs-14"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-end dropdown">
                                                                        <a href="javascript:void(0);" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="ri-more-fill fs-17"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li><a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>Voir détails</a></li>
                                                                            <li><a class="dropdown-item" href="#"><i class="ri-mail-line me-2"></i>Envoyer email</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="avatar-lg mx-auto">
                                                                <?php if (!empty($agent->agent_avatar)) : ?>
                                                                    <img src="<?php echo $agent->agent_avatar; ?>" alt="" class="img-fluid rounded-circle">
                                                                <?php else : ?>
                                                                    <div class="avatar-title bg-primary-subtle text-primary fs-20 rounded-circle">
                                                                        <?php echo strtoupper(substr($agent->agent_name ?? 'A', 0, 2)); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="mt-3">
                                                                <h5 class="mb-1 agent-name">
                                                                    <?php echo htmlspecialchars($agent->agent_name ?: 'Agent sans nom'); ?>
                                                                </h5>
                                                                <p class="text-muted mb-0 agent-designation">
                                                                    <?php echo htmlspecialchars($agent->position ?: 'Agent immobilier'); ?>
                                                                </p>
                                                            </div>
                                                            <div class="mt-2">
                                                                <p class="text-muted mb-1">
                                                                    <i class="ri-mail-line me-1"></i>
                                                                    <?php echo htmlspecialchars($agent->agent_email ?: 'Email non fourni'); ?>
                                                                </p>
                                                                <?php if (!empty($agent->phone)) : ?>
                                                                <p class="text-muted mb-0">
                                                                    <i class="ri-phone-line me-1"></i>
                                                                    <?php echo htmlspecialchars($agent->phone); ?>
                                                                </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- List View -->
                                <div id="agents-list-view" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                            <thead class="text-muted table-light">
                                                <tr>
                                                    <th scope="col">Agent</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Téléphone</th>
                                                    <th scope="col">Position</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($agents as $agent) : ?>
                                                    <tr class="agent-item">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-xs me-3">
                                                                    <?php if (!empty($agent->agent_avatar)) : ?>
                                                                        <img src="<?php echo $agent->agent_avatar; ?>" alt="" class="img-fluid rounded-circle">
                                                                    <?php else : ?>
                                                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                            <?php echo strtoupper(substr($agent->agent_name ?? 'A', 0, 2)); ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-1 agent-name"><?php echo htmlspecialchars($agent->agent_name ?: 'Agent sans nom'); ?></h6>
                                                                    <p class="text-muted mb-0 small">ID: <?php echo $agent->crm_id ?? $agent->agent_id ?? 'N/A'; ?></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="agent-email"><?php echo htmlspecialchars($agent->agent_email ?: 'Non fourni'); ?></td>
                                                        <td><?php echo htmlspecialchars($agent->phone ?: 'Non fourni'); ?></td>
                                                        <td class="agent-designation"><?php echo htmlspecialchars($agent->position ?: 'Agent immobilier'); ?></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-outline-primary">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-info">
                                                                    <i class="ri-mail-line"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="text-center py-5">
                                    <div class="avatar-md mx-auto mb-4">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-3-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2">Aucun agent trouvé</h5>
                                    <p class="text-muted mb-0">Cette agence n'a pas encore d'agents enregistrés.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between grid and list view
    const gridBtn = document.getElementById('grid-view-button');
    const listBtn = document.getElementById('list-view-button');
    const gridView = document.getElementById('agents-grid-view');
    const listView = document.getElementById('agents-list-view');

    gridBtn?.addEventListener('click', function() {
        gridView.style.display = 'block';
        listView.style.display = 'none';
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    });

    listBtn?.addEventListener('click', function() {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
    });

    // Search functionality
    const searchInput = document.getElementById('searchAgentList');
    searchInput?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const agentItems = document.querySelectorAll('.agent-item');
        
        agentItems.forEach(function(item) {
            const name = item.querySelector('.agent-name')?.textContent.toLowerCase() || '';
            const email = item.querySelector('.agent-email')?.textContent.toLowerCase() || '';
            const designation = item.querySelector('.agent-designation')?.textContent.toLowerCase() || '';
            
            if (name.includes(searchTerm) || email.includes(searchTerm) || designation.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
