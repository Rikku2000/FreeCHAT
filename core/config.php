<?php
error_reporting (0);

session_start();

define("DATA_DIR", __DIR__."/../data");
define("ONLINE_WINDOW", 60);
define("TITLE", "FreeCHAT");
define("DESCRIPTION", " - Chat for free and find new friends!");
define("KEYWORDS", "Chat, Friends, People, Meet, Communication, Online, Party, Erotic");
define("CHATBOT", "Alice");
define("UPLOAD", 4096 * 1024);
define("UPLOADDIR", "upload/");
define("TYPINGINTERVAL", 500);
define("ONLINEINTERVAL", 1000);
define("INVITEINTERVAL", 5000);
define("MESSAGEINTERVAL", 500);
define("HEARTBEATINTERVAL", 1000);
define("PRIVATEINTERVAL", 500);
define("SECRETKEY", "FreeChatSecretKey");

include "language-en_us.php";
if (!isset($_SESSION['lang'])) $_SESSION['lang']="en_us";
include "language-". $_SESSION['lang'] .".php";

include "function.php";
?>
