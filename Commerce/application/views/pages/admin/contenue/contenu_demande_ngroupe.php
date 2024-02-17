<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <h2 class="card-title">Demande</h2>
                </div>
                <div class="col-md-1">
                    <button class ="btn btn-primary" id="groupe" >Groupé</button>
                </div>
            </div>
        </div>

        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Date</td>
                    <td>Departement</td>
                    <td>Materiel</td>
                    <td>Designation</td>
                    <td>Unite</td>
                    <td>Quantite</td>
                    <td>Groupé</td>
                </thead>
                <tbody>
                    <?php foreach ($demandes as $key => $value) { ?>
                        <tr>
                            <td><?php echo  $value->date_demande; ?></td>
                            <td><?php echo  $value->departement; ?></td>
                            <td><?php echo  $value->materiel;?></td>
                            <td><?php echo  $value->produit;?></td>
                            <td><?php echo  $value->unite;?></td>
                            <td><?php echo  $value->quantite; ?></td>
                            <td><input type="checkbox" name="<?php echo  $value->id_demande; ?>" id="<?php echo  $value->id_demande; ?>"></td>
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
    $("#groupe").on("click", function() {
        var demandesCoches = null;
        $("input[type='checkbox']:checked").each(function() {
            if(demandesCoches == null){ demandesCoches = []; }       
            demandesCoches.push($(this).attr("name"));
        });
        if (demandesCoches.length > 2) {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('Admin/groupe_demande'); ?>",
                data: { demandes: demandesCoches },
                success: function(response) {
                    // if (response.success) {
                        window.location.href = "<?php echo site_url("Admin/demande_non_groupe"); ?>";
                    /*}else{
                        console.log(response.message);
                    }*/
                },
                error: function(error) {
                    console.error(error);
                }
            });
        } else {
            alert("Aucune demande cochée.");
        }
    });
});
</script>
