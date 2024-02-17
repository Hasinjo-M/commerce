<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-9">
                    <h2 class="card-title"> <?php echo $numero_commande; ?></h2>
                </div>
                <div class="col-md-3 row">
                    <button data-toggle="modal"  data-target="#modal-lg" class ="btn btn-primary col-md-6" id="validation" >Valider livraison</button>
                    <button data-numero = "<?php echo $numero_commande; ?>" class ="btn btn-secondary col-md-6" id="facture" >valider le facture</button>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                  <thead>
                    <tr>
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
                    </tr>
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
                            <td > <i id="t"><?php echo number_format($proforma->value_tax); ?></i></td>
                            <td > <i id="ht"><?php echo number_format($proforma->ht); ?></i></td>
                            <td > <i id="ttc"><?php echo number_format($proforma->ttc); ?></i></td>
                            <td><a href="#" class="btn btn-secondary edit-btn" >Modifier</a></td>
                            <td><a href="#" class="btn btn-primary livrer" >Livrer</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>
        </div>
        <div>
        <a href="<?php echo site_url("Facture/validation_facture")."?numero_commade=".$numero_commande; ?>" class ="btn btn-success col-md-6">Voir le facture</a>
        </div>
    </div>    
</section>

<script>
    
    const quantite = document.getElementById('quantite');
    const prix_unitaire = document.getElementById('prix_uniatire');
    const tax = document.getElementById('tax');
    const reponsetax = document.getElementById('t');
    const ht = document.getElementById('ht');
    const ttc = document.getElementById('ttc');

    function updatePrixUnitaire() {
        
        var valueQ = quantite.value;
        var valuePU = prix_unitaire.value;
        var valueT = tax.value;

        
        setTimeout(() => {
            const t = (valueQ * valuePU * valueT )/100;
            const h = valueQ * valuePU;
            const tt = t + h;

            ht.textContent = h;
            reponsetax.textContent = t;
            ttc.textContent = tt;
        }, 100);
    }

    quantite.addEventListener('input', updatePrixUnitaire);
    prix_unitaire.addEventListener('input', updatePrixUnitaire);
    tax.addEventListener('input', updatePrixUnitaire);
</script>



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

<!-- <button id="envoyerDonnees">Envoyer les données</button> -->

<script>
$(document).ready(function() {
    $("#facture").on("click", function() {
        var data_facture = [];

        // Parcours de chaque ligne du tableau et récupération des valeurs
        $(".proforma-row").each(function() {
            var rowData = {
                materiel: $(this).find("td:eq(0)").text().trim(),
                designation: $(this).find("td:eq(1)").text().trim(),
                id_produit: parseInt($(this).find(".produit_id").val(), 10),
                quantite: parseFloat($(this).find(".quantite").val().trim(), 10),
                unite: $(this).find("td:eq(3)").text(),
                prix_unitaire:parseFloat($(this).find(".prix_uniatire").val(),10),
                tax: parseFloat($(this).find(".tax").val() , 10),
                group_id: parseInt($(this).find(".group_id").val() , 10),
                numero_commande: '<?php echo $numero_commande; ?>',
                id_fournisseur: <?php echo $this->session->fournisseur['id_fournisseur'] ?>
            };
            data_facture.push(rowData);
        });
        Console.log(data_facture);
        // Envoi des données via AJAX à votre script PHP
        // $.ajax({
        //     url: '<?php echo site_url("Facture/index") ;?>',
        //     type: 'POST',
        //     data: { factures: data_facture },
        //     success: function(response) {
        //         console.log(response); // Affiche la réponse
        //         // Traitez la réponse si nécessaire
        //     },
        //     error: function(error) {
        //         console.error(error);
        //     }
        // });
    });
});
</script>