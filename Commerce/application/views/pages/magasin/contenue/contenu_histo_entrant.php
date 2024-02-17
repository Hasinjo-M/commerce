<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-3">
                        <div class="form-group">
                            <label for="materiel">Materiel</label>
                            <select name="materiel" class="form-control" id="materiel">
                                <option value="0">Aucun</option>
                                <?php foreach ($materiels as $key => $value) { ?>
                                    <option value="<?php echo $value->id_categorie ?>"><?php echo $value->libelle ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <select name="designation" class="form-control" id="designation">
                                    <option value="0">Aucun</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="designation"> </label>
                                <button class="btn btn-primary form-control" onclick="rechercher()">rechercher</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        
        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Date</td>
                    <td>Materiel</td>
                    <td>Designation</td>
                    <td>Quantite</td>
                    <td>Reste</td>
                    <td>Unite</td>
                    <td>Prix unitaire</td>
                    <td>Monant</td>
                    <td>Type de sortie</td>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<script>
    var idMateriel = 0;
    $("#materiel").on("change", function() {
        idMateriel = $(this).val();
        console.log("ID du Materiel sélectionné : " + idMateriel);
        if(idMateriel != 0){
            $.ajax({
                type: "POST",
                url: "<?php echo site_url("Magasin/getProduitsCategorie"); ?>",
                data: { idCategorie: idMateriel },
                dataType: "json",
                success: function(response) {
                    var options = "<option value='0'>Sélectionnez un produit</option>";
                    $.each(response, function(index, produit) {
                        options += "<option value=" + produit.id_produit + ">" + produit.libelle + "</option>";
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

    
</script>

<script>
    function rechercher() {
        var materiel = $("#materiel").val();
        var designation = $("#designation").val();
        console.log(materiel+ "  " +designation);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("Magasin/rechercher_materiel"); ?>",
            data: { materiel: materiel, designation: designation },
            dataType: "json",
            success: function(response) {
                console.log(response);
                $("table tbody").empty();
                for (var i = 0; i < response.length; i++) {
                    var row = $("<tr>");
                    row.append("<td>" + response[i].date_entrant + "</td>");
                    row.append("<td>" + response[i].materiel + "</td>");
                    row.append("<td>" + response[i].libelle + "</td>");
                    row.append("<td>" + response[i].quantite + "</td>");
                    row.append("<td>" + response[i].reste + "</td>");
                    row.append("<td>" + response[i].unite + "</td>");
                    row.append("<td>" + response[i].prix_unitaire + "</td>");
                    row.append("<td>" + response[i].montant + "</td>");
                    row.append("<td>" + response[i].types + "</td>");
                    $("table tbody").append(row);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>
