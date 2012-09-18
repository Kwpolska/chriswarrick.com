from hyde.plugin import Plugin
from jinja2 import contextfunction

@contextfunction
def media_url(context, path, safe=None):
    """
    Returns the media url given a partial path.
    """
    return context['site'].media_url(path, safe or '/#')

@contextfunction
def content_url(context, path, safe=None):
    """
    Returns the content url given a partial path.
    """
    return context['site'].content_url(path, safe or '/#')

@contextfunction
def full_url(context, path, safe=None):
    """
    Returns the full url given a partial path.
    """
    return context['site'].full_url(path, safe or '/#')

class SiteDefaults(Plugin):

    def template_loaded(self, template):
        self.template = template
        template.env.globals['content_url'] = content_url
        template.env.globals['media_url'] = media_url
        template.env.globals['full_url'] = full_url
