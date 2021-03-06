<?php /* Mobile JWiki 0.4.1
Copyright 2011 Bob Wadholm 
Dual licensed under the MIT or GPL Version 2 licenses. 
http://www.bob.wadholm.com/licenses.shtml

This script allows an entire wiki to be transformed into a mobile Website 
using only JQuery, JQuery Mobile & PHP. Put this script on your site anywhere, 
and change the $jWikiHome variable to the domain of the wiki, the $jWikiHomePage
variable to the home page of the mobile site, and the $mobileHome variable to 
the path to this script. That's it. 
*/
$jWikiHome = "http://en.door43.org"; // Change this to the domain of the site you want to feature
$jWikiHomePage = $jWikiHome . "/wiki/Mobile_Home"; // Change this to the home page of the mobile site
$mobileHome = "/door43/index.php"; // Change this to whatever you rename this file to (like /index.php or /m.php) 

// Everything below here can stay
// <html manifest="/door43/cache.manifest">
$linkValue = ($_GET["linkValue"]) . "";
if ($linkValue == ""){
	$linkValue = $jWikiHomePage;	
}

$urlWithParams = $_SERVER['SCRIPT_URI'] . '?' . $_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html> 
<html manifest="/door43/cache.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<title>Door43</title> 
<link rel="stylesheet" href="<?php echo $jWikiHome; ?>/w/extensions/JQueryTabs/js/themes/base/ui.all.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
<style type="text/css">
.ui-body-c { background: #fff; }
.ui-btn-text { color: #fff; text-decoration:none; }
a.ui-btn-hover-a { color: #fff; text-decoration:none; }
img { max-width: 100%; height: auto;}
.videoframe, .youtube-player { max-width: 100%; height: auto;}
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
<script type='text/javascript' src='<?php echo $jWikiHome; ?>/w/extensions/JQueryTabs/js/jquery.flydom-3.1.1.min.js'></script> 
<script type='text/javascript' src='<?php echo $jWikiHome; ?>/w/extensions/JQueryTabs/js/jquery-ui-1.8.13.custom.min.js'></script> 
<script type="text/javascript">
$(document).bind("mobileinit", function(){
  $.extend(  $.mobile , {
    hashListeningEnabled: false
  });
});
</script>
<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
</head> 
<body title="<?php echo $urlWithParams; ?>">
    <div data-role="page" data-refresh="refresh">
        <div data-role="header">
        
        	<?php 
				if ($linkValue != $jWikiHomePage){
        			echo '<a href="' . $mobileHome . '" rel="external" data-rel="back" data-role="button" data-icon="arrow-l">Back</a>';
				}
			?>
            
            <h1>Door43</h1>
            <div class="ui-btn-right jWikiHome">
            	<a href="<?php echo $mobileHome; ?>" data-site-home="<?php echo $jWikiHome; ?>" rel="external" data-role="button" data-icon="home" data-iconpos="notext">Home</a> 		
            </div>
        </div>
    
        <div data-role="content" class="contentDiv">
        
            <?php 
			$getContents = file_get_contents($linkValue . '?action=render');
			
			if ($getContents === false) { 
			   	echo '<p>We&rsquo;re sorry, but this page does not exist</p>';
			} else {
				
				// Replace all relative URLs
				$relativeContents = str_replace('href="/', 'rel="external" href="' . $mobileHome . '?linkValue=' . $jWikiHome . '/', $getContents);
				// Replace all absolute URLs
				$absoluteContents = str_replace('href="' . $jWikiHome, 'rel="external" href="' . $mobileHome . '?linkValue=' . $jWikiHome, $relativeContents);
				
				// Replace all anchor links
				$anchorContents = str_replace('href="#', 'rel="external" href="' . $urlWithParams . '#', $absoluteContents);
				$anchorContentsSQ = str_replace("href='#", "rel='external' href='" . $urlWithParams . "#", $anchorContents);
				$anchorContentsJS = str_replace("document.location='#", "document.location.replace='" . $urlWithParams . "#", $anchorContentsSQ);
				
				// Display fancy JQuery Mobile lists 
				$mobileLists = str_replace('<ul>', '<ul data-role="listview" data-inset="true">', $anchorContentsJS);
				$mobileorderedLists = str_replace('<ol>', '<ol data-role="listview" data-inset="true">', $mobileLists);
	
				// Display all pages (for browsers without JavaScript
				$displayContents = str_replace('display:none;','', $mobileorderedLists);
				
				// Replace all relative URLs for images, scripts, and styles
				$newContents = str_replace('src="/', 'src="' . $jWikiHome . '/', $displayContents);
				
				echo $newContents;
			};		
			?>
            
            <script type="text/javascript">
				$('div[data-refresh="refresh"]').live("pageshow", function() { 
					$('.editsection').remove();
				});
			</script>
            
        </div>
        <div data-role="footer">
            <h4><a rel="external" href="<?php echo $mobileHome . "?linkValue=" . $jWikiHome; ?>/copyrights"><img src="http://door43.org/media/images/by-sa_80x15.png" alt="by-sa" /></a><br /><a rel="external" href="<?php echo $linkValue; ?>"><img src="http://door43.org/media/images/d43_80x15.png" alt="door43.org" /></a></h4>
        </div>
    </div>
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://www.dsmedia.org/piwik/" : "http://www.dsmedia.org/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 2);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://www.dsmedia.org/piwik/piwik.php?idsite=10" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
</body>
</html>