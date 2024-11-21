## Resource Manager

This tool is initially to help manage demand and resource levelling across many projects in an enterprise. 
At this stage it expects input from a powerBi report out of Microsoft Project Server and thus works on allocations per month rather than project tasks.

Its early stages right now, we are looking for help in:

* styling - its default Bootstrap right now :vomit:
* reporting - building reports as needed, but input would be good.
* CRUD expansion, I am only updating cruds as I need them
* fancy alogrithms - there are a bunch like controlled annealing to allow some automation in resource allocation, it would be nice to have.

## Next steps

- [X] Allocation view per resource
- [X] Demand view to see if there are multiple resource requests
- [ ] Split out some hard coded things into env or a settings table
- [X] Assign demand to a resource
- [X] De-assign a project back to demand
- [X] Some kind of nicer dashboard
- [X] Collect skill list
- [X] Add skills to a resource
- [X] Add teams and privileges etc
- [ ] Manual Demand collection (inc business need, funding etc)
- [ ] Delete/edit Demands - this probably needs to be more usable than a month by month allocation, but still allow that
- [ ] Differentiate manual demand from uploads so we don't delete the wrong stuff
- [ ] Allow editing of manual demand, but not uploaded
- [ ] Provide rest of skills allocation crud
- [ ] Surely there is a decent dashboard with tile system that works easily (spatie/laravel-dashboard seems to have a very specific use case)
- [X] Do something with top nav bar
- [ ] Add region/office to a resource so we can do bulk leave
- [ ] Bulk add leave aka public holidays - to people in a region

Yes it is currently Laravel 10 based, not 11. Bleading edge, especially for major changes is not my thing.
