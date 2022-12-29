### Features
- Support any type of message
- Support users with closed forward
- Support reply in both user and admin chat
- Simple and fast

### What we can learn from it?
- How to use BPT-multi library
- How to use `mysql` class

### How can I use it?
#### composer : 
- Run `composer require bpt/bpt-multi` in your commend line
- Download bot.php and place it in the same folder as `vendor` folder is

#### Without composer :
- Download bot.php

#### From here do it for both styles
- Place bot admin userid in line 17
- Place your bot token in line 213
- Create a new mysql database and place it details in line 216~218
- Import `db.sql` file in your database
- Now open `bot.php` in your browser
- Test your bot

### Faq
**What is `vendor/autoload.php`?**<br>
If you use composer for installing it ,
It will create a folder called vendor which contains library and other needed files
When you require `autoload.php` , Its give you power to include only needed files<br>
For example : If you didn't use `tools` class ,
Then `tools.php` file does not include and this will make your bot even faster

**What is `BPT.phar`?**<br>
It is downloaded code with composer but compressed and converted to single file
It still supports composer autoload(explained above)<br>
We recommend you to use composer instead of this

**Why you saved data as constant in handler class?**<br>
Because they are constants, they do not change during process of code<br>
If you need to change them during execute of code, then save them in variable(or property)

**I didn't like mysql class style, Can I use original style of query?**<br>
Yes , call `mysql::pureQuery()` for running original style of query

### You have more question?
**Fell free to ask them from our supports**