<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <input type="text" id="search" class="form-control mb-2" placeholder="Recherche par mot-clé">
            <select id="statut" class="form-select mb-2">
                <option value="">Statut</option>
                <option value="à louer">À louer</option>
                <option value="à vendre">À vendre</option>
                <option value="vendu">Vendu</option>
            </select>
            <select id="type" class="form-select mb-2">
                <option value="">Type de bien</option>
                <option value="Appartement">Appartement</option>
                <option value="Maison">Maison</option>
                <option value="Terrain">Terrain</option>
            </select>
            <input type="number" id="min_price" class="form-control mb-2" placeholder="Prix min">
            <input type="number" id="max_price" class="form-control mb-2" placeholder="Prix max">
            <select id="chambres" class="form-select mb-2">
                <option value="">Chambres</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4+</option>
            </select>
        </div>
        <div class="col-md-9">
            <div id="properties-grid">
                <?php $this->load->view('include/list_grid', ['properties' => $properties]); ?>
            </div>
        </div>
    </div>
</div>
<script>
function loadProperties() {
    var params = {
        search: $('#search').val(),
        statut: $('#statut').val(),
        type: $('#type').val(),
        min_price: $('#min_price').val(),
        max_price: $('#max_price').val(),
        chambres: $('#chambres').val()
    };
    $.get('<?= base_url('properties/ajax_list') ?>', params, function(html) {
        $('#properties-grid').html(html);
    });
}
$('#search, #statut, #type, #min_price, #max_price, #chambres').on('input change', loadProperties);
</script>
