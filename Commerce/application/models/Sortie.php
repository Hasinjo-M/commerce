<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sortie extends CI_Model{
    public function insert_sortie_mere($data){
        $this->db->insert('sortie_mere', $data);  
    } 
    public function insert_sortie($data){
        $this->db->insert('sortie', $data); 
    }


    public function sortie($data){
        $this->load->model('Etat_stock');
        try {
            $this->Etat_stock->rest_stock($data['produit_id'], $data['quantite'], $data['date']);
            $data_mere = array(
                'date_sortie' =>$data['date'],
                'produit_id' =>$data['produit_id'],
                'magasin_id' =>1,
                'quantite_total' =>$data['quantite'],
                'status_cloture' =>10,
                'date_validation' =>null,
            );
            $this->insert_sortie_mere($data_mere);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function list_non_valider(){
            $this->db->select('*');
            $this->db->from('v_non_valider');
            $query = $this->db->get();
            return $query->result();
    }

    public function update_sortie_mere($data, $id_sortie_mere){
        $this->db->where('id_sortie_mere', $id_sortie_mere);
        $this->db->update('sortie_mere', $data);
    }

    public function sortie_mere($id_sortie_mere){
        $this->db->select('*');
        $this->db->from('sortie_mere');
        $this->db->where('id_sortie_mere', $id_sortie_mere);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0];
    }

   
    
    public function validation_mere($data, $id_sortie_mere){
        $this->load->model('Materiel');
        $this->load->model('Entrer');
        try {
            $this->update_sortie_mere($data, $id_sortie_mere);
            $mere =  $this->sortie_mere($id_sortie_mere);
            $quantite = $mere->quantite_total;
            $quantite2 = $mere->quantite_total; 
            $produit = $this->Materiel->detail_produit($mere->produit_id);
            $list_entrer = $this->Entrer->list_entrant($produit->id_produit, $produit->typesortie_id);
            var_dump($list_entrer);
            foreach ($list_entrer as $key => $value) {
                $reste = $value->reste; 
                if($quantite == 0){ break; }
                 if($quantite - $value->reste <= 0 ){
                    $data_reste = array(
                        'reste' => $value->reste -$quantite
                    );
                    $data_sortie = array(
                        'date_sortie' => $data['date_validation'],
                        'q_init' => $reste,
                        'q_sortant' => $quantite,
                        'prix_unitaire' => $value->prix_unitaire,
                        'sortie_mere_id' => $mere->id_sortie_mere,
                        'entrant_id' => $value->id_entrant
                    );
                    $this->Entrer->update_entrant($data_reste, $value->id_entrant);
                    $this->insert_sortie($data_sortie);
                    break;
                }else {
                    $data_reste = array(
                        'reste' => 0
                    );

                    
                    $data_sortie = array(
                        'date_sortie' => $data['date_validation'],
                        'q_init' => $value->reste,
                        'q_sortant' => $value->reste,
                        'prix_unitaire' => $value->prix_unitaire,
                        'sortie_mere_id' => $mere->id_sortie_mere,
                        'entrant_id' => $value->id_entrant
                    );
                    $this->Entrer->update_entrant($data_reste, $value->id_entrant);
                    $this->insert_sortie($data_sortie);
                    $quantite -=  $value->reste;
                }
            }
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function sortie_valider_categorie($categorie_id){
        $query = "select * from v_sortie_valider where categorie_id = " .$categorie_id  ." order by date_validation asc";
        $data = $this->db->query($query)->result();

        foreach ($data as $key => $value) {
            $sortie_mere_id = $value->id_sortie_mere;
            $data[$key]->montant = $this->montant_sortie($sortie_mere_id);
        }
        return $data;
    }

    public function montant_sortie($sortie_mere_id){
        $query = "select * from v_prix_sortie where sortie_mere_id = " .$sortie_mere_id ;
        $data = $this->db->query($query)->result();
        return $data[0]->sum;
    }

    public function sortie_valider_produit($produit_id){
        $query = "select * from v_sortie_valider where produit_id = " .$produit_id  ." order by date_validation asc";
        $data = $this->db->query($query)->result();

        foreach ($data as $key => $value) {
            $sortie_mere_id = $value->id_sortie_mere;
            $data[$key]->montant = $this->montant_sortie($sortie_mere_id);
        }
        return $data;
    }





}