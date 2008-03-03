from django import template

from aphorisms.models import Aphorism

register = template.Library()


@register.inclusion_tag("aphorisms/random_aphorism.html")
def show_random_aphorism():
    """ Shows a random aphorism """
    
    aphorism = Aphorism.objects.order_by("?")[0]
    
    return {'aphorism': aphorism}
