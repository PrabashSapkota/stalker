<?php
session_start();
include("_inc.configs.php");
if(!isset($_SESSION['yuvisession'])){  $pageTitle = "Admin Login"; } else { $pageTitle = "Welcome To Admin Panel"; }
$module = ""; if(isset($_GET['module'])){ $module = trim($_GET['module']); }
if($module == "application_logs")
{
    $apps_logs = "";
    if(file_exists($APP_CONFIG['DATA_FOLDER']."/axLogs.enc")) { $apps_logs = @file_get_contents($APP_CONFIG['DATA_FOLDER']."/axLogs.enc"); }
?>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Application Logs | Admin Panel - <?php print($APP_CONFIG['APP_NAME']); ?></title>
<link rel="icon" href="<?php print($APP_CONFIG['APP_FAVICON']); ?>"/>
<link rel="shortcut icon" href="<?php print($APP_CONFIG['APP_FAVICON']); ?>"/>
</head>
<body>
<pre><?php print($apps_logs); ?></pre>
</body>
</html>
<?php
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php print($pageTitle); ?> | <?php print($APP_CONFIG['APP_NAME']); ?></title>
<link rel="icon" href="<?php print($APP_CONFIG['APP_FAVICON']); ?>"/>
<link rel="shortcut icon" href="<?php print($APP_CONFIG['APP_FAVICON']); ?>"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.css"/>
<style>
body
{
    <?php $vo = array("-0J8oBmqbRg", "h8ZvH1Nf7Bk", "in9-n0JwgZ0", "zZkMki0yH6I", "80-IGI1mr24", "99GTObM6GUU", "PkeEM_G5GC8", "JuesIryw53E", "UAFXj9dRpwo", "-0J8oBmqbRg", "rn-0OotfzFA", "JHN1-mpgXjo"); $co = array_rand($vo); $imagecode = $vo[$co]; print('background-image: url("https://unsplash.com/photos/'.$imagecode.'/download?force=true&w=1920");'."\n"); ?>
    background-color: #343434;
    font-family: "Montserrat", sans-serif;
}
button { font-weight: bold; }
.modal-body, .modal-content {
    display: flex;
    justify-content: center;
    text-align: center;
    background-color: #000000;
}
.login-container {
    padding: 30px;
    width: 350px;
}
#stalkerdalert {
    display: none;
    font-weight: bold;
}
button {
    font-weight: bold !important;
}
#box_stalker_details {
    display: none;
}
#btn_toggle_proxy_status {
    display: none;
}
#btn_delete_mac {
    display: none;
}
</style>
</head>
<body>
<?php if(!isset($_SESSION['yuvisession'])){ ?>
<div class="modal fade" id="adminloginModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-body">
<div class="login-container">
    <div class="text-center"><img src="<?php print($APP_CONFIG['APP_LOGO']); ?>" width="160" height="65"/></div>
    <form>
        <div class="mt-3 text-center">
            <img src="api.php?action=getCaptcha" alt="Captcha Image" id="img_captcha" class="img-thumbnail text-center" />
        </div>
        <div class="mt-3">
            <input type="password" class="form-control" id="txt_pin" placeholder="Enter Access PIN" required="" autocomplete="off"/>
        </div>
        <div class="mt-3">
            <input type="text" class="form-control" id="txt_captcha" placeholder="Enter Captcha" required="" autocomplete="off"/>
        </div>
        <div class="mt-3">
            <div class="d-grid gap-2">
                <button class="btn btn-danger btn-sm" type="button" id="btn_login"> Login </button>
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div>
<?php } else { ?>
<div class="container">
    <div class="card mt-4 px-3">
        <div class="card-body">
            <br/>
            <h4>Welcome Admin !</h4>
            <hr/>
            <!-- <div class="input-group">
                <input type="number" maxlength="4" class="form-control" placeholder="Change Access PIN" id="txt_nadminPIN" autocomplete="off" />
                <button class="btn btn-success" id="btn_nadminPIN"><i class="fa-solid fa-key"></i></button><button class="btn btn-danger" id="btn_nadminLogout"><i class="fa-solid fa-right-from-bracket"></i></button>
            </div> -->
            <br/>
        </div>
    </div>
    <div class="card mt-4 px-3">
        <div class="card-body">
            <br/>
            <h4>Add/Update Stalker Portal</h4>
            <hr/>
            <div class="mt-3">
                <label class="form-label">MAC Stalker URL *</label>
                <input type="text" class="form-control" placeholder="MAC Stalker URL *" id="mac_url" autocomplete="off" />
            </div>
            <div class="mt-3">
                <label class="form-label">MAC ID *</label>
                <input type="text" class="form-control" placeholder="MAC ID *" id="mac_id" autocomplete="off" />
            </div>
            <div class="mt-3">
                <label class="form-label">Serial IDN</label>
                <input type="text" class="form-control" placeholder="Serial IDN" id="mac_serial" autocomplete="off" />
            </div>
            <div class="mt-3">
                <label class="form-label">Device ID 1</label>
                <input type="text" class="form-control" placeholder="Device ID 1" id="mac_dv1" autocomplete="off" />
            </div>
            <div class="mt-3">
                <label class="form-label">Device ID 2</label>
                <input type="text" class="form-control" placeholder="Device ID 2" id="mac_dv2" autocomplete="off" />
            </div>
            <div class="mt-3">
                <label class="form-label">Signature</label>
                <input type="text" class="form-control" placeholder="Signature" id="mac_sig" autocomplete="off" />
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" type="button" id="btn_mac">&nbsp;&nbsp;Save&nbsp;&nbsp;<i class="fa-solid fa-check"></i>&nbsp;&nbsp;</button>&nbsp;&nbsp;<button class="btn btn-danger" type="button" id="btn_delete_mac">&nbsp;&nbsp;<i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;Delete&nbsp;&nbsp;</button>
            </div>
            <br/>
        </div>
    </div>
    <div class="card mt-4 px-3" id="box_stalker_details">
        <div class="card-body">
            <br/>
            <h4>Stalker Details</h4>
            <hr/>
            <div class="alert alert-info" role="alert" id="stalkerdalert"></div>
            <div class="bx_stkdl">
                <ul>
                    <li><b>Expiry Date : </b><span class="mac_tv_expiry">-</span></li>
                    <li><b>Channels Count: </b><span class="mac_tv_count">0</span></li>
                </ul>
                <button class="btn btn-info" onclick="update_mac_data()"> Update Details </button>
            </div>
            <br/>
        </div>
    </div>
    <div class="card mt-4 mb-4 px-3">
        <div class="card-body">
            <br/>
            <h4>Settings</h4>
            <hr/>
            <ul>
                <li><b>Stream Proxy Status : </b><span class="mac_stream_proxy_status">-</span>&nbsp;<button class="btn btn-secondary btn-sm" id="btn_toggle_proxy_status"><i class="fa-solid fa-rotate-right"></i></button></li>
            </ul>
            <a href="?module=application_logs" target="_blank" class="btn btn-dark"> Logs </a>
        </div>
    </div>

</div>
<?php } ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<?php if(!isset($_SESSION['yuvisession'])){ ?>
<script src="assets/frontend.js?token=<?php print(generateRandomAlphanumericString(32)); ?>"></script>
<script>$(document).ready(function(){ $("#adminloginModal").modal("show"); });</script>
<?php } else {?>
<script src="assets/intriapp.js?token=<?php print(generateRandomAlphanumericString(32)); ?>" onload="load_dashboard_data()"></script>
<?php } ?>
</body>
</html>
