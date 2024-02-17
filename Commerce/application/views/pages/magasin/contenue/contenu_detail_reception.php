<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-9">
                    <h2 class="card-title"> <?php echo $numero_reception; ?></h2> <br>              
                </div>
                <div class="col-md-3">
                    <button data-toggle="modal"  data-target="#modal-lg" class ="btn btn-success" id="validation" >Valider Entrer</button>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                  <thead>
                      <td>Materiel</td>
                      <td>Designation</td>
                      <td>Quantite</td>
                      <td>unite</td>
                      <td>Entrer</td>
                  </thead>
                  <tbody>
                    <?php foreach ($details as $key => $detail) { ?>
                        <tr class="detail-row">
                            <td ><?php echo $detail->materiel; ?>
                                <input type="hidden" name="produit_id" id="produit_id" class="produit_id" value="<?php echo ($detail->produit_id); ?>">
                                <input type="hidden" name="numero_reception" id="numero_reception" class="numero_reception" value = "<?php echo ($detail->numero_reception); ?>">
                                <input type="hidden" name="prix_uniatire" id="prix_uniatire" class="prix_uniatire" value = "<?php echo ($detail->prix_uniatire); ?>">
                                <input type="hidden" name="tax" id="tax" class="tax" value = "<?php echo ($detail->tax); ?>">
                            </td>
                            <td ><?php echo $detail->libelle; ?></td>
                            <td > <input type="number" name="quantite" class="quantite" id="quantite" value="<?php echo ($detail->quantite); ?>"> </td>
                            <td ><?php echo $detail->unite; ?></td>
                            <td><a href="#" class="btn btn-primary entrer" >entrer</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Validation entrer  : <?php echo $numero_reception; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <input type="hidden" name="numero_reception" id="numero_reception" class="numero_reception" value = "<?php echo $details[0]->numero_reception; ?>">
                                
                <div class="form-group">
                    <label for="prix_unitaire">Responsable</label>
                    <input type="text" class="form-control" name="responsable" id="responsable" require>
                </div>  
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" require>
                </div>
                <div class="form-group">
                    <label for="prix_unitaire">Descriptions</label>
                    <textarea name="descriptions" id="descriptions" class="descriptions" cols="30" rows="5"></textarea>
                </div>          
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary valider" id="valider" data-dismiss="modal" >Valider</button>
            </div>
        </div>
    </div>
</div>

<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<script>
    $(document).ready(function() {
        $('.entrer').on('click', function() {
            var produitId = $(this).closest('tr').find('.produit_id').val();
            var numero_reception = $(this).closest('tr').find('.numero_reception').val();
            var quantite = $(this).closest('tr').find('.quantite').val();
            var prix_uniatire =  $(this).closest('tr').find('.prix_uniatire').val();
            var tax =  $(this).closest('tr').find('.tax').val();
            $.ajax({
                url: "<?php echo site_url("Magasin/ajout_entrer"); ?>", 
                type: 'POST',
                data: {
                    produit_id: produitId,
                    numero_reception: numero_reception,
                    quantite: quantite,
                    prix_uniatire: prix_uniatire,
                    tax:tax
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        function sendDataToController() {
            var numero_reception = $('#numero_reception').val();
            var responsable = $('#responsable').val();
            var date = $('#date').val();
            var descriptions = $('#descriptions').val();

            $.ajax({
                url: '<?php echo base_url("Magasin/validation_entrer"); ?>',
                type: 'POST',
                data: {
                    numero_reception: numero_reception,
                    responsable: responsable,
                    date: date,
                    descriptions: descriptions
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#valider').on('click', function() {
            sendDataToController();
        });
    });
</script>

