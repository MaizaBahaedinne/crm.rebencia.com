<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <input type="text" id="search" class="form-control mb-2" placeholder="Recherche client (nom, email, téléphone)">
        </div>
        <div class="col-md-9">
            <div id="clients-grid">
                <?php $this->load->view('client/list_grid', ['clients' => $clients]); ?>
            </div>
        </div>
    </div>
</div>
<script>
function loadClients() {
    var params = { search: $('#search').val() };
    $.get('<?= base_url('client/ajax_list') ?>', params, function(html) {
        $('#clients-grid').html(html);
    });
}
$('#search').on('input', loadClients);
</script>
