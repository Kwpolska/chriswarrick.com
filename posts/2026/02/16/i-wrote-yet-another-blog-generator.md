---
title: "I Wrote YetAnotherBlogGenerator"
htmlTitle: "I Wrote <i>YetAnotherBlogGenerator</i>"
published: "2026-02-16 22:15:00+01:00"
tags: ["YetAnotherBlogGenerator", "C#", ".NET", "Nikola", "Python", "web development", "static site generators"]
category: "C#/.NET"
description: "This site is now powered by a custom static site generator."
showTableOfContents: true
---

Writing a static site generator is a developer rite of passage. For the past 13 years, this blog was generated using [Nikola](https://getnikola.com/). This week, I finished implementing my own generator, the unoriginally named [YetAnotherBlogGenerator](https://github.com/Kwpolska/YetAnotherBlogGenerator).

Why would I do that? Why would I use C# for it? And how fast is it? Continue reading to find out.

<!-- TEASER_END -->

## OK, but why?

You might have noticed I’m not happy with [the Python](/blog/2023/01/15/how-to-improve-python-packaging/) [packaging ecosystem](/blog/2024/01/15/python-packaging-one-year-later). But the language itself is no longer fun for me to code in either. It is especially not fun to maintain projects in. [Elementary quality-of-life features](https://discuss.python.org/t/revisiting-pep-505-none-aware-operators/74568/) get bogged down in months of discussions and design-by-committee. At the same time, there’s a new release every year, full of removed and deprecated features. A lot of churn, without much benefit. I just don’t feel like doing it anymore.

Python is praised for being fast to develop in. That’s certainly true, but a good high-level statically-typed language can yield similar development speed with more correctness from day one. For example, I coded an entire table-of-contents-sidebar feature in one evening (and one more evening of CSS wrangling to make it look good). This feature extracts headers from either the Markdown AST or the HTML fragment. I could do it in Python, but I’d need to jump through hoops to get Python-Markdown to output headings with IDs. In C#, introspecting what a class can do is easier thanks to great IDE support and much less dynamic magic happening at runtime. There are also decompiler tools that make it easy to look under the hood and see what a library is doing.

Writing a static site generator is also a learning experience. A competent SSG needs to ingest content in various formats (as nobody wants to write blog posts in HTML by hand) and generate HTML (usually from templates) and XML (which you could, in theory, do from templates, but since XML parsers are not at all lenient, you don’t want to). Image processing to generate thumbnails is needed too. And to generate correct RSS feeds, you need to parse HTML to rewrite links. The list of small-but-useful things goes on.

## Is C#/.NET a viable technology stack for a static site generator?

C#/.NET is certainly not the most popular technology stack for static site generators. [JamStack.org](https://jamstack.org/generators/) have gathered a list of 377 SSGs. [Grouping by language](/listings/yabg-intro/jamstack-org-generators.js.html), there are 154 generators written in JavaScript or TypeScript, 55 generators written in Python, and 28 written in *PHP* of all languages. C#/.NET is in sixth place with 13 (not including YABG; I’m probably not submitting it).

However, it is a pretty good choice. Language-level support for concurrency with `async`/`await` (based on a thread pool) and JIT compilation help to make things fast. But it is still a high-level, object-oriented language where you don’t need to manually manage memory (hi Rustaceans!).

The library ecosystem is solid too. There are plenty of good libraries for working with data serialization formats: [CsvHelper](https://joshclose.github.io/CsvHelper/), [YamlDotNet](https://github.com/aaubry/YamlDotNet), [Microsoft.Data.Sqlite](https://www.nuget.org/packages/Microsoft.Data.Sqlite/), and the built-in [System.Text.Json](https://learn.microsoft.com/en-us/dotnet/standard/serialization/system-text-json/overview) and [System.Xml.Linq](https://learn.microsoft.com/en-us/dotnet/standard/linq/linq-xml-overview). [Markdig](https://github.com/xoofx/markdig) handles turning Markdown into HTML. [Fluid](https://github.com/sebastienros/fluid) is an excellent templating library that implements the Liquid templating language. [HtmlAgilityPack](https://html-agility-pack.net/) is solid for manipulating HTML, and [Magick.NET](https://github.com/dlemstra/Magick.NET) wraps the ImageMagick library.

```xml
<PackageReference Include="CsvHelper" Version="33.1.0"/>
<PackageReference Include="Fluid.Core" Version="2.31.0"/>
<PackageReference Include="Fluid.ViewEngine" Version="2.31.0"/>
<PackageReference Include="HtmlAgilityPack" Version="1.12.4"/>
<PackageReference Include="Magick.NET-Q8-AnyCPU" Version="14.10.2"/>
<PackageReference Include="Markdig" Version="0.45.0"/>
<PackageReference Include="Microsoft.Data.Sqlite" Version="10.0.3"/>
<PackageReference Include="Microsoft.Extensions.FileProviders.Physical" Version="10.0.3"/>
<PackageReference Include="Microsoft.Extensions.Logging.Console" Version="10.0.3"/>
<PackageReference Include="YamlDotNet" Version="16.3.0"/>
```

There’s one major thing missing from the above list: code highlighting. [There are a few highlighting libraries on NuGet](https://www.nuget.org/packages?q=highlight), but I decided to stick with [Pygments](https://pygments.org/). I still need the Pygments stylesheets around since I’m not converting old reStructuredText posts to Markdown (I’m copying them as HTML directly from Nikola’s `cache`), so using Pygments for new content keeps things consistent. Staying with Pygments means I still maintain a bit of Python code, but much less: 230 LoC in `pygments_better_html` and 89 in `yabg_pygments_adapter`, with just one third-party dependency. Calling a subprocess while rendering listings is slow, but it’s a price worth paying.

### Paid libraries in the .NET ecosystem

All the above libraries are open source (MIT, Apache 2.0, BSD-2-Clause). However, one well-known issue of the .NET ecosystem is the number of packages that suddenly become commercial. This trend was started by [ImageSharp](https://dotnetfoundation.org/news-events/detail/update-on-imagesharp), a popular 2D image manipulation library. I could probably use it, since it’s licensed to open-source projects under Apache 2.0, but I’d rather not. I initially tried [SkiaSharp](https://www.nuget.org/packages/SkiaSharp/), but it has terrible image scaling algorithms, so I settled on [Magick.NET](https://www.nuget.org/packages/SkiaSharp).

Open-source sustainability is hard, maybe impossible. But I don’t think transitioning from open-source to pay-for-commercial-use is the answer. In practice, many businesses just use the last free version or switch to a different library. I’d rather support open-source projects developed by volunteers in their spare time. They might not be perfect or always do exactly what I want, but I’m happy to contribute fixes and improve things for everyone. I will avoid proprietary or dual-licensed libraries, even for code that never leaves my computer. Some people complain when Microsoft creates a library that competes with a third-party open-source library (e.g. [Microsoft.AspNetCore.OpenApi](https://www.nuget.org/packages/Microsoft.AspNetCore.OpenApi), which was built to replace [Swashbuckle.AspNetCore](https://www.nuget.org/packages/Swashbuckle.AspNetCore)), but I am okay with that, since libraries built or backed by large corporations (like Microsoft) tend to be better maintained.

But at least sometimes [trash libraries take themselves out](https://www.jimmybogard.com/automapper-and-mediatr-commercial-editions-launch-today/).

## Is it fast?

One of the things that set Nikola apart from other Python static site generators is that it only rebuilds files that need to be rebuild. This does make Nikola fast when rebuilding things, but it comes at a cost: Nikola needs to track all dependencies very closely. Also, some features that are present in other SSGs are not easy to achieve in Nikola, because they would cause many pages to be rebuilt.

YetAnotherBlogGenerator has almost no caching. The only thing currently cached is code listings, since they’re rendered using Pygments in a subprocess. Additionally, the image scaling service checks the file modification date to skip regenerating thumbnails if the source image hasn’t changed. And yet, even if it rewrites everything, YABG finishes faster than Nikola when the site is fully up-to-date (there is nothing to do).

I ran some quick benchmarks comparing the performance of rendering the final Nikola version of this blog against the first YABG version (before the Bootstrap 5 redesign).

### Testing methodology

Here’s the testing setup:

* AWS EC2 instances
  * c7a.xlarge (4 vCPU, 8 GB RAM)
  * 30 GB io2 SSD (30000 IOPS)
  * Total cost: $2.95 + tax for about an hour’s usage ($2.66 of which were storage costs)
* Fedora 43 from official Fedora AMI
  * Python 3.14.2 (latest available in the repos)
  * .NET SDK 10.0.102 / .NET 10.0.2 (latest available in the repos)
  * setenforce 0, SELINUX=disabled
* Windows Server 2025
  * Python 3.14.3 (latest available in winget)
  * .NET SDK 10.0.103 / .NET 10.0.3 (latest available in winget)
  * Windows Defender disabled

I ran three tests. Each test was run 11 times. The first attempt was discarded (as a warmup and to let me verify the log). The other ten attempts were averaged as the final result. I used PowerShell’s `Measure-Command` cmdlet for measurements.

The tests were as follows:

1. **Clean build (no cache, no output)**
   * Removing `.doit.db`, `cache`, and `output` from the Nikola site, so that everything has to be rebuilt from scratch.
   * Removing `.yabg_cache.sqlite3` and `output` from the YABG site, so that everything has to be reuilt from scratch, most notably the Pygments code listings have to be regenerated via a subprocess.
2. **Build with cache, but no output**
   * Removing `output` from the Nikola site, so that posts rendered to HTML by docutils/Python-Markdown are cached, but the final HTML still need to be built.
   * Removing `output` from the YABG site, so that the code listings rendered to HTML by Pygments are cached, but everything else needs to be built.
3. **Rebuild (cache and output intact)**
   * Not removing anything from the Nikola site, so that there is nothing to do.
   * Not removing anything from the YABG site. Things are still rebuilt, except for Pygments code listings and thumbnails.

For YetAnotherBlogGenerator, I tested two builds: one in Release mode (standard), and another in [ReadyToRun mode](https://learn.microsoft.com/en-us/dotnet/core/deploying/ready-to-run), trading build time and executable size for faster execution.

All the scripts I used for setup and testing can be found in [listings](/listings/yabg-intro/speedtest/).

### Test results

| Platform    | Build type                        | Nikola | YABG (ReadyToRun) | YABG (Release) |
| ----------- | --------------------------------- | ------:| -----------------:| --------------:|
| **Linux**   | Clean build (no cache, no output) | 6.438  | 1.901             | 2.178          |
| **Linux**   | Build with cache, but no output   | 5.418  | 0.980             | 1.249          |
| **Linux**   | Rebuild (cache and output intact) | 0.997  | 0.969             | 1.248          |
| **Windows** | Clean build (no cache, no output) | 9.103  | 2.666             | 2.941          |
| **Windows** | Build with cache, but no output   | 7.758  | 1.051             | 1.333          |
| **Windows** | Rebuild (cache and output intact) | 1.562  | 1.020             | 1.297          |

## Design details and highlights

Here are some fun tidbits from development.

### Everything is an item

In Nikola, there are several different entities that can generate HTML files. Posts and Pages are both `Post` objects. Listings and galleries each have their own task generators. There’s no `Listing` class, everything is handled within the listing plugin. Galleries can optionally have a `Post` object attached (though that `Post` is not picked up by the file scanner, and it is not part of the timeline). The listings and galleries task generators both have ways to build directory trees.

In YABG, all of the above are `Item`s. Specifically, they start as `SourceItem`s and become `Item`s when rendered. For listings, the source is just the code and the rendered content is Pygments-generated HTML. For galleries, the source is a [TSV file](https://en.wikipedia.org/wiki/Tab-separated_values) with a list of included gallery images (order, filenames, and descriptions), and the generated content comes from a meta field named `galleryIntroHtml`. Gallery objects have a `GalleryData` object attached to their `Item` object as `RichItemData`.

This simplifies the final rendering pipeline design. Only four classes (actual classes, not temporary structures in some plugin) can render to HTML: `Item`, `ItemGroup` (tags, categories, yearly archives, gallery indexes), `DirectoryTreeGroup` (listings), and `LinkGroup` (archive and tag indexes). Each has a corresponding template model. Nikola’s sitemap generator recurses through the `output` directory to find files, but YABG can just use the lists of items and groups. The sitemap won’t include HTML files from the files folder, but I don’t need them there (though I could add them if needed).

### Windows first, Linux in zero time

I developed YABG entirely on Windows. This forced me to think about paths and URLs as separate concepts. I couldn’t use most `System.IO.Path` facilities for URLs, since they would produce backslashes. As a result, there are zero bugs where backslashes leak into output on Windows. Nikola has such bugs pop up occasionally; indeed, [I fixed one yesterday](https://github.com/getnikola/nikola/commit/d8d94c047cdc1718700f0b5d00627722241be68d).

But when YABG was nearly complete, I ran it on Linux. And it just worked. No code changes needed. No output differences. (I had to add `SkiaSharp.NativeAssets.Linux` and `apt install libfontconfig1` since I was stilll using SkiaSharp at that point, but that’s no longer needed with Magick.NET.)

Not everything is perfect, though. I added a `--watch` mode based on `FileSystemWatcher`, but it doesn’t work on Linux. I don’t *need* it there; I’d have to switch to polling to make it work.

### Dependency injection everywhere

A good principle used in object-oriented development (though not very often in Python) is **dependency injection**.  I have several grouping services, all implementing either `IPostGrouper` or `IItemGrouper`. They’re registered in the DI container as implementations of those interfaces. The `GroupEngine` doesn’t need to know about specific group types, it just gets them from the container and passes the post and item arrays.

```csharp
      .AddScoped<IPostGrouper, ArchiveGrouper>()
      .AddScoped<IPostGrouper, GuideGrouper>()
      .AddScoped<IPostGrouper, IndexGrouper>()
      .AddScoped<IPostGrouper, NavigationGrouper>()
      .AddScoped<IPostGrouper, TagCategoryGrouper>()
      .AddScoped<IItemGrouper, GalleryIndexGrouper>()
      .AddScoped<IItemGrouper, ListingIndexGrouper>()
      .AddScoped<IItemGrouper, ProjectGrouper>()
```

```csharp
internal class GroupEngine(
  IEnumerable<IItemGrouper> itemGroupers,
  IEnumerable<IPostGrouper> postGroupers)
    : IGroupEngine {
  public IEnumerable<IGroup> GenerateGroups(Item[] items) {
    var sortedItems = items
        .OrderByDescending(i => i.Published)
        .ThenBy(i => i.SourcePath)
        .ToArray();

    var sortedPosts = sortedItems
        .Where(item => item.Type == ItemType.Post)
        .ToArray();

    var itemGroups = itemGroupers.SelectMany(g => g.GroupItems(sortedItems));
    var postGroups = postGroupers.SelectMany(g => g.GroupPosts(sortedPosts));
    return itemGroups.Concat(postGroups);
  }
}
```

The `ItemRenderEngine` has a slightly different challenge: it needs to pick the correct renderer for the post (Gallery, HTML, Listing, Markdown). The renderers are registered as keyed services. The render engine does not need to know anything about the specific renderer types, it just gets the renderer name from the `SourceItem`’s `ScanPattern` (so ultimately from the configuration file) and asks the DI container to provide it with the right implementation.

```csharp
      .AddKeyedScoped<IItemRenderer, GalleryItemRenderer>(GalleryItemRenderer.Name)
      .AddKeyedScoped<IItemRenderer, HtmlItemRenderer>(HtmlItemRenderer.Name)
      .AddKeyedScoped<IItemRenderer, ListingItemRenderer>(ListingItemRenderer.Name)
      .AddKeyedScoped<IItemRenderer, MarkdownItemRenderer>(MarkdownItemRenderer.Name)
```

```csharp
  public async Task<IEnumerable<Item>> Render(IEnumerable<SourceItem> sourceItems) {
    var renderTasks = sourceItems
        .GroupBy(i => i.ScanPattern.RendererName)
        .Select(group => {
          var renderer = _keyedServiceProvider
            .GetRequiredKeyedService<IItemRenderer>(group.Key);
          return renderer switch {
              IBulkItemRenderer bulkRenderer => bulkRenderer.RenderItems(group),
              ISingleItemRenderer singleRenderer => Task.WhenAll(
                  group.Select(singleRenderer.RenderItem)),
              _ => throw new InvalidOperationException("Unexpected renderer type")
          };
        });
  }
```

In total, there are **37** specific service implementations registered (plus system services like `TimeProvider` and logging). Beyond these two examples, the main benefit is **testability**. I can write unit tests without dependencies on unrelated services, and without monkey-patching random names. (In Python, `unittest.mock` does both monkey-patching *and* mocking.)

Okay, I haven’t written very many tests, but I could easily ask an LLM to do it.

### Immutable data structures and no global state

All classes are immutable. This helps in several ways. It’s easier to reason about state when `SourceItem` becomes `Item` during rendering, compared to a single class with a nullable `Content` property. Immutability also makes concurrency safer. But the biggest win is how easy it was to develop the `--watch` mode. Every service has `Scoped` lifetime, and main logic lives in `IMainEngine`. I can just create a new scope, get the engine, and run it without state leaking between executions. No subprocess launching, no state resetting — everything disappears when the scope is disposed.

## Can anyone use it?

On one hand, it’s open source under the 3-clause BSD license and [available on GitHub](https://github.com/Kwpolska/YetAnotherBlogGenerator).

On the other hand, it’s more of a source-available project. There are no docs, and it was designed specifically for this site (so some things are probably too hardcoded for your needs). In fact, this blog’s configuration and templates were directly hardcoded in the codebase until the day before launch. But I’m happy to answer questions and review pull requests!
