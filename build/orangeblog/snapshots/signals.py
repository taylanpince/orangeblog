import os
from PIL import Image

from django.conf import settings

from snapshots.models import Snapshot


TARGET_WIDTH = 150
TARGET_HEIGHT = 100
TARGET_RATIO = TARGET_WIDTH / TARGET_HEIGHT

def generate_image(sender, instance, signal, *args, **kwargs):
    if type(instance) == Snapshot:
        img = Image.open(os.path.join(settings.MEDIA_ROOT, instance.image))
        
        if img.mode != "RGB":
            img = img.convert("RGB")
        
        img_ratio = img.size[0] / img.size[1]
        
        if img_ratio > TARGET_RATIO:
            img = img.resize(((TARGET_WIDTH * img.size[0] / img.size[1]), TARGET_WIDTH), Image.ANTIALIAS)
            img = img.crop((0, 0, TARGET_WIDTH, TARGET_HEIGHT))
        elif img_ratio < TARGET_RATIO:
            img = img.resize((TARGET_WIDTH, (TARGET_WIDTH * img.size[1] / img.size[0])), Image.ANTIALIAS)
            img = img.crop((0, 0, TARGET_WIDTH, TARGET_HEIGHT))
        else:
            img = img.resize((TARGET_WIDTH, TARGET_HEIGHT), Image.ANTIALIAS)
        
        image_path = os.path.join(settings.MEDIA_ROOT, 'dynamic', 'snapshots', ( '%s.jpg' % instance.id ) )
        img.save(image_path)
