import os

DEBUG = False
TEMPLATE_DEBUG = DEBUG

ADMINS = (
    ('Taylan Pince', 'taylanpince@gmail.com'),
)

DEFAULT_FROM_EMAIL = 'taylan@orangeslices.net'

MANAGERS = ADMINS

TIME_ZONE = 'Turkey'

SITE_ID = 1

USE_I18N = True

MEDIA_ROOT = os.path.join(os.path.realpath(os.path.dirname(__file__)), 'media/')
MEDIA_URL = '/media/'
ADMIN_MEDIA_PREFIX = '/media/admin/'

LARGE_AVATAR_URL = '/media/assets/images/avatar-large.jpg'
SMALL_AVATAR_URL = '/media/assets/images/avatar-small.jpg'

LOGIN_URL = "/giris/"
LOGIN_REDIRECT_URL = "/"

LOOKUP_URL = "/getur/"

SECRET_KEY = 'v&v-_btxodw3-(p86j9l5g@w#^v8l8o$n*mvxxm@iiphrdgo%$'

TEMPLATE_CONTEXT_PROCESSORS = (
    'django.core.context_processors.auth',
    'django.core.context_processors.debug',
    'django.core.context_processors.i18n',
    'django.core.context_processors.media',
    'django.core.context_processors.request',
)

TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
)

MIDDLEWARE_CLASSES = (
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.locale.LocaleMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.middleware.doc.XViewMiddleware',
    'orangeblog.profiles.middleware.UserProfileActivityMiddleware',
)

ROOT_URLCONF = 'orangeblog.urls'

AUTH_PROFILE_MODULE = 'profiles.UserProfile'

TEMPLATE_DIRS = (
    os.path.join(os.path.realpath(os.path.dirname(__file__)), 'templates/'),
)

INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.admin',
    
    'orangeblog.entries',
    'orangeblog.comments',
    'orangeblog.profiles',
    'orangeblog.snapshots',
    'orangeblog.tools',
)

ugettext = lambda s: s

LANGUAGE_CODE = 'tr'

LANGUAGES = (
    ('tr', ugettext('Turkish')),
)

try:
    from settings_local import *
except:
    pass