<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DepartementModel extends CI_Model{
    
    public function liste_departement(){
        $this->db->select('*');
        $this->db->from('departement');
        $query = $this->db->get();
        return $query->result();
    }

    public function test_login($email, $mdp){
        $this->db->select('*');
        $this->db->from('departement');
        $this->db->where('email', $email);
        $this->db->where('mot_passe', $mdp);
        $query = $this->db->get();
        return $query->result();
    }

    public function one_departement($departement_id){
        $query = "select * from departement where id_departemnt = ".$departement_id;
        return $this->db->query($query)->result();
    }
}