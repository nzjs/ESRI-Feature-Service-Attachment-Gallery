<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>ESRI Attachment Gallery</title>
    <!-- Custom styles -->
    <link href="css/custom.css" rel="stylesheet">
    <style>
      body {
        background-color:#222222;
        font-family: "Asap", sans-serif;
        color:#bdbdbd;
        margin:10px;
        font-size:16px;
      }
    </style>

  <?php 
    require('main.php');

    $base64 = CleanInput($_GET['id']);
    $pk = CleanInput($_GET['pk']);
    $theme = CleanInput($_GET['theme']);
    $serviceURL = urldecode(base64_decode($base64));

    if ($theme == 'dark') {
      $cssBg = $darkThemeBack;
      $cssCol = $darkThemeFont;
    }
    elseif ($theme == 'light') {
      $cssBg = $lightThemeBack;
      $cssCol = $lightThemeFont;
    }
  ?>

  </head>
  <body style="<?php echo 'background-color: '.$cssBg.' !important; color: '.$cssCol.' !important;'?>">
  
    <!-- Page Content -->
    <div class="container-fluid"> 

    <?php 
    echo '<div class="col-lg-3 col-md-4 col-xs-6" style="margin: 0 auto; text-align: center; margin-bottom: 10px;">';
    echo '<h5>Photo Submissions</h5>';
    $countText = ($maxImages != 0) ? 'Most Recent Images ('.$maxImages.')' : 'All Uploaded Images';
    echo $countText;

    // Output our variables for testing purposes
    // echo 'BASE64 EN: '.$base64;
    // echo '<br/>';   echo '<br/>';
    // echo 'BASE64 DE: '.$serviceURL;
    // echo '<br/>';   echo '<br/>';

    $token = GenerateToken($agolUsername, $agolPassword, $tokenReferrer, $tokenFormat);
    // echo 'TOKEN: '.$token;
    // echo '<br/>';   echo '<br/>';

    $definition = CreateDefinition($maxImages, $serviceURL, $pk, $token);
    // echo 'DEFINITION: '.$definition;
    // echo '<br/>';   echo '<br/>';

    // echo 'THEME: '.$theme;
    // echo '<br/>';   echo '<br/>';
    echo '</div>';
    ?>

    <div class="row">
  
      <?php
      $attachmentsJsonURL = $serviceURL . '/queryAttachments?objectIds=&globalIds=&definitionExpression=' . $definition . '&attachmentsDefinitionExpression=&attachmentTypes=&size=&keywords=&resultOffset=0&resultRecordCount=&f=pjson&token=' . $token;
      
      // Retrieve raw JSON from input URL and convert to a multidimensional associative array
      $json = file_get_contents($attachmentsJsonURL);
      $obj = json_decode($json, true);
      //var_dump($obj['attachmentGroups']); 

      // First part: Sort the array by the parentObjectID value, using usort(). 
      // Second part: Reverse the array to show the most recent parentObjectID at the top of the page.
      $obj_array = $obj['attachmentGroups'];
      usort($obj_array, function($a, $b) {
        return $a['parentObjectId'] <=> $b['parentObjectId'];
      });
      $obj_array_reversed = array_reverse($obj_array, true);
      
      // First level of array
      foreach ($obj_array_reversed as $obj_key => $obj_value) {
          //echo '-----------------------------------------------------------------------------------------------------------------------------------------<br/>';
          
          // var_dump($obj_key);
          // echo '<br/>';echo '<br/>';
          // var_dump($obj_value);
          // echo '<br/>';echo '<br/>';

          // Second level of array
          foreach ($obj_value['attachmentInfos'] as $attach_key => $attach_value) {
          //echo '------------------------<br/>';
          //var_dump($attach_value);
          //echo '<br/>';echo '<br/>';
          $imgUrl = ($serviceURL . '/' . $obj_value['parentObjectId'] . '/attachments/' . $attach_value['id'] . '?token=' . $token);
          echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb"><span class="top-left-text"># '.$obj_value['parentObjectId'].'</span>
          <a href="'.$imgUrl.'" class="fancybox" rel="ligthbox" target="_blank">
              <img src="'.$imgUrl.'" class="zoom img-fluid" title="Related Record: '.$obj_value['parentObjectId'].'">
          </a>
          </div>';
          //echo '<br/>';echo '<br/>';
          }
          //echo '------------------------------------------------------------------------------------------------------------------------------------------<br/>';
      }
      ?>

      </div>
    </div>
    <!-- /content -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <!-- Fancybox JS after jQuery -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script> 
    $(document).ready(function(){
      $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
        
        $(".zoom").hover(function(){
        
        $(this).addClass('transition');
      }, function(){
            
        $(this).removeClass('transition');
      });
    });
    </script>
  </body>
</html>