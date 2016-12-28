<?php
	
	if(empty($_GET['id']) || empty($_GET['bt'])) die('<!-- Empty parameters -->');
	
	$id = sanitize($_GET['id']);
	$val = sanitize($_GET['val']);
	$bt = sanitize($_GET['bt']);
	
	$addthis_services = array("100zakladok", "2tag", "2linkme", "a1webmarks", "addio", "menu", "adfty", "adifni", "aerosocial", "allmyfaves", "amazonwishlist", "amenme", "aim", "aolmail", "arto", "aviary", "baang", "baidu", "bebo", "bentio", "biggerpockets", "bitly", "bizsugar", "bleetbox", "blinklist", "blip", "blogger", "bloggy", "blogmarks", "blogtrottr", "blurpalicious", "boardlite", "bobrdobr", "bonzobox", "bookmarkedbyus", "socialbookmarkingnet", "bookmarkycz", "bookmerkende", "bordom", "box", "brainify", "bryderi", "buddymarks", "buzzzy", "camyoo", "care2", "chiq", "cirip", "citeulike", "classicalplace", "clickazoo", "clply", "cndig", "colivia", "technerd", "connotea", "cosmiq", "delicious", "designbump", "designmoo", "digthiswebhost", "digaculturanet", "digg", "diggita", "diglog", "digo", "digzign", "diigo", "dipdive", "domelhor", "dosti", "dotnetkicks", "dotnetshoutout", "woscc", "douban", "drimio", "dropjack", "dwellicious", "dzone", "edelight", "efactor", "ekudos", "elefantapl", "email", "mailto", "embarkons", "eucliquei", "evernote", "extraplay", "ezyspot", "fabulously40", "facebook", "informazione", "fark", "farkinda", "fashiolista", "fashionburner", "favable", "faves", "favlogde", "favoritende", "favorites", "favoritus", "flaker", "flosspro", "folkd", "followtags", "forceindya", "thefreedictionary", "fresqui", "friendfeed", "friendster", "funp", "fwisp", "gabbr", "gacetilla", "gamekicker", "givealink", "globalgrind", "gmail", "goodnoows", "google", "googlebuzz", "googlereader", "googletranslate", "gravee", "greaterdebater", "grono", "grumper", "habergentr", "hackernews", "hadashhot", "hatena", "hazarkor", "gluvsnap", "hedgehogs", "hellotxt", "hipstr", "hitmarks", "hotbookmark", "hotklix", "hotmail", "w3validator", "hyves", "idearef", "identica", "igoogle", "ihavegot", "instapaper", "investorlinks", "iorbix", "isociety", "iwiw", "jamespot", "jisko", "joliprint", "jumptags", "zooloo", "kaboodle", "kaevur", "kipup", "kirtsy", "kledy", "kommenting", "latafaneracat", "laaikit", "ladenzeile", "librerio", "linkninja", "linkagogo", "linkedin", "linksgutter", "linkshares", "linkuj", "livefavoris", "livejournal", "lockerblogger", "logger24", "lynki", "mymailru", "markme", "mashbord", "mawindo", "meccho", "meinvz", "mekusharim", "memori", "meneame", "live", "mindbodygreen", "misterwong", "misterwong_de", "mixx", "moemesto", "mototagz", "mrcnetworkit", "multiply", "myaol", "mylinkvault", "myspace", "n4g", "netlog", "netvibes", "netvouz", "newsmeback");
	
	$sharethis_services = array("adfty", "allvoices", "amazon_wishlist", "arto", "baidu", "bebo", "blinklist", "blip", "blogmarks", "blogger", "brainify", "buddymarks", "buffer", "bus_exchange", "care2", "chiq", "citeulike", "connotea", "corank", "corkboard", "current", "dealsplus", "delicious", "digg", "diigo", "dotnetshoutout", "dzone", "edmodo", "evernote", "email", "facebook", "fblike", "fbrec", "fbsend", "fark", "fashiolista", "folkd", "formspring", "fresqui", "friendfeed", "funp", "fwisp", "google", "googleplus", "google_bmarks", "google_reader", "google_translate", "hatena", "hyves", "identi", "instapaper", "jumptags", "kaboodle", "linkagogo", "linkedin", "livejournal", "mail_ru", "meneame", "messenger", "mister_wong", "mixx", "moshare", "myspace", "n4g", "netlog", "netvouz", "newsvine", "nujij", "odnoklassniki", "oknotizie", "orkut", "plusone", "pinterest", "raise_your_voice", "reddit", "segnalo", "sharethis", "sina", "slashdot", "sonico", "speedtile", "startaid", "startlap", "stumbleupon", "stumpedia", "technorati", "typepad", "tumblr", "twitter", "viadeo", "virb", "vkontakte", "voxopolis", "wordpress", "xanga", "xerpi", "xing", "yammer", "yigg");

// Clean the GET variables.
function sanitize($input) {
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);
	
	$output = htmlspecialchars(preg_replace($search, '', $input));
	return $output;
} // Thanks to CSS Tricks
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Select the services</title>
<link rel="stylesheet" href="wpsr-admin-mini-css.css" type="text/css" media="all" /> 

<script language="JavaScript" type="text/javascript">
<!--

var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);

function updateAndClose(val, id){
	opener.document.getElementById(id).value = val;
	window.close();
}

function addOption(theSel, theText, theValue){
	var newOpt = new Option(theText, theValue);
	var selLength = theSel.length;
	theSel.options[selLength] = newOpt;
	loopSelected();
}

function deleteOption(theSel, theIndex){ 
	var selLength = theSel.length;
	if(selLength>0){
		theSel.options[theIndex] = null;
	}
	loopSelected();
}

function moveOptions(theSelFrom, theSelTo){
  
	var selLength = theSelFrom.length;
	var selectedText = new Array();
	var selectedValues = new Array();
	var selectedCount = 0;
	
	var i;
	
	// Find the selected Options in reverse order
	// and delete them from the 'from' Select.
	for(i=selLength-1; i>=0; i--){
		if(theSelFrom.options[i].selected){
			selectedText[selectedCount] = theSelFrom.options[i].text;
			selectedValues[selectedCount] = theSelFrom.options[i].value;
			deleteOption(theSelFrom, i);
			selectedCount++;
		}
	}
  
	// Add the selected text/values in reverse order.
	// This will add the Options to the 'to' Select
	// in the same order as they were in the 'from' Select.
	for(i=selectedCount-1; i>=0; i--){
		addOption(theSelTo, selectedText[i], selectedValues[i]);
	}
	
	loopSelected();
}

function moveUp(lst){
	if(lst.selectedIndex == -1){
		alert('Please select an Item to move up.');
	}else{
		if(lst.selectedIndex == 0){
			alert('First element cannot be moved up');
			return false
		}else{
			var tempValue = lst.options[lst.selectedIndex].value; 
			var tempIndex = lst.selectedIndex-1; 
			lst.options[lst.selectedIndex].value = lst.options[lst.selectedIndex-1].value; 
			lst.options[lst.selectedIndex-1].value = tempValue; 
			var tempText = lst.options[lst.selectedIndex].text; 
			lst.options[lst.selectedIndex].text = lst.options[lst.selectedIndex-1].text; 
			lst.options[lst.selectedIndex-1].text = tempText; 
			lst.selectedIndex = tempIndex;
			loopSelected();
		}
	} 
	return false;
}

function moveDown(lst){
	if(lst.selectedIndex == -1){
		alert('Please select an Item to move down');
	}else{
		if(lst.selectedIndex == lst.options.length-1){
			alert('Last element cannot be moved down'); 
		}else{ 
			var tempValue = lst.options[lst.selectedIndex].value; 
			var tempIndex = lst.selectedIndex+1; 
			lst.options[lst.selectedIndex].value = lst.options[lst.selectedIndex+1].value; 
			lst.options[lst.selectedIndex+1].value = tempValue; 
			var tempText = lst.options[lst.selectedIndex].text; 
			lst.options[lst.selectedIndex].text = lst.options[lst.selectedIndex+1].text; 
			lst.options[lst.selectedIndex+1].text = tempText; 
			lst.selectedIndex = tempIndex;
			loopSelected();
		} 
	} 
	return false;
}

function loopSelected(){
	var txtSelectedValuesObj = document.getElementById('services');
	var selectedArray = new Array();
	var selObj = document.getElementById('sel2');
	var i;
	var count = 0;
	for (i=0; i<selObj.options.length; i++) {
		if (selObj.options[i].value) {
			selectedArray[count] = selObj.options[i].value;
			count++;
		}
	}
	txtSelectedValuesObj.value = selectedArray;
}

//http://www.mredkj.com/tutorials/tutorial004.html-->
</script>

</head>

<body>
<div id="wrapper">
  <h2>Select the services</h2>
  <small>Use Control key to select multiple services.</small>
  <form>
<table width="100%" border="0">
	<tr>
		<td width="44%">
			<select name="sel1" id="sel1" size="20" multiple="multiple" style="width:100%">
			<?php 
				switch($bt){
					case 'addthis':
						foreach($addthis_services as $srvc){
							echo "<option value='$srvc'>$srvc</option>";
						}
					break;
					
					case 'sharethis':
						foreach($sharethis_services as $srvc){
							echo "<option value='$srvc'>$srvc</option>";
						}
					break;
				}
			?>
</select>		</td>
		<td width="11%" align="center" valign="middle">
			<p>
			  <input type="button" value="--&gt;" onclick="moveOptions(this.form.sel1, this.form.sel2);" title="Add" />
			  <br />
			    <input type="button" value="&lt;--" onclick="moveOptions(this.form.sel2, this.form.sel1);" title="Remove" />
		  </p>
		  <br />
			<p>
			    <input type="button" value=" Up " onclick="moveUp(this.form.sel2);" title="Up" />
              <br />
			    <input type="button" value="Down" onclick="moveDown(this.form.sel2);" title="Down" />		
          </p>
		</td>
		<td width="45%">
			<select name="sel2" id="sel2"  size="20" multiple="multiple" style="width:100%">
				<?php 
					$selVal = $val;
					if($selVal != ''){
						$expSel = explode(',', $selVal);
						foreach ($expSel as $eSel){
							echo "<option value='$eSel'>$eSel</option>";
						}
					}
					
				?>
		    </select>
		</td>
      </tr>
</table>

<br/>

<h3>Selected services:</h3><br/>
<p><input name="services" type="text" id="services" value="<?php echo $val; ?>" size="40"/>
<input type="hidden" id="targetId" name="targetId" value="<?php echo $id; ?>"/></p>

</form>

<p align="center">
<input type="button" id="updateBt" onclick="updateAndClose(document.getElementById('services').value, document.getElementById('targetId').value);" value="Update" /><br/><br/>
<a href="http://www.aakashweb.com/" target="_blank">Aakash Web</a> | <a href="http://bit.ly/wpsrDonation" target="_blank">Donate</a> | <a href="http://www.aakashweb.com/wordpress-plugins/wp-socializer/" target="_blank">Help</a>
</p>

</div>

</body>
</html>