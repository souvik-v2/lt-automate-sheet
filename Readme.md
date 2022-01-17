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
# Actual Delivered = Planned Story Point - (LT + V2) Carryover //wrong
# V2 Delivered = Number of story point V2 delivered //  wrong
# LT Delivered	= Number of story point LT delivered // wrong
# V2 Rework	= Number of allocated story point V2 reopen (If the value of the 3rd sprint column of the uploaded excel sheet with "Resource" column value 1).
# LT Reopen	= Number of allocated story point LT reopen (If the value of the 3rd sprint column of the uploaded excel sheet with "Resource" column value 0).
# V2 Carryover	= Number of allocated story point V2 carry (If the value of the 2nd sprint column of the uploaded excel sheet with "Resource" column value 1).
# LT Carryover	= Number of allocated story point LT carry (If the value of the 2nd sprint column of the uploaded excel sheet with "Resource" column value 0).
# QA Passed	= V2 Delivered
# V2 Reopen Percentage	= (V2 Reopen / V2 Delivered) * 100
# LT Reopen Percentage	= (LT Reopen / LT Delivered) * 100
# V2 Carryover Percentage = (V2 Carryover / V2 Delivered) * 100
# LT Carryover Percentage = (LT Carryover / LT Delivered) * 100
# Planned Vs Completed ratio = (Actual Delivered / Planned Story Point) * 100
# if the sprint does not match with the current sprint is treated as carryover
# if vendor is empty or the first sprint is empty , then that record will not be considered as record.
# v2_total_completed and lt_total_completed will be calculated from status(pending deployment and closed)
# V2 Delivered = sum(v2 deployment status+v2 closed)-v2 deployment and closed carryover means v2_total_completed-v2 deployment and closed carryover
# LT Delivered	= sum(lt deployment status+lt closed)-lt deployment and closed carryover means lt_total_completed-lt deployment and closed carryover
# Actual Delivered = LT delivered+V2 delivered

# Development Phase 2 Modules:
# Seperate developer table [insert, update, disable]
# Developer login, Developer Dashboard
# LT_DEVELOPER_NAME / developer (Default username & password for developer)
# After logging in, the developer can change his password
# Developer can view own sprint ticket status.
# Developer can comment on completed/reopen/carryover Ticket Number in a Sprint
# Sprint Report section with Project name, Project sprint name, Project developer name filter option

