<?php
/**
 * Created by PhpStorm.
 * User: DELL 1
 * Date: 10/8/2018
 * Time: 6:05 AM
 */

use FacebookPhpSdk\Facebook;
use FacebookPhpSdk\Exceptions\FacebookResponseException;
use FacebookPhpSdk\Exceptions\FacebookSDKException;

session_start();
require_once __DIR__ . '/FacebookPhpSdk/autoload.php';
$fb = new Facebook([
    'app_id' => '1322495584465273',
    'app_secret' => 'bbea751ca01fd4e0146607cdc9dee27e',
    'default_graph_version' => 'v2.9',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = array("email","user_friends"); // optional


try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch(FacebookPhpSdk\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
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


    // Getting user facebook profile info
    try {

        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,birthday,email,link,gender,locale,picture',$_SESSION['facebook_access_token']);
        $profileRequest1 = $fb->get('/me?fields=name');
        $requestPicture = $fb->get('/me/picture?redirect=false&height=310&width=300'); //getting user picture
        $profileRequest3 = $fb->get('/me?fields=gender');
        $requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();

        $friends = $requestFriends->getGraphEdge();

        $birthday= $fb->get('/me?fields=age_range,timezone');

        $a = $fb->get('/me/friends?fields=name,gender');
        $b = $a ->getGraphEdge();


        $fbUserProfile1 = $profileRequest1->getGraphNode();
        $picture = $requestPicture->getGraphNode();
        $bday = $birthday->getGraphNode();
        $fbUserProfile3 = $profileRequest3->getGraphNode();


        // If button is clicked a photo with a caption will be uploaded to facebook
        if(isset($_POST['insert'])){
            $data = ['source' => $fb->fileToUpload(__DIR__.'/photo.jpeg'), 'message' => 'Check out this app! It is awesome http://localhost/Facebook/Outh.pnp '];
            $request = $fb->post('/me/photos', $data);
            $response = $request->getGraphNode()->asArray();
            header("Location: http://facebook.com");

        }



    } catch(FacebookResponseException $e) {


        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    // assigning a country according to the timezone
    $randomInteger = rand(0,19);
    $name= $friends[$randomInteger]['name'];
    $timeZone=$bday['timezone'];
    if($timeZone=='5.5'){

        $country = array("America","France","Somaliya","Italy","Nigeria");
    }
    else{
        $country = array("Sri Lanka","India","Ethiopia","Uganda","Gana");
    }

    $selected_country=$country[array_rand($country)];



    $output = $fbUserProfile1;



    // getting nickname

    $gender = array(
        "Mandy",
        "Teddy",
        "sossy",
        "windy",
        "Bunny"

    );
    $selected_gender=$gender[array_rand($gender)];


    // Reason

    $reasons = array(
        "Dancing is favorite",
        "Singing is favorite",
        "Reading Books",
        "Watching Movies",
        "cooking"
    );
    $selected_reason=$reasons[array_rand($reasons)];




}else{

    //   $loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
    //   $output = '<a href="'.htmlspecialchars($loginURL).'"><img src="images/fblogin-btn.png"></a>';
}
?>

<html>
<head>
<title>Facebook APP</title>
 <script src="html2canvas.js"></script>
<style type="text/css">
    body {
    background-image: url("bg.jpg");
    background-size: 1600px 800px;
  	background-repeat: no-repeat;

}
    .warning{font-family:Arial, Helvetica, sans-serif;color:#FFF; top:0px;position:relative;left:450px;}
    .ou { position: relative; top: -200px; left: 300px; }
    .cross { position: absolute; top: -200px; left: 270px; }
    .frame{position:absolute; top:-200px; left:800px;}
    .content{font-family: Papyrus,fantasy;top:-450px;left:830px;position:relative;font-size:20px; }


    .loader{

        border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:130px;
    left:350px;


    }
    .loader2{

        border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:-35px;
    left:900px;


    }


    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }

    .button{
        background-image: url("fb.png");
    background-size: 400px 50px;
    width: 400px;
    height:50px;
    }


    </style>

    <script>
        var hidden = false;


        setTimeout(function(){


            document.getElementById("ou").style.visibility='hidden';
            document.getElementById("cross").style.visibility='hidden';
            document.getElementById("frame").style.visibility='hidden';
            document.getElementById("content").style.visibility='hidden';
        },1);


        setTimeout(function(){


            document.getElementById("ou").style.visibility='visible';
            document.getElementById("cross").style.visibility='visible';
            document.getElementById("frame").style.visibility='visible';
            document.getElementById("content").style.visibility='visible';
        },3000);


    </script>

</head>
<body>
<form method="post"><center><input type="submit" name="insert" class="button" value=""/></center></form>


<h1 class="warning"><b><?php echo $name." is your killer!"; ?></b></h1>
<section><div class="loader"></div><div class="loader2"></div><div class="images" style="position:relative;left:0;"><?php echo "<img src='".$picture['url']."' class='ou' id='ou' />
<img src='frame.jpg'  width='550' height='350' class='frame' id='frame'/> <p class='content' id='content' style='color:white;'><b>Gender: $gender <br> Place : $selected_country <br> Reason : $selected_reason</b></p>"; ?></div></section>


</body>
</html>