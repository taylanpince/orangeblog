from django.dispatch import dispatcher
from django.db.models.signals import post_save

from snapshots.models import Snapshot
from snapshots.signals import generate_image


dispatcher.connect(generate_image, signal=post_save, sender=Snapshot)