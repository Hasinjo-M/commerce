<div class="card card-warning " style="width: 50%; margin-left: 25%;">
    <div class="card-header">
        <h3 class="card-title">Vos choix de commande</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo site_url('Departement/validation_demande'); ?>" method="post" id="demande">
            <div class="form-group">
                <label for="materiel">Materiel</label>
                <select name="materiel" class="form-control" id="materiel">
                    <option value="0">Aucun</option>
                    <?php foreach ($materiels as $key => $value) { ?>
                        <option value="<?php echo $value->id_categorie ?>"><?php echo $value->libelle ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="designation">Designation</label>
                <select name="designation" class="form-control" id="designation">
                    <option value="0">Aucun</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantite">Quantite</label> 
                <input type="number" class="form-control" name="quantite" id="quantite">
            </div>
            <div class="form-group">
                <label for="justificatif">Justificatif</label>
                <textarea name="justificatif" class="form-control" id="justificatif" cols="30" rows="3"></textarea>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" id="valider">Valider</button>
            </div>
        </form>
    </div>
</div>
<div id="loader" style="display: none;">Chargement en cours...</div>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<script>
$(document).ready(function() {
    var idMateriel = 0;
    $("#materiel").on("change", function() {
        idMateriel = $(this).val();
        console.log("ID du Materiel sélectionné : " + idMateriel);
        if(idMateriel != 0){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url("Departement/getProduitsCategorie"); ?>",
                data: { idCategorie: idMateriel },
                dataType: "json",
                success: function(response) {
                    var options = "<option value=''>Sélectionnez un produit</option>";
                    $.each(response, function(index, produit) {
                        options += "<option value='" + produit.id_produit + "'>" + produit.libelle + "</option>";
                    });
                    $("#designation").html(options);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
    $("#materiel").change();

    $("#valider").on("click", function() {
        var quantite = parseInt($("#quantite").val());

        if (quantite < 0) {
            alert("Erreur : La quantité ne peut pas être négative.");
            return;
        }else{
            $("#loader").show();
            var formData = $("#demande").serialize();
            $.ajax({
                type: "POST",
                url: $("#demande").attr("action"),
                data: formData,
                success: function(response) {
                    $("#loader").hide()
                    if (response.success) {
                        $("#demande").html("");
                    }else{
                        console.log(response.message);
                    }
                },
                error: function(error) {
                    $("#loader").hide()
                    console.error(error);
                }
            });
        }
        
    });
});
</script>