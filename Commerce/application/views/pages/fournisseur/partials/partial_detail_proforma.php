<div class="card card-primary">
    <div class="card-body">
        <form action="" method="post" id="proforma">
            <div class="row" style="border: 1px solid">
                <label for="materiel" class="col-md-4"><h4 style="width:50%;">Materiel :</h4> <?php echo $detailsproduit->materiel; ?></label>
                <label for="materiel" class="col-md-4"><h4>Produit :</h4> <?php echo $detailsproduit->libelle; ?></label>
                <label for="materiel" class="col-md-4"><h4>Unite :</h4> <?php echo $detailsproduit->unite; ?></label>
            </div>
            <br>

            <input type="hidden" name="id_produit" id="id_produit" value="<?php echo $detailsproduit->id_produit; ?>">
            <div class="form-group">
                <label for="prix_unitaire">Prix unitaire</label>
                <input type="number" class="form-control" name="prix_unitaire" id="prix_unitaire">
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="tax">Tax</label>
                    <select name="tax" class="form-control" id="tax">
                        <option value="0">Non</option>
                        <option value="1">Oui</option>
                    </select>
                </div>   
                <div class="form-group col-md-6">
                    <label for="taux_tax">Taux tax</label>
                    <input type="number" class="form-control" name="taux_tax" id="taux_tax" value="20">
                </div>
            </div>

            <div class="form-group">
                <label for="taux_tax">Taux tax</label>
                <input type="number" class="form-control" name="taux_tax" id="taux_tax" value="20">
            </div>

            <div class="form-group">
                <label for="remise">Remise</label>
                <input type="number" class="form-control" name="remise" id="remise" value="0">
            </div>
            
            <div class="form-group">
                <label for="quantite">Quantite</label>
                <input type="number" class="form-control" name="quantite" id="quantite">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
            </div>
        </form>
    </div>
</div>