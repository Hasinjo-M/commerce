<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reception extends CI_Model{
    public function insert_mere($data){
        $this->db->insert('bon_reception_mere', $data);
    }

    public function insert_bon_commande_fils($data){
        $this->db->insert('bon_reception', $data);
    }

    public function checkbon_reception($livraison_numero){
        $this->db->select('*');
        $this->db->from('bon_reception_mere');
        $this->db->where('livraison_numero', $livraison_numero);
        $this->db->where('etat <', 10);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab != null)
            return $tab[0];
        return null;
    }

    public function validation_reception($date_reception, $description, $responsable, $livraison_numero){
       try {
            $data = array(
                'etat' => 10,
                'responsable' => $responsable,
                'date_reception' => $date_reception,
                'descriptions' => $description
            );
            $this->db->where('livraison_numero', $livraison_numero);
            $this->db->update('bon_reception_mere', $data);
       } catch (\Throwable $th) {
            //throw $th;
       }
    }

    public function reception_produit($data){
        $mere = $this->checkbon_reception($data['numero_livraison'
    ]);
        if($mere == null){
            $data_mere = array(
                'responsable' => "reception", 
                'date_reception' => null, 
                'livraison_numero' => $data['numero_livraison'], 
                'etat' => 0, 
                'descriptions' => null
            );
            $this->insert_mere($data_mere);
        }
        $mere = $this->checkbon_reception($data['numero_livraison']);
        $data_fils = array(
            'bon_reception_mere_id' => $mere->id_bon_reception_mere,
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'prix_uniatire' => $data['prix_uniatire'],
            'tax' => $data['tax']
        );
        $this->insert_bon_commande_fils($data_fils);
    }

    /***** Liste de bon reception valider  ****/

    public function list_bon_reception(){
        $this->db->select('*');
        $this->db->from('bon_reception_mere');
        $this->db->where('etat < ', 20);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }

    public function detail_bon_reception($numero_reception){
        $this->db->select('*');
        $this->db->from('v_bon_reception');
        $this->db->where('numero_reception', $numero_reception);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }

    public function list_bon_reception_valider(){
        $this->db->select('*');
        $this->db->from('bon_reception_mere');
        $this->db->where('etat < ', 20);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }

    
}