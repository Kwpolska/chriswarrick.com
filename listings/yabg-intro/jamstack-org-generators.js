/* Run this in the console on https://jamstack.org/generators/ */
Object.entries(
  Object.groupBy(
    [...document.querySelectorAll(".generator-card")]
      .map(g => g.dataset.filterLanguage),
    g => g
  )
)
  .map(([k, v]) => `${k} ${v.length}`)
  .join('\n');

/* Full results as of 2026-02-15 after cleanup and merging:
JavaScript/TypeScript   154
Python                   55
PHP                      28
Ruby                     21
Go                       16
C#                       13
Java                      8
Bash                      7
Perl                      7
Haskell                   6
Scala                     6
Elixir                    5
Rust                      5
C++                       4
CoffeeScript              4
Ocaml                     4
C                         3
Elm                       3
R                         3
Swift                     3
Clojure                   2
F#                        2
Racket                    2
Ada                       1
AWK                       1
Crystal                   1
D                         1
Erlang                    1
Groovy                    1
Guile                     1
HTML                      1
Janet                     1
Julia                     1
Lisp                      1
M4                        1
Nim                       1
Nix                       1
Object Pascal             1
Tcl                       1
*/
