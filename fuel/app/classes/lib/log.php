<?php

/**
 *  MyLog
 *  Extends Fuel\Core\Log to allow for log entries to be written
 *  without checking log level first
 * 
 *  Usage: 
 * 	use \MyLog as MyLog; - put this at the start of the fiule you wish to use it in
 * 
 *  Then, call one of the following functions:
 * 	MyLog::info() 		- write info level message to log
 * 	MyLog::warning()	- write warning level message to log
 * 	MyLog::debug()		- write debug level message to log
 * 	MyLog::error() 		- write error level message to log
 */
class MyLog extends Fuel\Core\Log {

	/**
	 * Write Log File
	 *
	 * Overloads the function in the core log file, doesn't check log level
	 *
	 * @param	int|string    $level     the log level
	 * @param	string        $msg      the log message
	 * @param	string|array  $context  message context
	 * @return	bool
	 * @throws	\FuelException
	 */
   	public static function write($level, $msg, $context = null)
	{

		// for compatibility with Monolog contexts
		if (is_array($context))
		{
			return static::log($level, $msg, $context);
		}

		// if profiling is active log the message to the profile
		if (\Config::get('profiling'))
		{
			empty($context) ? \Console::log($msg) : \Console::log($context.' - '.$msg);
		}

		// log the message
		empty($context) ? static::instance()->log($level, $msg) : static::instance()->log($level, $context.' - '.$msg);

		return true;
	}
}