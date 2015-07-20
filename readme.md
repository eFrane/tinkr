# tinkr

tinkr is a testbed console thing for PHP. one day I will come up with a better
explanation. for now, just know that you can do this:

```bash
> tinkr
>>> tinkr use nebott/carbon
tinkr> fetching nesbot/carbon...
>>> $now = Carbon\Carbon::now();
=> <Carbon\Carbon #000000004f3e32d2000000012ce84d02> {
       date: "2015-07-20 16:44:17.000000",
       timezone_type: 3,
       timezone: "Europe/Berlin"
   } 
```

I did this because I wanted something to let me quickly test packages
outside of real projects.

License: MIT

