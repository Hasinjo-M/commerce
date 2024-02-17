<?php 
$this->load->view('css');
$this->load->view('navbar');
$this->load->view('sidebar'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">
                <?php echo $title_headers;  ?>
                </h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php $this->load->view($contenu);?>
        </div>
    </section>
</div>
<?php  
    $this->load->view('footer');
    $this->load->view('script');
?>

