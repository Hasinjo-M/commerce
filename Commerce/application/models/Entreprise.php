<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entreprise extends CI_Model{
    
    public function entreprise(){
        $this->db->select('*');
        $this->db->from('entreprise');
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0];
    }

   

}