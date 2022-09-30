<?php
return array (
  'seo' =>
  array (
    'upload' => 'Nadzorna plošča - Naloži datoteko CSV - :site_name',
    'csv-data-index' => 'Nadzorna plošča - Zgodovina nalaganja CSV - :site_name',
    'csv-data-edit' => 'Nadzorna plošča - Razčleni podatke CSV - :site_name',
    'item-index' => 'Nadzorna plošča - Uvoz seznama - :site_name',
    'item-edit' => 'Nadzorna plošča - Uredi seznam uvoza - :site_name',
  ),
  'alert' =>
  array (
    'upload-success' => 'Datoteka je bila uspešno naložena',
    'upload-empty-file' => 'Naložena datoteka ima prazno vsebino',
    'fully-parsed' => 'Datoteka CSV je bila v celoti razčlenjena, zato je ni mogoče znova razčleniti',
    'parsed-success' => 'Podatki o datotekah CSV so bili uspešno razčlenjeni na začasno zbirko podatkov, pojdite v meni Stranska vrstica> Orodja> Uvoznik> Seznam, da začnete končni uvoz',
    'csv-file-deleted' => 'Datoteka CSV je bila izbrisana iz pomnilnika strežniške datoteke',
    'import-item-updated' => 'Uvoz podatkov o seznamu je bil uspešno posodobljen',
    'import-item-deleted' => 'Uvoz podatkov o seznamu je bil uspešno izbrisan',
    'import-process-success' => 'Podatki o seznamu so bili uspešno uvoženi na seznam spletnih mest',
    'import-process-error' => 'Pri obdelavi uvoza je prišlo do napake. Podrobnosti preverite v dnevniku napak',
    'import-all-process-completed' => 'Uvoz vseh seznamov je končan',
    'import-item-cannot-edit-success-processed' => 'Ne morete urejati informacij o seznamu uvoza, ki so bili uspešno uvoženi',
    'import-process-completed' => 'Postopek uvoza končan',
    'import-process-no-listing-selected' => 'Pred začetkom postopka uvoza izberite sezname',
    'import-process-no-categories-selected' => 'Pred začetkom postopka uvoza izberite eno ali več kategorij',
    'import-listing-process-in-progress' => 'V teku, počakajte na zaključek',
    'delete-import-listing-process-no-listing-selected' => 'Pred začetkom postopka brisanja izberite sezname',
  ),
  'sidebar' =>
  array (
    'importer' => 'Uvoznik',
    'upload-csv' => 'Naložite CSV',
    'upload-history' => 'Zgodovina nalaganja',
    'listings' => 'Seznami',
  ),
  'show-upload' => 'Naložite datoteko CSV',
  'show-upload-desc' => 'Na tej strani lahko naložite datoteko CSV in jo v poznejših korakih razčlenite na surove podatke s seznama za uvoz.',
  'csv-for-model' => 'Datoteka CSV za',
  'csv-for-model-listing' => 'Seznam',
  'choose-csv-file' => 'Izberite datoteko',
  'choose-csv-file-help' => 'vrsta datoteke za podporo: csv, txt, največja velikost: 10mb',
  'upload' => 'Naloži',
  'csv-skip-first-row' => 'Preskoči prvo vrstico',
  'filename' => 'Ime datoteke',
  'progress' => 'Razčlenjen napredek',
  'uploaded-at' => 'Naloženo ob',
  'model-for' => 'Model',
  'import-csv-data-index' => 'Zgodovina nalaganja datotek CSV',
  'import-csv-data-index-desc' => 'Ta stran prikazuje vse naložene datoteke CSV in njihov razčlenjen napredek.',
  'parse' => 'Razčleni',
  'import-csv-data-edit' => 'Razčleni podatke datoteke CSV',
  'import-csv-data-edit-desc' => 'Ta stran vam omogoča razčlenitev podatkov datoteke CSV, ki ste jo naložili.',
  'start-parse' => 'Začnite razčleniti',
  'import-csv-data-parse-error' => 'Prišlo je do napake. Znova naložite stran, da nadaljujete z razčlenjevanjem preostalih vrstic.',
  'parsed-percentage' => ':parsed_count od :total_count zapisov je bilo razčlenjenih',
  'column' => 'Stolpec',
  'column-item-title' => 'naslov seznama',
  'column-item-slug' => 'seznam polžev',
  'column-item-address' => 'naslov na seznamu',
  'column-item-city' => 'seznam mesta',
  'column-item-state' => 'stanje seznama',
  'column-item-country' => 'država s seznamom',
  'column-item-lat' => 'seznam lat',
  'column-item-lng' => 'seznam lng',
  'column-item-postal-code' => 'navedba poštne številke',
  'column-item-description' => 'opis seznama',
  'column-item-phone' => 'telefonski seznam',
  'column-item-website' => 'seznam spletnih mest',
  'column-item-facebook' => 'seznam facebook',
  'column-item-twitter' => 'seznam twitter',
  'column-item-linkedin' => 'seznam povezanih',
  'column-item-youtube-id' => 'seznam youtube ID',
  'delete-file' => 'Izbriši datoteko',
  'csv-file' => 'Datoteka CSV',
  'import-errors' =>
  array (
    'user-not-exist' => 'Uporabnik ne obstaja',
    'item-status-not-exist' => 'Seznam mora biti v stanju oddaje, objave ali začasne ustavitve',
    'item-featured-not-exist' => 'Predstavljeni seznam mora biti pritrdilen ali ne',
    'country-not-exist' => 'Država ne obstaja, dodajte državo v Lokacija> Država> Dodaj državo',
    'state-not-exist' => 'Država ne obstaja, dodajte državo v Lokacija> Država> Dodaj državo',
    'city-not-exist' => 'Mesto ne obstaja, dodajte mesto v Lokacija> Mesto> Dodaj mesto',
    'item-title-required' => 'Naslov navedbe je obvezen',
    'item-description-required' => 'Opis vnosa je obvezen',
    'item-postal-code-required' => 'Potrebna je navedba poštne številke',
    'categories-required' => 'Seznam mora biti dodeljen eni ali več kategorijam',
    'import-item-cannot-process-success-processed' => 'Ne morete obdelati podatkov o seznamu uvoza, ki so bili uspešno uvoženi',
  ),
  'import-listing-index' => 'Uvoz seznamov',
  'import-listing-index-desc' => 'Ta stran prikazuje vse razčlenjene podatke o seznamu iz datoteke CSV. To so surovi podatki o seznamih, ki jih je mogoče uvoziti na sezname spletnih mest.',
  'import-listing-status-not-processed' => 'Ni obdelano',
  'import-listing-status-success' => 'Obdelano z uspehom',
  'import-listing-status-error' => 'Obdelano z napako',
  'import-listing-order-newest-processed' => 'Najnovejše obdelane',
  'import-listing-order-oldest-processed' => 'Najstarejša obdelava',
  'import-listing-order-newest-parsed' => 'Najnovejše razčlenjeno',
  'import-listing-order-oldest-parsed' => 'Najstarejše razčlenjeno',
  'import-listing-order-title-a-z' => 'Naslov (AZ)',
  'import-listing-order-title-z-a' => 'Naslov (ZA)',
  'import-listing-order-city-a-z' => 'Mesto (AZ)',
  'import-listing-order-city-z-a' => 'Mesto (ZA)',
  'import-listing-order-state-a-z' => 'Država (AZ)',
  'import-listing-order-state-z-a' => 'Država (ZA)',
  'import-listing-order-country-a-z' => 'Država (AZ)',
  'import-listing-order-country-z-a' => 'Država (ZA)',
  'select' => 'Izberite',
  'import-listing-title' => 'Naslov',
  'import-listing-city' => 'Mesto',
  'import-listing-state' => 'Država',
  'import-listing-country' => 'Država',
  'import-listing-status' => 'Stanje',
  'import-listing-detail' => 'Podrobnosti',
  'import-listing-slug' => 'Slug',
  'import-listing-address' => 'Naslov',
  'import-listing-lat' => 'Zemljepisna širina',
  'import-listing-lng' => 'Zemljepisna dolžina',
  'import-listing-postal-code' => 'Poštna številka',
  'import-listing-description' => 'Opis',
  'import-listing-phone' => 'Telefon',
  'import-listing-website' => 'Spletna stran',
  'import-listing-facebook' => 'Facebook',
  'import-listing-twitter' => 'Twitter',
  'import-listing-linkedin' => 'LinkedIn',
  'import-listing-youtube-id' => 'Id YouTube',
  'import-listing-do-not-parse' => 'NE RAZLIKUJ',
  'import-listing-source' => 'Vir',
  'import-listing-source-csv' => 'Nalaganje datoteke CSV',
  'import-listing-error-log' => 'Dnevnik napak',
  'import-listing-edit' => 'Uredi uvoz seznama',
  'import-listing-edit-desc' => 'Ta stran vam omogoča urejanje informacij o seznamu uvoza in obdelavo posameznih informacij o seznamu uvoza na seznam spletnih mest.',
  'import-listing-information' => 'Uvozi podatke o seznamu',
  'choose-import-listing-preference' => 'Uvozi na seznam',
  'choose-import-listing-categories' => 'Izberite eno ali več kategorij',
  'choose-import-listing-owner' => 'Lastnik seznama',
  'choose-import-listing-status' => 'Stanje na seznamu',
  'choose-import-listing-featured' => 'Seznam Featured',
  'import-listing-button' => 'Uvozi zdaj',
  'choose-import-listing-preference-selected' => 'Uvozi izbrano na seznam',
  'import-listing-selected-button' => 'Uvozi izbrano',
  'import-listing-selected-modal-title' => 'Uvozi izbrane sezname',
  'import-listing-selected-total' => 'Skupaj za uvoz',
  'import-listing-selected-success' => 'Uspeh',
  'import-listing-selected-error' => 'Napaka',
  'import-listing-per-page-10' => '10 vrstic',
  'import-listing-per-page-25' => '25 vrstic',
  'import-listing-per-page-50' => '50 vrstic',
  'import-listing-per-page-100' => '100 vrstic',
  'import-listing-per-page-250' => '250 vrstic',
  'import-listing-per-page-500' => '500 vrstic',
  'import-listing-per-page-1000' => '1000 vrstic',
  'import-listing-select-all' => 'Izberi vse',
  'import-listing-un-select-all' => 'Odznači vse',
  'csv-parse-in-progress' => 'V teku je razčlenitev datoteke CSV, počakajte na dokončanje',
  'error-notify-modal-close-title' => 'Napaka',
  'error-notify-modal-close' => 'Zapri',
  'csv-file-upload-listing-instruction' => 'Navodila',
  'csv-file-upload-listing-instruction-columns' => 'Stolpci za seznam: naslov, polž (možnost), naslov (možnost), mesto, država, država, zemljepisna širina (možnost), zemljepisna dolžina (možnost), poštna številka, opis, telefon (možnost), spletno mesto (možnost), facebook (možnost ), twitter (možnost), linkedin (možnost), YouTube ID (možnost).',
  'csv-file-upload-listing-instruction-tip-1' => 'Čeprav bo postopek razčlenjevanja datotek CSV poskušal po najboljših močeh uganiti, poskrbite, da se ime mesta, države in države ujema z lokacijskimi podatki (Stranska vrstica> Lokacija> Država, država, mesto) vašega spletnega mesta.',
  'csv-file-upload-listing-instruction-tip-2' => 'Če vaše spletno mesto gostuje v skupnem gostovanju, poskusite vsakič naložiti datoteko CSV z manj kot 15.000 vrsticami, da se izognete največji napaki, ki je presežena.',
  'csv-file-upload-listing-instruction-tip-3' => 'Datoteke CSV za udobje združite v kategorije. Na primer restavracije v eni datoteki CSV z imenom restaurant.csv in hoteli v drugi datoteki CSV z imenom hotel.csv.',
  'import-listing-delete-selected' => 'Izbriši izbrano',
  'import-listing-delete-progress' => 'Brisanje ... počakajte',
  'import-listing-delete-progress-deleted' => 'črtano',
  'import-listing-delete-complete' => 'Končano',
  'import-listing-delete-error' => 'Prišlo je do napake. Znova naložite stran, da nadaljujete z brisanjem preostalih zapisov.',
  'import-listing-import-button-progress' => 'Uvažanje ... počakajte',
  'import-listing-import-button-complete' => 'Končano',
  'import-listing-import-button-error' => 'Prišlo je do napake. Za nadaljevanje uvoza preostalih zapisov znova naložite stran.',
  'import-listing-markup' => 'Označevanje',
  'import-listing-markup-help' => 'Dajte nekaj besed, ki se bodo razlikovale od drugih paketov datotek',
  'import-listing-markup-all' => 'Vsi pribitki',
);
