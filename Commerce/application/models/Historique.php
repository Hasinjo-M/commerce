<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Historique extends CI_Model{
    public function historique_categorie($categorie_id){

        $query = "select * from v_entrant where categorie_id = ".$categorie_id."  order by   date_entrant, id_produit" ;
        $data =    $this->db->query($query)->result();
        
        return $data;
    }

    public function historique_produit($id_produit){
        $query = "select * from v_entrant where id_produit = ".$id_produit."  order by   date_entrant, id_produit";
        return $this->db->query($query)->result();
    }

    public function historique_sortie($categorie_id, $produit_id){
        $this->load->model('Sortie');
        if($produit_id == "0"){
            return $this->Sortie->sortie_valider_categorie($categorie_id);
        }
        return $this->Sortie->sortie_valider_produit($produit_id);
    }

    public function historique_detail_sortie($sortie_mere_id){
        $query = "select * from v_detail_sortie where sortie_mere_id = ".$sortie_mere_id;
        return $this->db->query($query)->result();
    }

    public function chart($categorie_id){
        $query = "select * from v_chart where categorie_id =  ".$categorie_id;
        $tab = $this->db->query($query)->result();
        $colors = [
            'red',
            'green',
            'blue',
            'orange',
            'purple',
            'yellow',
            'cyan',
            'magenta',
            'brown',
            'pink'
        ];
        $data = array();
        $labels = array(); $donne = array(); $colors_data = array();
        for ($i=0; $i < count($tab); $i++) { 
            $labels[] = $tab[$i]->libelle;
            $colors_data[] = $colors[$i];
            $donne[] = $tab[$i]->quantite;
        }
        $data['labels'] = $labels;
        $data['data'] = $donne;
        $data['colors'] = $colors_data;
        return $data;

    }
}