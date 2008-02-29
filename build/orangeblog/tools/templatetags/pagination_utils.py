from django import template

register = template.Library()


@register.inclusion_tag("tools/pagination.html")
def paginate(pager):
    """ Creates the page list, according to the current page """
    
    ON_EACH_SIDE = 6
    ON_ENDS = 5
    DOT = '.'
    
    pager.is_paginated = pager.pages > 1
    pager.has_next = pager.has_next_page(pager.page - 1)
    pager.has_previous = pager.has_previous_page(pager.page - 1)
    pager.next = pager.page + 1
    pager.previous = pager.page - 1
    pager.results_per_page = 5
    
    # If there are 10 or fewer pages, display links to every page.
    # Otherwise, do some fancy
    if pager.pages <= 10:
    	page_range = range(1, pager.pages + 1)
    else:
    	# Insert "smart" pagination links, so that there are always ON_ENDS
    	# links at either end of the list of pages, and there are always
    	# ON_EACH_SIDE links at either end of the "current page" link.
    	page_range = []
    	
    	if pager.page > (ON_EACH_SIDE + ON_ENDS):
    		page_range.extend(range(1, ON_EACH_SIDE))
    		page_range.append(DOT)
    		page_range.extend(range(pager.page - ON_EACH_SIDE, pager.page))
    	else:
    		page_range.extend(range(1, pager.page))
    	
    	if pager.page < (pager.pages - ON_EACH_SIDE - ON_ENDS):
    		page_range.extend(range(pager.page, pager.page + ON_EACH_SIDE))
    		page_range.append(DOT)
    		page_range.extend(range(pager.pages - ON_ENDS, pager.pages + 1))
    	else:
    		page_range.extend(range(pager.page, pager.pages + 1))
    
    return {"pager" : pager, "page_range" : page_range}


@register.simple_tag
def paginate_url(url, page):
    return url % page
