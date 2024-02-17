<!DOCTYPE html>
<html>
<head>
    <title>Export PDF</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="stylesheet" href="<?php echo site_url("assets/style.css"); ?>"> -->
</head>
<body>

<section class="content" id="commande" style="font-family: Arial, sans-serif; padding: 0px;">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Commande</h2>
        </div>
        
        <div class="card-body p-2">
            <div style="float: left; width: 40%;">
                <h1>Information Entreprise</h1>
                <p><?php echo $entreprise->nom; ?></p>
                <p><?php echo $entreprise->adress; ?></p>
                <p><?php echo $entreprise->phone; ?></p>
                <p><?php echo $entreprise->email; ?></p>
            </div>
            <div style="float: right; width: 40%;">
                <h1>Information Fournisseur</h1>
                <p><?php echo $fournisseur->libelle; ?></p>
                <p><?php echo $fournisseur->adress; ?></p>
                <p><?php echo $fournisseur->phone; ?></p>
                <p><?php echo $fournisseur->email; ?></p>
            </div>
            <div style="clear: both;"></div> <!-- Clear float -->
            
            <table style="width: 50%; border-collapse: collapse; background-color: #f2f2f2;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Fournisseur</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Materiel</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Designation</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Quantite</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Unite</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Prix Unitaire</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">Tax</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">HT</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #4CAF50; color: white;">TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes['data'] as $proforma) { ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $proforma->fournisseur; ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $proforma->materiel; ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $proforma->designation; ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?php echo number_format($proforma->quantite); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $proforma->unite; ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?php echo number_format($proforma->prix_uniatire); ?></td>      
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?php echo number_format($proforma->value_tax); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?php echo number_format($proforma->ht); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?php echo number_format($proforma->ttc); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div style="margin-top: 20px; width: 20%;">
                <table>
                    <tr>
                        <th colspan="2" style="background-color: #4CAF50; color: white;">TOTAL :</th>
                    </tr>
                    <tr>
                        <th>Ht</th>
                        <td style="text-align: right;"><?php echo number_format($commandes['ht']); ?></td>
                    </tr>
                    <tr>
                        <th>TTC</th>
                        <td style="text-align: right;"><?php echo number_format($commandes['ttc']); ?></td>
                    </tr>
                    <tr>
                        <th>Unite</th>
                        <td>Ariary</td>
                    </tr>
                </table>
                
                
            </div>
        </div>
    </div>
</section>

<button id="exportBtn">Export PDF</button>
<script src=<?php echo site_url("assets/pdf/html2pdf.bundle.js"); ?>></script>
<script>
    function exportToPdf() {
        var element = document.getElementById('commande');
        html2pdf().from(element).set({
            margin: 0,
            filename: 'exported-document.pdf',
            html2canvas: { scale: 2 },
            jsPDF: {
                orientation: 'portrait',
                unit: 'in',
                format: 'letter',
                compressPDF: true
            }
        }).save();
        /*var options = {
            margin: 0,
            filename: 'exported-document.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait',
                compressPDF: true
            }
        };
        html2pdf().from(element).set(options).save();
*/
    }


    document.getElementById('exportBtn').addEventListener('click', exportToPdf);
</script>

<style>
    /* Styles pour la section de commande */
    .content {
        font-family: Arial, sans-serif;
    }

    /* Styles pour les cartes (cards) */
    .card {
        background-color: #fff;
    }

    .card-header {
        background-color: #f2f2f2;
        padding: 0px;
    }

    .card-body {
        padding: 0px;
    }

    /* Styles pour la table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        color: #333;
    }
</style>
</body>
</html>
