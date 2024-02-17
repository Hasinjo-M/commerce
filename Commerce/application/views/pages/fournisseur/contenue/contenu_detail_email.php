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
                      <td>Materiel</td>
                      <td>Designation</td>
                      <td>Quantite total</td>
                      <td>Creation Proforma</td>
                  </thead>
                  <tbody>
                      <?php foreach ($details as $detail) { ?>
                          <tr>
                              <td><?php echo $detail->materiel; ?></td>
                              <td><?php echo $detail->produit; ?></td>
                              <td><?php echo $detail->sum; ?></td>
                              <td><button data-toggle="modal"  data-target="#modal-lg" class="btn btn-primary creer" data-numero-produit="<?php echo $detail->produit_id; ?>">Creer</button></td>
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
                <h4 class="modal-title">Creation du proforma </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
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
    $(document).ready(function() {

        $(".creer").on("click", function(e) {
            var produit_id = $(this).data("numero-produit");
            console.log(produit_id);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('Fournisseur/detail_creation_proforma'); ?>",
                data: { produit_id: produit_id },
                success: function(response) {
                    $("#modal-body-content").html(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        /***** Validation  *****/
        $(".valider").on("click", function(e) {
            var quantite = parseInt($("#quantite").val());
            var prixunitaire = parseInt($("#prix_unitaire").val());
            if (prixunitaire < 0) {
                alert("Erreur : Le prix unitaire ne peut pas être négative.");
                return;
            }
            if (quantite < 0) {
                alert("Erreur : La quantité ne peut pas être négative.");
                return;
            }else{
                var formData = $("#proforma").serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('Fournisseur/creation_proforma'); ?>",
                    data: formData,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });   
            }
        });
    });
</script>