<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 * @license  http://www.h-da.de  none 
 * @Release  1.2 
 * @link     http://www.fbi.h-da.de 
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';
require_once './BaeckerPizzas.php';

/**
 * This is a template for top level classes, which represent 
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class. 
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */
class Baecker extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        $baeckerpizzas = $this->_database->query("SELECT angebot.PizzaName, bestelltePizza.fBestellungID, bestellung.Bestellstatus FROM bestellung INNER JOIN bestelltepizza ON bestellung.BestellungID = bestelltepizza.fBestellungID INNER JOIN angebot ON angebot.PizzaNummer = bestelltepizza.fPizzaNummer");
        if(!$baeckerpizzas){
            throw new Exception("Query failed:" .$_database->error);
        }
        $ergebnis = [];
        while($item = $baeckerpizzas->fetch_assoc()){
            $bestellungpizzas = [];
            $bestellungIDvonPizza = $item['fBestellungID'];
            $pizzas = $this->_database->query("SELECT angebot.PizzaName FROM bestelltepizza, angebot WHERE bestelltepizza.fBestellungID = '$bestellungIDvonPizza' AND bestelltepizza.fPizzaNummer = angebot.PizzaNummer");
            if(!$pizzas){
                throw new Exception("Query failed:" .$_database->error);
            }
            while ($i = $pizzas->fetch_assoc()) {
                array_push($bestellungpizzas, $i['PizzaName']);
            }
            array_push($ergebnis, new BaeckerPizzas($bestellungpizzas, $item['fBestellungID'], $item['Bestellstatus']));
        }
        return $ergebnis;
        // to do: fetch data for this view from the database
    }
    
    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() 
    {
        
        $ergebnis = $this->getViewData();
        $this->generatePageHeader('Bäcker');
        echo <<<HTML
<body>
    <nav class="verlinkung" id="verlinkung">
        <a href="Bestellung.php">Bestellung</a>
        <a href="Baecker.php">Bäcker</a>
        <a href="Fahrer.php">Fahrer</a>
        <a href="Kunde.php">Kunde</a>
    </nav>
    <img id="logo" src="logo.png" alt="logoGrazieRagazzi">
    <h1>Bäcker</h1>
	
HTML;
$i = 0;
$temp = 0;
foreach($ergebnis as $item){
    $pizzastatus = $item->status;
    if($pizzastatus == "Unterwegs" || $pizzastatus == "Geliefert"){ //Wegen Anforderung fragen
        continue;
    }    
    $i++;
    $pizzasAusgabe = "";
    foreach($item->pizzas as $p) {
        $pizzasAusgabe .= $p . ", ";
    }
    if($temp == $item->bestellungid){
        continue;
    }
    $pizzasAusgabe = substr($pizzasAusgabe, 0, -2);
    $temp = $item->bestellungid;
echo <<<HTML
        <div class = "alltext">
            <form action="Baecker.php" method="post" id="statusform$i">
                <div class="text"><b>Bestellung:</b> $item->bestellungid   <b>Pizzas:</b> $pizzasAusgabe <b>Status:</b> $item->status</div>
                <input type="radio" name="status" id="bestellt" value="Bestellt" checked onclick="document.forms['statusform$i'].submit();">
                <label for="Bestellt">Bestellt</label>
                <input type="radio" name="status" id="imOfen" value="Im Ofen" onclick="document.forms['statusform$i'].submit();">
                <label for="ImOfen">Im Ofen</label>
                <input type="radio" name="status" id="fertig" value="Fertig" onclick="document.forms['statusform$i'].submit();">
                <label for="fertig">Fertig</label>
                <input type="hidden" id="bid" name="bid" value="$item->bestellungid">
            </form>
HTML;
//var_dump($item);
}
echo <<< HTML
    </div>
</body>
</html>
HTML;
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generatePageFooter();
    }
    
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here. 
     * If the page contains blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        if($this->_database == false){
            die("ERROR: Could not connect.". $this->_database->connect_error);
        }
        if(sizeof($_POST) > 0){
            $Status = $this->_database->real_escape_string($_REQUEST['status']);
            $BestellungID = $this->_database->real_escape_string($_REQUEST['bid']);
            $sql = "UPDATE `bestellung` SET Bestellstatus = '$Status' WHERE bestellung.BestellungID = '$BestellungID'";
            if($this->_database->query($sql) === true){
                echo "Records inserted successfully.";
            } else{
                echo "ERROR: Could not be able to execute $sql. " . $mysqli->error;
            }
        }
        
        // to do: call processReceivedData() for all members
    }

    /**
     * This main-function has the only purpose to create an instance 
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none 
     */    
    public static function main() 
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Baecker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >