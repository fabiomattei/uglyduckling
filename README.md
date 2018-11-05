First Step Core module
======================

### The best code is the code you don't need to write

I wrote this code in order to avoid to write over and over agian the same stuff.
Do you really need a framework when the code you write looks always the same, with small almost not-existents changes?

I don't think so!

I have been writing web applications for many years. What were those applications doing? They were thanking data from a form, 
saving that data in a database and then editing that data in another form and showing that data in a table or in a graph ad 
sometimes they were deleting that data (not very often to be fair).

Well, I have done that over and over again, form after form, ORM after ORM, MVC after MVC.

I felt lost and bored.

I have learned many framework to speed up my process, I read many books: the new thing, so exiting!

Then I started to notice that my work was repeatable and those frameworks were slowing me down.

So I started to wonder: What do I nood to do to make for example a table cotaining data taken from a database?
The answer was: I need to make a SQL query, I need to define the structure of the table and I need to put the results of the query on the table. That's it.
I need this three things, nothig more than that.

I put all this information in a json file and this came out:

```json
{
  "name": "requesttablev1",
  "metadata": { "type":"table", "version": "1" },
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
}
```

No ORM, no MVC, no framework.

I know I used SQL and not an ORM but I like SQL! SQL haven't changed in ages, and this means that it works!
It is not sad to be old!

I gave a title to the table and in the fields array I defined the headlines and the sql fields that were taken from the query in order to fill the cells of that table.
How many times have you solved this same simple problem?
Have you ever done it writing less code?
If that is true please let me know!

I know what you are thinking about: this is not general enough, what if I need to make calculations or generalize some logic or do something more complicated?
Well, you can always get back to your old way, and program a controller a view, maybe made of partials, connect and ORM, write down your model and... so on and so on and so on...

But, let's be straight, do you really need it? How often do you do that?
I often found mysel filling a table with the results of just one query, maybe with few joined tables.





