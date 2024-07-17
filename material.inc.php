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

$this->ID_CULTURE = 0;
$this->ID_MILITARY = 1;
$this->ID_ECONOMY = 2;
$this->ID_SCIENCE = 3;


$this->ID_FIELD = 0;
$this->ID_LAKE = 1;
$this->ID_FOREST = 2;
$this->ID_STONE = 3;
$this->ID_IRON = 4;
$this->buildings = array(
	"HOUSE" => array(
		"id" => 0,
		"cost" => "*",
		"vp" => 1,
		"culture" => 1,
		"description" => "Gives you 1 citizen (laid down). Don't forget, you will have to feed them in feeding phase."),
	"FARM" => array(
		"id" => 1,
		"cost" => "WW",
		"vp" => 1, 
		"culture" => 1,
		"description" => "Automatically produces 1 food per round."),
		),
	"BARRACKS" => array(
		"id" => 2,
		"cost" => "FWS",
		"vp" => 1,
		"culture" => 1,
		"bonus" => $this->$ID_MILITARY,
		"description" => "A starting point for soldiers ; also gives you 1 honor per turn and an automatic patrol (level 1 fixed)."),
	),
	"BANK" => array(
		"id" => 3,
		"cost" => "*", // IS(I/S)
		"vp" => 0, // 4 or 2
		"culture" => 0, // 2 or 1
		"bonus" => $this->$ID_ECONOMY,
		"description" => "At end of round, produces $ equal to the number of adjacent residential buildings, no matter their owner. If there are 3 or more of them, its culture value and VPs are doubled."),
	),
	"CENTER" => array(
		"id" => 4,
		"cost" => "IIIF",
		"vp" => 3,
		"culture" => 2,
		"bonus" => $this->$ID_CULTURE,
		"description" => "Gives you 2 citizens (laid down). Don't forget, you will have to feed them."),
	),
	"LAB" => array(
		"id" => 5,
		"cost" => "WSI",
		"vp" => 1,
		"culture" => 1,
		"bonus" => $this->$ID_SCIENCE,
		"description" => "Lets you grab a basic booster tile of your choice."),
	),
	"CHURCH" => array(
		"id" => 6,
		"cost" => "SSWW",
		"vp" => 2,
		"culture" => 2,
		"bonus" => $this->$ID_CULTURE,
		"description" => "Lets you perform a 'recruit citizen' effect with a reduction of $1 per culture boost. When the game ends, removes up to 1 of your Penalties."),
	),
);

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
