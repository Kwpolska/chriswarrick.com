.. title: Speeker — moja mała aplikacja na Androida
.. slug: speeker
.. date: 2014-08-26 15:00:00+02:00
.. tags: android, app, devel, programming, projects
.. category: Android Adventure
.. description: Moja mała aplikacja na Androida.
.. type: text

.. class:: android-adventure-logo-robot

.. image:: /blog-content/android-adventure/robot.png
   :target: /pl/blog/2014/08/01/series-android-adventure/

Skoro mam używalny telefon, mogę zająć się tworzeniem aplikacji na Androida.
Co uczyniłem.  Zacząłem od aplikacji do testowania i zabawy z usługami
text-to-speech systemu Android.  Nazwałem ją Speeker.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <img src="/blog-content/android-adventure/speeker.png" alt="Speeker logo">
   </p>
   <p style="text-align: center; clear: both;">
   <a href="/pl/galleries/speeker/" class="btn btn-default" style="width: 144px;">
   <i class="fa fa-picture-o"></i>
   Zrzuty ekranu
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/speeker" class="btn btn-default" style="width: 144px;">
   <i class="fa fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/speeker/releases" class="btn btn-default" style="width: 144px;">
   <i class="fa fa-download"></i>
   Pobieranie
   </a>
   </p>


Speeker jest małym i prostym frontendem dla systemowej usługi TTS.  W obecnej
iteracji jest dosyć ograniczony.  Kompletny zestaw funkcji to:

* wypowiedz tekst wpisany przez użytkownika
* wypowiedz tekst w domyślnym języku TTS systemu (wybranym w ustawieniach
  Androida, i nie ma obecnie przycisku otwierającego to menu)
* wyczyść pole tekstowe
* pokaż okno *O programie*
* pokaż licencje open source
* otwórz moją stronę internetową

To SZEŚĆ funkcji! I nie można nawet ściągnąć go z Google Play, bo jestem zbyt
skąpy by zapłacić $25 opłaty startowej!

Będę lub nie kontynuował rozwoju i być może dodam kilka funkcji.  W każdym
razie, moje doświadczenia z tworzeniem na Androida nie były dobre: nie ma
wystarczająco dużo dobrej dokumentacji, i musiałem często zgadywać w niektórych
miejscach, np. przy importach.  Dokumentacja mogłaby zostać znacznie
poprawiona.

Innym problemem jest wybór IDE.  O ile można oczywiście pracować nad Androidem
w dowolnym środowisku, w tym tylko w Vimie i w terminalu, oficjalnym i
rekomendowanym środowiskiem jest Eclipse.  Który okazuje się być najgorszym IDE
które kiedykolwiek powstało.  Jest nieprzyjazny i nieporęczny — czyli
standardowe cechy dużych aplikacji Javovych.  Jednym razem Eclipse udało się
otworzyć plik w jakieś dziesięć sekund.  Bardzo imponujące, dopóki nie
zauważysz, że cokolwiek innego zrobiłoby to w mniej czasu.  Nie, nie zmyślam.
To jest problem z dużymi IDE: próbują zrobić za dużo naraz.

Tworzenie na Androida nie było najlepszym doświadczeniem — ale da się, i nie
trzeba mieć za dużo doświadczenia z samą platformą by to robić.  Android ma
potencjał, ale musi być udoskonalony, by być przyjaznym dla developerów.
