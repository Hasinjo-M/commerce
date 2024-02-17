<h3>Liste des demande sur le groupe numero <?php echo $detailsGroupe[0]->numero_groupe; ?></h3>
<table class="table table-striped table-bordered projects">
                  <thead>
                      <td>Materiel</td>
                      <td>Designation</td>
                      <td>Quantite total</td>
                  </thead>
                  <tbody>
                      <?php foreach ($detailsGroupe as $detail) { ?>
                          <tr>
                              <td><?php echo $detail->materiel; ?></td>
                              <td><?php echo $detail->produit; ?></td>
                              <td><?php echo $detail->sum; ?></td>
                          </tr>
                      <?php } ?>
                  </tbody>
</table>
<form action="" method="post" id="form_email">
    <input type="hidden" name="numero_group" value = "<?php echo $detailsGroupe[0]->numero_groupe; ?>">
    <label for="fournisseur">Liste des fournisseur</label>
    <div class="form-group row">
        <?php foreach ($fournisseurs as $fournisseur) { ?>
            <div class="col-md-4">
                <input type="checkbox" name="fournisseurs[]" value="<?php echo $fournisseur->id_fournisseur; ?>"> <?php echo $fournisseur->libelle; ?>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="object">Object</label>
            <input type="text" class="form-control" id="object" name="object">
        </div>
        <div class="form-group col-md-6">
            <label for="description">Description</label> 
            <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
        </div>
    </div>
</form>