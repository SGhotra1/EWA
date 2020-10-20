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
require_once './angebot.php';

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
class Bestellung extends Page
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
        $angebotitems = $this->_database->query("SELECT * FROM angebot");
        if(!$angebotitems)
            throw new Exception("Query failed:" .$_database->error);
        $ergebnis = [];
        while($item = $angebotitems->fetch_assoc()){
          /*  $pizzaname = $item["PizzaName"];
            $pfad = $item["Bilddatei"];
           $preis = $item["Preis"];
            $this->ergebnis[]
            */
            array_push($ergebnis, new Angebot($item['PizzaName'], $item['Bilddatei'], $item['Preis']));
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
        $this->generatePageHeader('Bestellung');
        echo <<< HTML
<body>
    <nav class="verlinkung" id="verlinkung">
        <a href="Bestellung.php">Bestellung</a>
        <a href="Baecker.php">Bäcker</a>
        <a href="Fahrer.php">Fahrer</a>
        <a href="Kunde.php">Kunde</a>
    </nav>
    <img id="logo" src="logo.png" alt="logoGrazieRagazzi">
    <h1>Bestellung</h1>
  
    
	<h2>Speisekarte</h2>
	<div class ="speisekarte">
HTML;
foreach($ergebnis as $item){
    $pizpar = htmlspecialchars(json_encode($item));
echo <<<HTML
    <div class = "item">
        <div class="text">$item->pizzaname</div>
        <div class="price">$item->preis €</div>
         <div class="picture"><img src="$item->pfad" width="150" alt="$item->pizzaname" onclick="hinzufuegenWarenkorb('$pizpar')"></div>
    </div>
    
HTML;
}
$sizeergebnis = sizeof($ergebnis);
echo <<<HTML
    </div>
    <form action="Bestellung.php" method="post" accept-charset="UTF-8">
        <div class ="boxes">
        <fieldset id="warenkorbfield">
	        <h3>Warenkorb</h3>
            <select name="Pizzas[]" id="warenkorb" size="7" tabindex="1" multiple>
            </select>
            <br>
            <input type="button" value="Pizza aus Warenkorb entfernen" id="pizzaloeschen" onclick="entfernenWarenkorb()">
            <input type="button" value="Warenkorb leeren" id="warenkorbleeren" onclick="leereWarenkorb()">
            <br>
            <div class = "gesamtPreis" id="gesamtPreis">Gesamtpreis: </div>
        </fieldset>
    <br>
        <fieldset id="eingaben">
            <legend>Eingaben für die Bestellung</legend>
            Name:
            <br>
            <input type="text" name="Name" value="" id="name" required>
            <br>
            Adresse:
            <br>
            <input type="text" name="Adresse" value="" id="adresse" required >
            <br>
            Postleitzahl:
            <br>
            <input pattern="[0-9]{5}" name="plz" id="plz" title="Fünfstellige Postleitzahl in Deutschland." value="" required>
            <br>
            <br>
            <input type="reset">
            <input type="submit" value="Bestellen" id="bestellen" onclick="bestellungSelectAll()">
        </fieldset>
        </div>
    </form>
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
        if($this->_database == false){
            die("ERROR: Could not connect.". $this->_database->connect_error);
        }
        if(sizeof($_POST) > 0){
            //var_dump($_POST);
            if((!isset($_POST['Name'])) || (!isset($_POST['Adresse'])) || (!isset($_POST['plz'])) || (sizeof($_POST['Pizzas']) < 0)){
                throw new Exception("Eingaben ungültig");
            }
            $Name = $this->_database->real_escape_string($_REQUEST['Name']);
            $Adresse = $this->_database->real_escape_string($_REQUEST['Adresse']);
            $plz = $this->_database->real_escape_string($_REQUEST['plz']);
        
            
            $sql = "INSERT INTO `bestellung` (Adresse, KundeName, Postleitzahl, Bestellstatus) VALUES ('$Adresse', '$Name', '$plz', 'Bestellt')";
            if($this->_database->query($sql) === true){
                echo "Records inserted successfully.";
            } else{
                echo "ERROR: Could not be able to execute $sql. " . $mysqli->error;
            }
            $BestellungID = $this->_database->insert_id; 
            $_SESSION['BestellungID'] = $BestellungID;
            foreach(($_POST['Pizzas']) as $PizzaBestellung){
                $PizzaBestellung = $this->_database->real_escape_string($PizzaBestellung);
                $sql = "INSERT INTO `bestelltepizza` (fBestellungID, fPizzaNummer) VALUES(($BestellungID), (SELECT PizzaNummer FROM `angebot` WHERE angebot.PizzaName = '$PizzaBestellung'));";
                 if($this->_database->query($sql) === true){
                    echo "Records inserted successfully.";
            } else{
                echo "ERROR: Could not be able to execute $sql. " . $mysqli->error;
            }
            }
           
            //var_dump($sql);
            header('Location: Kunde.php');
        }
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
            $page = new Bestellung();
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
Bestellung::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >