<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>ESRI Attachment Gallery Generator</title>

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles -->
    <link href="css/custom.css" rel="stylesheet">

  </head>
  <body>
    <?php require('main.php'); ?>

    <form method="get" action="gallery.php" class="form-signin" name="form"><br/>
        <div class="text-center mb-4">
        <img class="mb-4" src="https://wcrc.maps.arcgis.com/sharing/rest/content/items/6e7eb18cd1984ba5a897294f44fc111e/data" alt="" height="72">
          <h1 class="h3 mb-3 font-weight-normal">ESRI Attachment Gallery Generator</h1>
          <p>Enter the ESRI hosted Feature Service URL that you want to generate a gallery page for.<br/><br/>
          <small>A responsive interface featuring a specified number of recent service attachments will appear on the page. <br/>
          Any attachment-enabled service (Survey123, QuickCapture, etc) should work.<br/><br/>
          Once your gallery URL is generated, you can add it as "Embedded Content" in an Operations Dashboard.<br/>
          Use these parameters &rarr; Type: Static, Content Type: Document, URL: [URL of gallery]</small><br/><br/>
          <a href="https://github.com/nzjs">View the documentation on GitHub.</a><br/><small>Developed by John Stowell</small></p>
        </div>

        <div class="form-label-group">
          <input type="text" name="id" class="form-control" placeholder="Feature Service URL" style="min-width: 600px;" required autofocus>
          <label for="id">Feature Service URL</label>
          <p><span style="font-size: 0.75em;">(Eg. https://services3.arcgis.com/za4HjpHWqnA4SFnH/ArcGIS/rest/services/WCRC_USAR_QuickCapture/FeatureServer/0)</span><p/>
        </div>

        <div class="form-group">
        <label for="pk">Primary key identifier <small>(Unique ID field for layer)</small></label>
        <select class="form-control" style="min-width: 600px;" name="pk" placeholder="">
          <option value="FID">FID</option>
          <option value="OBJECTID">OBJECTID</option>
        </select>
        </div>

        <div class="form-group">
        <label for="theme">Theme selection</label>
        <select class="form-control" style="min-width: 600px;" name="theme" placeholder="">
          <option value="dark">Dark</option>
          <option value="light">Light</option>
        </select>
        </div>

        <button class="btn btn-lg btn-primary btn-block" onclick="Submit()" style="min-width: 600px;" >Generate Gallery URL</button>
        <p class="mt-5 mb-3 text-muted text-center">&copy; 2018-2019</p>
    </form>



    <script type="text/javascript">
    function Submit() {
        // Retrieve the input value, strip trailing slash (if it exists), and base64 encode for nicer URL
        var value = (document.form.id.value).replace(/\/$/, "")
        var base64 = encodeURIComponent(window.btoa(value));
        document.form.id.value = base64;
        return true;
    }
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>