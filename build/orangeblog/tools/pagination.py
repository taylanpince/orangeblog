import urlparse


PAGE_VARIABLE = "sayfa"


def make_url_pattern(url, get_params=()):
    """ Takes a url and optionally a set of get params (in a QueryDict) and 
        builds a url with page param in its query string. The value of the 
        page param is left as an open variable so it can be filled later with 
        the modulo string operator."""

    kw_list = []
    
    for k, vlist in get_params.lists():
        if k != PAGE_VARIABLE:
            for v in vlist:
                kw_list.append('%s=%s' % (k, v))
    
    querystring = '&'.join(kw_list + ["sayfa=%s"])
    
    return urlparse.urljoin(url, '?%s' % querystring)