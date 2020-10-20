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
require_once './BestelltePizzas.php';
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
class Kunde extends Page
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
    protected function getViewData($BestellungID)
    {
        //$letzteBestellung = $this->_database->query("SELECT MAX(fBestellungID) FROM `bestelltepizza`");
        //$letzteBestellung = $this->_database->insert_id;
        
        $bestelltePizzas = $this->_database->query("SELECT Bestellstatus, PizzaName FROM `bestelltepizza`,`angebot`,`bestellung` WHERE bestelltepizza.fBestellungID = bestellung.BestellungID AND bestelltepizza.fPizzaNummer = angebot.PizzaNummer AND bestellung.BestellungID = $BestellungID;");
        //var_dump($bestelltePizzas);
        if(!$bestelltePizzas)
            throw new Exception("Query failed:" .$_database->error);
        $ergebnis = [];
//         if($this->_database->query($bestelltePizzas) === true){
//                echo "Records inserted successfully.";
//        } else{
//               // echo "ERROR: Could not be able to execute $bestelltePizzas. " . $mysqli->error;
//        }
        while($item = $bestelltePizzas->fetch_assoc()){
          /*  $pizzaname = $item["PizzaName"];
            $pfad = $item["Bilddatei"];
           $preis = $item["Preis"];
            $this->ergebnis[]
            */
            $pizzaname = $item["PizzaName"];
            $bestellstatus = $item["Bestellstatus"];
            if(!$pizzaname)
                throw new Exception("Query failed:" .$_database->error);
            array_push($ergebnis, new BestelltePizzas($pizzaname,$bestellstatus));
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
        if(isset($_SESSION['BestellungID'])){
            $ergebnis = $this->getViewData($_SESSION['BestellungID']);
        }
        $this->generatePageHeader('Lieferstatus');
        echo <<< HTML
<body>
    <nav class="verlinkung" id="verlinkung">
        <a href="Bestellung.php">Bestellung</a>
        <a href="Baecker.php">Bäcker</a>
        <a href="Fahrer.php">Fahrer</a>
        <a href="Kunde.php">Kunde</a>
    </nav>
    <img id="logo" src="logo.png" alt="logoGrazieRagazzi">
    <h1>Lieferstatus</h1>
    <h2> Ihre Bestellung </h2>
    <div class = "item">
HTML;
foreach($ergebnis as $item){
echo <<<HTML
        <div class="text"><b>Pizza</b>: $item->pizzaname</div>
        <br>
HTML;
}
echo <<<HTML
    <div class="text"><b>Status der Bestellung</b>:  </div>
    <div class="bstatus" id="bstatus">$item->bestellstatus</div>
    <br>
    <input type="button" value="Neue Bestellung" onclick="parent.location='Bestellung.php'">
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
            session_start();
            $page = new Kunde();
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
Kunde::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >