.. title: Misja Gynvaela 11 (stream anglojęzyczny): reverse-engineering bajtkodu Pythona
.. slug: gynvaels-mission-11-en-python-bytecode-reverse-engineering
.. date: 2017-08-03 12:45:40+02:00
.. tags: hacking, Python, reverse engineering, Gynvael Coldwind, Paint, BMP, writeup
.. section: Python
.. link:
.. description: Rozwiązuję misję, robię reverse-engineering bytecodu Pythona i piszę kod w Paint’cie.
.. type: text

Gynvael Coldwind jest badaczem bezpieczeństwa pracującym w Google, który organizuje cotygodniowe livestreamy na tematy bezpieczeństwa i programowania `po polsku <https://gaming.youtube.com/user/GynvaelColdwind/live>`_ i  `po angielsku <https://gaming.youtube.com/user/GynvaelEN/live>`_). Częścią streamów są misje — w skrócie, zadania w stylu CTF-owym dotyczące inżynierii wstecznej. Wczorajsza misja była o elfickim — znaczy o Paint’cie — znaczy o programowaniu w Pythonie i jego bajtkodzie.

.. TEASER_END

.. code:: text

   MISSION 011               goo.gl/13Bia9             DIFFICULTY: ██████░░░░ [6╱10]
   ┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅

   Finally some real work!

   One of our field agents managed to infiltrate suspects hideout and steal a
   pendrive possibly containing important information. However, the pendrive
   actually requires one to authenticate themselves before accessing the stored
   files.

   We gave the pendrive to our laboratory and they managed to dump the firmware. We
   looked at the deadlisting they sent and for our best knowledge it's some form of
   Elvish. We can't read it.

   Here is the firmware: goo.gl/axsAHt

   And off you go. Bring us back the password.

   Good luck!

   ---------------------------------------------------------------------------------

   If you decode the answer, put it in the comments under this video! If you write
   a blogpost / post your solution online, please add a link in the comments too!

   P.S. I'll show/explain the solution on the stream in ~two weeks.
   P.S.2. Bonus points for recreating the original high-level code.


Kod firmware:

.. code:: text

   co_argcount 1
   co_consts (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50)
   co_flags 67
   co_name check_password
   co_names ('decode', 'len', 'False', 'all', 'zip', 'ord')
   co_nlocals 4
   co_stacksize 6
   co_varnames ('s', 'good', 'cs', 'cg')
                 0 LOAD_CONST               1
                 3 LOAD_ATTR                0
                 6 LOAD_CONST               2
                 9 CALL_FUNCTION            1
                12 STORE_FAST               1
                15 LOAD_GLOBAL              1
                18 LOAD_FAST                0
                21 CALL_FUNCTION            1
                24 LOAD_GLOBAL              1
                27 LOAD_FAST                1
                30 CALL_FUNCTION            1
                33 COMPARE_OP               3 (!=)
                36 POP_JUMP_IF_FALSE       43
                39 LOAD_GLOBAL              2
                42 RETURN_VALUE
           >>   43 LOAD_GLOBAL              3
                46 BUILD_LIST               0
                49 LOAD_GLOBAL              4
                52 LOAD_FAST                0
                55 LOAD_FAST                1
                58 CALL_FUNCTION            2
                61 GET_ITER
           >>   62 FOR_ITER                52 (to 117)
                65 UNPACK_SEQUENCE          2
                68 STORE_FAST               2
                71 STORE_FAST               3
                74 LOAD_GLOBAL              5
                77 LOAD_FAST                2
                80 CALL_FUNCTION            1
                83 LOAD_CONST               3
                86 BINARY_SUBTRACT
                87 LOAD_CONST               4
                90 BINARY_AND
                91 LOAD_CONST               5
                94 BINARY_XOR
                95 LOAD_CONST               6
                98 BINARY_XOR
                99 LOAD_GLOBAL              5
               102 LOAD_FAST                3
               105 CALL_FUNCTION            1
               108 COMPARE_OP               2 (==)
               111 LIST_APPEND              2
               114 JUMP_ABSOLUTE           62
           >>  117 CALL_FUNCTION            1
               120 RETURN_VALUE

Dla niewtajemniczonych to może wyglądać na *elficki*. W rzeczywistości jest to bajtkod Pythona — zestaw instrukcji używany przez maszynę wirtualną Pythona (CPython 2.7.) Python, podobnie jak wiele innych języków, używa kompilatora do tłumaczenia kodu źródłowego czytelnego dla ludzi na coś bardziej odpowiedniego dla komputerów. Kod Pythona tłumaczony jest na bajtkod, który jest wykonywany przez maszynę wirtualną CPythona. Bajtkod CPythona może być używany na różnym sprzęcie, podczas gdy kod maszynowy nie może. Z drugiej strony kod maszynowy jest zazwyczaj szybszy niż języki oparte na maszynach wirtualnych i bajtkodzie. (Java i C# działają tak jak Python, C jest tłumaczone prosto do kodu maszynowego)

To jest wewnętrzna reprezentacja funkcji Pythona. Pierwsze kilka linii to zmienne należące do obiektu ``f.__code__`` naszej funkcji. Wiemy, że funkcja:

* ma 1 argument
* ma 7 stałych: None, długi ciąg cyfr hex, i liczby: 89, 255, 115 ,50.
* ma `flagi <https://docs.python.org/2.7/library/inspect.html#code-objects-bit-flags>`_ ustawione na 67 (CO_NOFREE, CO_NEWLOCALS, CO_OPTIMIZED). Jest to “standardowa” wartość używana przez większość nieskomplikowanych funkcji.
* nazywa się ``check_password``
* używa następujących zmiennych globalnych lub nazw atrybutów: ``decode``, ``len``, ``False``, ``all``, ``zip``, ``ord``
* ma 4 zmienne lokalne
* używa stosu o rozmiarze 6
* jej zmienne nazywają się ``s``, ``good``, ``cs``, ``cg``

Są dwa sposoby na rozwiązanie tego zadania: można spróbować zreasemblować wyjście ``dis`` przy pomocy modułu ``opcode`` lub odtworzyć funkcję ręcznie, używając bajtkodu. Wybrałem tę drugą opcję.

Reverse-engineering bajtkodu Pythona: ręczne odtwarzanie funkcji
================================================================

Zacząłem od odtworzenia oryginalnego pliku z firmware’em. Utworzyłem pustą funkcję i napisałem trochę kodu, który wypisuje zawartość ``__code__`` i wyjście ``dis.dis``. Dodałem też kolorowanie wyjścia, by łatwiej się czytało:

.. code:: python

   #!/usr/bin/env python2
   import dis
   import sys

   # Write code here
   def check_password(s):
       pass

   # Reverse engineering the code
   cnames = ('co_argcount', 'co_consts', 'co_flags', 'co_name', 'co_names', 'co_nlocals', 'co_stacksize', 'co_varnames')
   cvalues = (1, (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50), 67, 'check_password', ('decode', 'len', 'False', 'all', 'zip', 'ord'), 4, 6, ('s', 'good', 'cs', 'cg'))

   for n, ov in zip(cnames, cvalues):
       v = getattr(check_password.__code__, n)
       if v == ov:
           sys.stderr.write('\033[1;32m')
       else:
           sys.stderr.write('\033[1;31m')
       sys.stderr.flush()

       sys.stdout.write(str(n) + " " + str(v) + "\n")
       sys.stdout.flush()

       sys.stderr.write('\033[0m')
       sys.stderr.flush()

   dis.dis(check_password)

Jeśli uruchomimy ten solver, otrzymamy następujące wyjście (tekst w nawiasach kwadratowych dopisany przeze mnie):

.. code:: text

   co_argcount 1            [OK]
   co_consts (None,)        [1/7 się zgadza]
   co_flags 67              [OK]
   co_name check_password   [OK]
   co_names ()              [0/6 się zgadza]
   co_nlocals 1             [powinno być 4]
   co_stacksize 1           [powinno być 6]
   co_varnames ('s',)       [1/4 się zgadza]
     7           0 LOAD_CONST               0 (None)
                 3 RETURN_VALUE

Widzimy (przy pomocy kolorów, których tu nie ma), że ``co_argcount``, ``co_flags``, ``co_name`` są ustawione poprawnie. Mamy też jedną ze zmiennych (``None``, jest w każdej funkcji) i jedną nazwę zmiennej (nazwę argumentu ``s``). Widzimy wyjście ``dis.dis()``. O ile jest podobne do tego z zadania, to jest kilka zauważalnych różnic: nie ma ``7`` na początku (numer linii), a instrukcje ``LOAD_CONST`` nie miały niczego w nawiasach (tylko porównania i pętle coś miały). To utrudnia czytanie bajtkodu, ale to jest wciąż możliwe. (Początkowo chciałem sobie pomóc narzędziem ``diff``, ale nie jest trudno to zrobić ręcznie. Użyłem ``diff`` do ostatecznego sprawdzenia po ręcznej „konwersji”)

Zatrzymajmy się na chwilę i spójrzmy na stałe i nazwy. Po długim stringu pojawia się ``hex``, a jedną ze stałych jest ``decode``. To znaczy, że musimy użyć ``str.decode('hex')`` by utworzyć (byte)string z pewną informacją. Odpowiedzi do misji są czytelne dla ludzi, a ten string nie jest — więc musimy zrobić coś więcej.

Spróbujmy odtworzyć oryginalny kod misji. VM Pythona opiera się na stosie. W bajtkodzie powyżej widzimy, że instrukcje przyjmują 0 lub 1 argument. Niektóre z nich dodają obiekty na stos, inne wykonują akcje i usuwają rzeczy ze stosu. Większość nazw instrukcji jest łatwa do zrozumienia, ale pełna lista jest dostępna w `dokumentacji modułu dis <https://docs.python.org/2/library/dis.html#python-bytecode-instructions>`_.

Instrukcje takie jak ``LOAD`` czy ``STORE`` odwołują się do indeksów w krotkach constants/names/varnames. Aby było łatwiej, oto “tabelka” tych indeksów:

.. code:: text

   constants
    0     1                                                       2      3   4    5    6
   (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50)

   names (globals, attributes)
    0         1      2        3      4      5
   ('decode', 'len', 'False', 'all', 'zip', 'ord')

   varnames (locals, _fast)
    0    1       2     3
   ('s', 'good', 'cs', 'cg')

W celu poprawienia czytelności, użyję “nowe” wyjście ``dis`` z nazwami w nawiasach poniżej:

.. code:: text

    0 LOAD_CONST               1 ('4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89')
    3 LOAD_ATTR                0 (decode)
    6 LOAD_CONST               2 ('hex')
    9 CALL_FUNCTION            1 # funkcja pobiera 1 argument ze stosu
   12 STORE_FAST               1 (good)

Jak wcześniej zgadywałem, pierwsza linia funkcji wygląda tak:

.. code:: python

    def check_password(s):
        good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')  # new

Jeśli jeszcze raz uruchomimy solver, zobaczymy że pierwsze 12 bajtów w bajtkodzie zgadza się z treścią misji. Widzimy też, że ``varnames`` jest wypełnione w połowie, dodaliśmy dwie stałe, i jedną nazwę. Następne kilka linii wygląda tak:

.. code:: text

   15 LOAD_GLOBAL              1
   18 LOAD_FAST                0
   21 CALL_FUNCTION            1
   24 LOAD_GLOBAL              1
   27 LOAD_FAST                1
   30 CALL_FUNCTION            1
   33 COMPARE_OP               3 (!=)
   36 POP_JUMP_IF_FALSE       43
   39 LOAD_GLOBAL              2
   42 RETURN_VALUE

Widzimy że umieszczamy obiekt globalny na stosie i wywołujemy go z jednym argumentem. W obu przypadkach, obiekt globalny ma indeks 1, czyli ``len``. Dwa argumenty to ``s`` i ``good``. Umieszczamy obie długości na stosie i je porównujemy. Jeśli porównanie się nie uda (są równe), przeskakujemy do instrukcji zaczynającej się na bajcie 43, w przeciwnym razie kontynuujemy wykonywanie, by załadować drugi global (False) i go zwrócić. Ta ściana tekstu tłumaczy się na następujący prosty kod:

.. code:: python

    def check_password(s):
        good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
        if len(s) != len(good):  # new
            return False         # newr

Popatrzmy się jeszcze raz na nasze nazwy. Widzimy, że brakuje ``all``, ``zip``, ``ord``. Można zauważyć pewien znany wzorzec: iterujemy po obu stringach na raz (używając ``zip``), wykonujemy obliczenia na podstawie kodów znaków (``ord``) i sprawdzamy czy wszystkie (``all``) wyniki (zazwyczaj porównania) są prawdziwe.

Oto bajtkod z dopisanymi wartościami i komentarzami które tłumaczą, co się gdzie dzieje:


.. code:: text

   >>   43 LOAD_GLOBAL              3 (all)
        46 BUILD_LIST               0
        49 LOAD_GLOBAL              4 (zip)
        52 LOAD_FAST                0 (s)
        55 LOAD_FAST                1 (good)
        58 CALL_FUNCTION            2           # zip(s, good)
        61 GET_ITER                             # Początek iteracji: iter()
   >>   62 FOR_ITER                52 (to 117)  # początek iteracji pętli for (jeśli koniec iteratora, skocz +52 bajty do pozycji 117)
        65 UNPACK_SEQUENCE          2           # rozpakuj sekwencję (a, b = sequence)
        68 STORE_FAST               2 (cs)      # cs = wartość z s
        71 STORE_FAST               3 (cg)      # cg = wartość z good
        74 LOAD_GLOBAL              5 (ord)
        77 LOAD_FAST                2 (cs)
        80 CALL_FUNCTION            1           # umieść ord(cs) na stosie
        83 LOAD_CONST               3 (89)
        86 BINARY_SUBTRACT                      # - 89   [odejmij 89 od wartości na górze stosu]
        87 LOAD_CONST               4 (255)
        90 BINARY_AND                           # & 255  [bitwise AND z wartością na górze stosu]
        91 LOAD_CONST               5 (115)
        94 BINARY_XOR                           # ^ 115  [bitwise XOR z wartością na górze stosu]
        95 LOAD_CONST               6 (50)
        98 BINARY_XOR                           # ^ 50   [bitwise XOR z wartością na górze stosu]
        99 LOAD_GLOBAL              5 (ord)
       102 LOAD_FAST                3 (cg)
       105 CALL_FUNCTION            1           # umieść ord(cs) na stosie
       108 COMPARE_OP               2 (==)      # porównaj dwie wartości na stosie
       111 LIST_APPEND              2           # dodaj wartość umieszczoną na górze sotosu do listy góra-1; usuń górę stosu (dopisz do listy tworzonej w list comprehension)
       114 JUMP_ABSOLUTE           62           # przeskocz na początek pętli
   >>  117 CALL_FUNCTION            1           # po pętli: wywołaj all([wynik list comprehension])
       120 RETURN_VALUE                         # zwróć wartość zwróconą przez all()

Możemy teraz zapisać pełną odpowiedź.

.. listing:: listings/gynvaels-mission-11-en/mission11.py python

Ostatecznie, wyjście ``dis.dis()`` zgadza się z tekstem z misji (za wyjątkiem usuniętych wartości, ale ID się zgadzają), nasze zmienne ``co_*`` są zielone, i możemy rozwiązać prawdziwą zagadkę!

**Na marginesie:** zadanie używa list comprehension. Możesz chcieć ją zoptymalizować, usunąć nawiasy kwadratowe, i otrzymać generator expression. W ten sposób zadanie stałoby się trudniejsze, gdyż wymagałoby pracy również z wewnętrznym obiektem kodu generatora:

.. code:: text

  co_consts (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', <code object <genexpr> at 0x104a86c30, file "mission11-genexpr.py", line 11>)

  46 LOAD_CONST               3 (<code object <genexpr> at 0x104a86c30, file "mission11-genexpr.py", line 11>)

``BINARY_*`` i ``ord`` zniknęły z nowego listingu. Możesz zobaczyć `zmodyfikowany kod </listings/gynvaels-mission-11-en/mission11-genexpr.py.html>`_ (który różni się dwoma bajtami) i `wyjście solvera </listings/gynvaels-mission-11-en/mission11-genexpr.txt.html>`_.

Na marginesie marginesu: zna ktoś jakieś dobre tłumaczenie ``list comprehension``? Polska język trudna język.

Rozwiązywanie prawdziwej zagadki
================================

Rozwiązałem dodatkową część zagadki. Jej *prawdziwym* celem było odzyskanie hasła — tekstu, dla którego ``check_password()`` zwróci True.

Ta część jest dosyć nudna. Zbudowałem słownik, w którym przypisałem każdy bajt (0…255) do wyniku obliczeń wykonywanych w pętli funkcji ``check_password()``. Potem użyłem jej do odzyskania oryginalnego tekstu.

.. code:: python

   pass_values = {}
   for i in range(256):
       result = i - 89 & 255 ^ 115 ^ 50
       pass_values[result] = i

   good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
   password = ''
   for c in good:
       password += chr(pass_values[ord(c)])

   print(password)
   print(check_password(password))

**Hasło brzmi:** ``huh, that actually worked!``.

O co chodziło z tym Paintem?
============================

.. raw:: html

  <blockquote>Wczorajsza misja była o elfickim — <strong>znaczy o Paint’cie</strong> — znaczy o programowaniu w Pythonie i bytecode.<footer>niżej podpisany, w leadzie tego posta</footer></blockquote>

Większość moich czytelników była zdziwiona wspomnieniem programu Paint. Stali widzowie polskich streamów Gynvaela pamiętają film Python 101, który opublikował 1 kwietnia 2016. Zobacz `oryginalny film <https://www.youtube.com/watch?v=7VJaprmuHcw>`_, `wyjaśnienie <http://gynvael.coldwind.pl/?id=599>`_, `kod <https://github.com/gynvael/stream/tree/master/007-python-101>`_ (po polsku) **Uwaga, spoilery.**

W tym dowcipie primaaprilisowym, Gynvael uczył podstaw Pythona. Pierwsza część dotyczyła pisania bytecodu ręcznie. Druga (ok. 12 minuty) dotyczyła rysowania swoich własnych modułów Pythona. W programie Paint. Tak, Paint, prostym programie graficznym dołączonym do Windowsa. Narysował swój własny moduł Pythona w Paint’cie i zapisał jako BMP. Wyglądało to tak (powiększony PNG poniżej; `pobierz gynmod.bmp </pub/gynvaels-mission-11-en/gynmod.bmp>`_):

.. image:: /images/gynvaels-mission-11-en/gynmod-zoom.png
   :align: center

Jak to działa? Są trzy powody:

* Python może importować kod z pliku ZIP (dopisanego do sys.path). Niektóre narzędzia które tworzą pliki ``.exe`` z kodu Pythona używają tej metody; stary format ``.egg`` również używał ZIPów w ten sposób.
* Pliki BMP mają nagłówki na początku pliku.
* Pliki ZIP mają nagłówki na końcu pliku.
* Więc jeden plik może być jednocześnie poprawnym plikiem BMP i poprawnym ZIPem.

Wziąłem kod ``check_password`` i umieściłem go w pliku ``mission11.py`` (wcześniej zacytowanym). Potem skompilowałem do ``.pyc`` i utworzyłem z niego ``.zip``.

.. listing:: listings/gynvaels-mission-11-en/mission11.py python

Ponieważ nie jestem ekspertem w żadnym z formatów, uruchomiłem maszynę wirtualną z Windowsem i na ślepo `przekopiowałem parametry użyte przez Gynvaela <http://gynvael.coldwind.pl/img/secapr16_3.png>`_ do otwarcia pliku ZIP (nazwanego ``.raw``) w IrfanView i zapisałem jako ``.bmp``. Zmieniłem rozmiar na 83×2, ponieważ mój ZIP miał 498 bajty (3 BPP * 83 px * 2 px = 498 bytes) — dzięki temu i odpowiedniemu rozmiarowi plików, mogłem nie dodawać komentarzy i edytowaniu ZIPa. Dostałem ten obrazek (znowu PNG; `pobierz mission11.bmp </pub/gynvaels-mission-11-en/mission11.bmp>`_):

.. image:: /images/gynvaels-mission-11-en/mission11-zoom.png
   :align: center

Plik ``.bmp`` można uruchomić! Używamy tego kodu:

.. listing:: listings/gynvaels-mission-11-en/ziprunner.py python

I dostajemy to:

.. image:: /images/gynvaels-mission-11-en/running-bmp.png
   :align: center

Materiały
=========

* `mission11-solver.py (pełny kod solvera) </listings/gynvaels-mission-11-en/mission11-solver.py.html>`_
* `mission11-genexpr.py </listings/gynvaels-mission-11-en/mission11-genexpr.py.html>`_, `mission11-genexpr.txt </listings/gynvaels-mission-11-en/mission11-genexpr.txt.html>`_ (używane w notatce na marginesie dot. vs list comprehensions)
* `mission11.py, kod użyty w pliku BMP </listings/gynvaels-mission-11-en/mission11.py.html>`_
* `ziprunner.py, plik uruchamiający moduł BMP/ZIP </listings/gynvaels-mission-11-en/ziprunner.py.html>`_ (na bazie utworzonego przez Gynvaela)
* `gynmod.bmp </pub/gynvaels-mission-11-en/gynmod.bmp>`_
* `mission11.bmp </pub/gynvaels-mission-11-en/mission11.bmp>`_
* `dokumentacja modułu dis <https://docs.python.org/2/library/dis.html#python-bytecode-instructions>`_.

Dzięki za misję (i pomysł z BMP), Gynvael!
