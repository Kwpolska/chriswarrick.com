---
layout: plpost
title: 'Arch Linux: Najlepsza dystrybucja linuksa.'
category: polish
---
**Używam linuksa od roku 2007. Używałem wielu dystrybucji (w kolejności): [Mandriva][], [Ubuntu][], [openSuSE][], [Fedora][], i tylko w [VirtualBoksie][VirtualBox]: [Debian][] i [Linux Mint][]. Ale od dwóch miesięcy na obu listach jest nowe distro (najpierw testowałem je w VirtualBoksie, a potem postawiłem je na prawdziwej maszynie): [Arch Linux][].**

Arch Linux jest dystrybucją stworzoną przez Judda Vineta i Aarona Griffina, istniejącą od 2002, w zamiarze prosta, elegancka, wszechstronna i praktyczna (patrz powód #3). Używa `pacman`a jako menadżer pakietów. Jest to dystrybucja rolling-release, co znaczy, że wydawane są jedynie nowe obrazy pakietów, a aktualizacje otrzymujesz na bieżąco, nawet jeśli zacząłeś od Arch Linuksa v0.1. Aktualne wydanie CD to **2010.05**.

# Co sprawia, że jest to takie świetne distro?
Jest sześć powodów, dla których ta dystrybucja jest najlepsza:

## Powód 1. [ArchWiki][]
ArchWiki to swietne miejsce. Możesz znależć wiele przydatnych informacji o Archu. W tym artykule możesz znaleźć 9 linków do wersji angielskiej (plus dwa podwojone, istnieje [polskie ArchWiki][]).

## Powód 2. Pacman i AUR
Menadżrem pakietów jest `pacman`. Posiada specyficzną składnię. [Repozytorium Użytkowników Archa (*Arch User Repository*)][AUR] jest miejscem, w którym możesz znaleźć `PKGBUILD`y (instrukcje budowania pakietów, które pomagają je tworzyć) dla większej liczby pakietów niż posiadają domyślne repozytoria — `[core]`, `[extra]` i `[community]` (które posiada wersje binarne pakietów z AUR) Przeczytaj artykuły [Pacman][] i [AUR][ArchWiki: AUR] z [ArchWiki][], jeśli chcesz wiedzieć więcej.

## Powód 3. [The Arch Way][tawv2]
Dystrybucja jest he distro is <q>w zamiarze prosta, elegancka, wszechstronna i praktyczna (intended to be simple, elegant, versatile and expedient)</q>, jak głosi [The Arch Way v2.0][tawv2]. Możesz też zobaczyć [The Arch Way v1.0][tawv1].

## Powód 4. `/etc/rc.conf`
`/etc/rc.conf` jest głównym plikiem konfiguracyjnym. Mój możesz zobaczyć tutaj: [Mój rc.conf][]

## Powód 5. Społeczność: [IRC][], [Forum][Forums], [Listy mailingowe][MLs] i [e-zine The Rolling Release][The Rolling Release e-zine]
Arch Linux ma mocną społeczność: dużo ludzi można znaleźć na [IRC-u][IRC], na [forach][Forums] i na [listach mailingowych][MLs]. Istnieje też polska społeczność - [forum][] i [planeta][] archlinux.pl.

## Powód 6. Model rolling-release
Arch używa modelu rolling-release, opisanego wcześniej i zacytowanego poniżej.

> Jest to dystrybucja rolling-release, co znaczy, że wydawane są jedynie nowe obrazy pakietów, a aktualizacje otrzymujesz na bieżąco, nawet jeśli zacząłeś od Arch Linuksa v0.1.

<hr>

## A short install guide
Odwiedź stronę [pobierania Arch Linuksa][Arch Linux Downloads]. Znajdziesz tam sześć wersji. Obrazy netinstall są małe i zawierają tylko instalator, reszta systemu jest pobierana z internetu. Obrazy core są kopią repozytorium o tej samej nazwie. Oba borazy są dostępne w trzech architekturach: i686, x86-64 i w wydaniu na obie architektury (dwa razy większe).

Instalacja jest prosta. Zabootuj z płyty CD, zaloguj się jako root, użyj komendy `km` i uruchom instalator komendą `/arch/setup`. Więcej pomocy przy instalacji dostępne jest w [Oficjalnym Podręczniku Instalacji Arch Linuksa][Official Arch Linux Install Guide] Innym pomocnym dokumentem jest [Poradnik Początkującego][beginners guide].

[Mandriva]:                          http://www2.mandriva.com/en/ "Mandriva"
[Ubuntu]:                            http://ubuntu.com "Ubuntu"
[openSuSE]:                          http://opensuse.org "openSuSE"
[Fedora]:                            http://fedoraproject.org "Fedora"
[VirtualBox]:                        http://virtualbox.org "VirtualBox"
[Debian]:                            http://debian.org "Debian"
[Linux Mint]:                        http://linuxmint.com "Linux Mint"
[Arch Linux]:                        http://archlinux.org "Arch Linux"
[ArchWiki]:                          https://wiki.archlinux.org/index.php/Main_Page "ArchWiki"
[Pacman]:                            https://wiki.archlinux.org/index.php/Pacman "Pacman"
[AUR]:                               http://aur.archlinux.org/ "AUR"
[ArchWiki: AUR]:                     https://wiki.archlinux.org/index.php/Arch_User_Repository "ArchWiki: Arch User Repository"
[my rc.conf]:                        http://kwpolska.co.cc/privpastebin/index.php?id=1297105122.9 "Mój rc.conf"
[IRC]:                               https://wiki.archlinux.org/index.php/IRC_Channel "IRC Channel"
[Forums]:                            https://bbs.archlinux.org "Forums"
[MLs]:                               http://mailman.archlinux.org/mailman/listinfo/ "Mailing Lists"
[The Rolling Release e-zine]:        http://rollingrelease.com/ "Rolling Release"
[tawv1]:                             https://wiki.archlinux.org/index.php/The_Arch_Way "The Arch Way"
[tawv2]:                             https://wiki.archlinux.org/index.php/The_Arch_Way_v2.0 "The Arch Way v2.0"
[The History of Arch Linux]:         https://wiki.archlinux.org/index.php/History_of_Arch_Linux "History of Arch linux"
[Arch Linux Downloads]:              http://www.archlinux.org/download/ "Arch Linux Downloads"
[Official Arch Linux Install Guide]: https://wiki.archlinux.org/index.php/Official_Arch_Linux_Install_Guide "Official Arch Linux Install Guide"
[beginners guide]:                   https://wiki.archlinux.org/index.php/Beginners%27_Guide "Beginners&#8217; Guide"
[contact]:                           http://kwpolska.co.cc/polish/kontakt/ "Kontakt"
[polskie ArchWiki]:                  http://wiki.archlinux.pl "polskie ArchWiki"
[forum]:                             http://bbs.archlinux.pl "polskie forum Archa"
[planeta]:                           http://planeta.archlinux.pl "planeta Archa"
