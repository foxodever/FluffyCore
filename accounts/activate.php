
<!-- 0x46e7b0311B7FB95fd9857BDF01d2279D8B9C46d5 -->
<?php
include "../libs/main.php";
include "../config/register.php";

if($captcha == false) {
    $token = htmlspecialchars($_GET["token"]);
    $sql = "SELECT * FROM accounts WHERE registerToken = '$token'"; 
    $result = mysqli_query($link, $sql);
    $row = $result->fetch_assoc();
    if(!$row["id"]) exit("Activation token not found");
    $sql = "UPDATE accounts SET isActivated = 1 WHERE registerToken = '$token'"; 
    $result = mysqli_query($link, $sql);
    if(!$result) exit("Error");
    exit("Activated");
}

$token = htmlspecialchars($_GET["token"]);
$sql = "SELECT * FROM accounts WHERE registerToken = '$token'"; 
$result = mysqli_query($link, $sql);
$row = $result->fetch_assoc();
if(!$row["id"]) exit("Activation token not found");
if($row["isActivated"] == 1) {
    exit("You aleready activated your account");
}
if($_POST) {
    if(!$_POST["captcha_token"]) exit("Error");
    $data = array(
        'secret' => $captcha_secret,
        'response' => $_POST['h-captcha-response']
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $responseData = json_decode($response);
    if($responseData->success) {
        $sql = "UPDATE accounts SET isActivated = 1 WHERE registerToken = '".htmlspecialchars($_POST["captcha_token"])."'"; 
        $result = mysqli_query($link, $sql);
        if(!$result) exit("Error");
        exit("Activated");
    }
}
?>
<html>
    <head>
        <title>Verification</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
        <style>
            body {
                background: #36393f;
                color: white;
                font-family: Whitney,Helvetica,Arial,sans-serif;
            }

            .services__wrapper {
              display: grid;
              grid-template-columns: 1fr;
              grid-template-rows: 1fr;
            }

            .services__card {
              border-top: 3px solid #41A8FF;
              margin: 30px;
              height: 300px;
              width: 800px;
              border-radius: 25px;
              display: flex;
              flex-direction: column;
              justify-content: top;
              color: #fff;
              background: #292b2f;
              transition: 0.3s ease-in;
            }

            .services__card button:hover {
              cursor: pointer;
            }

            .services__card h1 {
                margin-top: 30px;
            }

            .services__card:hover {
              box-shadow: 0 0 50px #00000030;
              transition: 0.3s ease-in;
              cursor: pointer;
            }

            /* .services__card #line span {
              position: absolute;
              top: -10px;
              left: -10px;
              width: 150px;
              height: 150px;
              background: red;
              display: flex;
              justify-content: center;
              align-items: center;
            }

            .services__card #line span::before {
              content: '';
              position: absolute;
              width: 100%;
              height: 40px;
              background: blue;
            } */

            .main {
              background: #303339;
              border-top: 3px solid #525965;
              border-radius: 100px 100px 10px 10px;
              /* margin-top: 100px; */
              /* padding: 20px; */
              align-items: center;
              justify-content: center;
              text-align: center;
              display: flex;
              flex-direction: column;
              justify-content: center;

              min-height: 100%;
            }
            /* .logo {
              background: -webkit-linear-gradient(to right #00DEFF, #4D90FF);
              background: linear-gradient(to right, #00DEFF, #4D90FF);
              background-size: 100%;
              -webkit-background-clip: text;
              -moz-background-clip: text;
              -webkit-text-fill-color: transparent;
              -mo-text-fill-color: transparent;
            } */
            /* .title {
                text-align: center;
                font-size: 3em;
                margin-top: 0px;
                margin-bottom: 100px;
            } */
            .logo {
              font-size: 18px;
              color: #ffffff52;
            }
            .services__card h1 span {
                word-break: break-all;
                background: -webkit-linear-gradient(to right #00DEFF, #4D90FF);
                background: linear-gradient(to right, #00DEFF, #4D90FF);
                background-size: 100%;
                -webkit-background-clip: text;
                -moz-background-clip: text;
                -webkit-text-fill-color: transparent;
                -mo-text-fill-color: transparent;
            }
            .inputblock {
              margin-top: 40px;
            }
            input {
                background-color: #303339;
                border: 1px solid #00000000;
                color: white;
                outline: none;
                padding: 3px;
                color: white;
                font-size: 24px;
                border-radius: 3px;
            }
            input:hover {
                background-color: #3A3E45;
                border: 1px solid #00000000;
                color: white;
                outline: none;
                padding: 3px;
                border-radius: 3px;
            }
            input:focus {
                background-color: #303339;
                border: 1px solid #494D55ff;
                padding: 3px;
                outline: none;
                color: white;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .center {
                text-align: center;
            }
            #status {
                color: #FF7575;
            }
            #status2 {
                color: #FF7575;
            }
            .input .error {
              border-left: 2px solid #FF7575;
            }
            .error {
                color: #FF7575;
            }
            .button {
                padding: 8px 30px;
                border-radius: 10px;
                cursor: pointer;
                background-color: #3ba55d;
                transition: all 0.3s ease;
            }
            .button:hover {
                padding: 8px 30px;
                border-radius: 10px;
                cursor: pointer;
                background-color: #4EAF6D;
            }
            .button:focus {
                padding: 8px 30px;
                border-radius: 10px;
                cursor: pointer;
                background-color: #4EAF6D;
            }
            .lds-dual-ring {
              display: inline-block;
              margin-bottom: -2px;
              margin-left: 10px;
            }
            .lds-dual-ring:after {
              content: " ";
              display: block;
              width: 16px;
              height: 16px;
              border-radius: 50%;
              border: 2px solid #fff;
              border-color: #fff transparent #fff transparent;
              animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
              0% {
                transform: rotate(0deg);
              }
              100% {
                transform: rotate(360deg);
              }
            }
            @media only screen and (max-width: 1000px) {
              .main {
                margin-top: 100px;
              }
              .title {
                margin-top: 1em;
              }
              .services__wrapper {
                align-items: center;
                display: grid;
                grid-template-columns: 1fr;
                grid-template-rows: 1fr;
              }
              .services__card {
                  /* max-width: 70%; */
                  margin: 30px;
                  height: 500px;
                  width: 380px;
                  border-radius: 25px;
                  display: flex;
                  flex-direction: column;
                  justify-content: top;
                  color: #fff;
                  background: #292b2f;
              }
              p {
                font-size: 24px;
              }
              input {
                width: 280px;
              }
              .main {
                  width: 100%;
              }
            }
            @media only screen and (min-width: 1001px) {
              .main {
                  margin-left: 20%;
                  margin-right: 20%;
              }
            }
        </style>
    </head>
    <body>
      <div class="main">
        <img src="https://media.discordapp.net/attachments/881485225627099146/916691053514469396/updlogo.png" class="sisya">
      <h1 class="title">Verification</h1>
        <div class="services">
        <div class="services__wrapper">
          <div class="services__card" id="connect">
            <h1><span>Complete</span> captcha</h1>
              <form method="post" id="captcha_form" action="" >
                <input name="captcha_token" value="<?=$token?>" type="hidden" />
                <div class="g-recaptcha" data-sitekey="<?=$captcha_public?>" data-callback="subsex"></div>
                <script src="https://hcaptcha.com/1/api.js" async defer></script>
                <noscript>
                    <h1>You must enable JavaScript to continue</h1>
                </noscript>
              </form>
          </div>
        <p class="logo">Vulpine © Foxodever x Keisi</p>
    </div>
    </body>
</html>
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script> 
    function subsex() {
        $("#captcha_form").submit();
    }
</script>