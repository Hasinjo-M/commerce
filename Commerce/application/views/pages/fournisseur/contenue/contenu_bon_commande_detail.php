<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-9">
                    <h2 class="card-title"> <?php echo $numero_commande; ?></h2>
                </div>
                <div class="col-md-1">
                    <button data-toggle="modal"  data-target="#modal-lg" class ="btn btn-primary" id="validation" >Valider livraison</button>
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
                      <td>Prix unitaire</td>
                      <td>Tax</td>
                      <td>Valeur Tax</td>
                      <td>HT</td>
                      <td>TTC</td>
                      <td>Modifiaction</td>
                      <td>Livraison</td>
                  </thead>
                  <tbody>
                    <?php foreach ($commandes['data'] as $key => $proforma) { ?>
                        <tr class="proforma-row">
                            <td ><?php echo $proforma->materiel; ?>
                                <input type="hidden" name="produit_id" id="produit_id" class="produit_id" value="<?php echo ($proforma->produit_id); ?>">
                                <input type="hidden" name="group_id" id="group_id" class="group_id" value = "<?php echo ($proforma->group_id); ?>">
                                <input type="hidden" name="proforma_mere" id = "proforma_mere" class="proforma_mere" value="<?php echo ($proforma->proforma_mere); ?>">
                                <input type="hidden" name="numero_commande" id = "numero_commande" class="numero_commande" value="<?php echo ($numero_commande); ?>">
                            </td>
                            <td ><?php echo $proforma->designation; ?></td>
                            <td > <input type="number" name="quantite" class="quantite" id="quantite" value="<?php echo ($proforma->quantite); ?>"> </td>
                            <td ><?php echo $proforma->unite; ?></td>
                            <td ><input type="number" name="prix_uniatire" class="prix_uniatire" id="prix_uniatire" value="<?php echo ($proforma->prix_uniatire); ?>"> </td>      
                            <td ><input type="number" name="tax" class="tax"  id="tax" value="<?php echo ($proforma->tax); ?>"></td>
                            <td ><?php echo number_format($proforma->value_tax); ?></td>
                            <td ><?php echo number_format($proforma->ht); ?></td>
                            <td ><?php echo number_format($proforma->ttc); ?></td>
                            <td><a href="#" class="btn btn-secondary edit-btn" >Modifier</a></td>
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
                <h4 class="modal-title">Validation de Livraison du commande : <?php echo $numero_commande; ?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <input type="hidden" name="group_id" id="group_id" class="group_id" value = "<?php echo $commandes['data'][0]->group_id; ?>">
                                
                <div class="form-group">
                    <label for="prix_unitaire">Lieu</label>
                    <input type="text" class="form-control" name="lieu" id="lieu">
                </div>  
                <div class="form-group">
                    <label for="prix_unitaire">Date</label>
                    <input type="date" class="form-control" name="date" id="date">
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
        $(".edit-btn").click(function (event) {
            event.preventDefault();
            var row = $(this).closest(".proforma-row");
            var quantite = row.find(".quantite").val();
            var prix_uniatire = row.find(".prix_uniatire").val();
            var tax = row.find(".tax").val();
            var produit_id = row.find(".produit_id").val();
            var group_id = row.find(".group_id").val();
            var proforma_mere = row.find(".proforma_mere").val();
            var data = {
                quantite: quantite,
                prix_uniatire: prix_uniatire,
                tax: tax,
                produit_id: produit_id,
                group_id: group_id,
                proforma_mere:proforma_mere
            };
            $.ajax({
            url: "<?php echo site_url("Fournisseur/modifProforma"); ?>", 
            type: 'POST',
            data: data,
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
            }
        });

            console.log(data);
        });



         /****** !Livrer  ****/
    $(".livrer").click(function (event) {
            //event.preventDefault();
            var row = $(this).closest(".proforma-row");
            var quantite = row.find(".quantite").val();
            var prix_uniatire = row.find(".prix_uniatire").val();
            var tax = row.find(".tax").val();
            var produit_id = row.find(".produit_id").val();
            var group_id = row.find(".group_id").val();
            var proforma_mere = row.find(".proforma_mere").val();
            var numero_commande = row.find(".numero_commande").val();
            var data = {
                quantite: quantite,
                prix_uniatire: prix_uniatire,
                tax: tax,
                produit_id: produit_id,
                group_id: group_id,
                proforma_mere:proforma_mere,
                numero_commande: numero_commande
            };
            console.log(data);
            $.ajax({
                url: "<?php echo site_url("Fournisseur/livraison"); ?>", 
                type: 'POST',
                data: data,
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });

            console.log(data);
        });



    });
</script>
<script>
$(document).ready(function(){
    $("#valider").on("click", function(){
        var lieu = $("#lieu").val();
        var date = $("#date").val();
        var groupId = $("#group_id").val();
        var formData = {
            lieu: lieu,
            date: date,
            group_id: groupId
        };
        console.log(formData);
        $.ajax({
            url: "<?php echo site_url("Fournisseur/livraison_valider"); ?>", 
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
            },
            error: function(error){
                console.error(error);
            }
        });
    });
});
</script>
