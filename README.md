## Resource Manager

This tool is initially to help manage demand and resource levelling across many projects in an enterprise. 
At this stage it expects input from a powerBi report out of Microsoft Project Server and thus works on allocations per month rather than project tasks.

Its early stages right now, we are looking for help in:

* styling - its default Bootstrap right now :vomit:
* reporting - building reports as needed, but input would be good.
* CRUD expansion, I am only updating cruds as I need them
* fancy alogrithms - there are a bunch like controlled annealing to allow some automation in resource allocation, it would be nice to have.

## Features

    - Manage Resources: contracts, leave, skills
    - Receive Demands for resources and allocate them accordingly
    - View allocations of resources and if necessary remove resources from projects (puts the demand back into the pool)
    - Create a Service catalogue with associates required skills and estimated effort
    - Basic Skill library with imports using RSD

## Next steps

- [X] Split out some hard coded things into env or a settings table
- [X] Update Allocations view to show percent of availablity
- [X] Delete/edit Demands - this probably needs to be more usable than a month by month allocation, but still allow that
- [X] Differentiate manual demand from uploads so we don't delete the wrong stuff
- [ ] Allow editing of manual demand, but not uploaded
- [ ] Bulk add leave aka public holidays - to people in a region
- [X] Make calendar controls consistent
- [X] Set up teams 
- [ ] Create Resource Type crud and link with teams
- [ ] Filter multiple views/permissions by Team
- [ ] Create a "Senior Manager" role that might oversee one or more Teams
- [ ] Update User admin to assign the user into various function roles such as "Resource", "Team Owner", etc
- [ ] Add ability to release demand from a resource from a date (to handle exits)
- [ ] Separate allocations and demands from external sources and deliberately accept them instead of assuming they are correct

Yes it is currently Laravel 10 based, not 11. Bleading edge, especially for major changes is not my thing.
