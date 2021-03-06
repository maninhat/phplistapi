<?php

//No HTML-output, please!
ob_end_clean();

//Getting phpList globals for this plugin
$plugin = $GLOBALS["plugins"][$_GET["pi"]];

include 'includes/response.php';
include 'includes/pdo.php';

include 'includes/common.php';

include 'includes/actions.php';
include 'includes/lists.php';
include 'includes/users.php';
include 'includes/templates.php';
include 'includes/messages.php';

include 'doc/doc.php';


//Check if this is called outside phpList auth, this should never occur!
if ( empty( $plugin->coderoot ) ){
    phpList_API_Response::outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

//If other than POST then assume documentation report
if ( strcmp( $_SERVER['REQUEST_METHOD'], "POST")  ){

    $doc = new phpList_API_Doc();
    $doc->addClass( 'phpList_API_Actions' );
    $doc->addClass( 'phpList_API_Lists' );
    $doc->addClass( 'phpList_API_Users' );
    $doc->addClass( 'phpList_API_Templates' );
    $doc->addClass( 'phpList_API_Messages' );
    $doc->output();

}

//Check if command is empty!
$cmd = $_REQUEST['cmd'];
if ( empty($cmd) ){
    phpList_API_Response::outputMessage('OK! For action, please provide Post Param Key [cmd] !');
}

//Now bind the commands with static functions
if ( is_callable( array( 'phpList_API_Lists',       $cmd ) ) ) phpList_API_Lists::$cmd();
if ( is_callable( array( 'phpList_API_Actions',     $cmd ) ) ) phpList_API_Actions::$cmd();
if ( is_callable( array( 'phpList_API_Users',       $cmd ) ) ) phpList_API_Users::$cmd();
if ( is_callable( array( 'phpList_API_Templates',   $cmd ) ) ) phpList_API_Templates::$cmd();
if ( is_callable( array( 'phpList_API_Messages',    $cmd ) ) ) phpList_API_Messages::$cmd();

//If no command found, return error message!
phpList_API_Response::outputErrorMessage( 'No function for provided [cmd] found!' );

?>