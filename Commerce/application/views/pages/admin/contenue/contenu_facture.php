<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
</head>

<style>
    .section{
    border: 1xp solid black;
    width: 805px;
    box-shadow: rgb(152, 149, 149);
    margin-left: 25%;
    }
    .header{
        height: 155px;
        
    }
    .info{
        height: 100px;
    }

    .pareil{
        width: 48%;
        margin-left: 1%;
    }
    table{
        width: 800px;
        margin-left: 2px;
        border: 1px solid black;
    }

</style>

<body>

    <a href="<?php echo site_url('PdfController/generatePdf_facture?factures=' . urlencode(json_encode($facture))); ?>" class="btn btn-success">Exporter</a>
    <section class="section" style="border: 1px solid;">
        <div class="header">
            <div class="logo pareil" style=" float: left;">
                <img src="<?php echo site_url("assets/facture.jpg"); ?> " style="width: 200px; height: 150px;">
            </div>
            <div class="text pareil" style=" float: left;">
                <h3 style="color: rgb(8, 219, 8);">Facture :</h3>
                <h6> Numero :  <i><?php echo $facture[0]->numero_commande ?></i> </h6>
                <h6> Date : <i><?php echo $facture[0]->date_facture?></i> </h6>
            </div>
        </div>

        <div class="info">
            <div class="facturier pareil" style=" float: left;">
                <h5 style="background-color: rgb(8, 219, 8); color: white;">Entreprise :</h5>
                <h6>Nom :  <?php echo $facture[0]->libelle_fournisseur ?></h6>
                <h6>Adress :  <?php echo $facture[0]->adresse_fournisseur ?></h6>
                <h6>Email :  <?php echo $facture[0]->email_fournisseur ?></h6>
                <h6>Contacte :  <?php echo $facture[0]->telephone_fournisseur ?></h6>
            </div>
            <div class="destinataire pareil" style=" float: left;">
                
            </div>
        </div>
        <div class="detail" style="width: 800px;margin-top: 46px;">
            <h2>Liste des commandes :</h2>
            <hr>
            <table>
                <thead>
                    <tr style="background-color: rgb(8, 219, 8); color: white;">
                        <td>Materiel</td>
                        <td>Designation</td>
                        <td>Quantite</td>
                        <td>unite</td>
                        <td>Prix unitaire</td>
                        <td>Tax</td>
                        <td>Valeur Tax</td>
                        <td>HT</td>
                        <td>TTC</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totaltax = 0;
                    $totalht = 0;
                    $ttc = 0;
                     ?>
                <?php 
                    foreach ($facture as $item) { 
                        $value_t = $item->quantite * $item->prix_uniatire * $item->tax /100;
                        $value_ht = $item->quantite * $item->prix_uniatire;
                        $value_ttc = $value_t + $value_ht;
                        ?>
                        <tr class="proforma-row">
                            <td ><?php echo $item->libelle_categorie; ?></td>
                            <td ><?php echo $item->libelle_produit; ?></td>
                            <td > <?php echo ($item->quantite); ?></td>
                            <td ><?php echo $item->unite; ?></td>
                            <td ><?php echo ($item->prix_uniatire); ?></td>      
                            <td ><?php echo ($item->tax); ?></td>
                            <td > <i id="t"><?php echo number_format($value_t); ?></i></td>
                            <td > <i id="ht"><?php echo number_format($value_ht); ?></i></td>
                            <td > <i id="ttc"><?php echo number_format($value_ttc); ?></i></td>
                        </tr>
                    <?php 
                    $totaltax = $totaltax + $value_t;
                    $totalht = $totalht + $value_ht;
                    $ttc = $ttc + $value_ttc;
                    } 
                    ?>
                </tbody>
                
            </table>
            <div style="width: 50%; margin-left: 50%; border-left: 1px solid;">
                <h6>Total prix en valeur Tax : <?php echo number_format($totaltax) ?> Ariary </h6>
                <hr>
                <h6>Total TTC : <?php echo number_format($ttc) ?> Ariary </h6>
                <hr>
                <h6>Total HT : <?php echo number_format($totalht) ?> Ariary</h6>
                <hr>
            </div>
        </div>
    </section>
</body>
</html>