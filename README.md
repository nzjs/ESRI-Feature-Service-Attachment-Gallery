# ESRI Feature Service Attachment Gallery

## What is it?
This tool takes an input ESRI hosted Feature Service URL and a theme parameter, and generates a new public URL for viewing the Feature Service attachments in a nice, responsive gallery. The intent is for the gallery URL to then be used as "Embedded Content" in an Operations Dashboard. 

## Requirements
- Files hosted on your own web server with PHP enabled
- Customise the main.php file with the below variables as desired

## Customisation
```php
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
```

## Example
1. Input your parameters on the initial page
2. View your gallery and copy the custom gallery URL with GET parameters
3. Paste the gallery URL into Operations Dashboard - Embedded Content

Example screenshots:
![example-screenshots](https://github.com/nzjs/ESRI-Feature-Service-Attachment-Gallery/raw/master/demo/example-screenshots.jpg "Example screenshots")
![example-screenshots 2](https://github.com/nzjs/ESRI-Feature-Service-Attachment-Gallery/raw/master/demo/example-screenshots2.jpg "Example screenshots 2")