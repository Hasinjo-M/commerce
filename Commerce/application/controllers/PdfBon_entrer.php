<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfBon_entrer extends CI_Controller {

    public function generatePdf($numero_entrer) {
        $this->load->model('Entrer');
        $this->load->model('Entreprise');
        $entreprise =  $this->Entreprise->entreprise(); 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $data = $this->Entrer->detail_bon_entrer($numero_entrer);
        $html = $this->generateHtml($data, $entreprise);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $dompdf->stream('livraison.pdf', array('Attachment' => 0));
    }



    private function generateHtml($data, $entreprise)
    {
       $html = '
       <!DOCTYPE html>
       <html lang="en">
       <head>
           <meta charset="UTF-8">
           <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <title>Bon d\'Entrée</title>
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
       
               .details, .items, .total, .footer {
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
                   <h1>Bon d\'Entrée</h1>
               </div>
               <div class="details">
                   <p><strong>Numéro d\'Entrée: </strong></p>
                   <p><strong>Date d\'Entrée:</strong></p>
                   <p><strong>Fournisseur:</strong> </p>
                   <!-- Additional details as needed -->
               </div>
               <div class="items">
                   <h2>Détails des Articles</h2>
                   <table>
                       <thead>
                           <tr>
                               <th>Meteriel</th>
                               <th>Designation</th>
                               <th>Quantité</th>
                               <th>Unité</th>
                           </tr>
                       </thead>
                       <tbody> ';
                $tab = "";
                    foreach ($data as $key => $detail) {
                        $tab = $tab . '<tr class="detail-row">' .
                            '<td>'.$detail->materiel.'</td>' .
                            '<td>'.$detail->libelle.'</td>
                            <td >'.$detail->quantite.'</td>
                            <td >'.$detail->unite.'</td>
                        </tr> ';
                    }
                          
                 $rep =' </tbody>
                   </table>
               </div>
               <div class="total">
                   <!-- Total quantity or other relevant totals -->
               </div>
               <div class="footer">
                   <p>Nom et signature du réceptionniste: ________________________</p>
               </div>
           </div>
       </body>
       </html>
       
       ';        
       return $html.$tab.$rep;
    }



}
