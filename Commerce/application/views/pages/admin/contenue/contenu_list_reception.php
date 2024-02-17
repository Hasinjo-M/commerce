<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-11">
                    <h2 class="card-title"></h2>
                </div>
            </div>
        </div>

        <div class="card-body p-2">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <td>Date de reception</td>
                    <td>Numero</td>
                    <td>Responsable</td>
                </thead>
                <tbody>
                    <?php foreach ($listes as $key => $value) { ?>
                        <tr>
                            <td><?php echo  $value->date_reception; ?></td>
                            <td><?php echo  $value->numero_reception;?></td>
                            <td><?php echo  $value->responsable; ?></td>    
                            <td><a href="<?php echo site_url("PdfBon_reception/generatePdf")."/".$value->numero_reception; ?>" class="btn btn-success">expoter</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br>
        </div>
    </div>    
</section>


<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>
