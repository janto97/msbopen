<html>
<head>
<link rel="stylesheet" href="open.css">
</head>

<body>


<?php 


/**
 * Todo-List:
 * - Support for more than the current day
 * - Validation of clients
 *       An easy way may is the encryption of the transmitted data (incudes the current date not just hours and minutes (otherwise I can build a rainbow table with all hours an minutes stamp)) with aes.
 */

////////////////////////////////////////////////////////////////////////
// Function and varibale definition
////////////////////////////////////////////////////////////////////////

if(file_exists("msbopen.txt"))
{
    $fileContent = file_get_contents("msbopen.txt");
    if($fileContent == null || strtotime($fileContent) == null )
    {
        $fileContent = 0;
    }
}
else
{
    $fileContent = 0;
}


/**
 * Retruns a bool if the makespace bonn is open.
 * 
 * Parse the input string, get the timestamp and the current timestamp and compare them.
 * 
 * @param string $fileContent
 * @return bool makerspace is open = true | makerspace is open = false
 */
function isMsbOpen($fileContent)
{
    if(strtotime($fileContent) > time())
    {
        return true;
    }
    return false;
}

////////////////////////////////////////////////////////////////////////
// Receiving of Data and store them
////////////////////////////////////////////////////////////////////////
if(
    isset($_GET["msbopenhour"]) &&
    isset($_GET["msbopenminute"])
)
{
    // data vaidation
    if(
        $_GET["msbopenhour"] != "" &&
        $_GET["msbopenminute"] != "" &&
        is_numeric($_GET["msbopenhour"]) &&
        is_numeric($_GET["msbopenminute"]) &&
        $_GET["msbopenhour"] >= 0 && $_GET["msbopenhour"] <=23 &&
        $_GET["msbopenminute"] >= 0 && $_GET["msbopenminute"] <= 59
    )
    {
        $newData = date("d.m.Y") . " " . $_GET["msbopenhour"] . ":" . $_GET["msbopenminute"];
        file_put_contents("msbopen.txt", $newData);
        $fileContent = $newData;
    } 
}

////////////////////////////////////////////////////////////////////////
// Oputput on website
////////////////////////////////////////////////////////////////////////
if(isMsbOpen($fileContent))
{
    // output if msb is open
    echo '<span class="css-ampel ampelgruen"><span></span></span>' . "\n" ;    
    echo '<p></p>';
    echo "Der Makerspace ist offen bis " . $fileContent;
    echo '<p>Das Modul ist noch in der Testphase</p>' . "\n";

}
else
{
    echo '<span class="css-ampel ampelrot"></span>' . "\n" ;    
//    echo "Der Makerspace ist geschlossen" . "\n" ; 
    echo '<p>Das Modul ist noch in der Testphase</p>' . "\n" ;

   
    // Things to do, when msb is currently not open
}

// echo '<span class="css-ampel ampelgruen"><span></span></span>' ;


?>

</body>
</html>