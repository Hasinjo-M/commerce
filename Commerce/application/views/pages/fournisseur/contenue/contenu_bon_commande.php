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
                      <td>Numero</td>
                      <td>Entreprise</td>
                      <td>Details</td>
                  </thead>
                  <tbody>
                      <?php foreach ($bon_commandes as $bon_commande) { ?>
                          <tr>
                              <td><?php echo $bon_commande->numero_commande; ?></td>
                              <td><?php echo $entreprise->nom; ?></td>
                              <td><a href="<?php echo site_url("Fournisseur/detail_bon_commande")."/".$bon_commande->group_id."/". $bon_commande->numero_commande ; ?> " class="btn btn-primary" >Voir</a></td>
                          </tr>
                      <?php } ?>
                  </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>