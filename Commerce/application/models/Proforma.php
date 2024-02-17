<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proforma extends CI_Model{
    
    public function insert_proforma($data){
        $this->db->insert('proforma', $data);
    }

    public function list_proforma_admin($numero_group, $profoma_group){
        $query = "select *, ((prix_uniatire*tax*quantite)/100) as value_tax  from v_proforma where groupe_numero = ".$numero_group." order by quantite, ttc, fournisseur_id";
        $tab = $this->db->query($query)->result();
        
        return $tab;
    }


    public function list_proforma($numero_group){
        $query = "select *, ((prix_uniatire*tax*quantite)/100) as value_tax  from v_proforma where groupe_numero = ".$numero_group." order by date_proformat, fournisseur_id, quantite, ttc";
        $tab = $this->db->query($query)->result();
        return $tab;
    }

    public function list_proforma_group($numero_group){
        $query = "select *,((prix_uniatire*tax*quantite)/100) as value_tax from v_proforma_valider where group_id = ".$numero_group." and etat >= 10  order by  fournisseur_id, produit_id, quantite";
        $tab = $this->db->query($query)->result();
        
        return $tab;
    }

    public function details_groupe($numero_group){
        $query = "select numero_groupe,materiel, produit, produit_id,sum(quantite) as q,produit_id from v_demande where numero_groupe = ".$numero_group. "  group by numero_groupe,materiel, produit,produit_id";
        return $this->db->query($query)->result();
    }

    public function insert_proforma_group($data){
        $this->db->insert('proforma_group', $data);
    }

    public function list_commande($numero_group, $fournisseur_id){
        $tab =  $this->list_proforma_group($numero_group);
        $val = [];
        $ht = 0;
        $ttc = 0;
        foreach ($tab as $key => $value) {
            if($value->fournisseur_id == $fournisseur_id){
                $val[] = $value;
                $ht += $value->ht;
                $ttc += $value->ttc;
            }
                 
        }
        $data = array(
			'data' => $val,
			'ht' => $ht,
			'ttc' => $ttc
		);
        return $data;
    }

    /******** 02/12/2023 ****/
    public function detail_proforma($proform_id){
        $this->db->select('*');
        $this->db->from('v_proforma');
        $this->db->where('id_proforma', $proform_id);
        $query = $this->db->get();
        $tab = $query->result();
        return $tab[0];
    }

    public function details_groupe_produit($numero_group, $produi_id){
        $query = "select numero_groupe,materiel, produit, produit_id,sum(quantite) as q,produit_id from v_demande where numero_groupe = ".$numero_group. "  and produit_id =  ". $produi_id ." group by numero_groupe,materiel, produit,produit_id";
        $tab = $this->db->query($query)->result();
        return $tab[0];
    }

    public function insert_proforma_modif($modif){
        $this->db->insert('proforma_modif', $modif);
    }

    
}