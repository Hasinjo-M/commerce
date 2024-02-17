
<div class="row">

<?php foreach ($detailsGroupe as $detail) {
    $departements = $detail['dept'];
    $totale = 0;
    foreach ($departements as $dept){
        $totale = $totale + $dept->quantite;
    }
    ?>
    <div class="card col-md-6">
        <div class="card-body">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <tr>
                        <th>Departement</th>
                        <th>Designation: <?php echo $detail['produit']; ?> </th>
                        <th> Total : <i><?php echo $totale; ?></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departements as $dept) { ?>
                        <tr>
                            <td><?php echo $dept->departement; ?></td>
                            <td><?php echo $dept->quantite." ".$dept->unite; ?></td>
                            <td><button class="btn btn-danger delete-button" id="delete-button" data-numero-groupe="<?php echo $dept->numero_groupe; ?>"  data-id-demande="<?php echo $dept->id_demande; ?>">X</button></td>
                        </tr> 
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
</div>


<script>
    /****** Degroupement  *****/
    $('.delete-button').on('click', function() {
            e.preventDefault()
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
                        //$("#loading-indicator").removeClass("show");

                        window.location.href = "<?php echo site_url("Admin/demande_groupe"); ?>";
                        //$("#detail-groupe-container").html(responseData.data);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
    });
</script>
