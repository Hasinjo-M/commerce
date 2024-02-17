<table class="table table-striped table-bordered projects">
    <thead>
        <td>Date</td>
        <td>Departement</td>
        <td>Materiel</td>
        <td>Designation</td>
        <td>Unite</td>
        <td>Justification</td>
        <td>Quantite</td>
        <td>Dégroupé</td>
    </thead>
    <tbody>
        <?php foreach ($detailsGroupe as $detail) { ?>
            <tr>
                <td><?php echo $detail->date_demande; ?></td>
                <td><?php echo $detail->departement; ?></td>
                <td><?php echo $detail->materiel; ?></td>
                <td><?php echo $detail->produit; ?></td>
                <td><?php echo $detail->unite; ?></td>
                <td><?php echo $detail->justificatif; ?></td>
                <td><?php echo $detail->quantite; ?></td>
                <td><button class="btn btn-danger delete-button" id="delete-button" data-numero-groupe="<?php echo $detail->numero_groupe; ?>"  data-id-demande="<?php echo $detail->id_demande; ?>">X</button></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>

<script>
    /****** Degroupement  *****/
    $('.delete-button').on('click', function(e) {
           // e.preventDefault()
            var id_demande = $(this).data('id-demande');
            var numero_groupe = $(this).data('numero-groupe');
            console.log(id_demande +"  "+numero_groupe);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('Admin/de_groupement'); ?>",
                data: {
                    id_demande: id_demande,
                    numero_groupe: numero_groupe
                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    if(responseData.success == false){
                        console.log(responseData);
                    }else{
                        $("#detail-groupe-container").html(responseData.data);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
    });
</script>