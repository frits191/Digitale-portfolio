# Digitale-portfolio
Project digitale portfolio door de 

<//Korte beschrijving//>
Alle pagina's worden opgeroepen in de index.php, alle functies komen in functions.php.

<*Database login*>
Login: INF1H
ww: stenden

<*functies.php*>
*Hoe voeg ik een functie toe?
Een functie kan worden toegevoegd in functies.php, binnenin de class: "functies";

*Hoe roep ik mijn functie op?
Bovenaan het bestand moeten deze twee lines toegevoegd worden:
require ('functies.php');
$functions = new functions;

Om dan de functie zelf op te vragen gebruik je:
$functions->FUNCTIENAAM();

*Hoe roep ik mijn functie toe binnen functies.php?
Als je in het bestand functions.php een eerder gemaakte functie nodig hebt gebruik je het volgende:
$this->FUNCTIENAAM();

<*Database SQL*>
Om een query uit te voeren gebruik je de volgende functionaliteit:
$SQLstring = "SQL HIER"; //Gebruik de volledige tabel naam in je sql, de functie kan table names niet lezen.
$QueryResult = $functions->executeQuery($SQLstring);