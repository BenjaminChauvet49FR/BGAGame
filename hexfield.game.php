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
  * hexfield.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class HexField extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        $this->initGameStateLabels( array( 
			"moves_points_left" => 10,
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ) );        
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "hexfield";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
		// Fields
		$xMeepleStarting = array(1,2,3,4);
		$yMeepleStarting = array(1,2,3,4);
				
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = $this->getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, x_meeple, y_meeple, wood, food, stone, iron) VALUES ";
        $values = array();
		$i = 0;
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = 
			"('".$player_id."','$color','".$player['player_canal']."','"
			.addslashes( $player['player_name'] )."','"
			.addslashes( $player['player_avatar'] )."','"
			.$xMeepleStarting[$i]."','"
			.$yMeepleStarting[$i]."','0','0','0','0')";
			$i++;
			
        }
        $sql .= implode( ',', $values );
        $this->DbQuery( $sql );
		
		// Create the land
		$values = array();
		$sql = "INSERT INTO hexfieldtaken (x, y, taken) VALUES ";
		for ($y = 0 ; $y < $this->Y_SIZE ; $y++) {
			for ($x = 0 ; $x < $this->X_SIZE ; $x++) {
				$values[] =  "(".$this->getHexX($x, $y).",".$y.",FALSE)";
			}
		}
        $sql .= implode( ',', $values );
        $this->DbQuery( $sql );

		
        $this->reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        $this->reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        //$this->setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //$this->initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //$this->initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here
       

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = $this->getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score, x_meeple x, y_meeple y, food, wood, iron, stone FROM player "; // TODO WARNING ! BIG CRASH if we try to retrieve directly player_no... 
        $result['players'] = $this->getCollectionFromDb( $sql );
		
		$result['hexfieldtaken'] =  $this->getObjectListFromDB("SELECT * FROM hexfieldtaken");
	
        // TODO: Gather all information about current game situation (visible by player $current_player_id).
  
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */
	
	
	
	
	/*
	Returns distance without taking account for the terrain types
	*/
	function distanceNoTypeSpaces($p_x1, $p_y1, $p_x2, $p_y2) {
		return 1;
	}
	
	/*
	
	*/
	function getCoorsFromPlayerMeeple($player_id) {
		return $this->getObjectListFromDB("SELECT x_meeple x, y_meeple y from player WHERE player_id='$player_id'")[0];
	}
	
	/*
		Makes conversions 
	*/
	function getHexX($p_x, $p_y) {
		return 2*$p_x + ($p_y%2);
	}
	
	function getOriginalX($p_x) {
		return intdiv($p_x,2);
	}
	
	function getLandIDFromSpace($p_x, $p_y) {
		return $this->hexMap[$p_y][$this->getOriginalX($p_x)];
	}
	
	function getResourceIndexFromSpace($p_x, $p_y) {
		return $this->getLandIDFromSpace($p_x, $p_y);
	}
	
	function getMoveCost($p_x, $p_y, $p_playerId) {
		return 1;
	}
	
	function getPossibleNeighborsCoors($p_x, $p_y) {
		$answer = array();
		if ($p_x > 0) {
			if ($p_y > 0) {
				$answer[] = array('x'=> $p_x-1, 'y'=> $p_y-1);
			}			
			if ($p_y < 16) { // TODO fixer un max
				$answer[] = array('x'=> $p_x-1, 'y'=> $p_y+1);
			}
			if ($p_x > 1) {
				$answer[] = array('x'=> $p_x-2, 'y'=> $p_y+0);
			}
		}
		if ($p_x < 16) {
			if ($p_y > 0) {
				$answer[] = array('x'=> $p_x+1, 'y'=> $p_y-1);
			}			
			if ($p_y < 16) { // TODO fixer un max
				$answer[] = array('x'=> $p_x+1, 'y'=> $p_y+1);
			}
			if ($p_x < 15) {
				$answer[] = array('x'=> $p_x+2, 'y'=> $p_y+0);
			}
		}
		return $answer;
	}
	
	function addToChecker($p_array, $p_x, $p_y) {
		if (!isset($p_array[$p_y])) {
			$p_array[$p_y] = array();
		}
		$p_array[$p_y][$p_x] = true;
	}
	
	function getSpacesReachableInOne($p_playerId) {
		$coors = $this->getCoorsFromPlayerMeeple($p_playerId);
		// Plus qu'a tester la vitesse sur la plaine (sera fait plus tard)
		$movePointsLeft = self::getGameStateValue('moves_points_left');
		$coorsToTest = $this->getPossibleNeighborsCoors($coors['x'], $coors['y']);
		$answer = array();
		$answerList = array();
		// 1st space : all land types
		for ($i = 0 ; $i < count($coorsToTest) ; $i++) {
			$coors = $coorsToTest[$i];
			$xx = $coors['x'];
			$yy = $coors['y'];
			$spaceType = $this->getLandIDFromSpace($xx, $yy);
			if ($spaceType == $this->ID_FIELD) {
				$costForThatSpace = 1;
			} else {
				$costForThatSpace = $this->getMoveCostForAdjacentNonField($spaceType);
			}
			if ($movePointsLeft >= $costForThatSpace) {
				$answerList[] = array('x' => $xx, 'y' => $yy);
				if (!isset($answer[$yy])) {
					$answer[$yy] = array();
				}
				$answer[$yy][$xx] = true;
			}
		}
		// 2nd and next spaces : only plains
		$firstIndexToTest = 0;
		for ($dist = 1 ; $dist < 2 ; $dist++) {
			$lastIndexToTest = count($answerList);
			for ($i = $firstIndexToTest ; $i < $lastIndexToTest ; $i++) {
				$coors = $answerList[$i];
				$x = $coors['x'];
				$y = $coors['y'];
				if ($this->getLandIDFromSpace($x, $y) == $this->ID_FIELD) {					
					$neighborCoors = $this->getPossibleNeighborsCoors($x, $y);
					foreach ($neighborCoors as $coors2) {
						$xx = $coors2['x'];
						$yy = $coors2['y'];
						if (($this->getLandIDFromSpace($xx, $yy) == $this->ID_FIELD) and !isset($answer[$yy][$xx])) {
							if (!isset($answer[$yy])) {
								$answer[$yy] = array();
							}
							$answer[$yy][$xx] = true;
							$answerList[] = array('x' => $xx, 'y' => $yy);
						}
					}
				}
			}
		}
		
		
		return $answer;
	}
	
	function getMovePointsBeforeMoving($p_playerId, $p_actionId) {
		return 4;
	}
	
	function getMoveCostForAdjacentNonField($p_spaceType) {
		if ($p_spaceType == $this->ID_IRON || $p_spaceType == $this->ID_STONE) {
			return 2;
		} else {
			return 1;
		}
	}


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in hexfield.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        $this->checkAction( 'playCard' ); 
        
        $player_id = $this->getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        $this->notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => $this->getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */
	function clickMove() {
		self::checkAction( 'decideEffect_act' );
		$movesPoints = $this->getMovePointsBeforeMoving(0, 0); // TODO yeah, set this // Maybe this should be moved to its own function, reserving click to clicks, maybe not.
		self::setGameStateValue('moves_points_left', $movesPoints);
		$this->gamestate->nextState('beforeMoveMeeple_tra');
	}
	
	function clickFinish() {
		self::checkAction( 'decideEffect_act' );
		$this->gamestate->nextState('finish_tra');		
	}
	
	function cancel() {
		self::checkAction( 'cancel_act' );
		$this->gamestate->nextState('cancel_tra');		
	}
	
	function clickBuildHere() {
		self::checkAction( 'decideBuildHere_act' );
		$this->gamestate->nextState('buildHere_tra');		
	}
	
	function moveMeeple( $x, $y ) // TODO change into p_x, p_y
    {
        // Check that this player is active and that this action is possible at this moment
        self::checkAction( 'moveMeeple_act' );
		$player_id = self::getActivePlayerId();
		$coorsM = $this->getCoorsFromPlayerMeeple($player_id);
		$reachableSpaces = $this->getSpacesReachableInOne($player_id);
		
		if (!isset($reachableSpaces[$y][$x])) { // Note : this test made me realize a tree of y followed by x was better than a plain list of x,y
			throw new BgaVisibleSystemException(self::_("Please click on a space accessible in one step"));
		}
		
        $this->DbQuery("UPDATE player SET x_meeple='$x', y_meeple='$y' WHERE player_id='$player_id'");
		$movePointsCost = $this->getMoveCostForAdjacentNonField($this->getLandIDFromSpace($x, $y));
		
		
		// Notify
		self::notifyAllPlayers( "moveMeeple", clienttranslate( '${player_name} moves to ${x},${y} (player ${playerNum})' ), array(
			'playerNum' => $this->getPlayerNumber($player_id)-1,
			'playerId' => $player_id, 
			'player_name' => self::getActivePlayerName(),
			'x' => $x,
			'y' => $y
		) );
		
		$isTaken = self::getUniqueValueFromDb("SELECT taken FROM hexfieldtaken WHERE x='$x' and y='$y'");
		if (!$isTaken) {			
			self::extractResource($x, $y);
		}
		
		// Handle remaining moves
		self::setGameStateValue('moves_points_left', self::getGameStateValue('moves_points_left')-$movePointsCost);
		if (self::getGameStateValue('moves_points_left') == 0) {			
			$this->gamestate->nextState('done_tra');
		} else {
			$this->gamestate->nextState('beforeMoveMeeple_tra');
		}

	}
	
	function buildHere($p_buildingId) {
		$costArray = array("wood" => 0, "stone" => 0, "iron" => 0);
		if ($p_buildingId == 0) {
			$costArray["wood"] = 1;
			$costArray["stone"] = 1;
			$costArray["iron"] = 1;
		}
		if ($p_buildingId == 1) {
			$costArray["wood"] = 2;
		}
		
		$x = 0; // Todo, obviously :)
		$y = 0;
		
		$buildingName = ["house", "farm"][$p_buildingId];
		
		// Check the player's resources
		$player_id = self::getActivePlayerId();
		$player_resources = self::getObjectListFromDB("SELECT wood, stone, iron FROM player WHERE player_id='$player_id'")[0];
		
		if ($player_resources["wood"] < $costArray["wood"] or $player_resources["iron"] < $costArray["iron"] or $player_resources["stone"] < $costArray["stone"]) {
			throw new BgaVisibleSystemException(self::_("You don't have the required resources for this building !"));
		}
		$newWood = $player_resources["wood"]-$costArray["wood"];
		$newStone = $player_resources["wood"]-$costArray["wood"];
		$newIron = $player_resources["wood"]-$costArray["wood"];
		$this->DbQuery("UPDATE player SET wood=$newWood, stone=$newStone, iron=$newIron WHERE player_id='$player_id'");
		
		self::notifyAllPlayers( "build", clienttranslate( '${player_name} builds ${buildingName} to ${x},${y}' ), array(
			'playerId' => $player_id,
			'player_name' => self::getActivePlayerName(),
			'newStone' => $newStone,
			'newIron' => $newIron,
			'newWood' => $newWood,
			'buildingName' => $buildingName,
			'x' => $x,
			'y' => $y
		) ); 
		$this->gamestate->nextState('moveMeeple_tra');
	}
	
	
	// Still to be tested along with a way to test both moving and extracting
	function extractResource( $x, $y )
    {
        // Check that this player is active and that this action is possible at this moment
		$player_id = self::getActivePlayerId();

		$resourceId = $this->getResourceIndexFromSpace($x, $y);
		if ($this->getResourceIndexFromSpace($x, $y) < 1) {
			return;
		}
		
		$resourceTextArray = array('','food', 'wood','stone','iron'); // Note : linked to the index defined in material.inc.php (they start with 1 in here !)
		$resourceText = $resourceTextArray[$resourceId];
		$resourceName = $resourceTextArray[$resourceId];
		$resourceAmount = self::getUniqueValueFromDB("SELECT ".$resourceText." FROM player WHERE player_id=".$player_id)+1;
		// Notify
		self::notifyAllPlayers( "extractResource", clienttranslate( '${player_name} extracts ${resourceText} to ${x},${y}' ), array(
			'playerId' => $player_id,
			'playerNum' => $this->getPlayerNumber($player_id),
			'player_name' => self::getActivePlayerName(),
			'resourceText' => $resourceText,
			'resourceName' => $resourceName,
			'resourceId' => $resourceId,
			'resourceAmount' => $resourceAmount,
			'x' => $x,
			'y' => $y
		) ); 
		$this->DbQuery("UPDATE hexfieldtaken SET taken = TRUE WHERE x='$x' and y='$y'");
		$this->DbQuery("UPDATE player SET ".$resourceName." = ".$resourceAmount." WHERE player_id='$player_id'");
	}
	
	function getPlayerNumber($p_playerId) {
		return self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id=".$p_playerId);
	}
    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */
	
	function argMoveMeeple() {
        // Send the translatable name of ??? 
        return [
            "i18n" => ['movePointsLeft'],
            "movePointsLeft" => self::getGameStateValue('moves_points_left'),
			"possibleMovesInOne" => $this->getSpacesReachableInOne(self::getActivePlayerId())
        ];
    }
	
	function argBuildHere() {
		$coors = $this->getCoorsFromPlayerMeeple(self::getActivePlayerId());
        return [
            "i18n" => ['argBuildOnSpace'],
            "x" => $coors['x'],
			"y" => $coors['y'],
			"available_buildings" => [$this->ID_FARM, $this->ID_HOUSE]
        ];
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
	}    
    */
	function stPlayerEndTurn()
    {
        // Active next player
        $player_id = self::activeNextPlayer();
		// TODO : add some progression
		$this->gamestate->nextState('nextTurn_tra');
	}
	
	function stBeforeMoveMeeple() {
		//$movesPoints = $this->getMovePointsBeforeMoving(0, 0); // TODO yeah, set this
		$this->gamestate->nextState('moveMeeple_tra');		
	}
//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
