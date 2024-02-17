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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="designation"> </label>
                                <button class="btn btn-primary form-control" id="voirStatistiqueBtn">Voir statistique</button>
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
                    <td>Unite</td>
                    <td>Monant</td>
                    <td>Type de sortie</td>
                    <td>Sortie mere</td>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modal-content">
            <div class="contenue" id="contenue"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-lg2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Statistique</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <canvas id="myPieChart" ></canvas>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button> <s></s>
        </div>
    </div>
</div>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<!-- ChartJS -->
<script src=<?php echo site_url("assets/plugins/chart.js/Chart.min.js"); ?>></script>
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
            url: "<?php echo site_url("Magasin/histo_sortie"); ?>",
            data: { materiel: materiel, designation: designation },
            dataType: "json",
            success: function(response) {
                console.log(response);
                $("table tbody").empty();
                for (var i = 0; i < response.length; i++) {
                    var row = $("<tr>");
                    row.append("<td>" + response[i].date_sortie + "</td>");
                    row.append("<td>" + response[i].materiel + "</td>");
                    row.append("<td>" + response[i].libelle + "</td>");
                    row.append("<td>" + response[i].quantite_total + "</td>");
                    row.append("<td>" + response[i].unite + "</td>");
                    row.append("<td>" + response[i].montant + "</td>");
                    row.append("<td>" + response[i].types + "</td>");
                   
                    var btnColumn = $("<td>");
                    var btn = $("<button>")
                        .text("Voir les details") 
                        .attr("idmere", response[i].id_sortie_mere)
                        .addClass("btn btn-success") 
                        .attr("data-toggle", "modal") 
                        .attr("data-target", "#modal-lg") 
                        .click(function () {
                            var idSortieMere = $(this).attr("idmere");
                            console.log(idSortieMere);
                            $.ajax({
                                type: "POST",
                                url: "<?php echo site_url("Magasin/getDetailSortie"); ?>",
                                data: { idSortieMere: idSortieMere },
                                success: function (data) {
                                    $("#contenue").html(data);
                                },
                                error: function (error) {
                                   
                                    console.error("Erreur", error);
                                }
                            });
                        });
                    btn.appendTo(btnColumn);
                    btnColumn.appendTo(row);
                    $("table tbody").append(row);
                }

            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>
<script>
    $(document).ready(function(){
        $('#voirStatistiqueBtn').on('click', function(){
            var materiel = $("#materiel").val();
            console.log("rr ID du Materiel sélectionné : " + materiel);
      
            $.ajax({
                type: "POST",
                url: "<?php echo site_url("Magasin/getDataChart"); ?>",
                data: { categorie_id: materiel },
                dataType: "json",
                success: function(response) {
                    var ctx = document.getElementById('myPieChart').getContext('2d');
                    var data = {
                        labels: response.labels,
                        datasets: [{
                            data: response.data,
                            backgroundColor: response.colors
                        }]
                    };

                    var options = {
                        
                    };

                    var myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: options
                    });
                    $('#modal-lg2').modal('show');
            },
            error: function(error) {
                console.log(error);
            }
        });
           
        });
    });
</script>