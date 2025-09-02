<?php $this->load->view('includes/header'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
          <h4 class="mb-sm-0"><?= isset($lead)?'Modifier lead':'Nouveau lead'; ?></h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="<?= base_url('leads'); ?>">Leads</a></li>
              <li class="breadcrumb-item active"><?= isset($lead)?'Modifier':'Nouveau'; ?></li>
            </ol>
          </div>
        </div>
      </div></div>

      <?php if(validation_errors()): ?><div class="alert alert-danger small mb-3"><?= validation_errors(); ?></div><?php endif; ?>

  <form method="post" action="<?= isset($lead)? base_url('leads/save/'.$lead['id']) : base_url('leads/save'); ?>" id="leadWizardForm" enctype="multipart/form-data">
    <div class="card">
      <div class="card-body">
        <div id="wizard-steps">
          <!-- Etape 1 : Client WordPress -->
          <div class="wizard-step" data-step="1">
            <h5 class="mb-3">Client WordPress</h5>
            <div class="mb-3">
              <label class="form-label">Client WordPress *</label>
              <input type="text" id="wp_user_autocomplete" class="form-control wizard-check" placeholder="Nom, email ou téléphone..." autocomplete="off" required value="<?php
                if(!empty($lead['wp_user_id']) && !empty($wp_clients)) {
                  foreach($wp_clients as $c) {
                    if((int)$c['user_id'] === (int)$lead['wp_user_id']) {
                      echo htmlspecialchars('#'.$c['user_id'].' - '.$c['full_name'].' ('.$c['user_email'].')');
                      break;
                    }
                  }
                }
              ?>">
              <input type="hidden" name="wp_user_id" id="wp_user_id" value="<?= htmlspecialchars($lead['wp_user_id'] ?? ''); ?>">
              <div id="wp_user_suggestions" class="list-group position-absolute w-100" style="z-index:1000;display:none;"></div>
              <div class="invalid-feedback">Ce champ est obligatoire.</div>
            </div>
            <button type="button" class="btn btn-primary float-end" id="nextStep1">Suivant</button>
          </div>
          <!-- Etape 2 : Identité client -->
          <div class="wizard-step" data-step="2" style="display:none;">
            <h5 class="mb-3">Identité du client</h5>
            <div class="row g-3">
              <div class="col-md-4 mb-3">
                <label class="form-label">Type de client *</label>
                <select name="client_type" id="client_type" class="form-select wizard-check" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="personne" <?= (($lead['client_type'] ?? '')==='personne')?'selected':''; ?>>Personne physique</option>
                  <option value="societe" <?= (($lead['client_type'] ?? '')==='societe')?'selected':''; ?>>Société</option>
                </select>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Type d'identité *</label>
                <select name="client_identite_type" id="client_identite_type" class="form-select wizard-check" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="cin" <?= (($lead['client_identite_type'] ?? '')==='cin')?'selected':''; ?>>CIN</option>
                  <option value="passeport" <?= (($lead['client_identite_type'] ?? '')==='passeport')?'selected':''; ?>>Passeport</option>
                  <option value="titre_sejour" <?= (($lead['client_identite_type'] ?? '')==='titre_sejour')?'selected':''; ?>>Titre de séjour</option>
                  <option value="rc" <?= (($lead['client_identite_type'] ?? '')==='rc')?'selected':''; ?>>RC</option>
                  <option value="mf" <?= (($lead['client_identite_type'] ?? '')==='mf')?'selected':''; ?>>Matricule fiscal</option>
                  <option value="autre" <?= (($lead['client_identite_type'] ?? '')==='autre')?'selected':''; ?>>Autre</option>
                </select>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Numéro d'identité *</label>
                <input name="client_identite_numero" class="form-control wizard-check" value="<?= htmlspecialchars($lead['client_identite_numero'] ?? ''); ?>" required>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Date de délivrance</label>
                <input type="date" name="client_identite_date" class="form-control" value="<?= htmlspecialchars($lead['client_identite_date'] ?? ''); ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Lieu de délivrance</label>
                <input name="client_identite_lieu" class="form-control" value="<?= htmlspecialchars($lead['client_identite_lieu'] ?? ''); ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Date d'expiration</label>
                <input type="date" name="client_identite_date_expiration" class="form-control" value="<?= htmlspecialchars($lead['client_identite_date_expiration'] ?? ''); ?>">
              </div>
            </div>
            <button type="button" class="btn btn-light" id="prevStep2">Précédent</button>
            <button type="button" class="btn btn-primary float-end" id="nextStep2">Suivant</button>
          </div>
          <!-- Etape 3 : Coordonnées principales -->
          <div class="wizard-step" data-step="3" style="display:none;">
            <h5 class="mb-3">Coordonnées principales</h5>
            <div class="row g-3">
              <div class="col-md-6 mb-3">
                <label class="form-label">Prénom</label>
                <input name="prenom" class="form-control" value="<?= htmlspecialchars($lead['prenom'] ?? ''); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Nom</label>
                <input name="nom" class="form-control" value="<?= htmlspecialchars($lead['nom'] ?? ''); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($lead['email'] ?? ''); ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Téléphone</label>
                <input name="telephone" class="form-control" value="<?= htmlspecialchars($lead['telephone'] ?? ''); ?>" required>
              </div>
            </div>
            <button type="button" class="btn btn-light" id="prevStep3">Précédent</button>
            <button type="submit" class="btn btn-success float-end">Enregistrer</button>
<script>
// Validation HTML5 + JS sur la soumission finale
document.getElementById('leadWizardForm').addEventListener('submit', function(e) {
  if(!this.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
    this.classList.add('was-validated');
  }
});
</script>
          </div>
        </div>
        <div class="progress mt-4" style="height:6px;">
          <div class="progress-bar bg-primary" id="wizardProgress" style="width:33%;"></div>
        </div>
      </div>
    </div>
  </form>
<script>
// Wizard JS
let currentStep = 1;
function showStep(step) {
  document.querySelectorAll('.wizard-step').forEach(function(div) {
    div.style.display = (parseInt(div.dataset.step) === step) ? '' : 'none';
  });
  document.getElementById('wizardProgress').style.width = (step*33)+'%';
}
showStep(currentStep);
document.getElementById('nextStep1').onclick = function() {
  // Validation étape 1
  const el = document.getElementById('wp_user_autocomplete');
  if(el.value.trim() === '') {
    el.classList.add('is-invalid');
    el.nextElementSibling.style.display = 'block';
    return;
  }
  showStep(2);
  currentStep = 2;
};
document.getElementById('nextStep2').onclick = function() {
  // Validation étape 2
  let valid = true;
  document.querySelectorAll('.wizard-step[data-step="2"] .wizard-check').forEach(function(el) {
    if(el.value.trim() === '') {
      el.classList.add('is-invalid');
      el.nextElementSibling.style.display = 'block';
      valid = false;
    } else {
      el.classList.remove('is-invalid');
      el.nextElementSibling.style.display = 'none';
    }
  });
  if(!valid) return;
  showStep(3);
  currentStep = 3;
};
document.getElementById('prevStep2').onclick = function() { showStep(1); currentStep = 1; };
document.getElementById('prevStep3').onclick = function() { showStep(2); currentStep = 2; };
</script>
        
    </div>
  </div>
</div>
<?php $this->load->view('includes/footer'); ?>
