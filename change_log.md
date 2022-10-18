- 1.0.1
  - fix set webhook(for BPT itself) bug that cause infinite loop
  - fix easyKey method bug that make only last row visible
- 1.1.0
  - Move to api version 6.2 completely
  - Add a new setting for set default timeout of telegram methods
  - Fix a bug that timeouts causes errors
  - Add download method , You could use it for urls , for file_ids or in updates<br>
  `$update->message->document->download('file.zip');`<br>
  `telegram::downloadFile('file.zip','file_id_asdadadad');`<br>
  `tools::downloadFile('https://example.com/example.zip','example.zip');`