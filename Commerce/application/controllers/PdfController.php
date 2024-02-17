<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends CI_Controller {

    public function generatePdf($fournisseur_id, $numero_livraison) {
        $this->load->model('Livraison');
        $this->load->model('Entreprise');
        $entreprise =  $this->Entreprise->entreprise(); 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $data = $this->Livraison->detail_livraison($numero_livraison, $fournisseur_id);
        $html = $this->generateHtml($data, $entreprise);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $dompdf->stream('livraison.pdf', array('Attachment' => 0));
    }



    private function generateHtml($data, $entreprise)
    {
        // Construire le HTML du bon de livraison
        $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Bon de Livraison</title>
                    <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
}

.header h1 {
    text-align: center;
}

.details, .items, .total, .payment, .footer {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 8px;
    text-align: left;
}

.total {
    text-align: right;
}

.footer {
    margin-top: 40px;
}

</style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>Bon de Livraison</h1>
                        </div>
                        <div class="details">
                            <p><strong>Numéro de livraison: </strong>'.  $data['numero_livraison'] .'</p>
                            <p><strong>Date de livraison:</strong> ' .  $data['date_livraison'] .'</p>
                            <p><strong>Lieu de livraison:</strong> ' .  $data['lieu'] .'</p>
                            <p><strong>Destinataire:</strong></p>
                            <p>Nom de l\'entreprise/Client: ' .$entreprise->nom. '</p>
                            <p>Adresse: ' .$entreprise->adress. '</p>
                            <p><strong>Fournisseur:</strong></p>
                            <p>Nom du fournissseur: '.  $data['numero_livraison'] .'</p>
                            <p>Adresse: '.  $data['adress'] .'</p>
                            <p>Phone: '.  $data['phone'] .'</p>
                            <p>email: '.  $data['email'] .'</p>
                        </div>
                        <div class="items">
                            <h2>Détails de la Livraison</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Materiel</th>
                                        <th>Designation</th>
                                        <th>Quantité</th>
                                        <th>Unité</th>
                                    </tr>
                                </thead>
                                <tbody> ';
                         $tab = "";

                                foreach ($data['data'] as $key => $detail) { 
                                    
                                    $tab = $tab . '<tr class="detail-row">' .
                                        '<td>'.$detail->materiel.'</td>' .
                                        '<td>'.$detail->designation.'</td>
                                        <td >'.$detail->quantite.'</td>
                                        <td >'.$detail->unite.'</td>
                                    </tr> ';
                                }
                            
                            $rep =  '</tbody>
                            </table>
                        </div>
                        <div class="total">
                          
                        </div>
                        <div class="payment">
    <p><strong>Conditions de paiement:</strong> Paiement à la livraison en espèces ou par carte de crédit.</p>
</div>

                        <div class="footer">
                            <p>Nom et signature du livreur: ________________________</p>
                        </div>
                    </div>
                </body>
                </html>';

        return $html .$tab .$rep;
    }



}
