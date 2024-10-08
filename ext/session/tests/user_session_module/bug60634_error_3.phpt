--TEST--
Bug #60634 (Segmentation fault when trying to die() in SessionHandler::write()) - fatal error in write after exec
--INI--
session.save_path=
session.name=PHPSESSID
session.save_handler=files
--EXTENSIONS--
session
--FILE--
<?php

ob_start();

function open($save_path, $session_name) {
    return true;
}

function close() {
    echo "close: goodbye cruel world\n";
    exit;
}

function read($id) {
    return '';
}

function write($id, $session_data) {
    echo "write: goodbye cruel world\n";
    undefined_function();
}

function destroy($id): bool {
    return true;
}

function gc($maxlifetime) {
    return true;
}

session_set_save_handler('open', 'close', 'read', 'write', 'destroy', 'gc');
session_start();

?>
--EXPECTF--
Deprecated: session_set_save_handler(): Providing individual callbacks instead of an object implementing SessionHandlerInterface is deprecated in %s on line %d
write: goodbye cruel world

Fatal error: Uncaught Error: Call to undefined function undefined_function() in %s:%d
Stack trace:
#0 [internal function]: write(%s, '')
#1 {main}
  thrown in %s on line %d

Warning: PHP Request Shutdown: Cannot call session save handler in a recursive manner in Unknown on line 0
