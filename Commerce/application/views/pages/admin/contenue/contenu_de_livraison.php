
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-9">
                    <h2 class="card-title"> <?php echo $details['fournisseur']; ?></h2> <br>
                    <p><?php echo $details['adress']; ?></p>
                    <p><?php echo $details['phone']; ?></p>
                    <p><?php echo $details['email']; ?></p>
                    <p><?php echo $details['numero_livraison']; ?></p>
                    <p><?php echo $details['date_livraison']; ?></p>
                    <p><?php echo $details['lieu']; ?></p>
                </div>
                <div class="col-md-3">
                    <button data-toggle="modal"  data-target="#modal-lg" class ="btn btn-success" id="validation" >Valider livraison</button>
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
                      <td>Livraison</td>
                  </thead>
                  <tbody>
                    <?php foreach ($details['data'] as $key => $detail) { ?>
                        <tr class="detail-row">
                            <td ><?php echo $detail->materiel; ?>
                                <input type="hidden" name="produit_id" id="produit_id" class="produit_id" value="<?php echo ($detail->produit_id); ?>">
                                <input type="hidden" name="numero_livraison" id="numero_livraison" class="numero_livraison" value = "<?php echo ($detail->numero_livraison); ?>">
                                <input type="hidden" name="prix_uniatire" id="prix_uniatire" class="prix_uniatire" value = "<?php echo ($detail->prix_uniatire); ?>">
                                <input type="hidden" name="tax" id="tax" class="tax" value = "<?php echo ($detail->tax); ?>">
                            
                            </td>
                            <td ><?php echo $detail->designation; ?></td>
                            <td > <input type="number" name="quantite" class="quantite" id="quantite" value="<?php echo ($detail->quantite); ?>"> </td>
                            <td ><?php echo $detail->unite; ?></td>
                            <td><a href="#" class="btn btn-primary livrer" >Livrer</a></td>
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
                <h4 class="modal-title">Validation de Livraison  : <?php echo $details['data'][0]->numero_livraison; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <input type="hidden" name="livraison_numero" id="livraison_numero" class="livraison_numero" value = "<?php echo $details['data'][0]->numero_livraison; ?>">
                                
                <div class="form-group">
                    <label for="prix_unitaire">Responsable</label>
                    <input type="text" class="form-control" name="responsable" id="responsable" require>
                </div>  
                <div class="form-group">
                    <label for="prix_unitaire">Date</label>
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
    $(document).ready(function () {
        
        $(".livrer").click(function (event) {
            event.preventDefault();
            var row = $(this).closest(".detail-row");
            var quantite = row.find(".quantite").val();
            var numero_livraison = row.find(".numero_livraison").val();
            var produit_id = row.find(".produit_id").val();
            var prix_uniatire = row.find(".prix_uniatire").val();
            var tax = row.find(".tax").val();
            var data = {
                quantite: quantite,
                numero_livraison: numero_livraison,
                produit_id: produit_id,
                prix_uniatire: prix_uniatire,
                tax:tax
            };
            console.log(data);
            $.ajax({
                url: "<?php echo site_url("Admin/ajout_reception"); ?>", 
                type: 'POST',
                data: data,
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });

           
        });


        $('#valider').on('click', function() {
            var livraisonNumero = $('#livraison_numero').val();
            var responsable = $('#responsable').val();
            var date = $('#date').val();
            var descriptions = $('#descriptions').val();

            $.ajax({
                url: "<?php echo site_url("Admin/validation_reception"); ?>", 
                type: 'POST',
                data: {
                    livraison_numero: livraisonNumero,
                    responsable: responsable,
                    date: date,
                    descriptions: descriptions
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });




    });
     
</script>