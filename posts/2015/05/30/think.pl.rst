.. title: Nowy projekt: think (Terminal Think Music)
.. slug: think
.. date: 2015-05-30 15:15:00+02:00
.. tags: Python, projects, Linux, think, app, CLI, game show, Jeopardy!
.. category: Python
.. link: http://github.com/Kwpolska/think
.. description: Mój nowy projekt: Terminal Think Music.
.. type: text

Czy uruchamiasz proces, który długo się wykonuje?  Czy chcesz wiedzieć, kiedy
skończy pracę, gdy używasz innego Terminala/parzysz kawę?  Czy masz ulubioną
muzykę z teleturnieju do odtworzenia gdy coś robisz?

Jeśli tak: ``think`` jest właśnie dla ciebie.  By dowiedzieć się więcej, czytaj dalej lub `odwiedź stronę na GitHubie [en] <https://github.com/Kwpolska/think>`_.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/projects/think/" class="btn btn-primary" style="width: 250px;">
   <i class="fa fa-info-circle"></i>
   Strona projektu
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/think" class="btn btn-default" style="width: 250px;">
   <i class="fa fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/think/releases" class="btn btn-default" style="width: 250px;">
   <i class="fa fa-download"></i>
   Pobierz (GitHub)
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://pypi.python.org/pypi/think" class="btn btn-default" style="width: 250px;">
   <i class="fa fa-download"></i>
   Pobierz (PyPI)
   </a>
   </p>

Instalacja
----------

Możesz zainstalować ``think`` z `PyPI <https://pypi.python.org/pypi/think>`_ (przy uzyciu ``pip install think``). Użytkownicy Arch Linux
mogą zainstalować pakiet ``think`` z AUR.

Konfiguracja
------------

Utwórz plik ``~/.config/think.conf`` (tam, gdzie jest ``XDG_CONFIG_HOME``),
zawierający poniższe cztery linie::

    [Think]
    command = play
    file = /home/kwpolska/Dropbox/Media/Wielka Gra.mp3
    behavior = wait

* ``command`` to komenda odtwarzacza, która zostanie wywołana. Możesz użyć
  ``play`` (z pakietu ``sox``) albo jakiegokolwiek innego szybkiego odtwarzacza
  muzyki działającego w konsoli.
* ``file`` to nazwa pliku, który zostanie podany jako jedyny argument do komendy odtwarzacza. Nie jest potrzebne żadne escape’owanie. Ja używam czołówki z `Wielkiej Gry <https://www.youtube.com/watch?v=Nnu7I3b7ZbY>`__ (BTW: właśnie dodałem wersję MP3 na pierwszą rocznicę uploadu na YouTube!), Amerykanie mogą wybrać `Jeopardy! Think Music <https://www.youtube.com/watch?v=vXGhvoekY44>`__ (która jest źródłem nazwy komendy), Brytyjczycy mogą wybrać temat muzyczny z `Countdown <https://www.youtube.com/watch?v=M2dhD9zR6hk>`__.
* ``behavior`` może być jednym z:

  * ``return`` — oddaj kontrolę do terminala jak tylko program skończy działać, bez zatrzymywania muzyki
  * ``wait`` — poczekaj aż muzyka się skończy przed oddaniem kontroli
  * ``stop`` — zatrzymaj muzykę i oddaj kontrolę natychmiastowo

Użycie
------

Dodaj ``think`` przed komendą, która długo się wykonuje::

    think sleep 120
