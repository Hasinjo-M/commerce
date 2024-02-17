
<?php 
$this->load->view('css');
 ?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Login Service</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form action=""  method="post" id="login">
        <div class="input-group mb-3">
          <input type="email" class="form-control" type="email" name="email" id="email" value="toavinahasnii02@gmail.com">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="mode_passe" id="mdp" value="toavina">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          
          <!-- /.col -->
          <div class="col-4">
            <button type="button" id="btnLogin" class="btn btn-primary btn-block">Connexion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

<div id="loader" style="display: none;">Chargement en cours...</div>

<script src=<?php echo site_url("assets/plugins/jquery/jquery.min.js"); ?>></script>

<script>
$(document).ready(function() {
    $("#btnLogin").click(function() {
        $("#loader").show();
        var email = $("#email").val();
        var mdp = $("#mdp").val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("Login/check_login_service"); ?>",
            data: {
                email: email,
                mdp: mdp
            },
            dataType: "json",
            success: function(response) {
                $("#loader").hide()
                if (response.success) {
                    console.log(response.data);
                    window.location.href = "<?php echo site_url("Admin/index"); ?>";
                }else{
                    console.log(response.message);
                    $("#mdp").html("");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText+"  "+error);
                $("#loader").hide();
            }
        }); 
    });
});
</script>

<?php  
    $this->load->view('script');
?>