<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * HexField implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * hexfield.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in hexfield_hexfield.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */
  
require_once( APP_BASE_PATH."view/common/game.view.php" );
  
class view_hexfield_hexfield extends game_view
{
    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "hexfield";
    }
    
  	function build_page( $viewArgs )
  	{		
  	    // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count( $players );

        /*********** Place your code below:  ************/


        /*
        
        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = $this->_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = $this->raw( $some_html_code );
        
        */
        
        /*
        
        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 
        

        $this->page->begin_block( "hexfield_hexfield", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }
        
        */
			
	
		$typesToTPL = array("plains", "lake", "forest", "stone", "iron", "none");
		$pixSize = 84;
        $this->page->begin_block( "hexfield_hexfield", "hex" );
		$counterId = 0;
		$pixTop = 0;
		for ($y = 0 ; $y < $this->game->Y_SIZE ; $y++) {
			$pixLeft = $pixSize/2 * ($y % 2);
			for ($x = 0 ; $x < $this->game->X_SIZE ; $x++) {
				$this->page->insert_block( "hex", array( 
					"TYPE" => $typesToTPL[$this->game->hexMap[$y][$x]],
					"X" => $x,
					"Y" => $y,
					"LEFT" => $pixLeft,
					"TOP" => $pixTop
				 ) );
				 $pixLeft += $pixSize;
			}
			$pixTop += $pixSize*3/4;
		}


        /*********** Do not change anything below this line  ************/
  	}
}
?>