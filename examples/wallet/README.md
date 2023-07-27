### Features
- Support crypto payments
- User can pay with any crypto(You could filter them in your nowPayment account setting)
- Support in db balance
- Easy transfer with shared user feature
- Simple and fast

### What we can learn from it?
- How to use BPT-multi library
- How to use `mysql` class
- How to use `crypto` `ezPay`
- How to use `callback` handler

### How can I use it?
#### composer : 
- Run `composer require bpt/bpt-multi` in your commend line
- Download bot.php and place it in the same folder as `vendor` folder is

#### Without composer :
- Download bot.php

#### From here do it for both styles
- Place bot admin userid in line 23
- Place your bot token in line 357
- Place your nowPayments info in line 367-368
- Create a new mysql database and place it details in line 360~362
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

**I didn't like mysql class style, Can I use original style of query?**<br>
Yes , call `mysql::pureQuery()` for running original style of query

### You have more question?
**Fell free to ask them from our supports**