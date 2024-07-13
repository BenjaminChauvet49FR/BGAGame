<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * HexField implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * hexfield.action.php
 *
 * HexField main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/hexfield/hexfield/myAction.html", ...)
 *
 */
  
  
  class action_hexfield extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( $this->isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = $this->getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "hexfield_hexfield";
            $this->trace( "Complete reinitialization of board game" );
      }
  	} 
	
	public function clickMove() {
		self::setAjaxMode();
        $result = $this->game->clickMove();		
        self::ajaxResponse();
	}
	
	public function clickExtract() {
		self::setAjaxMode();
        $result = $this->game->clickExtract();		
        self::ajaxResponse();
	}
	
	public function clickFinish() {
		self::setAjaxMode();
        $result = $this->game->clickFinish();		
        self::ajaxResponse();
	}
	
	public function clickBuildHere() {
		self::setAjaxMode();
        $result = $this->game->clickBuildHere();		
        self::ajaxResponse();
	}
	
	public function clickCancel() {
		self::setAjaxMode();
        $result = $this->game->cancel();		
        self::ajaxResponse();
	}
  	
	public function moveMeeple() {
        self::setAjaxMode();     
        $x = self::getArg( "x", AT_posint, true );
        $y = self::getArg( "y", AT_posint, true );
        $result = $this->game->moveMeeple( $x, $y );
        self::ajaxResponse( );
    } 
	
	public function buildHere() {
		self::setAjaxMode(); 
		$buildingID = self::getArg( "buildingID", AT_posint, true );
        $result = $this->game->buildHere($buildingID);
        self::ajaxResponse( );
	}
	
	/*public function extractResource() TODO to scrap
    {
        self::setAjaxMode();     
        $x = self::getArg( "x", AT_posint, true );
        $y = self::getArg( "y", AT_posint, true );
        $result = $this->game->extractResource( $x, $y );
        self::ajaxResponse( );
    } */
	
  	// TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        $this->setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = $this->getArg( "myArgument1", AT_posint, true );
        $arg2 = $this->getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        $this->ajaxResponse( );
    }
    
    */

  }
  

