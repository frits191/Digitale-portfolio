# Digitale-portfolio
Project digitale portfolio door de 

<//Korte beschrijving//>
Alle pagina's worden opgeroepen in de index.php, alle functies komen in functies.php.

<*Nieuwe pagina aanmaken*>
Creer een nieuwe pagina en geef dit een bijpassende naam (bijvoorbeeld de naam van de student), en zet deze in de core files.

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
$SQLstring = "SQL HIER"; //Gebruik de volledige tabel naam in je sql, de fucntie kan table names niet lezen.