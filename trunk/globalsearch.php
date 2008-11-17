<?php
$siteName = "Policy Search";
$searchURL = "/index.php";
$searchPhase = "Home";
$showSearch = "Y";



//configuration settings for amberfish search system.
$dbPath = "/usr/local/lib/amberfish/";  //path to amberfish databases
$afPath = "/usr/local/bin/af";  //path to amberfish program
//$pdfPath = "/pp/";  //path to the pdf files on the webserver relative to web root could also be /forms/pdf/
$pdftotext = "/usr/bin/pdftotext -layout -htmlmeta"; //command line call for pdftotext program


//First search the policies
$pdfPath = "/pp/";  //path to the pdf files on the webserver relative to web root could also be /forms/pdf/
$DB .= "-d $dbPath".'resp'." "."-d $dbPath".'radiation'." "."-d $dbPath".'rad'." "."-d $dbPath".'hr'." "."-d $dbPath".'lab'." "."-d $dbPath".'lab'." "."-d $dbPath".'pharm'." "."-d $dbPath".'ptreg'." "."-d $dbPath".'ptcare'." "."-d $dbPath".'path'." "."-d $dbPath".'mss'." "."-d $dbPath".'mm'." "."-d $dbPath".'housekeep'." "."-d $dbPath".'eoc'." "."-d $dbPath".'corp'." "."-d $dbPath".'c12'." "."-d $dbPath".'board'." "."-d $dbPath".'er'." "."-d $dbPath".'is'."";





if ($_POST['search'] == 'Search' or $_GET['search'] == 'Search') {
	if ($_POST['theSearch'] != '') {
		 $theSearch = "'".$_POST['theSearch']."'";
	} else {
		 $theSearch = "'".$_GET['theSearch']."'";
	}
	
	//echo "$afPath $DB -sQ '$theSearch'<p>";
	exec("$afPath $DB -sQ '$theSearch'",$resultsArray,$return_val);



foreach ($resultsArray as $value) {
$theLine = explode(" ",$value);
if ($theLine[4] != '') {
		$filename = $theLine[4];
		$infoString = getMeta($filename);
		$dbPath = pathinfo($theLine[1]);
		$dbPath = $dbPath['basename'];
		$pathParts = pathinfo($theLine[4]);
		$pdfName =  preg_replace("/\.html/", ".pdf", $pathParts['basename']);
		$deptName = depNames($dbPath);
		if ($infoString == '') {$infoString = $pdfName;} //waht to do if title is not found.
		$ppResults .= "<!-- rowstart --><tr><td>$theLine[0]</td><td><a href=\"$pdfPath$dbPath/$pdfName\">$infoString</a></td><td>$deptName</td></tr><!-- rowend -->\n";
 
 
}

}






//Now search the forms
unset($DB,$resultsArray);
//configuration settings for amberfish search system.
$dbPath = "/usr/local/lib/amberfish/";  //path to amberfish databases
$afPath = "/usr/local/bin/af";  //path to amberfish program
$pdftotext = "/usr/bin/pdftotext -layout -htmlmeta"; //command line call for pdftotext program
$pdfPath = "/forms/";  //path to the pdf files on the webserver relative to web root could also be /forms/pdf/

$DB .= "-d $dbPath".'clinicalforms'." "."-d $dbPath".'generalforms'." "."-d $dbPath".'hrforms'." " . "-d $dbPath".'isforms'." ". "-d $dbPath".'isforms'." ". "-d $dbPath".'docsforms'." ". "-d $dbPath".'dischargeforms'." ". "-d $dbPath".'eecpforms'." ". "-d $dbPath".'giforms'." ". "-d $dbPath".'jobdescforms'." ". "-d $dbPath".'ladderforms'." ". "-d $dbPath".'mdprivforms'." ". "-d $dbPath".'medstaffforms'." ". "-d $dbPath".'pganeyforms'." ". "-d $dbPath".'rnactivityforms'." ". "-d $dbPath".'rncrossforms'." ". "-d $dbPath".'standing_ordforms'." ";

	
	//echo "$afPath $DB -sQ '$theSearch'<p>";
	exec("$afPath $DB -sQ '$theSearch'",$resultsArray,$return_val);



foreach ($resultsArray as $value) {
$theLine = explode(" ",$value);
if ($theLine[4] != '') {
		$filename = $theLine[4];
		$infoString = getMeta($filename);
		$dbPath = pathinfo($theLine[1]);
		$dbPath = $dbPath['basename'];
		$dbPath = preg_replace("/forms/","",$dbPath);
		$pathParts = pathinfo($theLine[4]);
		$pdfName =  preg_replace("/\.html/", ".pdf", $pathParts['basename']);
		$deptName = depNames($dbPath);
		if ($infoString == '') {$infoString = $pdfName;} //waht to do if title is not found.
		$formsResults .= "<!-- rowstart --><tr><td>$theLine[0]</td><td><a href=\"$pdfPath$dbPath/$pdfName\">$infoString</a></td><td>$deptName</td></tr><!-- rowend -->\n";
 
 
}

}


}


//get the document title
function getTitle($pdfFile) {
	preg_match("/\/Title *\(([A-Za-z0-9 ]*)\)/i",$pdfFile,$matches);
	return $matches[1];
	
}

function getHTMLTitle($pdfFile) {
	preg_match("/<title>(.*)<\/title>/i",$pdfFile,$matches);
	return $matches[1];
	
	
	}

//get the author
function getAuthor($pdfFile) {
	preg_match("/\/Author *\(([A-Za-z0-9 ]*)\)/i",$pdfFile,$matches);
	return $matches[1];
	
}

//get the subject
function getSubject($pdfFile) {
	preg_match("/\/Subject *\(([A-Za-z0-9 ]*)\)/i",$pdfFile,$matches);
	return $matches[1];
	
}

//get the keywords
function getKeywords($pdfFile) {
	preg_match("/\/Keywords *\(([A-Za-z0-9 ]*)\)/i",$pdfFile,$matches);
	return $matches[1];
	
}


function getMeta($filename) {

$pdfFile = file_get_contents("$filename"); // put the content into a varriable

		
		
		$title = getHTMLTitle($pdfFile);
		//$author =  getAuthor($pdfFile);
		//$subject =  getSubject($pdfFile);
		//$keywords =  getKeywords($pdfFile);
		
		$infoString = "$title";
		return $infoString;

}

function depNames($dbname) {
	switch($dbname) {
		case "general":
		$deptName = "General Forms";
		break;
		case "clinical":
		$deptName = "Clinical Forms";
		break;
		case "hr":
		$deptName = "Human Resources Forms";
		break;
		case "is":
		$deptName = "Information Services Forms";
		break;
				case "docs":
		$deptName = "General Documents";
		break;
				case "standing_ord":
		$deptName = "Preprinted Orders";
		break;
				case "rncross":
		$deptName = "RN Cross Training Forms";
		break;
				case "rnactivity":
		$deptName = "RN Activity Forms";
		break;
				case "pganey":
		$deptName = "Preceptor";
		break;
				case "medstaff":
		$deptName = "Medical Staff Forms";
		break;
				case "mdpriv":
		$deptName = "MD Priveleges";
		break;
				case "ladder":
		$deptName = "Ladder Forms";
		break;
				case "jobdesc":
		$deptName = "Job Descriptions";
		break;
				case "gi":
		$deptName = "GIs Forms";
		break;
		case "eecp":
		$deptName = "Employee Health Forms";
		break;
		case "discharge":
		$deptName = "Discharge Forms";
		break;
		case "newsletters":
		$deptName = "Newsletters";
		break;
		case "propose":
		$deptName = "Proposed";
		break;
		case "board":
		$deptName = "Board of Directors";
		break;
		case "c12":
		$deptName = "C12 (Purchasing)";
		break;
		case "corp":
		$deptName = "Corporate Compliance";
		break;
		case "eecp":
		$deptName = "Employee Health/Infection Control";
		break;
		case "eoc":
		$deptName = "Environment of Care";
		break;
		case "housekeep":
		$deptName = "Housekeeping";
		break;
		case "mm":
		$deptName = "Materials Management";
		break;
		case "mss":
		$deptName = "Medical Staff";
		break;
		case "path":
		$deptName = "Pathology";
		break;
		case "ptcare":
		$deptName = "Patient Care";
		break;
		case "ptreg":
		$deptName = "Patient Registration";
		break;
		case "pharm":
		$deptName = "Pharmacy";
		break;
		case "rad":
		$deptName = "Radiology";
		break;
		case "hr":
		$deptName = "Human Resources";
		break;
		case "lab":
		$deptName = "Laboratory";
		break;
		case "radiation":
		$deptName = "Radiation Safety";
		break;
		case "resp":
		$deptName = "Respiratory";
		break;
		//start of forms area.All Departments
		case "general":
		$deptName = "General";
		break;
		case "clinical":
		$deptName = "Clinical";
		break;
		case "discharge":
		$deptName = "Discharge";
		break;
		case "jobdesc":
		$deptName = "Job Description";
		break;
		case "pgangy":
		$deptName = "Satifaction Survey";
		break;
		case "docs":
		$deptName = "Documents";
		break;
		case "ladder":
		$deptName = "Ladder";
		break;
		case "rnactivity":
		$deptName = "RN Activity";
		break;
		case "hr":
		$deptName = "Human Resources";
		break;
		case "ar":
		$deptName = "Accounts Receivable";
		break;
		case "eecp":
		$deptName = "Employee Health";
		break;
		case "mdpriv":
		$deptName = "MD Privleges";
		break;
		case "rncross":
		$deptName = "RN Cross Training";
		break;
		case "is":
		$deptName = "Information Services";
		break;
		case "gi":
		$deptName = "GI";
		break;
		case "medstaff":
		$deptName = "Medical Staff";
		break;
		case "standing_ord":
		$deptName = "Pre-Printed Orders";
		break;
		case "er":
		$deptName = "Emergency";
		break;
		case "is":
		$deptName = "Information Services";
		break;

		default:
		$deptName = "";
		break;
		
	}
	return $deptName;
}
?>

<head>
<LINK REL=StyleSheet HREF="/css/wmc.css" TYPE="text/css">
<title>Search Policies</title>
<style>
	@import url(/css/wmc.css);

</style>

</head>
<body >
<?php include $_SERVER['DOCUMENT_ROOT']."/includes/head.php" ?>

<div class="form">
<p>
<form action="globalsearch.php" method="post" name="searchForm" id="searchForm">
<input type="hidden" name="all" value="all">
<h3>Enter search terms</h3>
<input type="text" name="theSearch" size="20"> <input type="submit" name="search" value="Search">
</p>

</form>
</div>
<div style="width : 500px;">
<h3><a name="policies">Search Results Policies</a> [<a href="#forms">Go to forms</a>]</h3>
<table width="100%">
<tr><th>Score</th><th>Title</th><th>Department</th></tr>
<!-- tableStart --><?php echo $ppResults; ?><!-- tableend -->
</table>

<h3><a name="forms">Search Results Forms [<a href="#policies">Go to policies</a>]</h3>
<table width="100%">
<tr><th>Score</th><th>Title</th><th>Department</th></tr>
<!-- tableStart --><?php echo $formsResults; ?><!-- tableend -->
</table></div></p>

<div class="instructions">
<h3>Instructions</h3>
<p>You can view all documents for any department by clicking the department name (Blue, underlined link).</p>
<p>You can also use this form to search policies from all, various, or only one department. Please follow the steps below:</p>
<ol>
<li>Enter your search term in the text field.</li>
<li>Select the department(s) to search in from the checkbox list of departments.</li>
<li>Push the <strong>Search</strong> button.</li>
</ol>
<p>The search engine can perform a number of different kinds of searches. Sample are given below:<br />
<ul>
<li>By placing &#038; between words you will get an AND statement.<br />For example <code>cat &#038; mouse</code> would find documents with both cat and mouse in the document.</li>
<li>By placing a | between the word you can do OR searches.<br />For example <code>cat | mouse</code> would find documents with either the word cat or mouse or both cat and mouse</li>
<li>If you place a * at the end of a word them Amberfish will find all the words that begin with the letters before the *.<br />For example <code>car*</code> would find car, cars or carpet.</li>
</ul>




</ul></p></div>

<p align="center"><img src="/art/amberfish.png" alt="Amberfish logo" width="100" height="40" border="0" /></p>
</body>
</html>
