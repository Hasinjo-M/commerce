<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FournisseurModel extends CI_Model{
    
    public function liste_fournisseurs(){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_email($numero_group, $object, $description, $fournisseurs){
        foreach ($fournisseurs as $key => $value) {
            $data = array(
                'groupe_numero' => $numero_group,
                'fournisseur_id' => $value,
                'object_email' => $object,
                'descriptions_email' => $description
            );
            $this->db->insert('email', $data);
        }
    }

    public function test_login($email, $mdp){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $this->db->where('email', $email);
        $this->db->where('mdp', $mdp);
        $query = $this->db->get();
        return $query->result();
    }

    public function liste_email($fournisseur_id){
        $query = "select * from email where fournisseur_id = ".$fournisseur_id." order  by date_email asc";
        return $this->db->query($query)->result();
    }

    public function fournisseur($fournisseur_id){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $this->db->where('id_fournisseur', $fournisseur_id);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0];
    }
    

}