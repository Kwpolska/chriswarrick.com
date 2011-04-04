---
layout: plpost
title: Jekyll dla Bystrzaków
category: polish
---
**Ten post jest wstępem do Jekylla, świetnego, <q>prostego, blogowego genneratora stron statycznych</q> Jest to how-to pomagające w instalacji Jekylla.**

## O Jekyllu
> # Jekyll
>
> By Tom Preston-Werner, Nick Quaranto, and many awesome contributors!
>
> Jekyll is a simple, blog aware, static site generator. It takes a template directory (representing the raw form of a website), runs it through Textile or Markdown and Liquid converters, and spits out a complete, static website suitable for serving with Apache or your favorite web server. This is also the engine behind [GitHub Pages][], which you can use to host your project&#8217;s page or blog right here from GitHub.
(Cytat z README)

## Krok 1. Zainstaluj Jekylla

Na początku musisz zainstalować Jekylla (a czego się spodziewałeś?). Możesz t zrobić na swoim serwerze lub na swoim domowym komputerze, jeśli znajdziesz zwadiowany sposób [wdrożenia][deploy] takiej implementacji. Innym sposobem jest użycie [GitHub Pages][]. Na linuksie, możesz to zrobić w ten sposób:
    gem install jekyll --user-install
Jeśli masz problemy, naprawdopodoniej musisz zainstalować odopwiedni pakiet. [Wiki Jekylla][wiki] ma więcej inforacji na ten temat: [Jekyll Wiki: Install][install]

## Krok 2: Skonfiguruj Jekylla i przygotuj katalogi

Stwórz katalog dla Jekylla. Ja używam `~/jekyll`. Wejdź do niego i stwórz dwa katalogi i dwa pliki. Katalogi to `_layouts` i `_posts`. Pliki to `_config.yml` i `index.html`. Jeśli chcesz, możesz utworzyć katalog `_site`.

Katalog `_layouts` przechowuje style twojej strony. Możesz mieć rózne style dla swojej strony głównej, postów lub stron. Możesz używać danych szablonów (patrz niżej). Pliki powinny być w HTML-u, ale **muszą mieć rozszerzenie .yml**.

Katalog `_posts` przechowuje twoje posty zapisane w Markdownie lub w Textile. Musi zaczynać się od [YAML Front Matter][yaml]. Plik musi być nazwany jak następuje: `YEAR-MO-DD-the-name-of-the-post-that-will-be-used-in-the-url.PARSER`, gdzie `YEAR` to rok, `MO` to miesiąc, `DD` to dzień a `PARSER` to `markdown` lub `textile`.

Plik `_config.yml` jest plikiem konfiguracyjnym Jekylla. Możesz dowiedzieć się więcej na świetnym Jekyll Wiki w artykule [Konfiguracja][Configuration].

Plik `index.html` to strona główna bloga. Musi zaczynać się od [YAML Front Matter][yaml]. Możesz zobaczyć mój tutaj: [Mój index.html][index].

### Dane szablonów

Dane szablonów są elementami wstawianymi do styli i do zwykłych stron. Najważniejsze to `content` i `title`. Możesz poznać je wszystkie w artykule na wiki nazwanym [Template Data][tdata].

## Krok 3: Kontynuuj tworzenie strony

Teraz możesz kontynuować tworzenie swojej strony. Możesz utworzyć feedy, strony, style, szablony CSS i pisać posty LUB importować treść z istniejącego bloga.

## Krok 4: Wygeneruj swoją stronę

Jeśli nie używasz [GitHub Pages][], musisz teraz wygenerować stronę. Musisz użyć komendy `jekyll` i otrzymasz Jekyll w katalogu ustawionym w konfiguracji. Przy [GitHub Pages][] wystarczy zaktualizować repozytorium.

## Dlaczego używać Jekylla?

Jekyll jest świetny, ponieważ generuje on strony *statycczne* przy użyciu Markdowna. Sprawia to, że kod jest czytelny. Pod tym paragrafem znajdują się wszystkie linki z tego posta.

    [index]:         https://github.com/Kwpolska/kwsblog/blob/master/index.html "Mój index.html"
    [github pages]:  http://pages.github.com "GitHub Pages"
    [wiki]:          http://github.com/mojombo/jekyll/wiki "Jekyll Wiki"
    [install]:       https://github.com/mojombo/jekyll/wiki/Install "Jekyll Wiki: Install"
    [deploy]:        https://github.com/mojombo/jekyll/wiki/Deployment "Jekyll Wiki: Deployment"
    [configuration]: https://github.com/mojombo/jekyll/wiki/Configuration "Jekyll Wiki: Configuration"
    [yaml]:          https://github.com/mojombo/jekyll/wiki/YAML-Front-Matter "Jekyll Wiki: YAML Front Matter"
    [tdata]:         https://github.com/mojombo/jekyll/wiki/Template-Data "Jekyll Wiki: Liquid template data"

Ale to nie wszystko. Jest wiele innych interesujących rzeczy w Jekyllu.

[index]:         https://github.com/Kwpolska/kwsblog/blob/master/index.html "Mój index.html"
[github pages]:  http://pages.github.com "GitHub Pages"
[wiki]:          http://github.com/mojombo/jekyll/wiki "Jekyll Wiki"
[install]:       https://github.com/mojombo/jekyll/wiki/Install "Jekyll Wiki: Install"
[deploy]:        https://github.com/mojombo/jekyll/wiki/Deployment "Jekyll Wiki: Deployment"
[configuration]: https://github.com/mojombo/jekyll/wiki/Configuration "Jekyll Wiki: Configuration"
[yaml]:          https://github.com/mojombo/jekyll/wiki/YAML-Front-Matter "Jekyll Wiki: YAML Front Matter"
[tdata]:         https://github.com/mojombo/jekyll/wiki/Template-Data "Jekyll Wiki: Liquid template data"
