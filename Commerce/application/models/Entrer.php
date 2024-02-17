<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entrer extends CI_Model{
    public function insert_mere($data){
        $this->db->insert('bon_entrer_mere', $data);
    }

    public function insert_bon_commande_fils($data){
        $this->db->insert('bon_entrer', $data);
    }

    public function check_bon_entrer($reception_numero){
        $this->db->select('*');
        $this->db->from('bon_entrer_mere');
        $this->db->where('reception_numero', $reception_numero);
        $this->db->where('etat <', 10);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab != null)
            return $tab[0];
        return null;
    }

    public function list_bon_entrer($reception_numero){
        $this->db->select('*');
        $this->db->from('v_bon_entrer');
        $this->db->where('reception_numero', $reception_numero);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }

    public function validation_bon_entrer($responsable, $date, $reception_numero){
        try {
            $data = array(
                'etat' => 10, 
                'responsable' => $responsable, 
                'date_entrer' => $date
            );
            $this->db->where('reception_numero', $reception_numero);
            $this->db->update('bon_entrer_mere', $data);
             

            $data_entrer = $this->list_bon_entrer($reception_numero);
            for ($i=0; $i < count($data_entrer); $i++) { 
                $data1 = array(
                    'date_entrant' => $data_entrer[$i]->date_entrer,
                    'produit_id' => $data_entrer[$i]->produit_id,
                    'unite_id' => $data_entrer[$i]->unite_id,
                    'quantite' => $data_entrer[$i]->quantite,
                    'reste' => $data_entrer[$i]->quantite,
                    'prix_unitaire' => $data_entrer[$i]->prix_uniatire,
                    'magasin_id' => 1
                );
                
                $this->insert_entrant($data1);
            }  
           
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function ajoute_entrer($data){
        $mere = $this->check_bon_entrer($data['numero_reception']);
        if($mere == null){
            $data_mere = array(
                'reception_numero' => $data['numero_reception'], 
                'responsable' => null, 
                'date_entrer' => null, 
                'etat' => 0
            );
            $this->insert_mere($data_mere);
        }
        $mere = $this->check_bon_entrer($data['numero_reception']);
        $data_fils = array(
            'produit_id' => $data['produit_id'],
            'bon_entrer_mere_id' => $mere->id_bon_entrer_mere,
            'quantite' => $data['quantite'],
            'prix_uniatire' => $data['prix_uniatire'],
            'tax' => $data['tax']
        );
        $this->insert_bon_commande_fils($data_fils);
    }


    /********** entrant  *********/
    public function insert_entrant($data){
        $this->db->insert('entrant', $data);
    }

    public function list_entrant($produit_id, $type_sortie){
        if($type_sortie == 2){
            $this->db->select('*');
            $this->db->from('entrant');
            $this->db->where('produit_id', $produit_id);
            $this->db->where('reste !=', 0);   
            $this->db->order_by('id_entrant', 'DESC'); 
            $query = $this->db->get();
            return $query->result();  
        }
        $this->db->select('*');
        $this->db->from('entrant');
        $this->db->where('produit_id', $produit_id);
        $this->db->where('reste !=', 0);  
        $query = $this->db->get();
        return $query->result();  
     
    }

    public function test2($produit_id ,$debut, $fin){
       
        
        $query = "SELECT SUM(prix_unitaire) as somme, SUM(reste) as reste FROM entrant WHERE produit_id = ".$produit_id." AND date_entrant >= '".$debut."' AND date_entrant <= '".$fin."'";
        $tab = $this->db->query($query)->result();

        if ($tab[0]->reste != 0) {
            $prix = $tab[0]->somme / $tab[0]->reste;
            return $prix;
        }
        return 0;
    } 

    public function list_entrant2($produit_id, $type_sortie, $debut, $fin){
        $this->db->select('*');
        $this->db->from('entrant');
        $this->db->where('produit_id', $produit_id);
        $this->db->where('date_entrant >=', $debut);
        $this->db->where('date_entrant <=', $fin);
        $this->db->where('reste !=', 0);
        if($type_sortie == 2){
            $this->db->order_by('id_entrant', 'DESC');
        }else if($type_sortie == 3){
            return $this->test2($produit_id, $debut, $fin);
        }
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0]->prix_unitaire;  
    }

    public function liste_entrant_FIFO($produit_id){
        return $this->list_entrant($produit_id, 1);
    }
    public function liste_entrant_LIFO($produit_id){
        return $this->list_entrant($produit_id, 2);
    }
    public function liste_entrant_QMP($produit_id){
        return $this->list_entrant($produit_id, 3);
    }

    public function update_entrant($data, $id_entrant){
        $this->db->where('id_entrant', $id_entrant);
        $this->db->update('entrant', $data);
    }

    public function listes_bon_entrer(){
        $this->db->select('*');
        $this->db->from('bon_entrer_mere');
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        return $query->result();
    }

    public function detail_bon_entrer($numero_entrer){
        $this->db->select('*');
        $this->db->from('v_bon_entrer');
        $this->db->where('numero_entrer', $numero_entrer);
        $query = $this->db->get();
        return $query->result();
    }
}