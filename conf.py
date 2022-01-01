# -*- coding: utf-8 -*-
from __future__ import unicode_literals
from nikola import filters
#import time

##############################################
# Configuration, please edit
##############################################


# Data about this site
BLOG_AUTHOR = "Chris Warrick"
BLOG_TITLE = "Chris Warrick"
# This is the main URL for your site. It will be used
# in a prominent link
SITE_URL = "https://chriswarrick.com/"
# This is the URL where nikola's output will be deployed.
# If not set, defaults to SITE_URL
BLOG_EMAIL = "chris@chriswarrick.com"
BLOG_DESCRIPTION = "A rarely updated blog, mostly about programming."

# Nikola is multilingual!
#
# Currently supported languages are:
#   English -> en
#   Greek -> gr
#   German -> de
#   French -> fr
#   Polish -> pl
#   Russian -> ru
#   Spanish -> es
#   Italian -> it
#   Simplified Chinese -> zh-cn
#
# If you want to use Nikola with a non-supported language you have to provide
# a module containing the necessary translations
# (p.e. look at the modules at: ./nikola/data/themes/default/messages/fr.py).
# If a specific post is not translated to a language, then the version
# in the default language will be shown instead.

# What is the default language?
DEFAULT_LANG = "en"
LOCALE_DEFAULT = 'en_GB.UTF-8'
FORCE_ISO8601 = True
# What other languages do you have?
# The format is {"translationcode" : "path/to/translation" }
# the path will be used as a prefix for the generated pages location
TRANSLATIONS = {
    DEFAULT_LANG: "",
    # Example for another language:
    "pl": "./pl",
}

TRANSLATIONS_PATTERN = '{path}.{lang}.{ext}'

# Links for the sidebar / navigation bar.
# You should provide a key-value pair for each used language.
NAVIGATION_LINKS = {
    'en': (
        ('/contact/', 'Contact'),
        ('/projects/', 'Projects'),
        ('/guides/', 'Guides'),
        ('/blog/', 'Archives'),
        ('/search/', 'Search'),
    ),

    'pl': (
        ('/pl/contact/', 'Kontakt'),
        ('/projects/', 'Projekty'),
        ('/guides/', 'Przewodniki'),
        ('/pl/blog/', 'Archiwum'),
        ('/pl/search/', 'Szukaj'),
    ),
}

NAVIGATION_ALT_LINKS = {
    'en': (
        ('Blog', (
            ('/blog/', 'Archive'),
            ('/blog/tags/', 'Tags'),
            ('/rss.xml', 'RSS Feed'),
            ('/search/', 'Search'),
        )),
        ('Projects and Work', (
            ('/projects/', 'Projects'),
            ('/guides/', 'Guides'),
            ('/kwbot/', 'KwBot'),
            ('/blog/tags/cat_python', 'Posts about Python'),
        )),
        ('Me', (
            ('/contact/', 'Contact'),
            ('/brand/', 'Brand'),
            ('https://twitter.com/Kwpolska', 'Twitter'),
            ('https://github.com/Kwpolska', 'GitHub'),
        )),
    ),
    'pl': (
        ('Blog', (
            ('/pl/blog/', 'Archiwum'),
            ('/pl/blog/tags/', 'Tagi'),
            ('/pl/rss.xml', 'Kanał RSS'),
            ('/pl/search/', 'Wyszukiwarka'),
        )),
        ('Projekty i Twórczość', (
            ('/projects/', 'Projekty (en)'),
            ('/guides/', 'Przewodniki (en)'),
            ('/pl/kwbot/', 'KwBot'),
            ('/pl/blog/tags/cat_python', 'Posty o Pythonie'),
        )),
        ('Ja', (
            ('/pl/contact/', 'Kontakt'),
            ('/pl/brand/', 'Brand'),
            ('https://twitter.com/Kwpolska', 'Twitter'),
            ('https://github.com/Kwpolska', 'GitHub'),
        )),
    ),
}

##############################################
# Below this point, everything is optional
##############################################


# post_pages contains (wildcard, destination, template, use_in_feed) tuples.
#
# The wildcard is used to generate a list of reSt source files
# (whatever/thing.txt).
# That fragment must have an associated metadata file (whatever/thing.meta),
# and opcionally translated files (example for spanish, with code "es"):
#     whatever/thing.txt.es and whatever/thing.meta.es
#
# From those files, a set of HTML fragment files will be generated:
# cache/whatever/thing.html (and maybe cache/whatever/thing.html.es)
#
# These files are combinated with the template to produce rendered
# pages, which will be placed at
# output / TRANSLATIONS[lang] / destination / pagename.html
#
# where "pagename" is specified in the metadata file.
#
# if use_in_feed is True, then those posts will be added to the site's
# rss feeds.
#

POSTS = (
    ("posts/*.rst", "blog", "post.tmpl"),
    # ("posts/*.ipynb", "blog", "post.tmpl"),
)

PAGES = (
    ("pages/*.rst", "", "story.tmpl"),
    ("pages/*.html", "", "story.tmpl"),
    ("err/*.html", "err", "err.tmpl"),
    ("projects/*.rst", "projects", "project.tmpl"),
)

PROJECT_PATH = 'projects'

# One or more folders containing files to be copied as-is into the output.
# The format is a dictionary of "source" "relative destination".
# Default is:
FILES_FOLDERS = {'files': ''}
# Which means copy 'files' into 'output'

# A mapping of languages to file-extensions that represent that language.
# Feel free to add or delete extensions to any list, but don't add any new
# compilers unless you write the interface for it yourself.
#
# 'rest' is reStructuredText
# 'markdown' is MarkDown
# 'html' assumes the file is html and just copies it
COMPILERS = {
    "rest": ('.txt', '.rst'),
    "markdown": ('.md', '.mdown', '.markdown'),
    "textile": ('.textile',),
    "txt2tags": ('.t2t',),
    "bbcode": ('.bb',),
    "wiki": ('.wiki',),
    "ipynb": ('.ipynb',),
    "html": ('.html', '.htm'),
    # Pandoc detects the input from the source filename
    # but is disabled by default as it would conflict
    # with many of the others.
    # "pandoc": ('.rst', '.md', '.txt'),
}

# Create by default posts in one file format?
# Set to False for two-file posts, with separate metadata.
ONE_FILE_POSTS = True

# If this is set to True, the DEFAULT_LANG version will be displayed for
# untranslated posts.
# If set to False, then posts that are not translated to a language
# LANG will not be visible at all in the pages in that language.
SHOW_UNTRANSLATED_POSTS = True

# Paths for different autogenerated bits. These are combined with the
# translation paths.

# Final locations are:
# output / TRANSLATION[lang] / TAG_PATH / index.html (list of tags)
# output / TRANSLATION[lang] / TAG_PATH / tag.html (list of posts for a tag)
# output / TRANSLATION[lang] / TAG_PATH / tag.xml (RSS feed for a tag)
TAG_PATH = "blog/tags"

# If TAG_PAGES_ARE_INDEXES is set to True, each tag's page will contain
# the posts themselves. If set to False, it will be just a list of links.
TAG_PAGES_ARE_INDEXES = True

# Final location is output / TRANSLATION[lang] / INDEX_PATH / index-*.html
INDEX_PATH = ""

# Create per-month archives instead of per-year
CREATE_MONTHLY_ARCHIVE = False
# Final locations for the archives are:
# output / TRANSLATION[lang] / ARCHIVE_PATH / ARCHIVE_FILENAME
# output / TRANSLATION[lang] / ARCHIVE_PATH / YEAR / index.html
# output / TRANSLATION[lang] / ARCHIVE_PATH / YEAR / MONTH / index.html
ARCHIVE_PATH = "blog"
ARCHIVE_FILENAME = "index.html"

# Final locations are:
# output / TRANSLATION[lang] / RSS_PATH / rss.xml
RSS_PATH = ""

# Number of posts in RSS feeds
FEED_LENGTH = 10

# Slug the Tag URL easier for users to type, special characters are
# often removed or replaced as well.
SLUG_TAG_PATH = True

# A list of redirection tuples, [("foo/from.html", "/bar/to.html")].
#
# A HTML file will be created in output/foo/from.html that redirects
# to the "/bar/to.html" URL. notice that the "from" side MUST be a
# relative URL.
#
# If you don't need any of these, just set to []
REDIRECTIONS = []

# Commands to execute to deploy. Can be anything, for example,
# you may use rsync:
# "rsync -rav output/* joe@my.site:/srv/www/site"
# And then do a backup, or ping pingomatic.
# To do manual deployment, set it to []
DEPLOY_COMMANDS = {'default': ['find . -name ".DS_Store" -print -delete', 'nikola check -fl', 'rsync -rav --exclude-from=deploy-ignores --del output/ nayru:/srv/chriswarrick.com']}

# Where the output site should be located
# If you don't use an absolute path, it will be considered as relative
# to the location of conf.py
OUTPUT_FOLDER = 'output'

# where the "cache" of partial generated content should be located
# default: 'cache'
CACHE_FOLDER = 'cache'

# Filters to apply to the output.
# A directory where the keys are either: a file extensions, or
# a tuple of file extensions.
#
# And the value is a list of commands to be applied in order.
#
# Each command must be either:
#
# A string containing a '%s' which will
# be replaced with a filename. The command *must* produce output
# in place.
#
# Or:
#
# A python callable, which will be called with the filename as
# argument.
#
# By default, there are no filters.
FILTERS = {
   ".html": ['filters.add_header_permalinks'],
}

# Create a gzipped copy of each generated file. Cheap server-side optimization.
# GZIP_FILES = False
# File extensions that will be compressed
# GZIP_EXTENSIONS = ('.txt', '.htm', '.html', '.css', '.js', '.json')

# #############################################################################
# Image Gallery Options
# #############################################################################

# Galleries are folders in galleries/
# Final location of galleries will be output / GALLERY_PATH / gallery_name
# GALLERY_PATH = "galleries"
# THUMBNAIL_SIZE = 180
# MAX_IMAGE_SIZE = 1280
# USE_FILENAME_AS_TITLE = True

GALLERY_SORT_BY_DATE = False
IMAGE_FOLDERS = {'images': 'images',
                 'project_banners': 'projects/_banners',
                 'project_logos': 'projects/_logos',}

IMAGE_THUMBNAIL_SIZE = 300

# #############################################################################
# HTML fragments and diverse things that are used by the templates
# #############################################################################

# Data about post-per-page indexes
# INDEXES_TITLE = ""  # If this is empty, the default is BLOG_TITLE
# INDEXES_PAGES = ""  # If this is empty, the default is 'old posts page %d'
# translated

# Name of the theme to use.
THEME = 'kw'

# Color scheme to be used for code blocks. If your theme provides
# "assets/css/code.css" this is ignored.
# Can be any of autumn borland bw colorful default emacs friendly fruity manni
# monokai murphy native pastie perldoc rrt tango trac vim vs
CODE_COLOR_SCHEME = 'monokai'

# If you use 'site-reveal' theme you can select several subthemes
# THEME_REVEAL_CONGIF_SUBTHEME = 'sky'
# You can also use: beige/serif/simple/night/default

# Again, if you use 'site-reveal' theme you can select several transitions
# between the slides
# THEME_REVEAL_CONGIF_TRANSITION = 'cube'
# You can also use: page/concave/linear/none/default

# date format used to display post dates.
# (str used by datetime.datetime.strftime)
DATE_FORMAT = {"en": "dd MMMM yyyy 'at' HH:mm",
               "pl": "dd MMMM yyyy 'o' HH:mm"}

DATE_FANCINESS = 0

# FAVICONS contains (name, file, size) tuples.
# Used for create favicon link like this:
# <link rel="name" href="file" sizes="size"/>
# For creating favicons, take a look at:
# http://www.netmagazine.com/features/create-perfect-favicon
# FAVICONS = {
    # ("icon", "/favicon.ico", "16x16"),
# }

# Show only teasers in the index pages? Defaults to False.
INDEX_TEASERS = True

# A HTML fragment with the Read more... link.
# The following tags exist and are replaced for you:
# {link}        A link to the full post page.
# {read_more}   The string “Read more” in the current language.
# {{            A literal { (U+007B LEFT CURLY BRACKET)
# }}            A literal } (U+007D RIGHT CURLY BRACKET)
# READ_MORE_LINK = '<p class="more"><a href="{link}">{read_more}…</a></p>'
INDEX_READ_MORE_LINK = ''
FEED_READ_MORE_LINK = '<p class="more"><a href="{link}">{read_more}…</a> ({remaining_paragraph_count} paragraphs remaining — a {remaining_reading_time} minutes’ read)</p>'

# A HTML fragment describing the license, for the sidebar.
LICENSE = """<a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA</a>"""
# I recommend using the Creative Commons' wizard:
# http://creativecommons.org/choose/
# LICENSE = """
# <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.5/ar/">
# <img alt="Creative Commons License BY-NC-SA"
# style="border-width:0; margin-bottom:12px;"
# src="http://i.creativecommons.org/l/by-nc-sa/2.5/ar/88x31.png"></a>"""

# A small copyright notice for the page footer (in HTML).
# Default is ''
CONTENT_FOOTER = {
    'en': """<p>this site uses cookies.<br>by using this site, you agree to our <a href="/privacy/">cookie and privacy policy</a>.</p><p>
powered by <strong><a href="https://getnikola.com/">Nikola</a></strong>, <a href="http://nginx.org/">nginx</a> and <a href="https://hetzner.cloud/?ref=Qy1lehF8PwzP">Hetzner Cloud</a><br>
copyright © 2009–2022 <a
href="/contact/">Chris Warrick</a><br> <a rel="license"
href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC
BY-NC-SA</a>, unless stated otherwise</p>""",
    'pl': """<p>ta strona używa ciasteczek.<br>
korzystając z tej strony, akceptujesz naszą <a href="/pl/privacy/">politykę prywatności i ciasteczek</a>.
</p><p>
powered by <strong><a href="https://getnikola.com/">Nikola</a></strong>, <a href="http://nginx.org/">nginx</a> and <a href="https://hetzner.cloud/?ref=Qy1lehF8PwzP">Hetzner Cloud</a><br>
copyright © 2009–2022 <a
href="/pl/contact/">Chris Warrick</a><br> <a rel="license"
href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC
BY-NC-SA</a>, unless stated otherwise</p>""",

}

COMMENT_SYSTEM = 'isso'
COMMENT_SYSTEM_ID = 'https://isso.chriswarrick.com/'

# Create index.html for story folders?
PAGE_INDEX = False
# Enable comments on story pages?
COMMENTS_IN_PAGES = False
# Enable comments on picture gallery pages?
COMMENTS_IN_GALLERIES = False

# What file should be used for directory indexes?
# Defaults to index.html
# Common other alternatives: default.html for IIS, index.php
INDEX_FILE = "index.html"

# If a link ends in /index.html,  drop the index.html part.
# http://mysite/foo/bar/index.html => http://mysite/foo/bar/
# (Uses the INDEX_FILE setting, so if that is, say, default.html,
# it will instead /foo/default.html => /foo)
# (Note: This was briefly STRIP_INDEX_HTML in v 5.4.3 and 5.4.4)
# Default = False
STRIP_INDEXES = True

# Should the sitemap list directories which only include other directories
# and no files.
# Default to True
# If this is False
# e.g. /2012 includes only /01, /02, /03, /04, ...: don't add it to the sitemap
# if /2012 includes any files (including index.html)... add it to the sitemap
SITEMAP_INCLUDE_FILELESS_DIRS = True
ROBOTS_EXCLUSIONS = ["/err/*", "/pl/err/*"]

# Instead of putting files in <slug>.html, put them in
# <slug>/index.html. Also enables STRIP_INDEXES
# This can be disabled on a per-page/post basis by adding
#    .. pretty_url: False
# to the metadata
PRETTY_URLS = True

# Publish future dated posts right away instead of scheduling them
FUTURE_IS_NOW = False

# Do you want a add a Mathjax config file?
# MATHJAX_CONFIG = ""

# If you are using the compile-ipynb plugin, just add this one:
#MATHJAX_CONFIG = """
#<script type="text/x-mathjax-config">
#MathJax.Hub.Config({
#    tex2jax: {
#        inlineMath: [ ['$','$'], ["\\\(","\\\)"] ],
#        displayMath: [ ['$$','$$'], ["\\\[","\\\]"] ]
#    },
#    displayAlign: 'left', // Change this to 'center' to center equations.
#    "HTML-CSS": {
#        styles: {'.MathJax_Display': {"margin": 0}}
#    }
#});
#</script>
#"""

# What MarkDown extensions to enable?
# You will also get gist, nikola and podcast because those are
# done in the code, hope you don't mind ;-)
# MARKDOWN_EXTENSIONS = ['fenced_code', 'codehilite']

# Enable Addthis social buttons?
# Defaults to true
#ADD_THIS_BUTTONS = False

# Content of the AddThis code, customize it to fit your needs
# Visit addthis.com to generate code samples
SOCIAL_BUTTONS_CODE = ''

# Show link to source for the posts?
SHOW_SOURCELINK = False
# Copy the source files for your pages?
# Setting it to False implies SHOW_SOURCELINK = False
COPY_SOURCES = False

# Modify the number of Post per Index Page
# Defaults to 10
# INDEX_DISPLAY_POST_COUNT = 10

# RSS_LINK is a HTML fragment to link the RSS or Atom feeds. If set to None,
# the base.tmpl will use the feed Nikola generates. However, you may want to
# change it for a feedburner feed or something else.
# RSS_LINK = None

# Show only teasers in the RSS feed? Default to True
FEED_TEASERS = False

# A search form to search this site, for the sidebar. You can use a google
# custom search (http://www.google.com/cse/)
# Or a duckduckgo search: https://duckduckgo.com/search_box.html
# Default is no search form.
# SEARCH_FORM = ""
#
# This search form works for any site and looks good in the "site" theme where
# it appears on the navigation bar:
#
#SEARCH_FORM = """
#<!-- Custom search -->
#<form method="get" id="search" action="http://duckduckgo.com/"
# class="navbar-form pull-left">
#<input type="hidden" name="sites" value="%s"/>
#<input type="hidden" name="k8" value="#444444"/>
#<input type="hidden" name="k9" value="#D51920"/>
#<input type="hidden" name="kt" value="h"/>
#<input type="text" name="q" maxlength="255"
# placeholder="Search&hellip;" class="span2" style="margin-top: 4px;"/>
#<input type="submit" value="DuckDuckGo Search" style="visibility: hidden;" />
#</form>
#<!-- End of custom search -->
#""" % SITE_URL
#
# If you prefer a google search form, here's an example that should just work:
#SEARCH_FORM = """
#<!-- Custom search with google-->
#<form id="search" action="http://google.com/search" method="get" class="navbar-form pull-left">
#<input type="hidden" name="q" value="site:%s" />
#<input type="text" name="q" maxlength="255" results="0" placeholder="Search"/>
#</form>
#<!-- End of custom search -->
#""" % SITE_URL

# Also, there is a local search plugin you can use, based on Tipue, but it requires setting several
# options:

# SEARCH_FORM = """
# <span class="navbar-form pull-left">
# <input type="text" id="tipue_search_input">
# </span>"""
#
# ANALYTICS = """
# <script type="text/javascript" src="/assets/js/tipuesearch_set.js"></script>
# <script type="text/javascript" src="/assets/js/tipuesearch.js"></script>
# <script type="text/javascript">
# $(document).ready(function() {
    # $('#tipue_search_input').tipuesearch({
        # 'mode': 'json',
        # 'contentLocation': '/assets/js/tipuesearch_content.json',
        # 'showUrl': false
    # });
# });
# </script>
# """

# EXTRA_HEAD_DATA = """
# <link rel="stylesheet" type="text/css" href="/assets/css/tipuesearch.css">
# <div id="tipue_search_content" style="margin-left: auto; margin-right: auto; padding: 20px;"></div>
# """
# ENABLED_EXTRAS = ['local_search']
#


# Use content distribution networks for jquery and twitter-bootstrap css and js
# If this is True, jquery is served from the Google CDN and twitter-bootstrap
# is served from the NetDNA CDN
# Set this to False if you want to host your site without requiring access to
# external resources.
USE_CDN = True

USE_CDN_WARNING = False

# Extra things you want in the pages HEAD tag. This will be added right
# before </HEAD>
# EXTRA_HEAD_DATA = ""
# Google analytics or whatever else you use. Added to the bottom of <body>
# in the default template (base.tmpl).
# ANALYTICS = ""

BODY_END = """
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-11937989-1', 'chriswarrick.com');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');

</script>
"""

# The possibility to extract metadata from the filename by using a
# regular expression.
# To make it work you need to name parts of your regular expression.
# The following names will be used to extract metadata:
# - title
# - slug
# - date
# - tags
# - link
# - description
#
# An example re is the following:
# '(?P<date>\d{4}-\d{2}-\d{2})-(?P<slug>.*)-(?P<title>.*)\.md'
# FILE_METADATA_REGEXP = None

# Nikola supports Twitter Card summaries / Open Graph.
# Twitter cards make it possible for you to attach media to Tweets
# that link to your content.
#
# IMPORTANT:
# Please note, that you need to opt-in for using Twitter Cards!
# To do this please visit
# https://dev.twitter.com/form/participate-twitter-cards
#
# Uncomment and modify to following lines to match your accounts.
# Specifying the id for either 'site' or 'creator' will be preferred
# over the cleartext username. Specifying an ID is not necessary.


TWITTER_CARD = {
    'use_twitter_cards': True,  # enable Twitter Cards
#     # 'site': '@website',  # twitter nick for the website
#     # 'site:id': 123456,  # Same as site, but the website's Twitter user ID
#                           # instead.
    'creator': '@Kwpolska',  # Username for the content creator / author.
#     # 'creator:id': 654321,  # Same as creator, but the Twitter user's ID.
}

# If you want to use formatted post time in W3C-DTF Format
# (ex. 2012-03-30T23:00:00+02:00),
# set timzone if you want a localized posted date.
#
TIMEZONE = 'Europe/Warsaw'

# If webassets is installed, bundle JS and CSS to make site loading faster
USE_BUNDLES = True

# Plugins you don't want to use. Be careful :-)
# DISABLED_PLUGINS = ["render_galleries"]

# Experimental plugins - use at your own risk.
# They probably need some manual adjustments - please see their respective
# readme.
# ENABLED_EXTRAS = [
#     'planetoid',
#     'ipynb',
#     'local_search',
#     'mustache',
# ]

# List of regular expressions, links matching them will always be considered
# valid by "nikola check -l"
# LINK_CHECK_WHITELIST = []

# If set to True, enable optional hyphenation in your posts (requires pyphen)
HYPHENATE = False

LOGO_URL = '/assets/img/logo.png'

# Put in global_context things you want available on all your templates.
# It can be anything, data, functions, modules, etc.

from nikola.post import TEASER_REGEXP

def post_lead_format(post):
    ptext = post.text()
    teaser_split = TEASER_REGEXP.split(ptext)
    try:
        lead = teaser_split[0]
        ptext = teaser_split[4]
    except IndexError:
        lead = None

    if lead:
        return '<div class="lead">' + lead + '</div></div>' + ptext
    else:
        return ptext


INDEXES_TITLE = "Blog"
INDEXES_PAGES = {'en': ', page %d',
                 'pl': ', page %d'}
INDEXES_STATIC = False
WRITE_TAG_CLOUD = False
URL_TYPE = 'full_path'

THEME_COLOR = '#00aadd'
CATEGORY_COLORS = {
    'Python': '#ffd43b',
    'Android': '#a4c639',
    'Windows': '#00adef',
}

NEW_POST_DATE_PATH = True
HEADER_PERMALINKS_XPATH_LIST = ['*//div[@class="e-content entry-content"]//{hx}']

ISSO_CONFIG = {
    'require-author': 'true',
    'require-email': 'true',
    'gravatar': 'true',
    'avatar': 'false',
    'css': 'false',
    'vote': 'false',
    'reply-notifications': 'true'
}

GLOBAL_CONTEXT = {'post_lead_format': post_lead_format,
                  'category_colors': CATEGORY_COLORS,
                  'isso_config': ISSO_CONFIG}
