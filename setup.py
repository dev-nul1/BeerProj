try:
    from setuptools import setup
except ImportError:
    from distutils.core import setup

config = {
    'description': 'Beer project',
    'author': 'Philip Scheid',
    'url': 'www.philipscheid.com',
    'download_url': 'github.com/dev-nul1',
    'author_email': 'hello@philipscheid.com',
    'version': '0.1',
    'install_requires': ['nose'],
    'packages': ['BeerProj'],
    'scripts': [],
    'name': 'projectname'
}

setup(**config)
