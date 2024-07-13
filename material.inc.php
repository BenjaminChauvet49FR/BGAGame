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
 * material.inc.php
 *
 * HexField game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);

*/

/*$P = 0;
$L = 1;
$F = 2;
$S = 3;
$I = 4;
$X = 5;*/

$this->ID_HOUSE = 0;
$this->ID_FARM = 1;

$this->ID_FIELD = 0;
$this->ID_LAKE = 1;
$this->ID_FOREST = 2;
$this->ID_STONE = 3;
$this->ID_IRON = 4;

$this->hexMap = array(
			array(0, 0, 2, 2, 2, 2, 1, 0, 0, 2, 4, 2, 3, 3, 2, 2),
			  array(0, 2, 3, 3, 1, 1, 0, 0, 2, 2, 4, 3, 1, 2, 2, 0),
			array(0, 2, 3, 3, 1, 1, 0, 0, 2, 2, 4, 3, 1, 1, 2, 0),
			  array(0, 2, 3, 4, 1, 0, 0, 0, 2, 4, 4, 3, 1, 1, 0, 0),
			array(0, 0, 2, 4, 1, 1, 0, 0, 0, 2, 2, 0, 0, 0, 4, 0),
			  array(0, 0, 2, 2, 2, 1, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			  );	
		
$this->Y_SIZE = 16;
$this->X_SIZE = 16;
