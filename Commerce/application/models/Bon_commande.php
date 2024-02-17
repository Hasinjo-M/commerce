<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bon_commande extends CI_Model{

    public function insert_mere($data){
        $this->db->insert('bon_commande_mere', $data);
    }

    public function insert_bon_commande_fils($data){
        $this->db->insert('bon_commande', $data);
    }

    public function check_bon_commande_mere($group_id, $fournisseur_id){
        $this->db->select('*');
        $this->db->from('bon_commande_mere');
        $this->db->where('group_id', $group_id);
        $this->db->where('fournisseur_id', $fournisseur_id);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        //var_dump($tab);
        return $tab;
    }

    public function insert_bon_commande($data){
        $rep_check =  $this->check_bon_commande_mere($data['group_id'], $data['fournisseur_id']);
        if( $rep_check == null ){
            $data_mere = array(
                'fournisseur_id' => $data['fournisseur_id'],
                'group_id' => $data['group_id'],
                'etat' => 10
            );
            $this->insert_mere($data_mere);
            $rep_check =  $this->check_bon_commande_mere($data['group_id'], $data['fournisseur_id']);
        }

        $data_fils = array(
            'bon_commande_mere_id' => $rep_check[0]->id_bon_commande_mere,
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'prix_uniatire' => $data['prix_uniatire'],
            'tax' => $data['tax'],
            'proforma_mere' => $data['proforma_mere']
        );
        $this->insert_bon_commande_fils($data_fils);
    }

    /**** 02/12/2023 ****/
    public function detail_bon_commande_fils($group_id, $produit_id){
        $this->db->select('*');
        $this->db->from('v_bon_commande');
        $this->db->where('group_id', $group_id);
        $this->db->where('produit_id', $produit_id);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        return $query->result();
    }

    public function  insert($proforma_id, $group_id, $produit_id){
        $this->load->model('Proforma');
        $q_proforma_id =  $this->Proforma->detail_proforma($proforma_id);
        $q_initial = $this->Proforma->details_groupe_produit($group_id, $produit_id);
        $quantite_req =  $q_initial->q;
        $list_bon_commande = $this->detail_bon_commande_fils($group_id, $produit_id);
        $quatite = 0;
        if($list_bon_commande != null){
            foreach ($list_bon_commande as $key => $value) {
                $quatite += $value->quantite;
            }
        }

        $quatite2 = $quantite_req - $quatite;
       // var_dump($quatite);
        if($quatite2 != 0 ){
            $quatite = $quatite2 - $q_proforma_id->quantite;
            
            if($quatite <= 0){
                $quatite = $q_proforma_id->quantite + $quatite;
                
            }else{
                $quatite = $quatite2 - $quatite;
                $d = array(
                    ' $quatite ttt ' => $quatite
                );
              //  var_dump($d);
                
            }
            $data = array(
                'fournisseur_id' => $q_proforma_id->fournisseur_id,
                'group_id' => $group_id,
                'produit_id' => $produit_id,
                'quantite' => $quatite,
                'prix_uniatire' => $q_proforma_id->prix_uniatire,
                'tax' => $q_proforma_id->taux_tax,
                'proforma_mere' =>$proforma_id
            );
            //var_dump($data);
            $data2 = array(
                'proforma_id' => $proforma_id
            );
            $this->Proforma->insert_proforma_group($data2);
            $this->insert_bon_commande($data);
        }  
    }
    /***** Fonction annulation bon commande  *****/
    public function annulation_bon_commande($bon_commande_mere_id){
        $data = array(
            'etat' => 0
        );
        $this->db->where('id_bon_commande_mere', $bon_commande_mere_id);
        $this->db->update('bon_commande_mere', $data);
    }

    /*** Liste bon_commande par fournisseur valider  ***/
    public function list_bon_commande($fournisseur_id){
        $this->db->select('*');
        $this->db->from('bon_commande_mere');
        $this->db->where('fournisseur_id', $fournisseur_id);
        $this->db->where('etat >=', 10);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab;
    }  

   
} 
 