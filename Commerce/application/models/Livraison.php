<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Livraison extends CI_Model{
    public function insert_mere($data){
        $this->db->insert('bon_livraison_mere', $data);
    }

    public function insert_bon_commande_fils($data){
        $this->db->insert('bon_livraison', $data);
    }

    public function checkBon_livraison($numero_commande, $fournisseur_id){
        $this->db->select('*');
        $this->db->from('bon_livraison_mere');
        $this->db->where('numero_commande', $numero_commande);
        $this->db->where('fournisseur_id', $fournisseur_id);
        $this->db->where('etat <', 10);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab != null)
            return $tab[0];
        return null;
    }

    /**** Validation livraison *****/
    public function validation_livraison_mere($numero_livraison, $lieu, $date){
        $data = array(
            'etat' => 10,
            'lieu' => $lieu, 
            'date_livraison' => $date 
        );
        $this->db->where('numero_livraison', $numero_livraison);
        $this->db->update('bon_livraison_mere', $data);
    }

    public function  validation_livraison($group_id, $fournisseur_id, $lieu, $date){
        $this->db->select('*');
        $this->db->from('bon_commande_mere');
        $this->db->where('group_id', $group_id);
        $this->db->where('fournisseur_id', $fournisseur_id);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab != null){
            $val = array();
            foreach ($tab as $key => $value) {
                $val = $this->checkBon_livraison($tab[0]->numero_commande, $fournisseur_id); 
                if($val != null) {break;}
            }
            
            if($val != null){
                $this->validation_livraison_mere($val->numero_livraison, $lieu, $date); 
            }
        }   
    }

    /**** Ajout livraison  ****/
    public function livraison($data){
        $mere = $this->checkBon_livraison($data['numero_commande'], $data['fournisseur_id']);
        if($mere == null){
            $data_mere = array(
                'fournisseur_id' => $data['fournisseur_id'],
                'numero_commande' => $data['numero_commande'],
                'date_livraison' =>null,
                'lieu' => "Lieux",
                'etat' => 0
            );
            $this->insert_mere($data_mere);
        }
        $mere = $this->checkBon_livraison($data['numero_commande'], $data['fournisseur_id']);
        $data_fils = array(
            'bon_livraison_mere_id' => $mere->id_bon_livraison_mere,
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'prix_uniatire' => $data['prix_uniatire'],
            'tax' => $data['tax'] 
        );
        $this->insert_bon_commande_fils($data_fils);
    } 


    /***** Liste de bon livraison valider  */
    public function list_bon_livraison(){
        $this->db->select('*');
        $this->db->from('v_bon_livraison_mere');
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }

    /****** Detail d'un livraison ***/
    public function detail_livraison($numero_livraison, $fournisseur_id){
        $this->db->select('*');
        $this->db->from('v_bon_livraison');
        $this->db->where('fournisseur_id', $fournisseur_id);
        $this->db->where('numero_livraison', $numero_livraison);
        $query = $this->db->get();
        $tab = $query->result();
        $data = array(
            'fournisseur_id' => $tab[0]->fournisseur_id,
            'bon_livraison_mere_id' => $tab[0]->bon_livraison_mere_id,
            'fournisseur' => $tab[0]->libelle,
            'adress' => $tab[0]->adress,
            'phone' => $tab[0]->phone,
            'email' => $tab[0]->email,
            'date_livraison' => $tab[0]->date_livraison,
            'lieu' => $tab[0]->lieu,
            'numero_livraison' => $tab[0]->numero_livraison,
            'data' => $tab          
        );
        return $data;
    }

    


}