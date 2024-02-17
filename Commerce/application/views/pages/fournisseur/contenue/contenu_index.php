<!-- Main content -->
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Boite d'email</h3>

              <div class="card-tools">
                <div class="input-group input-group-sm">
                    <a type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                    <?php foreach ($emails as $key => $value) { ?>
                        <tr>
                            <td class="mailbox-name"><?php echo $entreprise->nom; ?></td>
                            <td class="mailbox-subject">
                                <a href="<?php echo site_url('Fournisseur/detail_email')."/".$value->groupe_numero;?>"><b><?php echo $value->object_email; ?></b></a>
                            </td>
                            <td class="mailbox-attachment">
                                <?php echo $value->descriptions_email; ?>
                            </td>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
</section>