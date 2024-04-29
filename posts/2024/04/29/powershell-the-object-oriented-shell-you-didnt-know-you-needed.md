<!--
.. title: PowerShell: the object-oriented shell you didn’t know you needed
.. slug: powershell-the-object-oriented-shell-you-didnt-know-you-needed
.. date: 2024-04-29 18:45:00+02:00
.. tags: .NET, PowerShell, CSharp, programming, Windows, zsh
.. category: Programming
.. description: Microsoft’s modern shell is much better than Unix sh.
.. type: text
-->

PowerShell is an interactive shell and scripting language from Microsoft. It’s object-oriented — and that’s not just a buzzword, that’s a big difference to how the standard Unix shells work. And it is actually usable as an interactive shell.

<!-- TEASER_END -->

# Getting Started

PowerShell is so nice, Microsoft made it twice.

Specifically, there concurrently exist two products named PowerShell:

* Windows PowerShell (5.1) is a built-in component of Windows. It is proprietary, Windows-only, and is based on the equally proprietary and equally Windows-only .NET Framework 4.x. It has a blue icon.
* PowerShell (7.x), formerly known as PowerShell Core, is a stand-alone application. It is MIT-licensed [(developed on GitHub)](https://github.com/PowerShell/PowerShell), available for Windows, Linux, and macOS, and is based on the equally MIT-licensed and equally multi-platform .NET (formerly .NET Core). It has a black icon.

Windows PowerShell development stopped when PowerShell (Core) came out. There are some niceties and commands missing in it, but it is still a fine option for trying it out or for when one can’t install PowerShell on a Windows system but need to solve something with code.

All examples in this post should work in either version of PowerShell on any OS (unless explicitly noted otherwise).

Install the modern PowerShell: [Windows](https://learn.microsoft.com/en-us/powershell/scripting/install/installing-powershell-on-windows?view=powershell-7.4), [Linux](https://learn.microsoft.com/en-us/powershell/scripting/install/installing-powershell-on-linux?view=powershell-7.4), [macOS](https://learn.microsoft.com/en-us/powershell/scripting/install/installing-powershell-on-linux?view=powershell-7.4).

# Objects? In my shell?

Let’s try getting a directory listing. This is Microsoft land, so let’s try the DOS command for a directory listing — that would be `dir`:

```powershell
PS C:\tmp\hello> dir

    Directory: C:\tmp\hello

Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
d----          2024-04-29    18:00                world
-a---          2024-04-29    18:00             23 example.py
-a---          2024-04-29    18:00              7 foobar.txt
-a---          2024-04-29    18:00             14 helloworld.txt
-a---          2024-04-29    18:00              0 newfile.txt
-a---          2024-04-29    18:00              5 test.txt
```

This looks like a typical (if slightly verbose) file listing.

Now, let’s try to do something useful with this. Let’s get the total size of all `.txt` files.

In a Unix shell, one option is `du -bc *.txt`. The arguments: `-b` (`--bytes`) gives the real byte size, and `-c` (`--summarize`) produces a total. The result is this:

```
7       foobar.txt
14      helloworld.txt
0       newfile.txt
5       test.txt
26      total
```

But how to get just the number? This requires text manipulation (getting the first word of the last line). Something like `du -bc *.txt | tail -n 1 | cut -f 1` will do. There’s also `wc --total=only --bytes *.txt` — but this is specific to GNU wc, so it won’t cut it on \*BSD or macOS. Another option would be to parse the output of `ls -l` — but that might not always be easy, and the output may contain something unexpected added by the specific `ls` version or the user’s specific shell configuration.

Let’s try something in PowerShell. If we do `$x = dir`, we’ll have the output of the `dir` command in `$x`. Let’s try to analyse it further, is the first character a newline?

```powershell
PS C:\tmp\hello> $x = dir
PS C:\tmp\hello> $x[0]

    Directory: C:\tmp\hello

Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
d----          2024-04-29    18:00                world
```

That’s interesting, we didn’t get the first character or the first line, we got the first *file*. And if we try `$x[1]`?

```powershell
PS C:\tmp\hello> $x[1]

    Directory: C:\tmp\hello

Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
-a---          2024-04-29    18:00             23 example.py
```

What if we try getting the `Length` property out of that?

```powershell
PS C:\tmp\hello> $x[1].Length
23
```

It turns out that `dir` returns an array of objects, and PowerShell knows how to format this array (and a single item from the array) into a nice table. What can we do with it? This:

```powershell
PS C:\tmp\hello> Get-ChildItem -Filter '*.txt' |
  ForEach-Object { $_.Length } |
  Measure-Object -Sum

Count             : 4
Average           :
Sum               : 26
Maximum           :
Minimum           :
StandardDeviation :
Property          :

PS C:\tmp\hello> (Get-ChildItem -Filter '*.txt' |
  ForEach-Object { $_.Length } |
  Measure-Object -Sum).Sum
26
PS C:\tmp\hello> (Get-ChildItem -Filter '*.txt' |
  Measure-Object -Sum -Property Length).Sum
26
PS C:\tmp\hello> (Get-ChildItem -Recurse -Filter '*.txt' |
  Measure-Object -Sum -Property Length).Sum
30
PS C:\tmp\hello> $measured = (Get-ChildItem -Recurse -Filter '*.txt' |
  Measure-Object -Sum -Property Length)
PS C:\tmp\hello> $measured.Sum / $measured.Count
6
```

We can iterate over all file objects, get their length (using `ForEach-Object` and a lambda), and then use `Measure-Object` to compute the sum (`Measure-Object` returns an object, we need to get its `Sum` property). We can replace the `ForEach-Object` call with the `-Property` argument in `Measure-Object`. And if we want to look into subdirectories, we can easily add `-Recurse` to `Get-ChildItem`. We get actual integers we can do math on.

You might have noticed I used `Get-ChildItem` instead of `dir` in the previous example. `Get-ChildItem` is the full name of the command (*cmdlet*). `dir` is one of its aliases, alongside `gci` and `ls` (Windows-only to avoid shadowing `/bin/ls`). Many common commands have aliases defined for easier typing and ease of use — `Copy-Item` can be written as `cp` (for compatibility with Unix), `copy` (for compatibility with MS-DOS), and `ci`. In our examples, we could also use `measure` for `Measure-Object` and `foreach` or `%` for `ForEach-Object`. Those aliases are a nice thing to have for interactive use, but for scripts, it’s best to use the full names for readability, and to avoid depending on the environment for those aliases.

# More filesystem operations

## Files per folder

There’s a photo collection in a `Photos` folder, grouped into folders. The objective is to see how many `.jpg` files are in each folder. Here’s the PowerShell solution:

```powershell
PS C:\tmp> Get-ChildItem Photos/*/*.jpg |
  Group-Object { $_.Directory.Name } |
  Sort-Object -Property Count -Descending
Count Name                      Group
----- ----                      -----
   10 foo bar                   {C:\tmp\Photos\foo bar\img001.jpg, C:\tmp\Photos\foo bar\img002.jpg, C:\tmp\Photos\foo bar\img003.jpg…}
    2 example                   {C:\tmp\Photos\example\img101.jpg, C:\tmp\Photos\example\img201.jpg}
```

In Unix land, [StackOverflow has a lot of solutions](https://stackoverflow.com/questions/15216370/how-to-count-number-of-files-in-each-directory). The top solution is `du -a | cut -d/ -f2 | sort | uniq -c | sort -nr` — a lot of tools mashed together, starting with a tool to check disk usage, and a lot of string manipulation. The second solution uses find, read, and shell globbing. The PowerShell solution is quite simple and obvious to anyone who has ever touched SQL.

The above example works for one level of nesting. For more levels, given `Photos\one\two\three.jpg`, use `Get-ChildItem -Filter '*.jpg' -Recurse Photos`, and:

* Group by `$_.Directory.Name` (same as before) to get `two`
* Group by `Split-Path -Parent ([System.IO.Path]::GetRelativePath("$PWD/Photos", $_.FullName))` to get `one/two`
* Group by `([System.IO.Path]::GetRelativePath("$PWD/Photos", $_.FullName)).Split([System.IO.Path]::DirectorySeparatorChar)[0]` to get `one`

(All of the above examples work for a single folder as well. The latter two examples don’t work on Windows PowerShell.)

## Duplicate finder

Let’s build a simple tool to detect byte-for-byte duplicated files. `Get-FileHash` is a shell built-in. We can use `Group-Object` again, and `Where-Object` to filter only matching objects. Computing the hash of every file is quite inefficient, so we’ll group by the file length first, and then ensure the hashes match. This gives us a nice pipeline of 6 commands:

```powershell
# Fully spelled out
Get-ChildItem -Recurse -File |
  Group-Object { $_.Length } |
  Where-Object { $_.Count -gt 1 } |
  ForEach-Object { $_.Group } |
  Group-Object { (Get-FileHash -Algorithm MD5 $_).Hash } |
  Where-Object { $_.Count -gt 1 }

# Using aliases
gci -Recurse -File |
  group { $_.Length } |
  where { $_.Count -gt 1 } |
  foreach { $_.Group } |
  group { (Get-FileHash -Algorithm MD5 $_).Hash } |
  where { $_.Count -gt 1 }

# Using less readable aliases
gci -Recurse -File |
  group { $_.Length } |
  ? { $_.Count -gt 1 } |
  % { $_.Group } |
  group { (Get-FileHash -Algorithm MD5 $_).Hash } |
  ? { $_.Count -gt 1 }
```

# Serious Scripting: Software Bill of Materials

Software Bills of Materials (SBOMs) and supply chain security are all the rage these days. The boss wants to have something like that, i.e. a CSV file with a list of packages and versions, and only the direct production dependencies. Sure, there exist standards like SPDX, but the boss does not like those pesky “standards”. The backend is written in C#, and the frontend is written in Node.js. Since we care only about the production dependencies, we can look at the `.csproj` and `package.json` files. For Node packages, we’ll also try to fetch the license name from the npm API (the API is a bit more complicated for NuGet, so we’ll keep it as a `TODO` in this example).

```powershell
$ErrorActionPreference = "Stop" # stop execution on any error
Set-StrictMode -Version 3.0

function Get-CsprojPackages([string]$Path) {
  return Select-Xml -Path $Path -XPath '//PackageReference' |
    ForEach-Object {
      [PSCustomObject]@{
        Name = $_.Node.GetAttribute("Include")
        Version = $_.Node.GetAttribute("Version")
        Source = 'nuget'
        License = 'TODO'
      }
    }
}

function Get-NodePackages([string]$Path) {
  $nameToVersion = (Get-Content -Raw $Path | ConvertFrom-Json).dependencies
  return $nameToVersion.psobject.Properties | ForEach-Object {
    [PSCustomObject]@{
      Name = $_.Name
      Version = $_.Value
      Source = 'node'
      License = (Get-NodeLicense -Name $_.Name)
    }
  }
}

function Get-NodeLicense([string]$Name) {
  try {
    return (Invoke-RestMethod -TimeoutSec 3
      "https://registry.npmjs.org/$Name").license
  } catch {
    return "???"
  }
}

$csprojData = @(Get-ChildItem -Recurse -Filter '*.csproj' |
  ForEach-Object { Get-CsprojPackages $_.FullName })

$nodeData = @(Get-ChildItem -Recurse -Filter 'package.json' |
  Where-Object { $_.FullName -notlike '*node_modules*' } |
  ForEach-Object { Get-NodePackages $_.FullName })

$allData = $csProjData + $nodeData
$allData | ConvertTo-Csv -NoTypeInformation | Tee-Object sbom.csv
```

Just like every well-written shell script starts with `set -euo pipefail`, every PowerShell script should start with [`$ErrorActionPreference = "Stop"`](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/set-strictmode?view=powershell-7.4) so that execution is stopped as soon as something goes wrong. Note that this does *not* affect native commands, you still need to check `$LASTEXITCODE`. Another useful early command is [`Set-StrictMode -Version 3.0`](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/set-strictmode?view=powershell-7.4) to catch undefined variables.

For `.csproj` files, which are XML, we look for `PackageReference` elements using XPath, and then build a PSCustomObject out of a hashmap — extracting the appropriate attributes from the `PackageReference` nodes.

For `package.json`, we read the file, parse the JSON, and extract the properties of the `dependencies` object (it’s a map of package names to versions). To get the license, we use `Invoke-RestMethod`, which takes care of parsing JSON for us.

In the main body of the script, we look for the appropriate files (skipping things under `node_modules`) and call our parser functions. After retrieving all data, we concatenate the two arrays, convert to CSV, and use `Tee-Object` to output to a file and to standard output. We get this:

```csv
"Name","Version","Source","License"
"AWSSDK.S3","3.7.307.24","nuget","TODO"
"Microsoft.AspNetCore.SpaProxy","7.0.17","nuget","TODO"
"@testing-library/jest-dom","^5.17.0","node","MIT"
"@testing-library/react","^13.4.0","node","MIT"
"@testing-library/user-event","^13.5.0","node","MIT"
"@types/jest","^27.5.2","node","MIT"
"@types/node","^16.18.96","node","MIT"
"@types/react","^18.3.1","node","MIT"
"@types/react-dom","^18.3.0","node","MIT"
"react","^18.3.1","node","MIT"
"react-dom","^18.3.1","node","MIT"
"react-scripts","5.0.1","node","MIT"
"typescript","^4.9.5","node","Apache-2.0"
"web-vitals","^2.1.4","node","Apache-2.0"
```

Could it be done in a different language? Certainly, but PowerShell is really easy to integrate with CI, e.g. [GitHub Actions](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions#example-running-a-command-using-powershell-core) or [Azure Pipelines](https://learn.microsoft.com/en-us/azure/devops/pipelines/tasks/reference/powershell-v2?view=azure-pipelines). On Linux, you might be tempted to use Python — and you could get something done equally simply, as long as you don’t mind using the ugly `urllib.request` library, or alternatively ensuring `requests` is installed (and then you get into the hell that is Python package management).

# Using .NET classes

PowerShell is built on top of .NET. This isn’t just the implementation technology — PowerShell gives access to everything the .NET standard library offers. For example, the alternate ways to group photos in multiple subdirectories we’ve explored above involve a call to a static method of the .NET `System.IO.Path` class.

Other .NET types are also available. Need a HashSet? Here goes:

```powershell
PS> $set = New-Object System.Collections.Generic.HashSet[string]
PS> $set.Add("hello")
True
PS> $set.Add("hello")
False
PS> $set.Add("world") | Out-Null
PS> $set.Count
2
PS> $set -contains "hello"
True
PS> $set -contains "world"
False
```

It is also possible to load any .NET DLL into PowerShell (as long as it’s compatible with the .NET version PowerShell is built against) and use it as usual from C# (although possibly with slightly ugly syntax).

# Sick Windows Tricks

Microsoft supposedly killed off Internet Explorer last year. Attempting to launch `iexplore.exe` will bring up Microsoft Edge. But you see, Internet Explorer is a crucial part of Windows, and has been so for over two decades. Software vendors have built software that depends on IE being there and being able to show web content. Some of them are using web views, but some of them prefer something else: COM.

COM, or Component Object Model, is Microsoft’s thing for interoperability between different applications and/or components. COM is basically a way for classes offered by different vendors and potentially written in different languages to talk to one another. Under the hood, COM is C++ `vtable`s plus standard reference counting and class loading/discovery mechanisms. The .NET Framework, and its successor .NET, have always included COM interoperability. The modern WinRT platform is COM on steroids.

Coming back to Internet Explorer, it exposes some COM classes. They were *not* removed with `iexplore.exe`. This means you can bring up a regular Internet Explorer window in just two lines of PowerShell:

```powershell
$ie = New-Object -ComObject InternetExplorer.Application
$ie.Visible = $true
```

Why would you do that? The `InternetExplorer.Application` object lets you control the browser, e.g. you can use `$ie.Navigate("https://example.com/")` to go to a page. Why would you want to launch IE in 2024? I don’t know, I guess you can use it to laugh in the faces of the Microsoft developers who removed the user-accessible shortcuts? But there definitely exist some legacy applications that expect a COM-controllable IE.

We have already explored the possibility of using classes from .NET. .NET comes with a GUI framework named Windows Forms, [which can be loaded from PowerShell and used to build a GUI.](https://learn.microsoft.com/en-us/powershell/scripting/samples/creating-a-custom-input-box?view=powershell-7.4) There is no form designer, so it requires manually defining and positioning controls, but it actually works.

PowerShell can also do various Windows management tasks. It can manage boot settings, BitLocker, Hyper-V, networking, storage… For example, to get the percentage of disk space remaining:

```powershell
$c = Get-Volume C
"$(($c.SizeRemaining / $c.Size) * 100)%"
```

# Getting out of PowerShell land

As a shell, PowerShell can obviously launch subprocesses. Unlike something like Python, running a subprocess is as simple as running anything else. If you need to `git pull`, you just type that. Or you can make PowerShell interact with non-PowerShell commands, reading output and passing arguments:

```powershell
$changes = (git status --porcelain --null)
if ($LASTEXITCODE -eq 128) {
  throw "Not a git repository"
} elseif ($LASTEXITCODE -ne 0) {
  throw "Getting changes from git failed"
}

if ($null -eq $changes) {
  Write-Host "No changes found"
} else {
  $untrackedFiles = @(
    $changes.Split("`0") |
    Where-Object { $_.StartsWith('?? ') } |
    ForEach-Object { $_.Remove(0, 3) }
  )

  # Alternate spelling for regex fans:
  $untrackedFilesForRegexFans = @(
    $changes.Split("`0") |
    Where-Object { $_ -match '^\?\? ' } |
    ForEach-Object { $_ -replace '^\?\? ','' }
  )

  if ($untrackedFiles) {
    Write-Host "Opening $($untrackedFiles.Length) untracked files in VS Code"
    code $untrackedFiles
  } else {
    Write-Host "No untracked files"
  }
}
```

I chose to compute untracked files with the help of standard .NET string manipulation methods, but there’s also a regex option. On a related note, there are three content check operators: `-match` uses regex, `-like` uses [wildcards](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_wildcards?view=powershell-7.4), and `-contains` checks collection membership.

# Profile script

I use a fairly small profile script that adds some behaviours I’m used to from Unix, and to make Tab completion show a menu. Here are the most basic bits:

```powershell
Set-PSReadLineOption -HistorySearchCursorMovesToEnd
Set-PSReadLineKeyHandler -Key UpArrow -Function HistorySearchBackward
Set-PSReadLineKeyHandler -Key DownArrow -Function HistorySearchForward
Set-PSReadlineKeyHandler -Key ctrl+d -Function DeleteCharOrExit
Set-PSReadlineKeyHandler -Key Tab -Function MenuComplete
Set-PSReadLineOption -AddToHistoryHandler {
  param($command)
  # Commands starting with space are not remembered.
  return -not ($command -like ' *')
}
```

Apart from that, I use a few aliases and a pretty prompt with the help of [oh-my-posh](https://ohmyposh.dev/).

# The unusual and sometimes confusing parts

PowerShell can be verbose. Some of its syntax is a little quirky, compared to other languages, e.g. the equality and logic operators (for example, `-eq`, `-le`, `-and`). The aliases usually help with remembering commands, but they can’t always be depended on — `ls` is defined as an alias only on Windows, and Windows PowerShell aliases `wget` and `curl` to `Invoke-WebRequest`, even though all three have completely different command line arguments and outputs (this was removed in PowerShell).

Moreover, the Unix/DOS aliases do not change the argument handling. `rm -rf foo` is invalid. `rm -r foo` is, since argument names can be abbreviated as long as the abbreviation is unambiguous. `rm -r -f foo` is not valid, because `-f` can be an abbreviation of `-Filter` or `-Force` (so `rm -r -fo foo`) will do. `rm foo bar` does not work, an array is needed: `rm foo,bar`.

`C:\Windows\regedit.exe` launches the Registry editor. `"C:\Program Files\Mozilla Firefox\firefox.exe"` is a string. Launching something with spaces in its name requires the call operator: `& "C:\Program Files\Mozilla Firefox\firefox.exe"`. PowerShell’s tab completion will add the `&` if necessary.

There are two function call syntaxes. Calling a function/cmdlet uses the shell-style syntax with argument names: `Some-Function -Arg1 value1 -Arg2 value2`, and argument names can be abbreviated, and can sometimes be omitted. Calling a method requires a more traditional syntax: `$obj.SomeMethod(value1, value2)`. Names are case-insensitive in either case.

The escape character is the backtick. The backslash is the path separator in Windows, so making it an escape character would make everything painful on Windows. At least it makes it easy to write regex.

# The ugliest part

The ugliest and the least intuitive part of PowerShell is the handling of single-element arrays. PowerShell *really* wants to unpack them to a scalar. The command `(Get-ChildItem).Length` will produce the number of files in the current directory — *unless* there is exactly one file, in which case it will produce the single file’s size in bytes. And if there are zero items, instead of an empty array, PowerShell produces `$null`. Sometimes, things will work out in the end (since many cmdlets are happy to get either as inputs), but sometimes, PowerShell must be asked to stop this madness and return an array: `@(Get-ChildItem).Length`.

The previous example with `git status` leverages its `--null` argument to get zero-delimited data, so we expect either `$null` or a single string according to the rules. If we didn’t want to use `--null`, we would need to use `@(git status --porcelain)` to always get an array (but we would also need to remove quotes that `git` adds to paths that contain spaces).

# Conclusion

PowerShell is a fine interactive shell and scripting language. While it does have some warts, it is more powerful than your usual Unix shell, and its strongly-typed, object-oriented code beats *stringly-typed* `sh` spaghetti any day.
