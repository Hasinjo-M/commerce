select sum(prix_unitaire) as somme , sum(reste) as reste from entrant where produit_id = 2 and  date_entrant >= '2023-12-01' and date_entrant <= '2023-12-14'