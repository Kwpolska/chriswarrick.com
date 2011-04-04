---
layout: post
title: Zsh — Shell dla power userów.
category: polish
---
**Co jest najważniejszą rzeczą w systemie Uniksopodobnym poza kernelem? Shell. Dobry shell.**

Pracuję z systemami Uniksopodobnymi bardzo długo. Nie używałem shella cały czas, ale czarne okienko z czcionką monospace z czasem zagościło na moim ekranie. Czarne okienko z `bash`em w środku. Dlaczego? Ustawiono go jako domyślny shell. Niektórzy użytkownicy nie bawią się w zmienianie ustawień lub testowanie innych shelli.

## `user@localhost:~$ zsh`

Czy nadal używasz basha? Przejdź na zsh i dowiedz się dlaczego jest lepsze. Zacznijmy od listy powodów.

### Powód #1. Inteligentne dopełnianie

Wbudowanie dopełnianie zsh jest najlepszym na świecie. Dla przykładu, tak zwykły bash odpowiada na `pacman <Tab><Tab>`:

    [kwpolska@kwpolska-lin ~]$ pacman 
    Display all XXX possibilities? (y or n)

(Możliwości to pliki i katalogi w aktualnym katalogu.)

A to jest kolejna odpowiedź basha, tym razem z bash-completion:

    [kwpolska@kwpolska-lin ~]$ pacman -
    -D          -h          -Q          -R          -S          -U          -V          
    --database  --help      --query     --remove    --sync      --upgrade   --version

To jest znacznie bardziej pomocne, ale nowy użytkownik nie będzie wiedział co zrobić.

Zastanawiasz się co zsh zrobił po dodaniu `-` przed pierwszym `<Tab>`em?

    [kwpolska@kwpolska-lin ~]$ pacman -Q
    -Q  -- Query the package database
    -R  -- Remove a package from the system
    -S  -- Synchronize packages
    -U  -- Upgrade a package
    -V  -- Display version and exit
    -h  -- Display usage

(Jeśli wciśniesz Tab raz, pokaże możliwości. Jeśli wciśniesz go raz jeszcze, zostanie ustawione -R.)

Czy chcesz uruchomić Aplikacje Domyślne GNOME **z shella, bez używania klawisza Tab**? Życzę szczęścia! Nazwa brzmi `gnome-default-applications-properties`. To jest **38** znaków. **TRZYDZIEŚCI OSIEM** znaków. Jeśli zrobisz literówkę w bashu, zoaczysz „command not found”, przeklniesz parę razy i znajdziesdz literówkę sam. Przy pomocy zsh, zamiast szukania literówki, możesz wcisnąć `<Tab>`. W większości przypadków zobaczysz prawidłową komendę.

### Powód #2. `cd` nie jest wymagane

Jeśli dodasz jedną linię do zshrc, będziesz mógł pominąć cd przy zmianie katalogu (nie działa gdy jest coś w `$PATH` z taką samą nazwą):

    setopt autocd

### Powód #3. Bulit-in commands

Czy chcesz użyć podstawowego `more` by przeczytać plik? Powiedz `<filename` i zostanie to uczynione. Potrzebujesz skorzystać z FTP? Masz `zftp`.

### Powód #4. `bindkeys`

Chcesz używać specjalnych klawiszy dla operacji tekstowych? Możesz użyć bindkeys:

    bindkey "\e[1~" beginning-of-line       # Home
    bindkey "\e[4~" end-of-line             # End
    bindkey "\e[5~" beginning-of-history    # PageUp
    bindkey "\e[6~" end-of-history          # PageDown
    bindkey "\e[2~" quoted-insert           # Ins
    bindkey "\e[3~" delete-char             # Del
    bindkey "^[OH"  beginning-of-line       # Home
    bindkey "^[OF"  end-of-line             # End
    bindkey "^[[5~" beginning-of-history    # PageUp
    bindkey "^[[6~" end-of-history          # PageDown
    bindkey "^[[2~" quoted-insert           # Ins
    bindkey "^[[3~" delete-char             # Del
    bindkey "^[[1;5D" backward-word         # ^Left
    bindkey "^[[1;5C" forward-word          # ^Right
{:lang="zsh"}

## Getting The Help

Potrzebujesz pomocy? Wybierz jedno ze źródeł.

### #zsh @ freenode

Uwielbiasz IRC, jak ja? Odwiedź #zsh na freenode.

### ZSHWiki

Z Shell ma swoje własne wiki pod adresem <http://zshwiki.org>.

### Lista Mailingowa

Zapisz się na listę mailingową: <http://zsh.sourceforge.net/Arc/mlist.html>.

### Strona/dokumentacja w Internecie

Możesz znaleźć pomoc pod adresem <http://zsh.sourceforge.net/>.

### Strona man aka *Because zsh contains many features, the zsh manual has been split into a number of sections*

Strona `zsh` zawiera najważniejsze rzeczy i informuje Cię o innych sekcjach. Jeśli nie wiesz gdzie szukać, spróbuj `man zshall`. 

           zsh          Zsh overview
           zshroadmap   Informal introduction to the manual
           zshmisc      Anything not fitting into the other sections
           zshexpn      Zsh command and parameter expansion
           zshparam     Zsh parameters
           zshoptions   Zsh options
           zshbuiltins  Zsh built-in functions
           zshzle       Zsh command line editing
           zshcompwid   Zsh completion widgets
           zshcompsys   Zsh completion system
           zshcompctl   Zsh completion control
           zshmodules   Zsh loadable modules
           zshcalsys    Zsh built-in calendar functions
           zshtcpsys    Zsh built-in TCP functions
           zshzftpsys   Zsh built-in FTP client
           zshcontrib   Additional zsh functions and utilities
           zshall       Meta-man page containing all of the above
