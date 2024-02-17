<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FactureModel extends CI_Model{
    
    public function get_Facture($numero_commade){
        $this->db->select('*');
        $this->db->from('v_facture');
        $this->db->where('numero_commande' , $numero_commade);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab == null){
            return null;
        }
        return $tab;
    }

    public function get_one_facture($numero_commande){
        $this->db->select('*');
        $this->db->from('facture');
        $this->db->where('numero_commande' , $numero_commande);
        $query = $this->db->get();
        $tab = $query->result();
        if($tab == null){
            return false;
        }
        return true;
    }

    public function insert_facture($data){
        
        try {
            $this->db->insert('facture', $data);
            return $this->db->insert_id(); // Retourne l'ID de la ligne insérée
        } catch (Exception $e) {
            log_message('error', 'Erreur lors de l\'insertion dans la table facture : ' . $e->getMessage());
            return false; // Retourne false en cas d'échec de l'insertion
        }
    }

}
?>