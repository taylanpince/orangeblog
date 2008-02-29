import re

from django.core.urlresolvers import reverse
from django.template.defaultfilters import slugify


LOOKUP_MATCH = re.compile(r"\(bkz:( ?)(.*?)\)", re.IGNORECASE|re.DOTALL)


def mark_lookups(string):
    """ Matches the lookup strings and renders them as HTML """
    
    for m in LOOKUP_MATCH.finditer(string):
        try:
            string = string.replace(m.group(), m.expand(r'(bkz: <a href="%s">\2</a>)' % reverse("lookup_post", kwargs={"slug": slugify(m.group(2))})))
        except:
            pass
    
    return string


def unslugify(string):
    """ Tries to un-slugify a slugified title """
    
    return string.replace("-", " ")