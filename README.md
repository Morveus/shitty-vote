# shitty-vote
A self-hostable shitty voting system without any database nor user accounts.

This project was born to have family vote for a chef's menu, and I didn't find anything dead simple enough so I quickly wrote *very* bad PHP code.

<img width="917" alt="image" src="https://github.com/Morveus/shitty-vote/assets/2972468/01a91ee5-5695-48d7-86f1-3685e9a06796">


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
- Sessions prevent another vote. _This is of course intended for family/friends use, people who "play fair"_
