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
                    <td>Commande</td>
                    <td>Fournisseur</td>
                    <td>Livraison</td>
                    <td>Date</td>
                    <td>Lieu</td>
                    <td>PDF</td>
                    <td>Reception</td>
                </thead>
                <tbody>
                    <?php foreach ($livraisons as $key => $value) { ?>
                        <tr>
                            <td><?php echo  $value->numero_commande; ?></td>
                            <td><?php echo  $value->libelle; ?></td>
                            <td><?php echo  $value->numero_livraison;?></td>
                            <td><?php echo  $value->date_livraison;?></td>
                            <td><?php echo  $value->lieu;?></td>
                            <td><a href="<?php echo site_url("PdfController/generatePdf")."/".$value->fournisseur_id."/".$value->numero_livraison ;; ?>" class="btn btn-success">expoter</a></td>
                            <td><a href="<?php echo site_url("Admin/detail_livraison")."/".$value->fournisseur_id."/".$value->numero_livraison ; ?>" class="btn btn-primary">creer</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
