<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utilisateur extends CI_Model{
    
    public function test_login($email, $mdp){
        $this->db->select('*');
        $this->db->from('utilisateur');
        $this->db->where('email', $email);
        $this->db->where('mdp', $mdp);
        $query = $this->db->get();
        return $query->result();
    }
}