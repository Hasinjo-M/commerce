<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departement extends CI_Model{
    
    public function liste_departement(){
        $this->db->select('*');
        $this->db->from('departement');
        $query = $this->db->get();
        return $query->result();
    }
}