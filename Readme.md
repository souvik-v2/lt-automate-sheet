# Project Name: Automate Sheet
# Author Name: Souvik Patra
# Project Description: This is an internal function based PHP custom project. After login user can upload csv file, system auto calculate required field value and insert into database accordingly. User can retrive calculated data from db and export as xls file.

# Create lt-automate-sheet database and import sql file from __db__ folder.
# test :: test // user
# admin :: admin // admin@123

# Note:
# *All (%) calulation based on two excel file upload
# Sprint Name = Name of the Sprint
# Planned Story Point = Total story points
# Actual Delivered = Planned Story Point - (LT + V2) Carryover
# V2 Delivered = Number of story point V2 delivered
# LT Delivered	= Number of story point LT delivered
# V2 Rework	= Number of allocated story point V2 reopen (If the value of the 3rd sprint column of the uploaded excel sheet with "Resource" column value 1).
# LT Reopen	= Number of allocated story point LT reopen (If the value of the 3rd sprint column of the uploaded excel sheet with "Resource" column value 0).
# V2 Carryover	= Number of allocated story point V2 carry (If the value of the 2nd sprint column of the uploaded excel sheet with "Resource" column value 1).
# LT Carryover	= Number of allocated story point LT carry (If the value of the 2nd sprint column of the uploaded excel sheet with "Resource" column value 0).
# QA Passed	= V2 Delivered - V2 Reopen
# V2 Reopen Percentage	= (V2 Reopen / V2 Delivered) * 100
# LT Reopen Percentage	= (LT Reopen / LT Delivered) * 100
# V2 Carryover Percentage = (V2 Carryover / V2 Delivered) * 100
# LT Carryover Percentage = (LT Carryover / LT Delivered) * 100
# Planned Vs Completed ratio = (Actual Delivered / Planned Story Point) * 100