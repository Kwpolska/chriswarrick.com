---
layout: plpost
title: Jekyll dla Bystrzaków
category: polish
---
**This post is an introduction to Jekyll, an awesome, <q>simple, blog aware, static site generator</q>. It&#8217;s a how-to helping you in setting up Jekyll.**

## About Jekyll
> # Jekyll
>
> By Tom Preston-Werner, Nick Quaranto, and many awesome contributors!
>
> Jekyll is a simple, blog aware, static site generator. It takes a template directory (representing the raw form of a website), runs it through Textile or Markdown and Liquid converters, and spits out a complete, static website suitable for serving with Apache or your favorite web server. This is also the engine behind [GitHub Pages][], which you can use to host your project&#8217;s page or blog right here from GitHub.
(A quote from the README)
## Step 1: Install Jekyll

First of, you have to install Jekyll. You can do it either on your server or on your home machine, if you&#8217;ll develop a crazy way to [deploy][] it. Another way is to use [GitHub Pages][]. On Linux, you can do it like this:
    gem install jekyll --user-install
If you have problems, you might have to install an approperiate package. The [Jekyll Wiki][wiki] has more information about it: [Jekyll Wiki: Install][install]

## Step 2: Configure Jekyll and prepare the directories

Create a directory for it. I&#8217;m using `~/jekyll`. Go into that directory and create two directories and two files. The directories are `_layouts` and `_posts`. The files are `_config.yml` and `index.html`. If you want, you can create a `_site` directory. Now, you have to fill them with content.

The directory `_layouts` stores layouts of your site. You can have a different layout for your home page, posts or pages. You can use template data (see below). The files shall be HTML files **with .yml extension**.

The directory `_posts` is a place for your post written in Markdown or Textile. It must begin with the [YAML Front Matter][yaml]. The file name must be as follows: `YEAR-MO-DD-the-name-of-the-post-that-will-be-used-in-the-url.PARSER`, where `YEAR` is current year, `MO` is current month, `DD` is the day and `PARSER` is either `markdown` or `textile`.

The file `_config.yml` is the configuration of Jekyll. You can find more information at the awesome Jekyll Wiki in the article [Configuration][].

The file `index.html` is the main page of your blog. It must begin with the [YAML Front Matter][yaml]. You can see a sample one here: [A sample Jekyll index.html][index]. This is a modded version of my own index.

### Template data

The template data are elements inserted into layouts and generic pages. Some of the most important are `content` and `title`. You can learn about all of them in a Wiki article, called [Template Data][tdata].

## Step 3: Continue making the site

Now you shall continue making your blog. You can make some feeds, pages, layouts, CSS templates and write posts OR import posts from your existing blog.

## Step 4: Generate your site

Unless you&#8217;re using GitHub Pages, you must generate the page. It&#8217;s easy, you must use the `jekyll` shell command and you get Jekyll in the directory, which you&#8217;ve set in the config file. If you&#8217;re using GH Pages, you just have to push into your repository.

## Why use Jekyll?

Jekyll is awesome, because it generates *static* webpages with use of Markdown. This makes this post&#8217;s original source easily readable. Below this paragraph, I&#8217;ve got all the links that I used in this post.

    [index]:         http://kwpolska.co.cc/blog-content/sampleindex.txt "Sample Jekyll index.html"
    [github pages]:  http://pages.github.com "GitHub Pages"
    [wiki]:          http://github.com/mojombo/jekyll/wiki "Jekyll Wiki"
    [install]:       https://github.com/mojombo/jekyll/wiki/Install "Jekyll Wiki: Install"
    [deploy]:        https://github.com/mojombo/jekyll/wiki/Deployment "Jekyll Wiki: Deployment"
    [configuration]: https://github.com/mojombo/jekyll/wiki/Configuration "Jekyll Wiki: Configuration"
    [yaml]:          https://github.com/mojombo/jekyll/wiki/YAML-Front-Matter "Jekyll Wiki: YAML Front Matter"
    [tdata]:         https://github.com/mojombo/jekyll/wiki/Template-Data "Jekyll Wiki: Liquid template data"

There are a lot of other awesome things in it.

[index]:         http://kwpolska.co.cc/blog-content/sampleindex.txt "Sample Jekyll index.html"
[github pages]:  http://pages.github.com "GitHub Pages"
[wiki]:          http://github.com/mojombo/jekyll/wiki "Jekyll Wiki"
[install]:       https://github.com/mojombo/jekyll/wiki/Install "Jekyll Wiki: Install"
[deploy]:        https://github.com/mojombo/jekyll/wiki/Deployment "Jekyll Wiki: Deployment"
[configuration]: https://github.com/mojombo/jekyll/wiki/Configuration "Jekyll Wiki: Configuration"
[yaml]:          https://github.com/mojombo/jekyll/wiki/YAML-Front-Matter "Jekyll Wiki: YAML Front Matter"
[tdata]:         https://github.com/mojombo/jekyll/wiki/Template-Data "Jekyll Wiki: Liquid template data"
