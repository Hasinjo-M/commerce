<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <h2 class="card-title"></h2>
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
                    <td>Date de validation</td>
                </thead>
                <tbody>
                    <?php foreach ($listes as $key => $value) { ?>
                        <tr>
                            <td><?php echo  $value->date_sortie; ?></td>
                            <td><?php echo  $value->materiel; ?></td>
                            <td><?php echo  $value->libelle;?></td>
                            <td><?php echo  $value->quantite_total;?></td>
                            <td><?php echo  $value->unite;?></td>
                            <td>
                                <input type="date" name="date" class="date-input">
                            </td>
                            <td><button  data-id="<?php echo $value->id_sortie_mere; ?>" class="btn btn-success valider-btn">Valider</button></td>
                            <td><a href="<?php echo site_url("Magasin/annulation_sortie")."/".$value->id_sortie_mere; ?>" class="btn btn-danger">Annuler</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<script>
$(document).ready(function() {
    $(".valider-btn").on("click", function() {
        var dateValue = $(this).closest("tr").find(".date-input").val();
        var idSortieMere = $(this).data("id");
        console.log(dateValue+"  "+idSortieMere);
        $.ajax({
            url: "<?php echo site_url("Magasin/validation_sortie"); ?>",
            method: "POST",
            dataType: "json",
            data: {
                date: dateValue,
                id_sortie_mere: idSortieMere
            },
            success: function(response) {
                if(response.status == true){
                    console.log(response);
                    window.location.href = "<?php echo site_url("Magasin/list_sortie_non_valider"); ?>";
                }else{
                    console.log(response.message);
                }
               
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>
