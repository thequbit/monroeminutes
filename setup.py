#!/bin/env python
# -*- coding: utf8 -*-

from distribute_setup import use_setuptools
use_setuptools()

from setuptools import setup, find_packages

version = "0.0.0"

setup(
    name="MonroeMinutes",
    version=version,
    description="Scrapes minutes, tik tik tok",
    classifiers=[
        "Intended Audience :: Monroe",
    ],
    keywords="",
    author="Tim Duffy",
    author_email="tim@timduffy.me",
    license="GPLv3+",
    packages=find_packages(
    ),
    include_package_data=True,
    zip_safe=False,
    install_requires=[
        "flask",
        "pdfminer==20110515",
        "BeautifulSoup4",
        "elasticsearch",
        "python-magic",
        "pymongo",
	"mysql-python",
    ],
    #TODO: Deal with entry_points
    #entry_points="""
    #[console_scripts]
    #pythong = pythong.util:parse_args
    #"""
)

