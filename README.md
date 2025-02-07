Notes
=====

### Documentation

If you are looking for a more complete description of the project check out the documentation site: <a href="https://www.uddocs.com/">https://www.uddocs.com/</a>

### Demo

This <a href="https://github.com/fabiomattei/ud-demo">demo</a> shows the main features of the library.

### The best code is the code you don't need to write

I wrote this code in order to avoid to write over and over again the same stuff.
I have been writing web applications for many years. What were those applications doing? They were thaking data from a form, 
saving that data in a database and then editing that data in another form and showing that data in a table or in a diagram ad 
sometimes they were deleting that data (not very often to be fair).

Well, I have done that over and over again, form after form, ORM after ORM, MVC after MVC.

I felt lost and bored. I was working so hard and I was accomplishing so little.

I have learned many framework to speed up my process, I read many books: the new thing, so exiting!

Then I started to notice that my work was repeatable and those frameworks were slowing me down.

With an MVC approach each change you wond to make to your application requires you open at least 3 files.

So I started to wonder: What do I really need in order to make for example a table cotaining data taken from a database?
The answer was: I need to make a SQL query, I need to define the structure of the table and I need to put the results of the query on the table. That's it.
I need this three things, nothig more than that.

I put all this information in a json file and this came out:

```json
{
  "query": {
    "sql": "select id, name, amount, duedate FROM requestv1;"
  },
  "table": {
    "title": "My table",
    "fields": [
      {"headline": "Name", "sqlfield": "name"},
      {"headline": "Amount", "sqlfield": "amount"},
      {"headline": "Due date", "sqlfield": "duedate"}
    ]
  }
}
```

No ORM, no MVC, and a framework that stays out of my way.

I know I used SQL and not an ORM but I like SQL! SQL haven't changed in ages, and this means that it works! It is not sad to be old! 

I gave a title to the table and in the fields array I defined the headlines and the sql fields that were taken from the query in order to fill the cells of that table.
How many times have you solved this same simple problem?
Have you ever done it writing less code?
If that is true please let me know!

I know what you are thinking about: this is not general enough, what if I need to make calculations or generalize some logic or do something more complicated?
Well, you can always get back to your old way, and program a controller a view, maybe made of partials, connect and ORM, write down your model and... so on and so on and so on...

But, let's be straight, do you really need it? How often do you do that?
I often found mysel filling a table with the results of just one query, maybe with few joined tables.

### Let's add some link

What if I need to add a link to some supported action to the table?
I defined that in the json format too.

```json
{
  "query": {
    "sql": "select id, name, amount, duedate FROM requestv1;"
  },
  "table": {
    "title": "My table",
    "fields": [
      {"headline": "Name", "sqlfield": "name"},
      {"headline": "Amount", "sqlfield": "amount"},
      {"headline": "Due date", "sqlfield": "duedate"}
    ],
    "actions": [
      {"label": "Info", "resource": "inforequest", "parameters":[{"name": "id", "sqlfield": "id"}] },
      {"label": "Edit", "resource": "formrequest", "parameters":[{"name": "id", "sqlfield": "id"}] },
      {"label": "Delete", "resource": "deletereport", "parameters":[{"name": "id", "sqlfield": "id"}] }
    ]
  }
}
```

A link is defined from a label (the user need to see what is clicking) an action and few parameters maybe coming from the SQL query.

If you are wondering what a resource is, it is a index to find a specific json configuration file, like this one, in the system.
There are resources for forms, for pdf exports, for data charts, for whatever you need.
And if you need more you can always define a new template, this is an open source project after all.

### Let's finish up the json file

There are few things to do in order to complete the file.
We neet to give it a name so we can find it between al the resources.
We need to add some metadata, in case in the future we need to add more features.

The system supports the concept of allowed groups to access a specific resource, this explains the "allowedgroups" array.

There is a get section in this file, it is there because all this configurations are for a GET request.

```json
{
  "name": "requesttablev1",
  "metadata": { "type":"table", "version": "1" },
  "allowedgroups": [ "administrationgroup", "teachergroup", "managergroup" ],
  "get": {
    "request": {
      "parameters": []
    },
    "query": {
      "sql": "select id, name, amount, duedate FROM requestv1;"
    },
    "table": {
      "title": "My table",
      "fields": [
        {"headline": "Name", "sqlfield": "name"},
        {"headline": "Amount", "sqlfield": "amount"},
        {"headline": "Due date", "sqlfield": "duedate"}
      ],
      "actions": [
        {"label": "Info", "resource": "inforequest", "parameters":[{"name": "id", "sqlfield": "id"}] },
        {"label": "Edit", "resource": "formrequest", "parameters":[{"name": "id", "sqlfield": "id"}] },
        {"label": "Delete", "resource": "deletereport", "parameters":[{"name": "id", "sqlfield": "id"}] }
      ]
    }
  }
}
```

If you can use the standard templates you can make an entire application just filling the src/Custom folder with all the resources you need. Open that folder and have a look so you can see how the other resources are defined.
 
### Static check using phpstan

```bash
vendor/bin/phpstan --memory-limit=1G analyse src
```
