import os
import sys

sys.path.append("/home/taylan/sites/orangeblog/app")
sys.path.append("/home/taylan/sites/orangeblog/app/libs")
sys.path.append("/home/taylan/sites/orangeblog/app/orangeblog")

os.environ["DJANGO_SETTINGS_MODULE"] = "orangeblog.settings"

import django.core.handlers.wsgi

application = django.core.handlers.wsgi.WSGIHandler()
