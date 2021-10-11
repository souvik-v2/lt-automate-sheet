# Project Name: Automate Sheet
# Author Name: Souvik Patra
# Project Description: This is an internal function based PHP custom project. After login user can upload csv file, system auto calculate required field value and insert into database accordingly. User can retrive calculated data from db and export as xls file.

# Below Are Some Calcution Formula:
# Planned story points = total story point
# Actual delivered = Planned story points - (v2 + lt carryover)		
# V2 = check resource with 1 get (++storypoint)
# LT = Planned story points = v2
# V2 Carryover = if sprint colmn 2 exist and check resource with 1 get (++storypoint)
# LT Carryover = if sprint colmn 2 exist and check resource with 0 get (++storypoint)
# V2 Reopen calculation based on second file upload
# LT Reopen calculation based on second file upload
# All (%) calulation based on first and second file upload

# Create lt-automate-sheet database and import sql file from __db__ folder.
# test :: test // user
# admin :: admin // admin
