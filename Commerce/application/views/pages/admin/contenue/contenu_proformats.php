<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <h2 class="card-title">Liste des proformats</h2>
                </div>
            </div>
        </div>
        
        <div class="card-body p-2">
            
            <h3>Liste des demande sur le groupe numero <?php echo $numero; ?></h3>
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Materiel</td>
                    <td>Designation</td>
                    <td>Quantite total</td>
                </thead>
                <tbody>
                    <?php foreach ($detailsGroupe as $detail) { ?>
                        <tr>
                            <td><?php echo $detail->materiel; ?></td>
                            <td><?php echo $detail->produit; ?></td>
                            <td><?php echo $detail->q; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br> <br>
                     
            <h4>Liste des proformats validés<?php echo $numero; ?></h4>

            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Fournisseur</td>
                    <td>Materiel</td>
                    <td>Designation</td>
                   <td>Quantite</td>
                    <td>Unite</td>
                    <td>Prix Unitaire</td>
                    <td>Tax</td>
                    <td>HT</td>
                    <td>TTC</td>
                    <td>Bon de commande</td>
                    <td>Annulation</td>
                </thead>
                <tbody>
                    <?php foreach ($proforma_groups as $proforma) { ?>
                        <tr>
                            <td><?php echo $proforma->fournisseur ; ?></td>
                            <td><?php echo $proforma->materiel ; ?></td>
                            <td><?php echo $proforma->designation ; ?></td>
                            <td><?php echo $proforma->quantite ; ?></td>
                            <td><?php echo $proforma->unite ; ?></td>
                            <td><?php echo $proforma->prix_uniatire ; ?></td>      
                            <td><?php echo $proforma->value_tax ; ?></td>
                            <td><?php echo $proforma->ht ; ?></td>
                            <td><?php echo $proforma->ttc ; ?></td>
                            <td>
                                <a href="<?php echo site_url("Admin/bon_commande")."/".$proforma->group_id."/".$proforma->fournisseur_id ; ?> " class="btn btn-primary ">Voir</a>
                            </td>
                            <td>
                                <a href="<?php echo site_url("Admin/bon_commande_anuller")."/".$proforma->group_id."/".$proforma->bon_commande_mere_id ; ?> " class="btn btn-danger ">Annuler</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <br><br><br>

            <h3>Listes de meilleurs offres</h3>
            <br>
            
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Fournisseur</td>
                    <td>Materiel</td>
                    <td>Designation</td>
                    <td>Description</td>
                    <td>Quantite</td>
                    <td>Unite</td>
                    <td>Prix Unitaire</td>
                    <td>Tax</td>
                    <td>HT</td>
                    <td>TTC</td>
                    <td>Choisie</td>
                </thead>
                <tbody>
                    <?php foreach ($proformas as $proforma) { ?>
                        <tr>
                            <td><?php echo $proforma->fournisseur ; ?></td>
                            <td><?php echo $proforma->materiel ; ?></td>
                            <td><?php echo $proforma->designation ; ?></td>
                            <td><?php echo $proforma->descriptions ; ?></td>
                            <td><?php echo $proforma->quantite ; ?></td>
                            <td><?php echo $proforma->unite ; ?></td>
                            <td><?php echo $proforma->prix_uniatire ; ?></td>      
                            <td><?php echo $proforma->value_tax ; ?></td>
                            <td><?php echo $proforma->ht ; ?></td>
                            <td><?php echo $proforma->ttc ; ?></td>
                            <td><a href="<?php echo site_url("Admin/valider_proforma")."/".$proforma->id_proforma."/".$proforma->groupe_numero."/".$proforma->produit_id; ?> " class="btn btn-secondary ">Valider</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>

        </div>

    </div>

    
</section>