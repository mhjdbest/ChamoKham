var UniteLayersRev = new function(){
	
	var initTop = 100;
	var initLeft = 100;
	var initSpeed = 300;
	
	var initTopVideo = 20;
	var initLeftVideo = 20;
	var g_startTime = 500;
	var g_stepTime = 300;
	
	var initText = "Caption Text";
	
	//init system vars
	var t = this;
	var containerID = "#divLayers";
	var container;	
	var arrLayers = {};
	var id_counter = 0;
	var initLayers = null;
	var selectedLayerSerial = -1;
	var urlCssCaptions = null;
	var initArrCaptionClasses = [];
	var sortMode = "time";				//can be "depth" or "time"
	
	/**
	 * set init layers object (from db)
	 */
	t.setInitLayersJson = function(jsonLayers){
		initLayers = jQuery.parseJSON(jsonLayers);
	}
	
	
	/**
	 * set init captions classes array (from the captions.css)
	 */
	t.setInitCaptionClasses = function(jsonClasses){
		initArrCaptionClasses = jQuery.parseJSON(jsonClasses);
	}
	
	/**
	 * set captions url for refreshing when needed
	 */
	t.setCssCaptionsUrl = function(url){
		urlCssCaptions = url;
	}
		
	
	/**
	 * close css dialog
	 */
	t.closeCssDialog = function(){
		jQuery("#dialog_edit_css").dialog("close");
	}

	
	/**
	 * insert button by class and text
	 */
	t.insertButton = function(buttonClass,buttonText){
		var html = "<a href='javascript:alert(\"click\");' class='tp-button "+buttonClass+" small'>"+buttonText+"</a>";
		var objUpdate = {};
		objUpdate.style = "";
		objUpdate.text = html;
		
		updateCurrentLayer(objUpdate);
		updateLayerFormFields(selectedLayerSerial);
		redrawLayerHtml(selectedLayerSerial);
		
		jQuery("#dialog_insert_button").dialog("close");
	}
	
	
//======================================================
//	Init Functions
//======================================================
	
	/**
	 * init the layout
	 */
	t.init = function(){
		
		container = jQuery(containerID);
		
		//add all layers from init
		if(initLayers){
			for(key in initLayers)
				addLayer(initLayers[key]);
		}
		
		//disable the properties box
		disableFormFields();
		
		//init elements
		initMainEvents();
		initSortbox();
		initButtons();
		initEditCSSDialog();
		initHtmlFields();
	}
	
	
	/**
	 * init general events
	 */
	var initMainEvents = function(){
		
		//unselect layers on container click
		container.click(unselectLayers);
	}
	
	
	/**
	 * init events (update) for html properties change.
	 */
	var initHtmlFields = function(){
		
		//set layers autocompolete
		jQuery( "#layer_caption" ).autocomplete({
			source: initArrCaptionClasses,
			minLength:0,
			close:updateLayerFromFields
		});
		
		
		//open the list on right button
		jQuery( "#layer_captions_down" ).click(function(){
			if(jQuery(this).hasClass("ui-state-active"))
				jQuery( "#layer_caption" ).autocomplete( "search", "" );
		});
		
		//set events:
		jQuery("#layer_animation, #layer_easing").change(updateLayerFromFields);
		jQuery("#layer_text").keyup(updateLayerFromFields);
		var pressEnterFields = "#layer_left, #layer_top, #layer_caption, #layer_easing, #layer_speed, #layer_image_link";
		jQuery(pressEnterFields).blur(updateLayerFromFields);
		jQuery(pressEnterFields).keypress(function(event){
			if(event.keyCode == 13)
				updateLayerFromFields();
		});
		
	}
		
	
	/**
	 * init buttons actions
	 */
	var initButtons = function(){
		
		//set event buttons actions:
		jQuery("#button_add_layer").click(function(){
			addLayerText();
		});
		
		jQuery("#button_add_layer_image").click(function(){
			UniteAdminRev.openAddImageDialog("Select Layer Image",function(urlImage){
				addLayerImage(urlImage);
			});
		});
		
		//add youtube actions:
		jQuery("#button_add_layer_video").click(function(){
			UniteAdminRev.openVideoDialog(function(videoData){
				addLayerVideo(videoData);
			});
		});
		
		//edit video actions
		jQuery("#button_edit_video").click(function(){
			var objCurrentLayer = getCurrentLayer();
			//prepare video object
			var objVideo = { video_type:objCurrentLayer.video_type,
							 title:objCurrentLayer.video_title,
							 width:objCurrentLayer.video_width,
							 height:objCurrentLayer.video_height,
							 id:objCurrentLayer.video_id
							};
			
			//open video dialog
			UniteAdminRev.openVideoDialog(function(videoData){
				//update video layer
				var objLayer = getVideoObjLayer(videoData);
				updateCurrentLayer(objLayer);
				updateLayerFormFields(selectedLayerSerial);
				redrawLayerHtml(selectedLayerSerial);
			},
			objVideo);
			
		});
		
		
		//delete layer actions:
		jQuery("#button_delete_layer").click(function(){
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			//delete selected layer
			deleteCurrentLayer();
		});

		//delete layer actions:
		jQuery("#button_duplicate_layer").click(function(){
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			//delete selected layer
			duplicateCurrentLayer();
		});
		
		//delete all layers actions:
		jQuery("#button_delete_all").click(function(){
			if(confirm("Do you really want to delete all the layers?") == false)
				return(true);
			
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			deleteAllLayers();
		});
		
		//insert button link - open the dialog
		jQuery("#linkInsertButton").click(function(){			
			if(jQuery(this).hasClass("disabled"))
				return(false);
			
			var buttons = {"Cancel":function(){jQuery("#dialog_insert_button").dialog("close")}}
			jQuery("#dialog_insert_button").dialog({buttons:buttons,minWidth:500,modal:true});
			
		});
		
	}	
	
	
//======================================================
//		Init Function End
//======================================================

	
	/**
	 * clear layer html fields, and disable buttons
	 */
	var disableFormFields = function(){
		//clear html form
		jQuery("#form_layers")[0].reset();
		jQuery("#form_layers input, #form_layers select").attr("disabled", "disabled").addClass("setting-disabled");
		
		jQuery("#button_delete_layer").addClass("button-disabled");
		jQuery("#button_duplicate_layer").addClass("button-disabled");
		
		jQuery("#form_layers .setting_text").addClass("text-disabled");
		
		jQuery("#layer_captions_down").removeClass("ui-state-active").addClass("ui-state-default");
		
		jQuery("#linkInsertButton").addClass("disabled");
	}
	
	/**
	 * enable buttons and form fields.
	 */
	var enableFormFields = function(){
		jQuery("#form_layers input, #form_layers select").removeAttr("disabled").removeClass("setting-disabled");
		
		jQuery("#button_delete_layer").removeClass("button-disabled");
		jQuery("#button_duplicate_layer").removeClass("button-disabled");
		
		jQuery("#form_layers .setting_text").removeClass("text-disabled");
		
		jQuery("#layer_captions_down").removeClass("ui-state-default").addClass("ui-state-active");
		
		jQuery("#linkInsertButton").removeClass("disabled");
	}
	
	
	/**
	 * init dialog actions
	 */
	var initEditCSSDialog = function(){
		jQuery("#button_edit_css").click(function(){
			
			UniteAdminRev.ajaxRequest("get_captions_css","",function(response){				
				//update textarea with css:
				var cssData = response.data;
				jQuery("#textarea_edit").val(cssData);
				
				//open captions edit dialog	
				var buttons = {	
						
				//---- update button action:
						
				"Update":function(){
						UniteAdminRev.setErrorMessageID("dialog_error_message");
						var data = jQuery("#textarea_edit").val();
						UniteAdminRev.ajaxRequest("update_captions_css",data,function(response){
							jQuery("#dialog_success_message").show().html(response.message);
							setTimeout("UniteLayersRev.closeCssDialog()",500);
							
							if(urlCssCaptions)
								UniteAdminRev.loadCssFile(urlCssCaptions,"rs-plugin-captions-css");
							
							//update html select (got as "data" from response)
							updateCaptionsInput(response.arrCaptions);	
						});
				},
				
				//---- restore original button action:
				
				"Restore Original":function(){
					UniteAdminRev.setErrorMessageID("dialog_error_message");
					UniteAdminRev.ajaxRequest("restore_captions_css","",function(response){						
						jQuery("#dialog_success_message").show().html("css content restored");
						jQuery("#textarea_edit").val(response.data);
						setTimeout("jQuery('#dialog_success_message').hide()",500);
					});					
				},
						
						//----- cancel button action:
				"Cancel":function(){t.closeCssDialog()}
				};
				
				//hide dialog error message
				jQuery("#dialog_error_message").hide();
				jQuery("#dialog_success_message").hide();
				
				//open the dialog
				jQuery("#dialog_edit_css").dialog({buttons:buttons,minWidth:800,modal:true});
				
			});	//main ajax request
			
		});	//edit css button click	
	}
	
	
	/**
	 * update z-index of the layers by order value
	 */
	var updateZIndexByOrder = function(){
		for(var key in arrLayers){
			var layer = arrLayers[key];
			if(layer.order !== undefined){
				var zindex = layer.order+1;
				jQuery("#slide_layer_"+key).css("z-index",zindex);
			}
		};		
	}
	
	
	/**
	 * update the select html, set selected option, and update events.
	 */
	var updateCaptionsInput = function(arrCaptions){
		
		jQuery("#layer_caption").autocomplete("option","source",arrCaptions);
		
	}
	
	
	/**
	 * get layers array
	 */
	t.getLayers = function(){
		if(selectedLayerSerial != -1)
			updateLayerFromFields();
		
		return(arrLayers);
	}
	
	
	/**
	 * refresh layer events
	 */
	var refreshEvents = function(serial){
		
		//update layer events.
		var layer = getHtmlLayerFromSerial(serial);		
		layer.draggable({
					drag: onLayerDrag,	//set ondrag event
					grid: [1,1]	//set the grid to 1 pixel
				});

		
		layer.click(function(event){
			setLayerSelected(serial);
			event.stopPropagation();
		});
				
	}

	
	/**
	 * get layer serial from id
	 */
	var getSerialFromID = function(layerID){
		var layerSerial = layerID.replace("slide_layer_","");
		return(layerSerial);
	}
	
	/**
	 * get serial from sortID
	 */
	var getSerialFromSortID = function(sortID){
		var layerSerial = sortID.replace("layer_sort_","");
		return(layerSerial);
	}
	
	/**
	 * get html layer from serial
	 */
	var getHtmlLayerFromSerial = function(serial){
		var htmlLayer = jQuery("#slide_layer_"+serial);
		if(htmlLayer.length == 0)
			UniteAdminRev.showErrorMessage("Html Layer with serial: "+serial+" not found!");
		
		return(htmlLayer);
	}
	
	/**
	 * get sort field element from serial
	 */
	var getHtmlSortItemFromSerial = function(serial){
		var htmlSortItem = jQuery("#layer_sort_"+serial);
		if(htmlSortItem.length == 0){
			UniteAdminRev.showErrorMessage("Html sort field with serial: "+serial+" not found!");
			return(false);
		}
		
		return(htmlSortItem);
	}
	
	/**
	 * get layer object by id
	 */
	var getLayer = function(serial){
		var layer = arrLayers[serial];
		if(!layer)
			UniteAdminRev.showErrorMessage("getLayer error, Layer with serial:"+serial+"not found");
		
		return layer;
	}
	
	/**
	 * get current layer object
	 */
	var getCurrentLayer = function(){
		if(selectedLayerSerial == -1){
			UniteAdminRev.showErrorMessage("Selected layer not set");
			return(null);
		}
		
		return getLayer(selectedLayerSerial);
	}
	
	
	/**
	 * set layer object to array
	 */
	var setLayer = function(layerID,layer){
		if(!arrLayers[layerID]){
			UniteAdminRev.showErrorMessage("setLayer error, Layer with ID:"+layerID+"not found");
			return(false);
		}
		arrLayers[layerID] = layer;
	}
	
	
	/**
	 * make layer html, with params from the object
	 */
	var makeLayerHtml = function(serial,objLayer){
		var type = "text";
		if(objLayer.type)
			type = objLayer.type;
		
		var style = "left:"+objLayer.left+"px;top:"+objLayer.top+"px;z-index:"+serial;
		var html = '<div id="slide_layer_' + serial + '" style="' + style + '" class="slide_layer caption '+objLayer.style+'" >';
		
		//add layer specific html
		switch(type){
			case "image":
				html += '<img src="'+objLayer.image_url+'" alt="'+objLayer.text+'"></img>';
			break;
			default:
			case "text":
				html += objLayer.text;
			break;
			case "video":
				var styleVideo = "width:"+objLayer.video_width+"px;height:"+objLayer.video_height+"px;";
				styleVideo += ";background-image:url("+objLayer.video_image_url+");";
				
				html += "<div class='slide_layer_video' style='"+styleVideo+"'><div class='video-layer-inner video-icon-"+objLayer.video_type+"'>"
				html += "<div class='layer-video-title'>" + objLayer.video_title + "</div>";
				html += "</div></div>";
			break;
		}
		
		html += '</div>';
		return(html);
	}
	
	
	/**
	 * update layer by data object
	 */
	var updateLayer = function(serial,objData){
		var layer = getLayer(serial);
		if(!layer)
			return(false);
		
		for(key in objData){
			layer[key] = objData[key];
		}
		
		setLayer(serial,layer);
	}
	
	
	/**
	 * update current layer
	 */
	var updateCurrentLayer = function(objData){
		if(!arrLayers[selectedLayerSerial]){
			UniteAdminRev.showErrorMessage("error! the layer with serial: "+selectedLayerSerial+" don't exists");
			return(false);
		}
		
		updateLayer(selectedLayerSerial,objData);
	}
	
	
	/**
	 * add image layer
	 */
	var addLayerImage = function(urlImage){
		
		objLayer = {
			style : "",
			text : "Image " + (id_counter+1),
			type : "image",
			image_url : urlImage
		};
				
		addLayer(objLayer);
	}
	
	
	/**
	 * get video layer object from video data
	 */
	var getVideoObjLayer = function(videoData){
		
		var objLayer = {
				type:"video",
				style : "",
				video_id: videoData.id,
				video_type: videoData.video_type,
				video_title: videoData.title,
				video_image_url: videoData.thumb_medium.url,
				video_width:videoData.width,
				video_height:videoData.height
			};
			
			//set sortbox text
			switch(objLayer.video_type){			
				case "youtube":
					objLayer.text = "Youtube: " + videoData.title;
				break;
				case "vimeo":
					objLayer.text = "Vimeo: " + videoData.title;
				break;
			}
			
		return(objLayer);
	}
	
	
	/**
	 * add video layer
	 */
	var addLayerVideo = function(videoData){
		var objLayer = getVideoObjLayer(videoData);
		addLayer(objLayer);
	}
	
	
	/**
	 * add text layer
	 */
	var addLayerText = function(){
		
		var objLayer = {
				text:initText + (id_counter+1),
				type:"text"
		};
		
		addLayer(objLayer);
	}
	
	
	
	/**
	 * add layer
	 */
	var addLayer = function(objLayer){
		
		//set init fields (if not set):
		if(objLayer.order == undefined)
			objLayer.order = (id_counter);
		
		//set init position
		if(objLayer.type == "video"){
			if(objLayer.left == undefined)			
				objLayer.left = initLeftVideo;
			
			if(objLayer.top == undefined)			
				objLayer.top = initTopVideo;
			
		}else{
			if(objLayer.left == undefined)			
				objLayer.left = initLeft;
			
			if(objLayer.top == undefined)			
				objLayer.top = initTop;	
		}
		
		//set animation:
		if(objLayer.animation == undefined)			
			objLayer.animation = jQuery("#layer_animation").val();

		//set easing:
		if(objLayer.easing == undefined)
			objLayer.easing = jQuery("#layer_easing").val();
		
		//set speed:
		if(objLayer.speed == undefined)			
			objLayer.speed = initSpeed;

		//set image link
		if(objLayer.link == undefined)
			objLayer.link = "";
		
		//set image link open in
		if(objLayer.link_open_in == undefined)
			objLayer.link_open_in = "same";
		
		if(objLayer.style == undefined)			
			objLayer.style = jQuery("#layer_caption").val();
		
		//add time
		if(objLayer.time == undefined)			
			objLayer.time = getNextTime();
		
		objLayer.time = Number(objLayer.time);	//casting
		
		//round position
		objLayer.top = Math.round(objLayer.top);
		objLayer.left = Math.round(objLayer.left);
		
		arrLayers[id_counter] = objLayer;
		
		//add html
		var htmlLayer = makeLayerHtml(id_counter,objLayer);
		container.append(htmlLayer);
		
		//add layer to sortbox
		addToSortbox(id_counter,objLayer);
		
		//refresh draggable
		refreshEvents(id_counter);
		id_counter++;
		
		//enable "delete all" button, not event, but anyway :)
		jQuery("#button_delete_all").removeClass("button-disabled");

	}
	
	
	
	/**
	 * 
	 * delete layer from layers object
	 */
	var deleteLayerFromObject = function(serial){
		
		var arrLayersNew = {};
		var flagFound = false;
		for (key in arrLayers){
			if(key != serial)
				arrLayersNew[key] = arrLayers[key];
			else
				flagFound = true;
		}
		
		if(flagFound == false)
			UniteAdminRev.showErrorMessage("Can't delete layer, serial: "+serial+" not found");
		
		arrLayers = arrLayersNew;
	}
	
	/**
	 * delete the layer from html.
	 */
	var deleteLayerFromHtml = function(serial){
		var htmlLayer = getHtmlLayerFromSerial(serial);
		htmlLayer.remove();
	}
		
	
	/**
	 * delete all representation of some layer
	 */
	var deleteLayer = function(serial){
		deleteLayerFromObject(serial);
		deleteLayerFromHtml(serial);
		deleteLayerFromSortbox(serial);
	}
	
	/**
	 * 
	 * call "deleteLayer" function with selected serial
	 */
	var deleteCurrentLayer = function(){
		if(selectedLayerSerial == -1)
			return(false);
		
		deleteLayer(selectedLayerSerial);
		
		//set unselected
		selectedLayerSerial = -1;
		
		//clear form and disable buttons
		disableFormFields();
	}

	
	/**
	 * duplicate layer, set it a little aside of the layer position
	 */
	var duplicateLayer = function(serial){
		var obj = arrLayers[serial];
		obj.left += 5;
		obj.top += 5;
		addLayer(obj);
		redrawSortbox();
	}
	
	
	/**
	 * call "duplicateLayer" function with selected serial 
	 */
	var duplicateCurrentLayer = function(){
		if(selectedLayerSerial == -1)
			return(false);
		
		duplicateLayer(selectedLayerSerial);
	}
	
	
	/**
	 * delete all layers
	 */
	var deleteAllLayers = function(){

		arrLayers = {};
		container.html("");
		emptySortbox();
		selectedLayerSerial = -1;
		
		disableFormFields();
		jQuery("#button_delete_all").addClass("button-disabled");		
	}
	
	
	/**
	 * update html layer position
	 */
	var updateHtmlLayerPosition = function(htmlLayer,top,left){
		htmlLayer.css({"top":top+"px","left":left+"px"});
	}
	
	
	/**
	 * update html layers from object
	 */
	var updateHtmlLayersFromObject = function(serial){
		if(!serial)
			serial = selectedLayerSerial
			
		var objLayer = getLayer(serial);
		
		if(!objLayer)
			return(false);
		
		var htmlLayer = getHtmlLayerFromSerial(serial);
		
		//set class name
		var className = "slide_layer ui-draggable caption";
		if(serial == selectedLayerSerial)
			className += " layer_selected";
		className += " "+objLayer.style;
		htmlLayer.attr("class",className);
		

		//set html
		var type = "text";
		if(objLayer.type)
			type = objLayer.type;
		
		//update layer by type:
		switch(type){
			case "image":
			break;
			case "video":
			break;
			default:
			case "text":
				htmlLayer.html(objLayer.text);
			break;
		}
		
		//set position
		updateHtmlLayerPosition(htmlLayer,objLayer.top,objLayer.left);
	}
	
	/**
	 * update layer from html fields
	 */
	var updateLayerFromFields = function(){
		
		if(selectedLayerSerial == -1){
			UniteAdminRev.showErrorMessage("No layer selected, can't update.");
			return(false);
		}
		
		var objUpdate = {};
		
		objUpdate.style = jQuery("#layer_caption").val();
		objUpdate.text = jQuery("#layer_text").val();
		objUpdate.top = Number(jQuery("#layer_top").val());
		objUpdate.left = Number(jQuery("#layer_left").val());				
		objUpdate.animation = jQuery("#layer_animation").val();				
		objUpdate.speed = jQuery("#layer_speed").val();				
		objUpdate.easing = jQuery("#layer_easing").val();
		objUpdate.link = jQuery("#layer_image_link").val();
		objUpdate.link_open_in = jQuery("#layer_link_open_in").val();
		
		//update object
		updateCurrentLayer(objUpdate);
		
		//update html layers
		updateHtmlLayersFromObject();
		
		//update html sortbox
		updateHtmlSortboxFromObject();
	}
	
	/**
	 * redraw some layer html
	 */
	var redrawLayerHtml = function(serial){
		var objLayer = getLayer(serial);		
		var html = makeLayerHtml(serial,objLayer)
		var htmlInner = jQuery(html).html();
		var htmlLayer = getHtmlLayerFromSerial(serial);
		
		htmlLayer.html(htmlInner);
	}
	
	
	/**
	 * update layer parameters from the object
	 */
	var updateLayerFormFields = function(serial){
		var objLayer = arrLayers[serial];
		
		jQuery("#layer_caption").val(objLayer.style);
		jQuery("#layer_text").val(objLayer.text);
		jQuery("#layer_top").val(objLayer.top);
		jQuery("#layer_left").val(objLayer.left);
		jQuery("#layer_animation").val(objLayer.animation);
		jQuery("#layer_easing").val(objLayer.easing);
		jQuery("#layer_speed").val(objLayer.speed);
		jQuery("#layer_image_link").val(objLayer.link);
		jQuery("#layer_link_open_in").val(objLayer.link_open_in);
	}
	
	/**
	 * unselect all html layers
	 */
	var unselectHtmlLayers = function(){
		jQuery(containerID + " .slide_layer").removeClass("layer_selected");
	}
	
	
	/**
	 * set all layers unselected
	 */
	var unselectLayers = function(){
		unselectHtmlLayers();
		unselectSortboxItems();
		selectedLayerSerial = -1;
		disableFormFields();
		
		//reset elements
		jQuery("#button_edit_video_row").hide();
		jQuery("#layer_text").css("height","80px");
		
		jQuery("#layer_image_link_row").hide();
		jQuery("#layer_link_open_in_row").hide();
		
	}
	
	
	/**
	 * set layer selected representation
	 */
	var setLayerSelected = function(serial){
		objLayer = getLayer(serial);
		
		var layer = getHtmlLayerFromSerial(serial);
		var sortItem = getHtmlSortItemFromSerial(serial);
		
		//unselect all other layers
		unselectHtmlLayers();
		
		//set selected class
		layer.addClass("layer_selected");
		
		//unselect all sortbox items
		unselectSortboxItems();
		
		//set sort item selected class
		sortItem.removeClass("ui-state-default").addClass("ui-state-hover");
		
		//update selected serial var
		selectedLayerSerial = serial;
		
		//update bottom fields
		updateLayerFormFields(serial);
		
		//enable form fields
		enableFormFields();
		
		//do specific operations depends on type
		switch(objLayer.type){
			case "video":	//show edit video button
				jQuery("#button_edit_video_row").show();
				jQuery("#layer_text").css("height","25px");
			break;
			case "image":	
				//disable the insert button
				jQuery("#linkInsertButton").addClass("disabled");
				jQuery("#layer_text").css("height","25px");
				jQuery("#layer_image_link_row").show();
				jQuery("#layer_link_open_in_row").show();
			break;
			default:  //set layer text to default height
				jQuery("#layer_text").css("height","80px");
			break;
		}
		
		//hide edit video button
		if(objLayer.type != "video")
			jQuery("#button_edit_video_row").hide();
		
		//hide image layer related fields
		if(objLayer.type != "image"){			
			jQuery("#layer_image_link_row").hide();
			jQuery("#layer_link_open_in_row").hide();
		}
		
	}
	
	
	/**
	 * 
	 * return if the layer is selected or not
	 */
	var isLayerSelected = function(serial){
		return(serial == selectedLayerSerial);
	}

	
//======================================================
//			Sortbox Functions
//======================================================	

	/**
	 * init the sortbox
	 */
	var initSortbox = function(){
				
		redrawSortbox();
		
		//set the sortlist sortable
		jQuery( "#sortlist" ).sortable({
				axis:'y',
				update: function(){
					onSortboxSorted();
				}
		});
		
		//set input time events:
		jQuery("#sortlist").delegate(".sortbox_time","keyup",function(event){
			if(event.keyCode == 13)
				onSortboxTimeChange(jQuery(this));
		});
		
		jQuery("#sortlist").delegate(".sortbox_time","blur",function(event){
			onSortboxTimeChange(jQuery(this));
		});
		
		//set input depth events:
		jQuery("#sortlist").delegate(".sortbox_depth","keyup",function(event){
			if(event.keyCode == 13)
				onSortboxDepthChange(jQuery(this));
		});
		
		jQuery("#sortlist").delegate(".sortbox_depth","blur",function(event){
			onSortboxDepthChange(jQuery(this));
		});
		
		//set click event
		jQuery("#sortlist").delegate("li","mousedown",function(){
			var serial = getSerialFromSortID(this.id);
			setLayerSelected(serial);
		});
		
		//sort type buttons events
		jQuery(".button_sorttype").click(function(){
			var mode = this.id.replace("button_sort_","");
			changeSortmode(mode);
		});
		
	}
	
	
	/**
	 * 
	 * change sortmode, display the changes
	 */
	var changeSortmode = function(mode){
		
		if(mode != "depth" && mode != "time"){
			trace("wrong mode: "+mode);
		}
		if(sortMode == mode)
			return(false);
		
		sortMode = mode;
		
		redrawSortbox();

		//change to time mode
		if(sortMode == "time"){
			
			jQuery("#button_sort_time").removeClass("ui-state-hover").addClass("ui-state-active");
			jQuery("#button_sort_depth").removeClass("ui-state-active").addClass("ui-state-hover");	
			
		}else{	//change to depth mode
			
			jQuery("#button_sort_depth").removeClass("ui-state-hover").addClass("ui-state-active");
			jQuery("#button_sort_time").removeClass("ui-state-active").addClass("ui-state-hover");
			
			updateOrderFromSortbox();
		}
		
	}
	
	
	/**
	 * 
	 * add layer to sortbox
	 */
	var addToSortbox = function(serial,objLayer){
		
		var sortboxText = getSortboxText(objLayer.text);
		var depth = objLayer.order+1;
		
		var htmlSortbox = '<li id="layer_sort_'+serial+'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
		htmlSortbox += '<span class="sortbox_text">' + sortboxText + '</span>';
		htmlSortbox += '<input type="text" class="sortbox_time" title="Edit Timeline" value="'+objLayer.time+'">';
		htmlSortbox += '<input type="text" class="sortbox_depth" title="Edit Depth" value="'+depth+'">';
		htmlSortbox += '<div class="clear"></div>';
		htmlSortbox += '</li>';
		
		jQuery("#sortlist").append(htmlSortbox);
	}
	
	
	/**
	 * 
	 * delete layer from sortbox
	 */
	var deleteLayerFromSortbox = function(serial){
		var sortboxLayer = getHtmlSortItemFromSerial(serial);
		sortboxLayer.remove();
	}
	
	
	/**
	 * 
	 * unselect all items in sortbox
	 */
	var unselectSortboxItems = function(){
		jQuery("#sortlist li").removeClass("ui-state-hover").addClass("ui-state-default");
	}
	
	
	/**
	 * update layers order from sortbox elements
	 */
	var updateOrderFromSortbox = function(){
		var arrSortLayers = jQuery( "#sortlist" ).sortable("toArray");

		for(var i=0;i<arrSortLayers.length;i++){
			var sortID = arrSortLayers[i];
			var serial = getSerialFromSortID(sortID);
			var objUpdate = {order:i};
			updateLayer(serial,objUpdate);
			
			//update sortbox order input
			var depth = i+1;
			jQuery("#"+sortID+" input.sortbox_depth").val(depth);
		}
		
		//update z-index of the html window by order
		updateZIndexByOrder();
	}

	
	
	/**
	 * get sortbox text from layer html
	 */
	var getSortboxText = function(text){
		sorboxTextSize = 50;
		var textSortbox = UniteAdminRev.stripTags(text);
		
		//if no content - escape html
		if(textSortbox.length < 2)
			textSortbox = UniteAdminRev.htmlspecialchars(text);
			
		//short text
		if(textSortbox.length > sorboxTextSize)
			textSortbox = textSortbox.slice(0,sorboxTextSize)+"...";
		
		return(textSortbox);
	}
	
	/**
	 * 
	 * redraw the sortbox
	 */
	var redrawSortbox = function(mode){
		
		if(mode == undefined)
			mode = sortMode;
		
		emptySortbox();	
		
		var layers_array = getLayersSorted(mode);
		
		for(key in layers_array){
			var objLayer = layers_array[key];
			addToSortbox(objLayer.serial,objLayer);
		}
	}
	
	
	/**
	 * remove all from sortbox
	 */
	var emptySortbox = function(){
		jQuery("#sortlist").html("");
	}
	
	/**
	 * 
	 * update sortbox text from object
	 */
	var updateHtmlSortboxFromObject = function(serial){
		if(!serial)
			serial = selectedLayerSerial;

		var objLayer = getLayer(serial);
		
		if(!objLayer)
			return(false);
		
		var htmlSortItem = getHtmlSortItemFromSerial(serial);
		
		if(!htmlSortItem)
			return(false);

		var sortboxText = getSortboxText(objLayer.text);
		htmlSortItem.children(".sortbox_text").text(sortboxText);
	}
	
	/**
	 * on sortbox sorted event.
	 */
	var onSortboxSorted = function(){
		
		if(sortMode == "depth")
			updateOrderFromSortbox();
		else	//sort by time
			redistributeTimes();
		
	}
	
	
//======================================================
//			Sortbox Functions End
//======================================================	
	

//======================================================
//			Time Functions
//======================================================	
	
	/**
	 * get next available time
	 */
	var getNextTime = function(){
		var maxTime = 0;
		
		//get max time
		for (key in arrLayers){
			var layer = arrLayers[key];
			
			layerTime = (layer.time)?Number(layer.time):0;
			
			if(layerTime > maxTime)
					maxTime = layerTime;
		}
				
		var outputTime;
		if(maxTime == 0)
			outputTime = g_startTime;
		else
			outputTime = Number(maxTime) + Number(g_stepTime);
						
		return(outputTime);
	}
	
	
	/**
	 * change time on the layer from the sortbox and reorder
	 */
	var onSortboxTimeChange = function(inputBox){
		
		//update the time by inputbox:
		var timeValue = inputBox.val();
		timeValue = Number(timeValue);
		var sortLayerID = inputBox.parent().attr("id");
		var serial = getSerialFromSortID(sortLayerID);		
		var objUpdate = {time:timeValue};
		
		updateLayer(serial,objUpdate);
		
		if(sortMode == "time")
			redrawSortbox();
	}
	
	/**
	 * change time on the layer from the sortbox and reorder
	 */
	var onSortboxDepthChange = function(inputBox){
		
		//update the time by inputbox:
		var depthValue = inputBox.val();
		depthValue = Number(depthValue);
		var order = depthValue-1;
		
		var sortLayerID = inputBox.parent().attr("id");
		var serial = getSerialFromSortID(sortLayerID);		
		var objUpdate = {order:order};
		
		updateLayer(serial,objUpdate);
		
		redrawSortbox();
		
		if(sortMode == "depth")
			updateOrderFromSortbox();
		
	}
	
	
	/**
	 * order layers by time
	 * type can be [time] or [order]
	 */
	var getLayersSorted = function(type){	
				
		if(type == undefined)
			type = "time";
		
		//convert to array
		var layers_array = [];
		for(key in arrLayers){
			var obj = arrLayers[key];
			obj.serial = key;
			layers_array.push(obj);
		}
		
		//sort layers array
		layers_array.sort(function(layer1,layer2){
			
			switch(type){
				case "time":
					
					if(Number(layer1.time) == Number(layer2.time)){
						if(layer1.order == layer2.order)
							return(0);
						
						if(layer1.order > layer2.order)
							return(1);
						
						return(-1);
					}
					
					if(Number(layer1.time) > Number(layer2.time))
						return(1);
				break;
				case "depth":
					if(layer1.order == layer2.order)
						return(0);
					
					if(layer1.order > layer2.order)
						return(1);
				break;
				default:
					trace("wrong sort type: "+type);
				break;
			}
			
			return(-1);
		});
		
		return(layers_array);
		
		//convert back to layers object
		/*
		arrLayers = {};
		for(key in layers_array){
			var obj = layers_array[key];
			var serial = String(obj.serial);
			arrLayers[serial] = obj;
		}
		*/
	}

	
	/**
	 * reditribute times between the layers sorted from small to big
	 */
	var redistributeTimes = function(){
		
		//collect times to array:
		var arrTimes = [];
		for(key in arrLayers)
			arrTimes.push(Number(arrLayers[key].time));
		
		arrTimes.sort(function(a,b){return a-b});	//sort number
		
		var arrSortLayers = jQuery( "#sortlist" ).sortable("toArray");

		for(var i=0;i<arrSortLayers.length;i++){
			var sortID = arrSortLayers[i];
			var serial = getSerialFromSortID(sortID);
			
			//update time:
			var newTime = arrTimes[i];
			var objUpdate = {time:newTime};
			updateLayer(serial,objUpdate);
			
			//update input box:
			jQuery("#"+sortID+" input.sortbox_time").val(newTime);
		}
		
	}
	
	
	
//======================================================
//				Time Functions End
//======================================================	
	
	
//======================================================
//				Events Functions
//======================================================	
	
	
	
	/**
	 * 
	 * on layer drag event - update layer position
	 */
	var onLayerDrag = function(){
		
		var layerSerial = getSerialFromID(this.id);
		var htmlLayer = jQuery(this); 
		var position = htmlLayer.position();
		var objUpdate = {top:Math.round(position.top),left:Math.round(position.left)};
		updateLayer(layerSerial,objUpdate);
				
		//update the position back with the rounded numbers (improve precision)
		updateHtmlLayerPosition(htmlLayer,objUpdate.top,objUpdate.left);
		
		//update bottom fields (only if selected)
		if(isLayerSelected(layerSerial))
			updateLayerFormFields(layerSerial);
		
	}
	
	/**
	 * move some layer
	 */
	var moveLayer = function(serial,dir,step){
		var layer = getLayer(serial);
		if(!layer)
			return(false);
		
		switch(dir){
			case "down":
				arrLayers[serial].top += step;
			break;
			case "up":
				arrLayers[serial].top -= step;
			break;
			case "right":
				arrLayers[serial].left += step;
			break;
			case "left":
				arrLayers[serial].left -= step;
			break;			
			default:
				UniteAdminRev.showErrorMessage("wrong direction: "+dir);
				return(false);
			break;
		}
		
		updateHtmlLayersFromObject(serial);
		
		if(isLayerSelected(serial))
			updateLayerFormFields(serial);
	}
	
	

//======================================================
//		Events Functions End
//======================================================	

	
}