<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="debut">Date debut </label>
                                <input type="date" class="form-control" name="debut" id="debut">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fin">Date debut </label>
                                <input type="date" class="form-control" name="fin" id="fin">
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
                    <td>Unite</td>
                    <td>Prix unitaire</td>
                    <td>Monant</td>
                    
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
    function rechercher() {
        var debut = $("#debut").val();
        var fin = $("#fin").val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url("Magasin/reponse_etat_stock"); ?>", 
            data: { debut: debut, fin: fin },
            dataType: "json",
            success: function (response) {
                $("table tbody").empty();
                for (var i = 0; i < response.length; i++) {
                    var row = $("<tr>");
                    row.append("<td>" + response[i].date_etat + "</td>");
                    row.append("<td>" + response[i].materiel + "</td>");
                    row.append("<td>" + response[i].libelle + "</td>");
                    row.append("<td>" + response[i].quantite + "</td>");
                    row.append("<td>" + response[i].unite + "</td>");
                    row.append("<td>" + response[i].montant / response[i].quantite  + "</td>");
                    row.append("<td>" + response[i].montant +  "</td>");
                    $("table tbody").append(row);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }


</script>
