#!/bin/env python
# -*- coding: utf8 -*-

from distribute_setup import use_setuptools
use_setuptools()

from setuptools import setup, find_packages

version = "0.0.1"

setup(
    name="MonroeMinutes",
    version=version,
    description="Scrapes Meeting Minutes for Monroe County, NY and provides a web front-end for searching.",
    classifiers=[
    	"Development Status :: 3 - Alpha",
    	"Natural Language :: English",
    	"Operating System :: POSIX :: Linux",
    	"Programming Language :: Python :: 2.7",
    	
    	"Framework :: Flask",
        "Intended Audience :: Developers",
        "Intended Audience :: Other Audience",
        "Intended Audience :: System Administrators",
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
        "barking-owl",
        "elasticsearch",
        "pymongo",
    ],
    #TODO: Deal with entry_points
    #entry_points="""
    #[console_scripts]
    #pythong = pythong.util:parse_args
    #"""
)

