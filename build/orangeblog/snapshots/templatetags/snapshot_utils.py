from django import template

from snapshots.models import Snapshot

register = template.Library()


@register.inclusion_tag("snapshots/random_snapshot.html")
def show_random_snapshot():
    """ Shows a random snapshot """
    
    snapshot = Snapshot.objects.order_by("?")[0]
    
    return {'snapshot': snapshot}
