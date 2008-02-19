from django.dispatch import dispatcher
from django.contrib.auth.models import User
from django.db.models.signals import post_save, post_delete

from profiles.signals import create_profile, delete_profile


dispatcher.connect(create_profile, signal=post_save, sender=User)
dispatcher.connect(delete_profile, signal=post_delete, sender=User)