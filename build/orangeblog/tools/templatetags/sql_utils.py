from django import template

register = template.Library()


@register.simple_tag
def total_query_time(queries):
    """ Calculates the total sql exec time """
    
    total = 0
    
    for query in queries:
        total += float(query["time"])
    
    return total
