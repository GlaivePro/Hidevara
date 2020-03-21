<?php

namespace GlaivePro\Hidevara;

class HidingHandler implements \Illuminate\Contracts\Debug\ExceptionHandler
{
	protected $superGlobies = ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER', '_ENV'];
	
	// The original error handler
	protected $handler;
	
	public function __construct($handler)
	{
		// Merge the config here
		$config = \Config::get('hidevara', []);
        \Config::set('hidevara', array_merge(require __DIR__.'/config.php', $config));
		
		$this->handler = $handler;
	}
	
	public function __call($method, $arguments)
	{
		// Pass whatever calls to the original error handler
		return $this->handler->$method(...$arguments);
	}
	
	protected function variableMatches($variable, $test)
	{	
		if (true === $test)
			return true;
		
		if (is_array($test) && in_array($variable, $test))
			return true;
		
		if (is_string($test) && preg_match($test, $variable))
			return true;
			
		return false;
	}
	
	protected function executeActionOnVariable($superG, $action, $field)
	{
		if ('expose' == $action)
			return;
		
		if ('hide' == $action)
		{
			$replaceWith = config('hidevara.replaceHiddenValueWith');
			if (null === $GLOBALS[$superG][$field] || '' === $GLOBALS[$superG][$field])
				$replaceWith = config('hidevara.replaceHiddenEmptyValueWith');
			
			$GLOBALS[$superG][$field] = $replaceWith;
			return;
		}
		
		// treat any unrecognized action as 'remove'
		unset($GLOBALS[$superG][$field]);
	}
	
	protected function dealWithField($superG, $field, $rules)
	{
		foreach ($rules as $action => $test)
			if ($this->variableMatches($field, $test))
			{
				$this->executeActionOnVariable($superG, $action, $field);
				return;
			}
		
		$this->executeActionOnVariable($superG, 'remove', $field);
	}
	
	protected function dealWithSuperG($superG)
	{
		if (!isset($GLOBALS[$superG]))
			return;
		
		$GLOBALS['hidevara'][$superG] = $GLOBALS[$superG];
		
		$rules = config('hidevara.'.$superG);
		
		foreach ($GLOBALS[$superG] as $field => $content)
			$this->dealWithField($superG, $field, $rules);
	}
	
    public function render($request, \Throwable $exception)
    {
		foreach ($this->superGlobies as $superG)
			 $this->dealWithSuperG($superG);
		
		return $this->handler->render($request, $exception);
    }
	
	// The following functions are here so we could implement the ExceptionHandler interface
	public function report(\Throwable $exception)
	{
		return $this->handler->report($exception);
	}
	
	public function renderForConsole($output, \Throwable $exception)
	{
		return $this->handler->renderForConsole($output, $exception);
	}
	
	public function shouldReport(\Throwable $exception)
	{
		return $this->handler->shouldReport($exception);
	}
}
