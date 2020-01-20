 Zde je zdrojový kód k bakalářské práci. 
 
 Kód není psán horkou jehlou, ač se to tak může zdát, ale učím se na něm. Je to můj první "větší" projekt. 
 Kód je humus, ale funguje :).
 
 
 ## Instalační manuál 
 
 
Příloha 1 – Stručný instalační manual

Nejaktuálnější zdrojové kódy aplikace a instalačního manuálu jsou dostupné na github.com/pavelvodstrcil/BP-program1

Ke správnému fungování je zapotřebí nainstalovaný OpenVas a Nessus, popřípadě stačí mít alespoň stažené a rozbalené soubory pluginů. Je ale lepší mít instalovaný celé skenery, jelikož se za nás starají o aktualizaci pluginů.

Aplikace byla vyvíjena a testována pouze na webovém serveru Apache, proto je důrazně doporučen!
Kostra databáze je vytvořená pro databázový server PostrgreSQL a nedoporučuje se používat MySQL (či jiný databázový server) z důvodu nefunkčnosti některých funkcí.

 1) Instalace skenerů, nebo mít ve složce /var/lib/openvas/plugins stažené pluginy pro OpenVas, pro Nessus /opt/nessus/lib/nessus/plugins
 2) Ověření funkčnosti webového serveru a PHP min 7.13 (popřípadě povolnění PHP)
        a. Nutné je povolit modul Mod_Rewrite příkazem „sudo a2enmod rewrite“
3) Instalace databázového serveru PostgreSQL (postup dle oficiální dokumentace) + pgAdmin
4) Nakopírování celé aplikace do složky webového serveru, popřípadě vytvoření virtuálního hostu (dle uvážení uživatele) – pokud bude v základní instalaci Apache je potřeba nasměrovat default server a DocumentRoot do složky public v projektu
5) V konfiguračním souboru hosta je nutné přidat tyto řádky, aby nastavení složky vypadalo takto:
        a. <Directory /var/www/>
Options Indexes FollowSymLinks
AllowOverride All
Require all granted
</Directory>
        b. Po jakékoliv změně je nutné webový server restartovat (sudo service apache2 restart)
    6) Nastavení správných práv celému projektu (dle uvážení uživatele) – pokud nastane problém s oprávněním, framework Laravel na to upozorní
    7) Obnovení přiložení přiložené databáze (v případě změn v názvu, uživatele apod. je zapotřebí změnit tyto informace v souboru .env v kořenovém adresáři projektu 
    8) V konfiguračním souboru .env, který je v kořenovém adresáři, je nutné vyplnit URL, přes kterou je aplikace dostupná, pokud nebude vyplněná tato adresa nebudou správně fungovat odkazy
    9) Vytvoření prázdné databáze → poté v této databázi obnovit přiložený soubor „kostra_DB.sql“ ze složky „_DATABAZE_EXPORT“. (Query tool → vložit obsah souboru a spustit query)
    10) Databáze je nyní prázdná, je potřeba vložit dodatečné informace, aby aplikace správně fungovala. Tj importovat soubory do připravených tabulek DB (kliknout pravým tlačítkem v pgAdmin na danou tabulku a vybrat import/export a vybrat soubor)
        a. Soubor „import_scanner.csv“ do tabulky „scanner“
        b. Soubor „import_types.csv“ do tabulky „device_type“
        c. Soubor „import_user_premis.csv“ do tabulky „user_permissions“
    11) Založení prvního uživatele – URL/register → prvního uživatele povolí aplikace zaregistrovat bez nutnosti přihlášení. Je nutné, aby první uživatel měl FULL oprávnění. Pokud se zaregistruje špatně, musí se záznam ručně smazat v databázi!
    12) Pro správné fungování je nutné zajistit přítomnost programů grep a Nmap, ping
 
 ## Manuál k používání 
 
 Bude :-)
 
  ## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

Bakalářská práce je školní dílo. Musím si doplnit znalosti o jaký přesný typ licence se jedná. 
