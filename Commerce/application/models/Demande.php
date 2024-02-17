<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demande extends CI_Model{
    
    public function insert($data){
        $this->db->insert('demande', $data);
    }

    public function demande_non_groupe(){
        $this->db->select('*');
        $this->db->from('v_demande');
        $this->db->where('numero_groupe', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function demande_groupe(){
        $query = "select numero_groupe,materiel from v_demande where numero_groupe != 0 group by numero_groupe,materiel order by  numero_groupe desc ";
        return $this->db->query($query)->result();
    }

  

    public function getNumerogroup(){
        $this->db->select("nextval('numero_groupe')");
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0]->nextval ;
    }

    
    public function update_numero_group($demandes){
        try {
            $numero = $this->getNumerogroup();
            $data = array(
                'numero_groupe' => $numero
            );
            foreach ($demandes as $key => $value) {
                $this->db->where('id_demande', $value);
                $this->db->update('demande', $data);
            }
        } catch (\Throwable $th) {
            throw $th;
        }  
    }

    public function details_groupe($numero_group){
        $this->db->select('*');
        $this->db->from('v_demande');
        $this->db->where('numero_groupe', $numero_group);
        $query = $this->db->get();
        return $query->result();
    }

    public function details_groupe_email($numero_group){
        $query = "select numero_groupe,materiel, produit, sum(quantite),produit_id from v_demande where numero_groupe = ".$numero_group. "  group by numero_groupe,materiel, produit,produit_id";
        return $this->db->query($query)->result();
    }

    public function nombre_emaile($numero_group){
        
    }
    
    public function de_groupement($demande_id) {
        try {
            $this->db->trans_start();
            $demande_id = (int)$demande_id;
            if ($demande_id <= 0) {
                throw new InvalidArgumentException("Invalid demande_id  ".$demande_id);
            }
            $data = array(
                'numero_groupe' => 0
            );
            $this->db->where('id_demande', $demande_id);
            $this->db->update('demande', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Transaction failed");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function demande_non_valid_par_dept($numero){
        $this->db->select('*');
        $this->db->from('v_demande');
        $this->db->where('numero_groupe', $numero);
        $query = $this->db->get();
        return $query->result();
    }

    public function mise_a_jour_demande($numero ){
        $data = array(
            "numero_groupe" => 0,
        );
        $this->db->where('numero_groupe', $numero);
        $this->db->update('demande', $data);
        return $this->db->affected_rows(); // Renvoie le nombre de lignes affectées par la mise à jour
    }

    public function details_par_produit($idproduit , $numero_group){
        $this->db->select('*');
        $this->db->from('v_demande');
        $this->db->where('numero_groupe', $numero_group);
        $this->db->where('produit_id', $idproduit);
        $query = $this->db->get();
        return $query->result();
    }

    public function details_produit($numero_group){
        $group = $this->details_groupe($numero_group);
        $data = array();
        $produits_traites = array(); // Tableau pour suivre les produits déjà traités
        $id = 0;

        foreach ($group as $g) {
            $produit = $g->produit;

            // Vérifier si le produit a déjà été traité
            if (!in_array($produit, $produits_traites)) {
                $data[$id]['produit'] = $produit;
                $data[$id]['dept'] = $this->details_par_produit($g->produit_id , $numero_group);

                // Ajouter le produit au tableau des produits traités
                $produits_traites[] = $produit;
                $id++;
            }
        }
        return $data;
    }

    
    
}

