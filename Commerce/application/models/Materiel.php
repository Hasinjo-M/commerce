<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Materiel extends CI_Model{
    
    public function liste_materiel(){
        $this->db->select('*');
        $this->db->from('categorie_materiel');
        $query = $this->db->get();
        return $query->result();
    }

    public function liste_produits($id_categorie){
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->where('categorie_id', $id_categorie);
        $query = $this->db->get();
        return $query->result();
    }

    public function detail_produit($produit_id){
        $this->db->select('*');
        $this->db->from('v_produit');
        $this->db->where('id_produit', $produit_id);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0];
    }

    

}