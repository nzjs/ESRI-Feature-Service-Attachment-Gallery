<?php

// Configure the ArcGIS Online username/password here.
// This is used for token authentication with secure services. 
//
// The AGOL user must have access to the feature layer(s) that you are generating a gallery for.
// This will generate a new token each time the Gallery page is refreshed (as tokens don't last forever).
// Generally speaking the tokenReferrer and tokenFormat should be left as the defaults below.
$agolUsername = '<ARCGIS ONLINE USERNAME>';
$agolPassword = '<ARCGIS ONLINE PASSWORD>';

$tokenReferrer = 'https://www.arcgis.com';
$tokenFormat   = 'pjson';

// Only show the latest ($maxImages) number of attachments in the Gallery page.
// Set to 0 (zero) if you want to return all attachments (not recommended).
$maxImages = 80;

// Here we also allow customisation of the dark/light themes.
// By default, we're using the ArcGIS Ops Dashboard CSS colours.
$darkThemeBack = '#222222';
$darkThemeFont = '#bdbdbd';

$lightThemeBack = '#ffffff';
$lightThemeFont = '#4c4c4c';




// ----------------------------------------------------------------------------------------------------------------
// Do not edit below this line - unless you know what you're doing :)
// ----------------------------------------------------------------------------------------------------------------




function GenerateToken($agolUsername, $agolPassword, $tokenReferrer, $tokenFormat) {
    try {
        // Generate a temporary API token for accessing the ArcGIS Online REST endpoints
        $tokenUrl = 'https://www.arcgis.com/sharing/rest/generateToken?f='.$tokenFormat;
        $data = array('username' => $agolUsername, 
                    'password' => $agolPassword, 
                    'referer' => $tokenReferrer);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($tokenUrl, false, $context);
        if ($result === FALSE) { /* Handle error */ }

        // Enable for testing
        //var_dump($result);

        $tokenResult = json_decode($result, true);
        return $tokenResult['token'];
    }
    catch (Exception $e) {
        return $e;
        echo 'Failed to Generate Token';
    }
} // function


function CreateDefinition($maxImages, $serviceURL, $token) {
    try {
        // Identify our _most recent_ images depending on $maxImages variable
        if ($maxImages != 0) {
            // Compile a URL for getting our feature count
            $featureCountURL = $serviceURL . '/query?where=1%3D1&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=&returnHiddenFields=false&returnGeometry=false&multipatchOption=xyFootprint&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=true&returnExtentOnly=false&returnDistinctValues=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pjson&token=' . $token;

            // Send GET request via cURL and save the results to $resp.
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $featureCountURL,
                CURLOPT_USERAGENT => 'cURL Request'
            ));
            // Send the request & save response to $resp
            $resp = json_decode(curl_exec($curl), true);
            // Close request to clear up some resources
            curl_close($curl);

            $featureCount = $resp['count'];

            // Grab the feature count and calculate our parameters based on the $maxImages variable in main.php
            // This will return the "FID >= #" definition for only returning the _most recent_ $maxImages    

            // If our feature count is less than desired max images, just return the total features in the layer.
            if ($featureCount < $maxImages) {
                $definition = '1=1';
                return $definition;
            }
            // Or else we do the calculation as outlined above
            else {
            $maxImagesCalc = ($featureCount - $maxImages);
            $definition = "FID>" . $maxImagesCalc;
            return $definition;
            }
        }
        // or else we return all attachments ($maxImages set to zero)
        else {
            $definition = '1=1';
            return $definition;
        }
    }
    catch (Exception $e) {
        return $e;
        echo 'Failed to Create Definition';
    }
} // function

function CleanInput($string) {
    try {
        // Removes all special chars from GET input apart from hyphen, underscore, or equal sign.
        // These special chars are potential base64 characters, so we need to keep them.
        $string =  preg_replace('/[^A-Za-z0-9\-_=]/', '', $string); 
        return $string;
    }
    catch (Exception $e) {
        return $e;
        echo 'Failed to Clean Input';
    }
}

?>