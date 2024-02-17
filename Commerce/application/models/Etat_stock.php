<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Etat_stock extends CI_Model{
    
    public function rest_stock($produit_id, $quantiet_sortie, $date){
        $this->db->select('*');
        $this->db->from('v_etat_stock');
        $this->db->where('produit_id', $produit_id);
        $this->db->where('date_etat <=', $date);
        $this->db->order_by('id_mouvement', 'DESC'); 
        $query = $this->db->get();
        $tab = $query->result();
        if($tab == null){
            throw new Exception('Vous n\'avez pas de stock pour ce produit '); 
        }
        $reste = $tab[0]->quantite;
        if( ($reste - $quantiet_sortie) < 0 ) {
            throw new Exception('Le stock du produit '. $tab[0]->libelle .' est insuffisant il vous reste '.$reste);
        }
    }

    public function etat_stock($debut, $fin){
        $query = " select  m.*, ma.magasin, p.id_produit, p.libelle, p.types, p.unite, p.materiel from mouvement m 
                join magasin ma on ma.id_magasin = m.magasin_id 
                join  v_produit p on p.id_produit = m.produit_id  
                where (m.produit_id, m.id_mouvement) IN 
                (SELECT produit_id, MAX(id_mouvement) AS 
                max_id_mouvement FROM mouvement WHERE 
                date_etat BETWEEN '".$debut."' AND '".$fin."' GROUP BY produit_id)";
        $data =    $this->db->query($query)->result();
        $this->load->model('Materiel');
        $this->load->model('Entrer'); 
        $rep = array();
        /*foreach ($data as $key => $value) {
            $produit = $this->Materiel->detail_produit($value->id_produit);
            $prix_unitaire =  $value->montant;
            $prix = $this->Entrer->list_entrant2($value->id_produit, $produit->typesortie_id, $debut, $fin);
            $value->montant = $prix; 
            
        } */

        if($data == null){
            $query = " select  m.*, ma.magasin, p.id_produit, p.libelle, p.types, p.unite, p.materiel from mouvement m 
                join magasin ma on ma.id_magasin = m.magasin_id 
                join  v_produit p on p.id_produit = m.produit_id  
                where (m.produit_id, m.id_mouvement) IN 
                (SELECT produit_id, MAX(id_mouvement) AS 
                max_id_mouvement FROM mouvement WHERE 
                date_etat <=  '".$debut."'  GROUP BY produit_id)";
            $data =    $this->db->query($query)->result();
        }
        return $data;
    }

            
    
    
    
}

    