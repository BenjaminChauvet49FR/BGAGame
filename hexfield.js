/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * HexField implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * hexfield.js
 *
 * HexField user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.hexfield", ebg.core.gamegui, {
        constructor: function(){
            console.log('hexfield constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
			var i = 0;
            this.frCounter = 0;
			console.log(gamedatas);
			
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
				var player = gamedatas.players[player_id];
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_board', player ), player_board_div );                   
				pixMeeple = this.getPixPositionsMeeple(player.x, player.y);
				dojo.place(this.format_block('jstpl_moving_meeple', {
					xpc: i,
					player_id: i,//(player.num-1), // TODO see if we can change it directly on server. Also, see WARNING BIG CRASH !
					pixOffsetX : pixMeeple.x,
					pixOffsetY : pixMeeple.y
				}), 'game_board');
				
				this.updateResourceValue(player_id, this.ID_FOOD, player.food); // TODO warning, ressource IPS in hard
				this.updateResourceValue(player_id, this.ID_WOOD, player.wood);
				this.updateResourceValue(player_id, this.ID_STONE, player.stone);
				this.updateResourceValue(player_id, this.ID_IRON, player.iron);
				
				i++;
            }

			var x, y;
			for (var k = 0 ; k < gamedatas.hexfieldtaken.length ; k++) {
				if (gamedatas.hexfieldtaken[k].taken != '0') {
					x = gamedatas.hexfieldtaken[k].x;
					y = gamedatas.hexfieldtaken[k].y;
					pixTaken = this.getPixPositionsExtractedRsrc(x, y);
					dojo.place(this.format_block('jstpl_taken_marker', {
						pixOffsetX : pixTaken.x,
						pixOffsetY : pixTaken.y
					}), 'game_board');
				}
			}
			
			
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();
			dojo.query( '#game_board' ).connect( 'onclick', this, 'clickGameBoard' );

            console.log( "Ending game setup" );
			
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
                case 'moveMeeple':
					this.updateSpacesReachableInOne( args.args.possibleMovesInOne );
                break;
           
				case 'dummmy':
					break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
			case 'moveMeeple':
				this.clearSpacesReachableInOne();
			break;
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
					case 'playerTurnMain':
						this.addActionButton( 'move_button', _('Move'), 'clickMove' ); 
						this.addActionButton( 'finish_button', _('End turn'), 'clickFinish', null, false, 'red' ); 
						//this.addActionButton( 'extract_button', _('Extract ressource'), ()=>this.ajaxcallwrapper('extractRessource') ); 
					break;
					case 'moveMeeple':
						this.addActionButton( 'build_here_button', _('Build here'), 'clickBuildHere' ); 
						this.addActionButton( 'cancel1_button', _('Cancel'), 'clickCancel' ); 
					break;
					case 'buildHere':
						this.addActionButton( 'build_house_here_button', _('House'), () => this.onBuildHere(0) ); 
						this.addActionButton( 'build_farm_here_button', _('Farm'), () => this.onBuildHere(1) ); 
						this.addActionButton( 'cancel2_button', _('Cancel'), 'clickCancel' ); 
					break;
				}
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */
		
		getPixPositionsHex : function(p_xTile, p_yTile) {
			return {
				x : p_xTile * 42,
				y : p_yTile * 63
			}
		},
		
		getPixPositionsMeeple : function(p_xTile, p_yTile) {
			return {
				x : p_xTile *42+(84-40)/2,
				y : p_yTile * 63
			}
		},
		
		getPixPositionsExtractedRsrc : function(p_xTile, p_yTile) {
			var pix = this.getPixPositionsMeeple(p_xTile, p_yTile);
			return {
				x : pix.x+10,
				y : pix.y+50
			}
		},
		
		ID_FOOD : 1,
		ID_WOOD : 2,
		ID_STONE : 3,
		ID_IRON : 4,
		
		updateResourceValue : function(p_playerId, p_resourceId, p_amount) {
			const textRsrc = ["","food","wood","stone","iron"][p_resourceId];
			console.log(p_playerId + " " + p_resourceId + " " + p_amount);
			document.getElementById(textRsrc+"count_p"+p_playerId).innerHTML = p_amount;
		},
		
		getHexX : function(p_x, p_y) {
			return 2*p_x + (p_y%2);
		},

		getOriginalX : function(p_x) {
			return Math.floor(p_x/2);
		},
		
		updateSpacesReachableInOne : function(p_listOfSpaces) {
            this.addTooltipToClass( 'possibleMove', '', _("Move on this space (551551)") );
			for( var y in p_listOfSpaces) {
				for( var x in p_listOfSpaces[y]) {
					pix = this.getPixPositionsHex(x, y);
					dojo.place( this.format_block( 'possible_move', {
						pixX : pix.x,
						pixY : pix.y
					} ) , 'possible_move_place' );
				}
			}
		},
		
		clearSpacesReachableInOne : function() {
			document.getElementById('possible_move_place').innerHTML = "";
		},
		
		
		
        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/hexfield/hexfield/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */
		clickMove: function(evt) {
			if( this.checkAction( 'decideEffect_act' ) ) {				
				this.ajaxcall( "/hexfield/hexfield/clickMove.html", {}, this, function( result ) {})
			}
		},
		clickFinish: function(evt) {
			if( this.checkAction( 'decideEffect_act' ) ) {				
				this.ajaxcall( "/hexfield/hexfield/clickFinish.html", {}, this, function( result ) {})
			}
		},
		clickCancel: function(evt) {
			if( this.checkAction( 'cancel_act') ) {				
				this.ajaxcall( "/hexfield/hexfield/clickCancel.html", {}, this, function( result ) {})
			}
		},
		clickBuildHere: function(evt) {
			if( this.checkAction( 'decideBuildHere_act') ) {				
				this.ajaxcall( "/hexfield/hexfield/clickBuildHere.html", {}, this, function( result ) {})
			}
		},
		
		onBuildHere : function(p_buildingID) {
			this.ajaxcall("/" + this.game_name + "/" +  this.game_name + "/buildHere.html", 
				{lock: true, buildingID: p_buildingID}, this, function (result) {}, function (is_error) {});
		},
		
		clickGameBoard: function(evt) { 
			var pixHSS = 84; // pixHSS = pix "hex square side"
			var docPage = document.body.getBoundingClientRect();
			var pixX = evt.offsetX + evt.target.offsetLeft;
			var pixY = evt.offsetY + evt.target.offsetTop;
			var sliceY = Math.floor(pixY*4/pixHSS); 
			var sliceX = Math.floor(pixX*2/pixHSS);
			if (sliceY % 3 == 0) {
				pixXInSlice = pixX-sliceX*pixHSS/2;
				pixAntiXInSlice = (1+sliceX)*pixHSS/2-pixX;
				pixDoubleYInSlice = pixY-sliceY*pixHSS/2; // Instead of /4, because the slope in y/x is 1/2
				sliceY6 = (sliceY % 6 == 0); // Slices for the upper part of rows 0, 2, 4... starting leftmost
				sliceX2 = (sliceX % 2 == 0); // Same horizontally
				if (sliceY6 == sliceX2) {
					if (pixAntiXInSlice > pixDoubleYInSlice) { // Any / line
						sliceY--; // Treat it as if it was in the above slice
					} else { // Below the / line
						sliceY++; // Treat it as if it was below
					}								
				} else { 
					if (pixXInSlice > pixDoubleYInSlice) { // Any \ line
						sliceY--; // Treat it as if it was in the above slice
					} else { // Below the \ line
						sliceY++; // Treat it as if it was below
					}													
				}
			}
			sliceYMod6 = sliceY % 6;
			if (sliceYMod6 == 1 || sliceYMod6 == 2) {
				// Even row
				hexY = Math.floor(sliceY/3);
				hexX = Math.floor(sliceX/2);
			} else {
				// Odd row
				hexY = Math.floor(sliceY/3);
				hexX = Math.floor((sliceX-1)/2);
			}
			
			
			console.log(evt);
			console.log(pixX + " " + pixY);
			console.log(hexX + " " + hexY);
            dojo.stopEvent( evt );
			
			var x = this.getHexX(hexX, hexY);
			var y = hexY;
			
			if (this.checkAction( 'moveMeeple_act', false ) )    // Check that this action is possible at this moment
			{            
				this.ajaxcall( "/hexfield/hexfield/moveMeeple.html", {
					x:x,
					y:y				
				}, this, function( result ) {} );
			} /* else if (this.checkAction( 'extract_act', false) )    // Check that this action is possible at this moment
			{            
				this.ajaxcall( "/hexfield/hexfield/extractResource.html", {
					x:x,
					y:y
				}, this, function( result ) {} );
			} */ else {
				this.checkAction('dummy_state'); // checkAction are useful and we can check them, but sometimes you don't want the message by default and sometimes you want it
			}
		},
        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your hexfield.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
			dojo.subscribe( 'moveMeeple', this, "notif_moveMeeple" );
            this.notifqueue.setSynchronous( 'moveMeeple', 500 );
			dojo.subscribe( 'extractResource', this, "notif_extractResource" );
            this.notifqueue.setSynchronous( 'extractResource', 500 );
			dojo.subscribe( 'build', this, "notif_build" );
            this.notifqueue.setSynchronous( 'build', 500 );
			
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
		
		notif_moveMeeple: function( notif )
        {
			this.clearSpacesReachableInOne();
			var pix = this.getPixPositionsMeeple(notif.args.x, notif.args.y);
			console.log(notif.args);
			this.slideToObjectPos( 'moving_meeple_'+notif.args.playerNum, 'game_board', pix.x, pix.y, 400 ).play();		
			this.gamedatas.gamestate.description = "${actplayer} must move their meeple on a space (${movePointsLeft} move point(s) left)"		
			this.gamedatas.gamestate.descriptionmyturn = "${you} must move your meeple on a space (${movePointsLeft} move point(s) left)"		
        },
		notif_extractResource: function( notif )
        {
			var pix = this.getPixPositionsMeeple(notif.args.x, notif.args.y);
			dojo.place( this.format_block( 'flying_resource', {
                jsId: this.frCounter,
                type: notif.args.resourceText,
				pixX : pix.x,
				pixY : pix.y
            } ) , 'flying_resource_place' );
			this.slideToObject( "flying_resource_" + this.frCounter, 'overall_player_board_'+notif.args.playerId ).play();
			document.getElementById("flying_resource_" + this.frCounter);			
			this.frCounter++;
			if (this.frCounter == 64) {
				this.frCounter = 0;
			}
			
			pix = this.getPixPositionsExtractedRsrc(notif.args.x, notif.args.y);
			dojo.place( this.format_block( 'taken_marker', {
                type: notif.args.resourceText,
				pixX : pix.x,
				pixY : pix.y
            } ) , 'taken_marker_place' );
			
			this.updateResourceValue(notif.args.playerId, notif.args.resourceId, notif.args.resourceAmount);
        },
		notif_build : function(notif) {
			this.updateResourceValue(notif.args.playerId, this.ID_WOOD, notif.args.newWood);
			this.updateResourceValue(notif.args.playerId, this.ID_STONE, notif.args.newStone);
			this.updateResourceValue(notif.args.playerId, this.ID_IRON, notif.args.newIron);
		}
   });             
});
