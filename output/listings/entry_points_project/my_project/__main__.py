"""The main routine of my_project."""

# Copyright (C) 2014 Chris Warrick.
# From: http://chriswarrick.com/
# License: CC BY http://creativecommons.org/licenses/by/3.0/

import sys


def main(args=None):
    """The main routine."""
    if args is None:
        args = sys.argv[1:]

    print("This is the main routine.")
    print("It should do something interesting.")

    # Do argument parsing here (eg. with argparse) and anything else
    # you want your project to do.

if __name__ == "__main__":
    main()
