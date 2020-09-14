# http://go.chriswarrick.com/entry_points

from setuptools import setup

setup(
    name="my_project",
    version="0.1.0",
    packages=["my_project"],
    entry_points={
        "console_scripts": [
            "my_project = my_project.__main__:main"
        ]
    },
)
