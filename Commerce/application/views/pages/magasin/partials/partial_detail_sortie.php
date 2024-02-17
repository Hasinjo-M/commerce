<?php  ?>
<div class="modal-header">
    <h4 class="modal-title">Datail de sortie du produit <?php echo $detailsSortie[0]->libelle;  ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body" id="modal-body-content">
    <div class="card card-primary">
        <div class="card-body p-2">
                <table class="table table-striped table-bordered projects">
                    <thead>
                        <td>Date entrant</td>
                        <td>Date sortie</td>
                        <td>Quantite init</td>
                        <td>Quantite sortant</td>
                        <td>Prix unitaire</td>
                        <td>Montant</td>
                        <td>Unite</td>                        
                    </thead>
                    <tbody>
                        <?php foreach ($detailsSortie as $key => $value) { ?>
                            <tr>
                                <td> <?php echo $value->date_entrant ; ?> </td>
                                <td> <?php echo $value->date_sortie ; ?> </td>
                                <td> <?php echo $value->q_init ; ?> </td>
                                <td> <?php echo $value->q_sortant ; ?> </td>
                                <td> <?php echo $value->prix_unitaire ; ?> </td>
                                <td> <?php echo $value->prix_unitaire * $value->q_sortant ; ?> </td>
                                <td> <?php echo $value->unite ; ?> </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br><br>
        </div>
    </div>         
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
    <button type="button" class="btn btn-primary valider" id="valider" data-dismiss="modal" >Valider</button>
</div>
