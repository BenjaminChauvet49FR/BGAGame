{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- HexField implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    hexfield_hexfield.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div id="game_board">
	<!-- BEGIN hex -->
	<div class="hextile hextile_{TYPE}" id="hextile_{X}_{Y}" style="left: {LEFT}px; top: {TOP}px;">
	</div>
	<!-- END hex -->
</div>

<script type="text/javascript">

// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

var jstpl_moving_meeple = '<div class="meeple" id="moving_meeple_${player_id}" style="background-position:-${xpc}00% 0%; left: ${pixOffsetX}px; top: ${pixOffsetY}px;"></div>';
var jstpl_taken_marker = '<div class="marker" style="left: ${pixOffsetX}px; top: ${pixOffsetY}px;"></div>';
var jstpl_flying_resource='<div class="flying_resource flying_resource_${type}" id="flying_resource_${jsId}" style="left: ${pixX}px; top: ${pixY}px;"></div>';
var jstpl_possible_move='<div class="possible_move" style="left: ${pixX}px; top: ${pixY}px;"></div>';
var jstpl_building='<div class="building building_${kind}" style="left: ${pixX}px; top: ${pixY}px;"></div>';

var jstpl_player_board = '\<div class="cp_board">\
    <div id="rsrc_food_icon_p${id}" class="rsrc_icon rsrc_food_icon"></div><span id="foodcount_p${id}">0</span>\
    <div id="rsrc_wood_icon_p${id}" class="rsrc_icon rsrc_wood_icon"></div><span id="woodcount_p${id}">0</span>\
    <div id="rsrc_stone_icon_p${id}" class="rsrc_icon rsrc_stone_icon"></div><span id="stonecount_p${id}">0</span>\
    <div id="rsrc_iron_icon_p${id}" class="rsrc_icon rsrc_iron_icon"></div><span id="ironcount_p${id}">0</span>\
</div>';



</script>  

{OVERALL_GAME_FOOTER}
