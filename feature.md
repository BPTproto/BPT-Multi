# What is BPT multi features?

### 1- Support all telegram methods and classes (Api v7.4)

### 2- autoload system tnx to composer (only needed file will include)

### 3- Self documented for itself and Telegram

### 4- A lot of syntax for calling Telegram methods
Methods are case-insensitive but IDE guides only applied to original methods<br>
Examples :

* `telegram::sendMessage('hello');`
* `request::sendMessage('hello');`
* `BPT::sendMessage('hello');`
* `$this->sendMessage('hello'); //in handler class`
* `$BPT->sendMessage('hello'); //$BPT is an object of BPT class`

We suggest you use these formats(named arguments) because arguments order could be changed by telegram :
* `telegram::sendMessage(['text' => 'hello']); All of above examples support this format too`
* `telegram::sendMessage(text: 'hello'); All of above examples support this format too`

### 5- A lot alices name for Telegram methods
Alices are case-insensitive but IDE guides only applied to original methods<br>
Examples :

* `sendMessage` => `send`
* `sendDocument` => `senddoc` `document` `doc`

### 6- Autofill methods arguments based on update
Examples :
* `sendMessage` => `chat_id`
* `copyMessage` => `from_chat_id` `message_id`

### 7- IP verify for Telegram
Also support CloudFlare-CDN and ArvanCloud-CDN

### 8- Support webhook and getUpdates method for receiving updates
you could use webhook without ssl (certificate file needed)

### 9- Build in handler for listening to updates
support a lot of update types for listening

### 10- Build in multiprocess
support exec and curl way for doing that

### 11- A good way for handling database
for now only `json` and `mysql` type supported

### 12- Special way for using payment
We got crypto class for payments with cryptoCurrency
We got idpay and zarinpal for payments with rial

### 13- A lot of extra methods and functions for making your job easier
See this for more info [tools class](#tools-class)

### 14- A lot of tricks and settings for keeping your bot safe

### 15- A lot classes

#### `json class`
json class is there for manage and use json database<br>
There is a lot of properties and methods for making your job easier<br>
Examples :

- `user` property which has user data in private chat
- `supergroup` property which has supergroup data
- `group_user` property which has user data in group
- `getUsers` method for receiving user ids list

#### `tools class`
tools class is there for adding extra methods<br>
These methods will help you to do some easy~difficult things with only one call<br>
Examples :

- `modeEscape` method for escaping text with wanted parse mode
- `delete` method for deleting file or folder (even folder with subFiles)
- `randomString` method for generating random string
- `isUsername` method for checking is it a username or not
- `isJoined` method for checking is user joined in our selected channels or not

#### `mysql`, `idpay`, `zarinpal`, `crypto` and a lot more of useful classes

#### `function file`
for now its only has two function

`object` function for creating objects easily

Examples :

`object(key: 'value');`

`strReplace` same as str_replace but with a small difference


#### constants classes
a lot of classes for simplify some texts and params and names
Examples :

- `chatActions` for chat actions like typing or ...
- `chatType` for chat type like privates or ...
- `fields` for catchField method like chat_id or ...
- `parseMode` for parse modes like html or ...
- `updateTypes` for update types like message or ...

### 16- And a lot more, just give it a try ;)


### Planning to add
- [x] mysql db type
- [x] crypto payment
- [x] rial payment
  - [x] zarinpal
  - [x] idpay
- [ ] OpenSwoole support
- [ ] ezPay
  - [x] ezPay crypto
  - [ ] ezPay zarinpal
  - [ ] ezPay idpay
- [ ] adding aghaye pardakht and ezPay for it
- [ ] cronjob manager
- [ ] anti spam
- [ ] broadcast