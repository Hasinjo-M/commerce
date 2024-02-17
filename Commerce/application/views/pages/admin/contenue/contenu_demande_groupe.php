<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <h2 class="card-title">Demande</h2>
                </div>
                
            </div>
        </div>
        
        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Groupe</td>
                    <td>Materiel</td>
                    <td>Detail Groupe</td>
                    <td>Detail Proformats</td>
                    <td>Email</td>
                </thead>
                <tbody>
                    <?php foreach ($demandes as $key => $value) { ?>
                        <tr>
                            <td>Group  <?php echo $value->numero_groupe  ;?></td>
                            <td><?php echo $value->materiel;?></td>
                            <td><button class="btn btn-info voir-detail" data-numero-groupe="<?php echo $value->numero_groupe; ?>">Voir</button></td>
                            <td><a href="<?php echo site_url("Admin/detail_proformats")."/".$value->numero_groupe ; ?> " class="btn btn-secondary ">Voir</a></td>
                            <td><button class="btn btn-primary envoyer" data-toggle="modal"  data-numero-groupe="<?php echo $value->numero_groupe; ?>" data-target="#modal-lg" >Envoyer</button></td>
                            <td><a href="<?php echo site_url("Admin/Degroup")."/".$value->numero_groupe ; ?> " class="btn btn-success ">De groupe</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br><br>
            <h3>Detail du groupe</h3>
            <div id="detail-groupe-container"></div>
            <br><br>
        </div>
    </div>
</section>
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Détails du Groupe <span id="numeroGroupeModal"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary envoyer-email" id="envoyer-email" data-dismiss="modal">Envoyer</button>
            </div>
        </div>
    </div>
</div>
    

<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
<script>
$(document).ready(function() {


    $(".voir-detail").on("click", function(e) {
        e.preventDefault();
        var numeroGroupe = $(this).data("numero-groupe");
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Admin/details_groupe'); ?>",
            data: { numero_groupe: numeroGroupe },
            success: function(response) {
                $("#detail-groupe-container").html(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    /**** Email ***/
    $(".envoyer").on("click", function(e) {
        var numeroGroupe = $(this).data("numero-groupe");
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Admin/detail_envoye_email_groupe'); ?>",
            data: { numero_groupe: numeroGroupe },
            success: function(response) {
                $("#modal-body-content").html(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    /**** Envoye email ***/
    $(".envoyer-email").on("click", function(e) {
        var formData = $("#form_email").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Admin/envoye_email'); ?>",
            data: formData,
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
