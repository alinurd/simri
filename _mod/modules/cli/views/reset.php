<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>Simontana</title>
    </head>
    <body>
        <?php echo form_open("cli/save-forgot-password", ['class' => 'login-form', 'id' => 'form_login'], ['id'=>$id]);?>
        <div class="container">
            <div class="row text-center align-items-center" style="height:200px;margin-top:100px;">
                <div class="col col-lg-2">&nbsp;</div>
                <div class="col-md-8">
                <?php
                if ($sts==0){
                    echo $pesan;
                }else{
                    echo $pesan;
                    echo '<br/><table class="table">';
                    echo '<tr><td width="30%">Password Baru : </td><td>'.$pass.'</td></tr>';
                    echo '<tr><td>Konfirmasi Password: </td><td>'.$pass2.'</td></tr></table><br/>';
                    echo $tombol;
                }
                ?>
                </div>
                <div class="col col-lg-2">&nbsp;</div>
            </div>
        </div>
        <?php echo form_close();?>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script>
        $(function () {
            $("#pass, #pass2").keyup(function(){
                var pass=$("#pass").val();
                var pass2=$("#pass2").val();
                console.log(pass);
                console.log(pass2);
                if (pass==pass2 && pass.length>=6){
                    $("#button_submit").removeAttr("disabled");
                }else{
                    $("#button_submit").removeAttr("disabled");
                    $("#button_submit").attr("disabled", "disabled");
                }
            })
        })
        </script>
    </body>
</html>