<?php 
    $this->load->view('css');
?>
<div class="content" style="width: 30% ; margin-left: 35%; margin-top: 15%;">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Founisseur</h3>
            </div>
            <form action="" method="post" id="login" >
                <div class="card-body">
                    <div class="form-group">
                        <label>email</label>    
                        <input class="form-control" type="email" name="email" id="email" value="hasinjo@gmail.com">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="mode_passe" id="mdp" value="hasinjo" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="button" id="btnLogin">Connexion</button>
                    </div>
                </div>
            </form>
        </div>
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
            url: "<?php echo site_url("Login/check_login_fournisseur"); ?>",
            data: {
                email: email,
                mdp: mdp
            },
            dataType: "json",
            success: function(response) {
                $("#loader").hide()
                if (response.success) {
                    console.log(response.data);
                    window.location.href = "<?php echo site_url("Fournisseur/index"); ?>";
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