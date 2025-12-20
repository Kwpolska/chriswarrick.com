.. title: Nowy projekt: upass — konsolowy interfejs dla pass
.. slug: upass
.. date: 2015-07-06 14:30:00+02:00
.. tags: Python, projects, Linux, CLI, upass, app, password
.. category: Python
.. link: https://github.com/Kwpolska/upass
.. description: Bo pass jest za trudny.
.. type: text
.. nocomments: true

`pass <http://www.passwordstore.org/>`_ to standardowy Uniksowy manager haseł.
A ja właśnie stworzyłem odrobinę przyjaźniejszy, klikalniejszy interfejs przy
użyciu biblioteki urwid w Pythonie.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/projects/upass/" class="btn btn-primary" style="width: 250px;">
   <i class="fa fa-info-circle"></i>
   Strona projektu
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/pl/galleries/upass/" class="btn btn-secondary" style="width: 250px;">
   <i class="far fa-image"></i>
   Zrzuty ekranu
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/upass" class="btn btn-secondary" style="width: 250px;">
   <i class="fab fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://pypi.python.org/pypi/upass" class="btn btn-secondary" style="width: 250px;">
   <i class="fa fa-download"></i>
   Pobierz (PyPI)
   </a>
   </p>

``upass`` używa biblioteki urwid, co oznacza, że ma przyjazny pełnoekranowy interfejs konsolowy.
Pokazuje strukturę katalogów (ze spłaszczonymi podkatalogami) i wywołuje
``pass`` na żądanie.  (Nie używa ``pass -c`` przez problemy z podprocesami, w
zamian samodzielnie kopiując tekst — zauważ, że schowek **nie zostanie
wyczyszczony**)

Jeśli chcesz zobaczyć, jak wygląda ``upass``, odwiedź `galerię zrzutów ekranu </pl/galleries/upass/>`_.

``upass`` jest ciągle rozwijany (i został pierwotnie napisany w jeden wieczór).
Jeśli masz pomysły, znalazłeś błędy, lub chcesz pomóc, odwiedź
`stronę na GitHubie <https://github.com/Kwpolska/upass>`_.

Możesz zainstalować ``upass`` z `PyPI <https://pypi.python.org/pypi/upass>`_ (przy uzyciu ``pip install upass``). Użytkownicy
Arch Linux mogą zainstalować pakiet ``upass`` z AUR.
