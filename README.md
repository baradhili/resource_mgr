## Resource Manager

![Code Rabbit Pull Reviews](https://img.shields.io/github/issues-pr/baradhili/resource_mgr.svg)


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

- [ ] Make a non-API/JS export method?
- [ ] New change Requests should supercede old ones
- [ ] Import project allocations should supercede existing for change requests - thus if the project moves it should delete existing as well as modify
- [ ] Show only change requests that apply to the viewer (ie don't show BAs to someone managing SAs)
- [ ] Ignore roles where we don't have a resource manager (ie. if no one on the system handles PMs - don't import at all)
- [ ] Sort out importing of resource types and make sure demands view shows the names
- [ ] Make CRUDs consistent (search, backs on show, etc)


Yes it is currently Laravel 11 based, not 12. But several of the packages don't yet support 12.
