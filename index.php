<?php

if( file_exists('install.php') )
{
	header("Location: install.php");
	exit;
}

if( file_exists('mail.php') )
{
	echo('Update: Unlinking mail.php. You can safely ignore this message. Please refresh the page.');
	unlink('mail.php');
}

if( file_exists('validation.js') )
{
	echo('Update: Unlinking validation.js. You can safely ignore this message. Please refresh the page.');
	unlink('validation.js');
}


if( !file_exists('data.xml') )
{
	if( file_exists('_data.xml') )
	{
		echo '<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Instructions</title>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/1.2.0/css/uikit.gradient.min.css" rel="stylesheet" type="text/css">
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="uk-container uk-container-center">
			<div class="uk-grid">
			</div>
		</div>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$(".uk-grid").load("create.php?s=installation");

				setInterval(function() {
					$(".uk-grid").load("create.php?s=installation");
				}, 5000);
			});
		</script>
	</body>
</html>';
		exit;
	}
}

// Language logic

include 'lang/TranslateTool.php';
$language = TranslateTool::loadLanguage(isset($_GET['l']) ? $_GET['l'] : null, 'index.php');
$languageQuery = ($language != TranslateTool::getDefaultLanguage() ? '?l='. $language : '');

if (file_exists('data-'. $language .'.xml'))
	$xml = simplexml_load_file('data-'. $language .'.xml');
else
	$xml = simplexml_load_file('data.xml');

foreach( $xml->children() as $child )
{
	switch( $child->getName() )
	{
		case("title"):
			define("COMPANY_TITLE", $child);
			break;	
		case("founding-date"):
			define("COMPANY_DATE", $child);
			break;
		case("website"):
			define("COMPANY_WEBSITE", $child);
			break;
		case("press-contact"):
			define("COMPANY_CONTACT", $child);
			break;
		case("based-in"):
			define("COMPANY_BASED", $child);
			break;
		case("analytics"):
			define("ANALYTICS", $child);
			break;
		case("socials"):
			$socials = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$socials[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;
		case("address"):
			$address = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$address[$i] = $subchild;
				$i++;
			}
			break;	
		case("phone"):
			define("COMPANY_PHONE", $child);
			break;
		case("description"):
			define("COMPANY_DESCRIPTION", $child);
			break;
		case("histories"):
			$histories = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$histories[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;
		case("features"):
			$features = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$features[$i] = $subchild;
				$i++;
			}
			break;	
		case("trailers"):
			$trailers = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$trailers[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("awards"):
			$awards = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$awards[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("quotes"):
			$quotes = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$quotes[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("additionals"):
			$additionals = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$additionals[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("credits"):
			$credits = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$credits[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("contacts"):
			$contacts = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$contacts[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
	}
}

function parseLink($uri)
{
    $parsed = trim($uri);
    if( strpos($parsed, "www.") === 0 )
        $parsed = substr($parsed, 4);
    if( strrpos($parsed, "/") == strlen($parsed) - 1)
        $parsed = substr($parsed, 0, strlen($parsed) - 1);
    if( substr($parsed,-1,1) == "/" )
    	$parsed = substr($parsed, 0, strlen($parsed) - 1);

    return $parsed;
}

echo '<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>MetaDOS Press Kit</title>
		<link rel="shortcut icon" href="https://d1ly3rk1lsmf13.cloudfront.net/favicon_55b73389cf_e5ae82b213_42501c3294.ico">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/1.2.0/css/uikit.gradient.min.css" rel="stylesheet" type="text/css">
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="uk-container uk-container-center">
			<div class="uk-grid">
				<div id="navigation" class="uk-width-medium-1-4">
					<h1 class="nav-header">'. COMPANY_TITLE .'</h1>
					<a class="nav-header" href="'. parseLink(COMPANY_WEBSITE) .'">'. trim( parseLink(COMPANY_WEBSITE), "/") .'</a>
					<ul class="uk-nav uk-nav-side">';

if (count(TranslateTool::getLanguages()) > 1) {
	echo '<li class="language-select"><a>'. tl('Language: ') .'<select onchange="document.location = \'index.php?l=\'+ this.value;">';
	foreach (TranslateTool::getLanguages() as $tag => $name)
	{
		echo '<option value="'. $tag .'" '. ($tag == $language ? 'selected':'') .'>'. htmlspecialchars($name) .'</option>';
	}
	echo '</select></a></li>';
	echo '<li class="uk-nav-divider"></li>';
}

echo '					<li><a href="#factsheet">'. tl('Factsheet') .'</a></li>
						<li><a href="#description">'. tl('Description') .'</a></li>
						<li><a href="#history">'. tl('History') .'</a></li>
						<li><a href="#trailers">'. tl('Videos') .'</a></li>
						<li><a href="#logo">'. tl('Logo MetaDOS') .'</a></li>
						<li><a href="#second-token">'. tl('Second Token') .'</a></li>
						<li><a href="#nft-chest">'. tl('NFT Chest') .'</a></li>
						<li><a href="#images">'. tl('Hunters') .'</a></li>
						<li><a href="#hunter-skins">'. tl('Hunter Skins') .'</a></li>
						<li><a href="#cover-and-screenshot">'. tl('Cover and Screenshot') .'</a></li>
						<li><a href="#fonts">'. tl('Fonts') .'</a></li>
						<li><a href="#in-game-screenshot">'. tl('Ingame Screenshot') .'</a></li>
						<li><a href="#keyart-and-cover">'. tl('Keyart and Cover') .'</a></li>
						<li><a href="#store-artworks">'. tl('Store Artworks') .'</a></li>
						<li><a href="#throwables">'. tl('Throwables') .'</a></li>
						<li><a href="#weapons">'. tl('Weapons') .'</a></li>
						
						
';
						if( count($awards) > 0 ) echo'<li><a href="#awards">'. tl('Awards & Recognition') .'</a></li>';
						if( count($quotes) > 0 ) echo '<li><a href="#quotes">'. tl('Selected Articles') .'</a></li>';

if( count($additionals) > 0 ) {
	echo '<li><a href="#links">'. tl('Additional Links') .'</a></li>';
}

echo '						<li><a href="#credits">'. tl('Team') .'</a></li>
						<li><a href="#contact">'. tl('Contact') .'</a></li>
					</ul>
				</div>
				<div id="content" class="uk-width-medium-3-4">';

if( file_exists("images/header.png") ) {
	echo '<img src="images/header.png" class="header">';
}

echo '					<div class="uk-grid">
						<div class="uk-width-medium-2-6">
							<h2 id="factsheet">'. tl('Factsheet') .'</h2>
							<p>
								<strong>'. tl('Developer:') .'</strong><br/>
								<a href="">'. COMPANY_TITLE .'</a><br/>
								'. tl('Based in %s', COMPANY_BASED) .'
							</p>
							<p>
								<strong>'. tl('Founding date:') .'</strong><br/>
								'. COMPANY_DATE .'
							</p>
							<p>
								<strong>'. tl('Website:') .'</strong><br/>
								<a href="http://'. parseLink(COMPANY_WEBSITE) .'">'. parseLink(COMPANY_WEBSITE) .'</a>
							</p>
							<p>
								<strong>'. tl('Press / Business Contact:') .'</strong><br/>
								<a href="mailto:'. COMPANY_CONTACT .'">'. COMPANY_CONTACT .'</a>
							</p>        
							<p>
								<strong>'. tl('Social:') .'</strong><br/>';

for( $i = 0; $i < count($socials); $i++ )
{
	$name = $link = "";

	foreach( $socials[$i]['social']->children() as $child )
	{
		if( $child->getName() == "name" ) $name = $child;
		else if( $child->getName() == "link" ) $link = $child;
	}
	echo( '<a href="http://'.parseLink($link).'">'.$name.'</a><br/>' );
}

echo '							</p>
							<p>
							<br />';

if ($handle = opendir('.')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != ".." && $entry != "lang" && substr($entry,0,1) != "_" && strpos($entry, ".") === FALSE && substr($entry,-4) != ".log" && substr($entry,0,6) != "images" && substr($entry,0,8) != "trailers" && substr($entry,0,9) != "error_log") {
			echo '<a href="sheet.php?p='.$entry . str_replace('?', '&', $languageQuery).'">'.ucwords(str_replace("_", " ", $entry)).'</a><br />';
		}
	}
}
closedir($handle);

echo '							</p>
							<p>';

if( count($address) > 0 )
{
	echo '<strong>'. tl('Address:') .'</strong><br/>';
	for( $i = 0; $i < count($address); $i++ )
	{
		echo $address[$i].'<br/>';
	}
}

echo'							</p> 
						</div>
						<div class="uk-width-medium-4-6">
							<h2 id="description">'. tl('Description') .'</h2>
							<p>'. COMPANY_DESCRIPTION .'</p>
							<h2 id="history">'. tl('History') .'</h2>';

for( $i = 0; $i < count($histories); $i++ )
{
	$header = $text ="";

	foreach( $histories[$i]['history']->children() as $child )
	{
		if( $child->getName() == "header" ) $header = $child;
		else if( $child->getName() == "text" ) $text = $child;
	}
	echo '<strong>'.$header.'</strong>
<p>'.$text.'</p>';
}

echo '							
							<ul>';

if ($handle = opendir('.')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != ".." && $entry != "lang" && substr($entry,0,1) != "_" && strpos($entry, ".") === FALSE && substr($entry,-4) != ".log" && substr($entry,0,6) != "images" && substr($entry,0,8) != "trailers" && substr($entry,0,9) != "error_log") {
			echo '<li><a href="sheet.php?p='.$entry. str_replace('?', '&', $languageQuery).'">'.ucwords(str_replace("_", " ", $entry)).'</a></li>';
		}
	}
}
closedir($handle);

echo '							</ul>
						</div>
					</div>

					<hr>

					<h2 id="trailers">'. tl('Videos') .'</h2>';

if( count($trailers) == 0 )
{
	echo '<p>'. tlHtml('There are currently no trailers available for %s. Check back later for more or <a href="#contact">contact us</a> for specific requests!', COMPANY_TITLE) .'</p>';
}
else
{
	for( $i = 0; $i < count($trailers); $i++ )
	{
		$name = $youtube = $vimeo = $mov = $mp4 = "";
		$ytfirst = -1;

		foreach( $trailers[$i]['trailer']->children() as $child )
		{
			if( $child->getName() == "name" ) {
				$name = $child;
			} else if( $child->getName() == "youtube" ) { 
				$youtube = $child; 
			
				if( $ytfirst == -1 ) { 
					$ytfirst = 1; 
				} 
			} else if( $child->getName() == "vimeo" ) {
				$vimeo = $child; if( $ytfirst == -1 ) {
					$ytfirst = 0;
				}
			} else if( $child->getName() == "mov" ) {
				$mov = $child;
			} else if( $child->getName() == "mp4" ) {
				$mp4 = $child;
			}
		}
				
		if( strlen($youtube) + strlen($vimeo) > 0 )				
		{
			echo '<p><strong>'.$name.'</strong>&nbsp;';
			$result = "";

			if( strlen( $youtube ) > 0 ) {
				$result .= '<a href="https://www.youtube.com/watch?v='.$youtube.'">YouTube</a>, ';
			}
			if( strlen( $vimeo ) > 0 ) {
				$result .= '<a href="https://www.vimeo.com/'.$vimeo.'">Vimeo</a>, ';
			}
			if( strlen( $mov ) > 0 ) {
				$result .= '<a href="trailers/'.$mov.'">.mov</a>, ';
			}
			if( strlen( $mp4 ) > 0 ) {
				$result .= '<a href="trailers/'.$mp4.'">.mp4</a>, ';
			}

			echo substr($result, 0, -2);

			if( $ytfirst == 1 ) 
			{
				echo '<div class="uk-responsive-width iframe-container">
		<iframe src="https://www.youtube.com/embed/'. $youtube .'" frameborder="0" allowfullscreen></iframe>
</div>';
			} elseif ( $ytfirst == 0 ) {
				echo '<div class="uk-responsive-width iframe-container">
		<iframe src="https://player.vimeo.com/video/'.$vimeo.'" frameborder="0" allowfullscreen></iframe>
</div>';
			}
			echo '</p>';
		}				
	}
}
/**Logo MetaDOS */
echo '					<p class="images-text">'. tlHtml('There are far more images available for %s, but these are the ones we felt would be most useful to you. If you have specific requests, please do <a href="#contact">contact us</a>!', COMPANY_TITLE) .'</p>

					<hr>

					<h2 id="logo">'. tl('Logo MetaDOS') .'</h2>';

if( file_exists("images/logo.zip") )
{
	$filesize = filesize("images/logo.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/logo.zip"><div class="uk-alert">'. tl('download logo files as .zip (%s)', $filesize) .'</div></a>';
}

echo '<div class="uk-grid images">';

if( file_exists('images/logo.png') ) {
	echo '<div class="uk-width-medium-1-2"><a href="images/logo.png"><img src="images/logo.png" alt="logo" /></a></div>';
}

if( file_exists('images/icon.png') ) {
	echo '<div class="uk-width-medium-1-2"><a href="images/icon.png"><img src="images/icon.png" alt="logo" /></a></div>';
}

echo '</div>';

if( !file_exists('images/logo.png') && !file_exists('images/icon.png')) {
	echo '<p>'. tlHtml('There are currently no logos or icons available for %s. Check back later for more or <a href="#contact">contact us</a> for specific requests!', COMPANY_TITLE) .'</p>';
}
/**Logo MetaDOS  */
/**Second Token */
echo '					<hr>

					<h2 id="second-token">'. tl('Second Token') .'</h2>';

if( file_exists("images/images.zip") )
{
	$filesize = filesize("images/images.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/images.zip"><div class="uk-alert">'. tl('download all screenshots & photos as .zip (%s)', $filesize) .'</div></a>';
}
echo '<a href="https://drive.google.com/drive/folders/13v2FGJaZQ1k9SHHWhSuuAvLGcbwP5tNT?usp=drive_link"><div class="uk-alert">'. tl('download all Second Token ') .'</div></a>';

echo '<div class="uk-grid images">';
if ($handle = opendir('images/SecondToken'))
{
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle)))
	{
		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
		{
			if( substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
			{	
				echo '<div class="uk-width-medium-1-2"><a href="images/SecondToken/'. $entry .'"><img src="images/SecondToken/'.$entry.'" alt="'.$entry.'" /></a></div>';
			}
		}
	}
}
echo '</div>';
/**Second Token */
/**NFT Chest */
echo '					<hr>

					<h2 id="nft-chest">'. tl('NFT Chest') .'</h2>';

if( file_exists("images/images.zip") )
{
	$filesize = filesize("images/images.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/images.zip"><div class="uk-alert">'. tl('download all screenshots & photos as .zip (%s)', $filesize) .'</div></a>';
}
echo '<a href="https://drive.google.com/drive/folders/1WFWZtrkUQ4FxDgPcGFmKNa__1Yc9kKLi?usp=drive_link"><div class="uk-alert">'. tl('download all NFT Chest') .'</div></a>';

echo '<div class="uk-grid images">';
if ($handle = opendir('images/NFTChest'))
{
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle)))
	{
		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
		{
			if( substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
			{	
				echo '<div class="uk-width-medium-1-2"><a href="images/NFTChest/'. $entry .'"><img src="images/NFTChest/'.$entry.'" alt="'.$entry.'" /></a></div>';
			}
		}
	}
}
echo '</div>';
/**NFT Chest */
/*Image */
echo '					<hr>

					<h2 id="images">'. tl('Hunters') .'</h2>';

if( file_exists("images/images.zip") )
{
	$filesize = filesize("images/images.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/images.zip"><div class="uk-alert">'. tl('download all screenshots & photos as .zip (%s)', $filesize) .'</div></a>';
}
echo '<a href="https://drive.google.com/drive/folders/1hic8KeAs30n4FRTehQq6uQus9IvLIHqX?usp=drive_link"><div class="uk-alert">'. tl('download all screenshots & photos') .'</div></a>';

echo '<div class="uk-grid images">';
if ($handle = opendir('images/Hunters'))
{
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle)))
	{
		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
		{
			if( substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
			{	
				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/'. $entry .'"><img src="images/Hunters/'.$entry.'" alt="'.$entry.'" /></a></div>';
			}
		}
	}
}
echo '</div>';
/*Image*/
/**Hunter Skins */
echo '					<hr>

					<h2 id="hunter-skins">'. tl('Hunter Skins') .'</h2>';

if( file_exists("images/images.zip") )
{
	$filesize = filesize("images/images.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/images.zip"><div class="uk-alert">'. tl('download all screenshots & photos as .zip (%s)', $filesize) .'</div></a>';
}
echo '<a href="https://drive.google.com/drive/folders/1ejxtsr9a-OZAsy3BwCdtKeuKeB-Pmf2-?usp=drive_link"><div class="uk-alert">'. tl('download all Hunter Skins') .'</div></a>';

echo '<div class="uk-grid images">';
if ($handle = opendir('images/Hunters/HunterSkins'))
{
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle)))
	{	
		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
		{
			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
			{	
				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
			}
		}
	}
}
echo '</div>';
/**Hunter Skins */

/**Cover and Screenshot */
echo '					<hr>

					<h2 id="cover-and-screenshot">'. tl('Cover and Screenshot') .'</h2>';

if( file_exists("images/images.zip") )
{
	$filesize = filesize("images/images.zip");
	if( $filesize > 1024 && $filesize < 1048576 ) {
		$filesize = (int)( $filesize / 1024 ).'KB';
	}
	if( $filesize > 1048576 ) {
		$filesize = (int)(( $filesize / 1024 ) / 1024 ).'MB';
	}

	echo '<a href="images/images.zip"><div class="uk-alert">'. tl('download all screenshots & photos as .zip (%s)', $filesize) .'</div></a>';
}

echo '<a href="https://drive.google.com/drive/folders/1eXmYam5LKLQAYnsIQaivLpVgB30gBEHV?usp=drive_link"><div class="uk-alert">'. tl('download all Cover and Screenshot') .'</div></a>';

echo '<div class="uk-grid images">';
if ($handle = opendir('images/CoverandScreenshot'))
{
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle)))
	{
		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
		{
			if( substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
			{	
				echo '<div class="uk-width-medium-1-2"><a href="images/CoverandScreenshot/'. $entry .'"><img src="images/CoverandScreenshot/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
			}
		}
	}
}
echo '</div>';
/**Cover and Screenshot */

/**Fonts */
echo '<hr>

	<h2 id="fonts">'. tl('Fonts') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1hJfG45-LHqtXIkKZIg1FXUAqB0bVPRSx?usp=drive_link"><div class="uk-alert">'. tl('download all Fonts') .'</div></a>';

/**Fonts*/

/**Ingame Screenshot */
echo '					<hr>

					<h2 id="in-game-screenshot">'. tl('Ingame Screenshot') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1AftaSJqjOggJZVgWaYhF5WnpDFBjftEC"><div class="uk-alert">'. tl('download all Ingame Screenshot') .'</div></a>';

echo '<div class="uk-grid images">';
// if ($handle = opendir('images/Hunters/HunterSkins'))
// {
// 	/* This is the correct way to loop over the directory. */
// 	while (false !== ($entry = readdir($handle)))
// 	{	
// 		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
// 		{
// 			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
// 			{	
// 				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
// 			}
// 		}
// 	}
// }
echo '</div>';
/**Ingame Screenshot */

/**Keyart and Cover */
echo '					<hr>

					<h2 id="keyart-and-cover">'. tl('Keyart and Cover') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1cFCUXsngk_WV6ynySQvp1fQIQs_2FBDG?usp=drive_link"><div class="uk-alert">'. tl('download all Keyart and Cover') .'</div></a>';

echo '<div class="uk-grid images">';
// if ($handle = opendir('images/Hunters/HunterSkins'))
// {
// 	/* This is the correct way to loop over the directory. */
// 	while (false !== ($entry = readdir($handle)))
// 	{	
// 		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
// 		{
// 			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
// 			{	
// 				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
// 			}
// 		}
// 	}
// }
echo '</div>';
/**Keyart and Cover */

/**Store Artworks */
echo '					<hr>

					<h2 id="store-artworks">'. tl('Store Artworks') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1cdyqqQm3LH5pTsFQySLeVT6NU1_zml7f?usp=drive_link"><div class="uk-alert">'. tl('download all Store Artworks') .'</div></a>';

echo '<div class="uk-grid images">';
// if ($handle = opendir('images/Hunters/HunterSkins'))
// {
// 	/* This is the correct way to loop over the directory. */
// 	while (false !== ($entry = readdir($handle)))
// 	{	
// 		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
// 		{
// 			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
// 			{	
// 				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
// 			}
// 		}
// 	}
// }
echo '</div>';
/**Store Artworks */

/**Throwables */
echo '					<hr>

					<h2 id="throwables">'. tl('Throwables') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1B1YJkROK7-B9LJu329a2shogoEsmX5tT?usp=drive_link"><div class="uk-alert">'. tl('download all Throwables') .'</div></a>';

echo '<div class="uk-grid images">';
// if ($handle = opendir('images/Hunters/HunterSkins'))
// {
// 	/* This is the correct way to loop over the directory. */
// 	while (false !== ($entry = readdir($handle)))
// 	{	
// 		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
// 		{
// 			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
// 			{	
// 				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
// 			}
// 		}
// 	}
// }
echo '</div>';
/**Throwables */

/**Weapons */
echo '					<hr>

					<h2 id="weapons">'. tl('Weapons') .'</h2>';

echo '<a href="https://drive.google.com/drive/folders/1yWmH3Rd1nCp_sNWCcMBfc3nlNZ6lBoDs?usp=drive_link"><div class="uk-alert">'. tl('download all Weapons') .'</div></a>';

echo '<div class="uk-grid images">';
// if ($handle = opendir('images/Hunters/HunterSkins'))
// {
// 	/* This is the correct way to loop over the directory. */
// 	while (false !== ($entry = readdir($handle)))
// 	{	
// 		if( substr($entry,-4) == ".png" || substr($entry,-4) == ".gif" )
// 		{
// 			if(substr($entry,0,3) != "TN_" && substr($entry,0,4) != "logo" && substr($entry,0,4) != "icon" && substr($entry,0,6) != "header" )
// 			{	
// 				echo '<div class="uk-width-medium-1-2"><a href="images/Hunters/HunterSkins/'. $entry .'"><img src="images/Hunters/HunterSkins/TN_'.$entry.'" alt="'.$entry.'" /></a></div>';
// 			}
// 		}
// 	}
// }
echo '</div>';
/**Weapons */

closedir($handle);

echo '					<hr>';

if( count( $awards ) > 0 )
{
	echo('<h2 id="awards">'. tl('Awards & Recognition') .'</h2>
					<ul>');

for( $i = 0; $i < count($awards); $i++ )
{
	$description = $info = "";

	foreach( $awards[$i]['award']->children() as $child )
	{
		if( $child->getName() == "description" ) {
			$description = $child;
		} else if( $child->getName() == "info" ) {
			$info = $child;
		}
	}

	echo '<li>"'.$description.'" - <cite>'.$info.'</cite></li>';
}

echo('</ul><hr>');
}

if( count($quotes) > 0 )
{
	echo '					<h2 id="quotes">'. tl('Selected Articles') .'</h2>
						<ul>';
	
	for( $i = 0; $i < count($quotes); $i++ )
	{
		$description = $name = $website = $link = "";
	
		foreach( $quotes[$i]['quote']->children() as $child )
		{
			if( $child->getName() == "description" ) {
				$description = $child;
			} else if( $child->getName() == "name" ) {
				$name = $child;
			} else if( $child->getName() == "website" ) {
				$website = $child;
			} else if( $child->getName() == "link" ) {
				$link = $child;
			}
		}
	
		echo '<li>"'.$description.'"<br/><cite>- '.$name.', <a href="'.parseLink($link).'/">'.$website.'</a></cite></li></li>';
	}
	
	echo '</ul><hr>';
}

if( count($additionals) > 0 ) {
	echo '<h2 id="links">'. tl('Additional Links') .'</h2>';

	for( $i = 0; $i < count($additionals); $i++ )
	{
		$title = $description = $link = "";
				
		foreach( $additionals[$i]['additional']->children() as $child )
		{
			if( $child->getName() == "title" ) {
				$title = $child;
			} else if( $child->getName() == "description" ) {
				$description = $child;
			} else if( $child->getName() == "link" ) {
				$link = $child;
			}
		}

		if( strpos(parseLink($link),'/') !== false ) {
			$linkTitle = substr(parseLink($link),0,strpos(parseLink($link),'/'));
		} else { $linkTitle = $link; }
		
		echo '<p>
		<strong>'.$title.'</strong><br/>
		'.$description.' <a href="'.parseLink($link).'" alt="'.parseLink($link).'">'.$link.'</a>.
	</p>';

	}

	echo '<hr>';
}

echo '					<div class="uk-grid">
						<div class="uk-width-medium-1-2">
							<h2 id="credits">'. tl('Team & Repeating Collaborators') .'</h2>';

for( $i = 0; $i < count($credits); $i++ )
{
	$previous = $website = $person = $role = "";
	foreach( $credits[$i]['credit']->children() as $child )
	{
		if( $child->getName() == "person" ) {
			$person = $child;
		} else if( $child->getName() == "previous" ) {
			$previous = $child;
		} else if( $child->getName() == "website" ) {
			$website = $child;
		} else if( $child->getName() == "role" ) {
			$role = $child;
		}
	}

	echo '<p>';
				
	if( strlen($website) == 0 )
	{
		echo '<strong>'.$person.'</strong><br/>'.$role;
	}
	else
	{
		echo '<strong>'.$person.'</strong><br/><a href="'.parseLink($website).'/">'.$role.'</a>';
	}

	echo '</p>';
}

echo '						</div>
						<div class="uk-width-medium-1-2">
							<h2 id="contact">'. tl('Contact') .'</h2>';

for( $i = 0; $i < count($contacts); $i++ )
{
	$link = $mail = $name = "";
	foreach( $contacts[$i]['contact']->children() as $child )
	{
		if( $child->getName() == "name" ) {
			$name = $child;
		} else if( $child->getName() == "link" ) {
			$link = $child;
		} else if( $child->getName() == "mail" ) {
			$mail = $child;
		}
	}

	echo '<p>';

	if( strlen($link) == 0 && strlen($mail) > 0 ) {
		echo '<strong>'.$name.'</strong><br/><a href="mailto:'.$mail.'">'.$mail.'</a>';
	}
	if( strlen($link) > 0 && strlen($mail) == 0 ) {
		echo '<strong>'.$name.'</strong><br/><a href="'.parseLink($link).'">'.parseLink($link).'</a>';
	}

	echo '</p>';
}

echo '						</div>
					</div>

					<hr>

				</div>
			</div>
		</div>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.js"></script>		
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.1.2/masonry.pkgd.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				var container = $(\'.images\');

				container.imagesLoaded( function() {
					container.masonry({
						itemSelector: \'.uk-width-medium-1-2\',
					});
				});
			});
		</script>';		
if ( defined("ANALYTICS") && strlen(ANALYTICS) > 10 )
{
	echo '<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push([\'_setAccount\', \'' . ANALYTICS . '\']);
	_gaq.push([\'_trackPageview\']);

	(function() {
		var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
		ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
		var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>';
}
echo'	</body>
</html>';

