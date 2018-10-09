
/**
 * Created by PhpStorm.
 * User: DELL 1
 * Date: 10/7/2018
 * Time: 5:35 AM
 */
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facebook APP</title>

    <style type="text/css">
        body {
            background-image: url("bg.jpg");
            background-size: 1600px 800px;
            background-repeat: no-repeat;

        }

        .warning {
            font-family: Arial, Helvetica, sans-serif;
            color: #FFF;
            top: 0px;
            position: relative;
            left: 400px;
            font-size: 40px;
        }

        .ou {
            position: relative;
            top: -200px;
            left: 300px;
        }

        .cross {
            position: absolute;
            top: -200px;
            left: 270px;
        }

        .letter {
            position: absolute;
            top: -200px;
            left: 800px;
        }

        .content {
            font-family: Papyrus, fantasy;
            top: -300px;
            left: 820px;
            position: relative;
            font-size: 20px;
        }

        .link {
            background-image: url("click.png");
            background-size: 400px 50px;
            width: 400px;
            height: 50px;
            display: block;
            background-repeat: no-repeat;
            position: relative;
        }

        .skull {
            width: 250px;
            height: 300px;
            left: 700px;
            position: relative;

        }
    </style>
    <script>var hidden = false;
        var count = 1;
        setInterval(function () {

            document.getElementById("link").style.visibility = hidden ? "visible" : "hidden";

            hidden = !hidden;

        }, 300);


    </script>


</head>
<body>
<h1 class="warning" id="warning"><b>Who  is your facebook twin friend?</b></h1>

<img src="112.jpg" class="kitty"/>
<img src="121.jpg" class="kitty1"/>
</body>
</html>

<?php

session_start();
require_once __DIR__ . '/FacebookPhpSdk/autoload.php';
$fb = new FacebookPhpSdk\Facebook([
    'app_id' => '1332492284494487',
    'app_secret' => 'bbda751ca01fd4e0157707cdc9kbb27e',
    'default_graph_version' => 'v2.9',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = array("email","user_friends"); // optional
//$permissions = ['friendlist'];

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch(FacebookPhpSdk\Exceptions\FacebookResponseException $e) {

    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(FacebookPhpSdk\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        header('Location: http://localhost/Facebook/Outh.php');
    } else {
        // getting short-lived access token
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        // setting default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    // redirect the user back to the same page if it has "code" GET variable
    if (isset($_GET['code'])) {

        header('Location: ./');
    }


} else {
    // replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
    $loginUrl = $helper->getLoginUrl('http://localhost/Facebook/index.php', $permissions);
    echo"<script>alert('hello1')</script>";
    echo '<center><a class="link" href="' . $loginUrl . '"></a></center>';

}



?>