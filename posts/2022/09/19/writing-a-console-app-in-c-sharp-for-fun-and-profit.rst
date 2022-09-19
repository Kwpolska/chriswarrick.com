.. title: Writing a Console App in C# for Fun and Profit
.. slug: writing-a-console-app-in-c-sharp-for-fun-and-profit
.. date: 2022-09-19 22:35:00+02:00
.. tags: .NET, CSharp, programming, web development
.. category: Programming
.. link:
.. description: I wrote an app in C# and I liked it.
.. type: text

I recently wrote a simple console app in C#. This post tells you more about the language, the .NET ecosystem, and why you should write your next app in it.

.. TEASER_END

What is the app?
================

The app is called Gitco.NET. It’s basically a rewrite of a previous Ruby script. It shows a console menu with Git branches, allowing things like filtering and toggling the display of remote branches. Simple, but quite convenient for working with Git in a terminal. I rewrote it in C# for better Windows compatibility — but the new version works on Linux and macOS equally well, can be distributed as a single executable, and is also unit-tested.

What is C#?
===========

C# is a modern, high-level language designed by Microsoft in 2000, heavily inspired by (and competing with) Java.

The obligatory hello world program
----------------------------------

.. code:: csharp

   Console.WriteLine("Hello, world!");

Just one line is enough. This program requires C# 10 and .NET 6, the latest versions of the language and the framework (and the implicit usings feature enabled).

The slightly less cool version of the hello world program
---------------------------------------------------------

.. code:: csharp

   using System;

   class Program
   {
     static void Main(string[] args)
     {
       Console.WriteLine("Hello, world!");
     }
   }

We’ve got four lines of code (plus four lines of braces [1]_ ). We can see the ``using`` directive to import everything from the ``System`` namespace, the definition of a ``class``, a ``Main`` method, and a call to ``Console.WriteLine``.

We’ll talk more about C# later, highlighting some of the cooler things seen in Gitco.NET.

What is .NET?
=============

The term “.NET” had quite a lot of meanings over the past two decades. Microsoft accounts were once called .NET Passport, and Windows Server 2003 was almost called “Windows Server .NET 2003”. Another thing called .NET was the .NET Framework. .NET Framework is a heavily integrated component of Windows, and it’s basically what was used to run C# (and F#, and VB.NET) — it includes the virtual machine (CLR, Core Language Runtime), a lot of libraries (Framework Class Library), and a lot of Windows-specific things (such as COM, Windows Forms, WPF).

Microsoft’s .NET Framework is proprietary and tied to Windows. An open-source, independent re-implementation of .NET is Mono. At one point, some GNOME apps were written in Mono and Gtk#. Mono was also used in Xamarin, which can be used to write Android and iOS apps in C#.

But then came out .NET Core, which is Microsoft’s open-source .NET with a new runtime (CoreCLR), new set of libraries (CoreFX), and multi-platform compatibility (Linux and macOS). After a few years, .NET Core got renamed to .NET (around the time it had pretty good feature parity with the classic .NET Framework). With the new .NET, you can build console apps, web apps (using ASP.NET Core, which is a pretty cool framework), mobile apps (soon using MAUI), and desktop apps (there are a few options).

Gitco.NET code tour
===================

Let’s go on a little tour of the more interesting parts of the code.

Snippet 1
---------

.. code:: csharp

   public static List<Branch> ExtractBranchListFromGitOutput(string gitOutput)
     => gitOutput
       .TrimEnd()
       .ReplaceLineEndings("\n")
       .Split('\n')
       .Select(branchLine =>
       {
         var isCurrent = branchLine.StartsWith('*');
         var branch = branchLine[2..];
         var isRemote = false;

         if (branch.StartsWith(remotePrefix))
         {
           isRemote = true;
           branch = string.Join(
             '/',
             branch.Split(" ").First().Split("/").Skip(2));
         }

         return new Branch(branch, isRemote, isCurrent);
       })
       .OrderBy(b => b.Name)
       .ThenBy(b => b.IsRemote)
       .DistinctBy(b => b.Name)
       .ToList();

This snippet defines a fairly standard pipeline that goes from ``git`` output (a single string) to a list of parsed objects. This pipeline is a function (or a static method, to be more precise). This function uses expression-bodied members: since we can fit the entire pipeline in a single expression, we can skip the braces and the ``return`` keyword, and instead use a more compact syntax with an arrow (``=>``). After some cleanups and sanitization of the string, we split the string by the ``\n`` character, and the type of our pipeline changes from ``string`` to ``string[]`` (an array of strings). We then use five operations from the ``System.Linq`` namespace. Those operations are extension methods for enumerables (``IEnumerable<T>``) — adding ``using System.Linq;`` at the top of your program adds those methods to any enumerables (including arrays, lists, dictionaries, sets…).

The first operation is a ``Select``. LINQ methods are inspired by SQL; the more typical name for this one would be ``map``. (Similarly, ``Where`` is LINQ’s name for ``filter``.) The logic inside ``Select`` is written in a multi-line anonymous function (lambda), with braces (so there’s a ``return``) [2]_. Inside that anonymous function, there are a few niceties, such as ``var`` (type inference for variables), slicing (``[2..]``), as well as some more LINQ in string manipulations (``.First()`` and ``.Skip(2)``, which do what they say on the tin).

The next three operations are fairly straightforward sorting, and extracting unique values. Those use single-expression lambdas, which don’t use ``return``. The pipeline ends with converting ``IEnumerable<Branch>`` (which appeared at the ``.Select()`` stage) into a ``List<Branch>``.

Snippet 2
---------

.. code:: csharp

   public static IEnumerable<BranchDisplay> FilterAndNumberBranches(
    List<Branch> branches, string? filter)
   {
     var branchWidth = branches.Count
       .ToString(CultureInfo.InvariantCulture).Length;
     var numberFormatString = $"{{0,{branchWidth}}}. ";

     return branches.Select(
       (branch, index) =>
         new BranchDisplay(
           Number: string.Format(numberFormatString, index + 1),
           BranchName: branch.Name,
           IsRemote: branch.IsRemote,
           IsCurrent: branch.IsCurrent
         )
       ).Where(branchDisplay =>
         filter == null || branchDisplay.BranchName.Contains(filter));
   }

This function adds numbers to the branch list, and then filters branches based on the user’s query. The first thing to notice is the second argument: ``string? filter``. C# has support for nullable types, which means the compiler warns you if you use a possibly null value somewhere it isn’t expected [3]_. ``numberFormatString`` uses an interpolated string, in which ``{branchWidth}`` will be replaced with the variable defined before. In the LINQ expression, you can see two interesting things: one is a two-argument lambda for ``Select``, and argument names, which can be optionally passed to functions and constructors for readability or to set parameters out of order.

Snippet 3
---------

How much boilerplate do you need to define an immutable data class with a constructor, value equality, and a string representation?

Exactly zero:

.. code:: csharp

   public record Branch(
    string Name,
    bool IsRemote = false,
    bool IsCurrent = false);

   public record BranchDisplay(
    string Number,
    string BranchName,
    bool IsRemote = false,
    bool IsCurrent = false);

(If you want things to be mutable, you do need to write some more code. Still, all you need for encapsulated properties is ``int Foo { get; set; }``, which is miles better than having to write out getters and setters by hand, as you would do in Java.)

Dependency management
=====================

Gitco.NET is a fairly simple thing, and it doesn’t need any third-party libraries, it can do its job with just the standard library.

However, Gitco.NET has a test suite. .NET doesn’t ship with a unit testing framework. There are three popular options, I picked xUnit (which is the most popular). I created the test project with a template, and then added a reference to the main code (under test). I ended up with the following project file (``gitco.NET.Tests.csproj``):

.. code:: xml

   <Project Sdk="Microsoft.NET.Sdk">

     <PropertyGroup>
       <TargetFramework>net6.0</TargetFramework>
       <ImplicitUsings>enable</ImplicitUsings>
       <LangVersion>10.0</LangVersion>
       <Nullable>enable</Nullable>
       <IsPackable>false</IsPackable>
     </PropertyGroup>

     <ItemGroup>
       <PackageReference Include="Microsoft.NET.Test.Sdk" Version="17.1.0" />
       <PackageReference Include="xunit" Version="2.4.1" />
       <PackageReference Include="xunit.runner.visualstudio" Version="2.4.3">
         <IncludeAssets>runtime; build; native; contentfiles; analyzers; buildtransitive</IncludeAssets>
         <PrivateAssets>all</PrivateAssets>
       </PackageReference>
       <PackageReference Include="coverlet.collector" Version="3.1.2">
         <IncludeAssets>runtime; build; native; contentfiles; analyzers; buildtransitive</IncludeAssets>
         <PrivateAssets>all</PrivateAssets>
       </PackageReference>
     </ItemGroup>

     <ItemGroup>
       <ProjectReference Include="..\gitco.NET\gitco.NET.csproj" />
     </ItemGroup>

   </Project>

Yeah, it’s an XML file. But it’s pretty straightforward: there’s a ``<PropertyGroup>`` with some project configuration, and two ``<ItemGroup>>`` tags. One of them has ``<PackageReference>`` tags, which specify third-party dependencies to use. The other has a ``<ProjectReference>`` to the main code, pointing at its ``.csproj`` file. (Note that this split is arbitrary, you can have as many ``<ItemGroup>`` tags as you want, you could have just one with both package and project references.)

How does this work? Quite simply, and transparently to the developer. Building the project will lead to packages being *restored* (fetched from NuGet, or copied from the local NuGet cache). There are no “virtual environments” to manage, there aren’t 10 competing package managers. Visual Studio will also expect both projects to be part of one solution, which is something you’d likely do anyway for convenient access to both at the same time.

Tooling
=======

dotnet CLI
----------

.NET has a CLI for performing typical build and project configuration tasks. You can ``dotnet build`` a project, you can ``dotnet run`` it, you can ``dotnet test`` things, and you can ``dotnet publish``. The CLI figures out what to do, it restores the dependencies if needed, it handles the compilation of your code. If you type ``dotnet test`` in a directory with your solution file (``.sln``), it will restore dependencies, build the code, and then find tests and run them.

IDE
---

What IDE should you use? There are a few options:

* **Visual Studio Code.** The quite advanced text editor supports pretty much any language. C# support works okay, with all the IDE features available, but in my experience, it can sometimes get confused (requiring a restart of the IDE). You will probably need to spend some more time with the ``dotnet`` CLI than you would with the other options.
* **Visual Studio for Windows.** The IDE with the purple icon is an option, although VS can feel arcane to people used to other IDEs/editors, and the Vim bindings are quite bad (especially if you select things with a mouse sometimes). It’s free for personal and very-small-business use, but for anything even slightly serious, you’ll need paid licenses.
* **Visual Studio for Mac.** A completely separate product, works reasonably well, same pricing as with the Windows version.
* **Visual Studio for Windows + ReSharper Ultimate.** Adding this (paid) extension makes VS much smarter, although it can also affect performance negatively.
* **JetBrains Rider.** This is an IDE based on the IntelliJ platform, with all the magic seen in ReSharper (as well as other JetBrains IDEs), but none of the performance issues and Visual Studio being Visual Studio (although if you do prefer VS behaviors and keyboard shortcuts, you can configure those as well). This is probably your best bet if you’re willing to invest some money (or your employer is).

Why should I pick it over X?
============================

Well, it depends. If this post has piqued your interest, perhaps install the SDK and write some small things to get a feel for the language and to see if it’s for you. (And note this post didn’t cover the Web stuff.)

But here are a few things of note:

Python
------

* C# is statically typed. Modern Python’s static typing (via things like mypy) is quite cool, but not all libraries and ecosystems have adopted it. Statically typed languages are safer, and allow IDEs to be smarter.
* C# has a better approach to functional programming. Python has ugly and single-expression lambdas (with a pointless ``lambda`` keyword), C# has inline functions that can contain multiple statements.
* C# has much better package management.
* C# is trivial to compile to a single-file executable.
* C# is much faster than Python.
* ~Nobody does machine learning and data science in C#, which is a plus in my book.

Java
----

* C# has a lot more developer-quality-of-life features and less boilerplate. For example, Lists and Dictionaries can be accessed using brackets, and properties are accessible via dot notation instead of having to explicitly call getter and setter methods.
* C#’s generic are more flexible, as they aren’t erased on compilation.
* C# has null safety. It also has the safe navigation ``?.`` operator, and the null coalescing ``??`` operator, both of which make working with nullable values easier.
* C# has easy concurrency via ``async`` and ``await``.
* Web stuff: Spring is painful, Spring Boot doesn’t make it much better. ASP.NET Core is much nicer.

*Additional reading:* Wikipedia has a very nice and detailed `Comparison of C# and Java <https://en.wikipedia.org/wiki/Comparison_of_C_Sharp_and_Java>`_.

Also…
-----

* C# is a high-level language with automated memory management, which is very convenient in many use-cases.
* C# has exceptions.
* There are quite a lot of jobs for C# developers, although not necessarily in Silicon Valley.

But on the other hand…
----------------------

* C# can still sometimes feel a bit Windows-oriented.
* C# jobs tend to be enterprisey.
* Python is a great language to learn as a beginner. It’s also great for one-off things, interactive work, and scripting.
* The non-Windows desktop GUI story isn’t too great, although it is getting better with MAUI (which supports macOS).
* If you’re targeting mobile, I would probably focus on the native APIs and languages for the best user experience (Swift and Cocoa Touch for iOS; Kotlin and the Android Platform APIs for Android). That said, MAUI might be worth a go as well.
* If you’re doing very low-level stuff, C# probably won’t cut it.
* If you want real functional programming, go with F#. You might also prefer Scala or Haskell or such.
* And if you’re making web front-end stuff, TypeScript (or plain JavaScript) is still your best bet. C# has Blazor, but I’d prefer for web apps not to embed all of .NET via WebAssembly.

But for console apps, Windows desktop, and web back-end services? **Do give C# a try,** it might just win you over. It is a pretty good language, but one that was held back by the Windows association for a long time. But now it’s part of a modern, multi-platform, developer-friendly ecosystem.

Footnotes
=========

.. [1] The code samples in this post are using the usual Microsoft code style with braces on separate lines, the usual Microsoft naming convention (PascalCase for ~everything, camelCase for local variable names), and 2-space indentation, which isn’t the usual Microsoft style.
.. [2] This could be moved to a separate static method. If that method was ``private static Branch ParseLineAsBranch(string branchLine)``, then the expression could be ``.Select(ParseLineAsBranch)``.
.. [3] There’s some inconsistency and mixing when working with nullables: nullable objects (such as ``string?``) are accessible directly, whereas nullable value types (such as ``int?``) need to be accessed with ``.Value``, due to historical reasons and implementation details.
