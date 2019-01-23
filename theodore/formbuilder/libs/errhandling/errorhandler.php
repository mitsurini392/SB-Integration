<?php

function J316errorHandler($errno, $errstr, $errfile, $errline) {
$v_errno = mysql_real_escape_string($errno);
$v_errstr = mysql_real_escape_string($errstr);
$v_errfile = mysql_real_escape_string($errfile);
$v_errline = mysql_real_escape_string($errline);


    if (!(error_reporting() & $errno)) {
        return;
    }

	switch ($errno) {
	case E_USER_ERROR:
   	mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_USER_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");
   	
        break;

	case E_USER_WARNING:
	mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_USER_WARNING', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");
        
        break;

	case E_USER_NOTICE:
       mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_USER_NOTICE', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");
	$errorctr++;
        break;

	case E_ERROR:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_WARNING:
	mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_WARNING', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_PARSE:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_PARSE', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_NOTICE:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_NOTICE', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_CORE_ERROR:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_CORE_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_CORE_WARNING:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_CORE_WARNING', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_COMPILE_ERROR:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_COMPILE_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_COMPILE_WARNING:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_COMPILE_WARNING', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_STRICT:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_STRICT', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	case E_RECOVERABLE_ERROR:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('E_RECOVERABLE_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

	break;
	
	default:
        mysql_query("INSERT INTO error_logs (err_type, err_no, err_str, filename, linenumber) VALUES('UNKNOWN_ERROR', '".$v_errno."', '".$v_errstr ."', '".$v_errfile."', '".$v_errline."')");

        break;
    }

	return true;
}
?>