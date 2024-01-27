# shitty-vote

A shitty voting system without any database nor user accounts

# Setup
- Create a "/votes" folder (or update the corresponding variable)
- Change the contents of "menu.json" with whatever you wish it to contain
- Run shitty-vote

# Use it
Run a PHP server, point your friends and family to its URL and click the "Vote" buttons.

People can only vote once per session, which is good enough for a family and/or friends usage

# Warnings
- Make sure your options all have a different label, they are identified by an MD5 of their contents (yeah)
- Make sure to make no changes to your JSON file, because of the obvious implications of the aforementioned limitation


