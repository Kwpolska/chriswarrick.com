---
title: "Date Conditionals"
published: "2026-02-01T00:00:00+00:00"
description: "Date range parser in Python."
devstatus: 4
download: "https://github.com/Kwpolska/datecond/releases"
github: "https://github.com/Kwpolska/datecond"
bugtracker: "https://github.com/Kwpolska/datecond/issues"
role: "Maintainer"
license: "3-clause BSD"
language: "Python"
sort: 30
---
This is a minimalistic (and slightly hacky) parser for date range conditionals.

Supported format:

* comma-separated clauses (AND)
* clause: attribute comparison_operator value (spaces optional)
  * attribute: year, month, day, hour, month, second, weekday, isoweekday or empty for full datetime
  * comparison_operator: == != <= >= < >
  * value: integer, 'now' or dateutil-compatible date input

For example, you can state `year == 2016, month > 06, day >= 07` which
matches dates between July and December 2016, but ignoring days 1-6 of each
month.
