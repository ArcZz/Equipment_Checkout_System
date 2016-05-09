<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js"></script>

        <link rel="stylesheet" href="https://mixitup.kunkalabs.com/wp-content/themes/mixitup.kunkalabs/style.css?ver=1.5.4" type="text/css">
    
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="css/dropdown-enhancement.css">

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="libraries/dropdown-enhancement.js"></script>
    
        <link rel="stylesheet" type="text/css" href="css/design.css">
    </head>


    <script type="text/javascript">
        
        var selected = [];
		var currentBox = -1;
        
        $(function(){
            
            $('#SandBox').mixItUp();
            
            $('.car input:checkbox, .other input:checkbox').change(function() {
                if($(this).is(":checked")) {
                    selected.push($(this).attr('name'));
                    
                    var tempName = GetTempName();
                    $('#SandBox').mixItUp('filter', tempName);
                } else
                {
                    var index = selected.indexOf($(this).attr('name'));
                    if (index > -1) {
                        selected.splice(index, 1);
                    }
                    var tempName = GetTempName();
                    
                    $('#SandBox').mixItUp('filter', tempName);
                }
                $('#textbox1').val($(this).is(':checked'));        
            });
            
            addItemMixBoxes($('#SandBox'));//new
            
            $("#rightWrapper").click(function(){
            
                $(this).animate({width: 'toggle'}, 512, function(){
                
                    $("#rightTab").animate({width: 'toggle'});
                });
            });
            
            $("#rightTab").click(function(){
            
                $(this).animate({width: 'toggle'}, 512, function(){
                
                    $("#rightWrapper").animate({width: 'toggle'}, 512);
                });
            });
			
			$("#check").click(function(){
				
				if($("#student").val().trim().length < 1){
					
					$("#student").css("background-color", "gold");
				}else if($("#item").val().trim().length < 1){
					
					$("#item").css("background-color", "gold");
				}else{
					
					var date = new Date();
					date.setHours(date.getHours() + 2);
					var timeDue = date.getFullYear() + "-" + date.getMonth() + "-" + date.getDay() + " " + date.getHours() + ":" + date.getMinutes() + ":00";
					
					$.post("checkOut.php", {"student" : $("#student").val().trim(), "item" : $("#item").val().trim(), "time" : Date().substring(16, 24)}, function(data){
						
						console.log(data);
					});
				}
			});
        });
        
        function GetTempName(){
            
            var tempName = "";
            
            for(var i = 0; i < selected.length; i++){
            
                tempName += selected[i];
                
                if(i < selected.length - 1){
                    
                    tempName += ",";
                }
            }

            if(tempName == ""){
            
                tempName = "all";
            }

            return tempName;
        }
        
        function addItemMixBoxes(mixDiv){//new
        
            $.post("getItems.php", function(data){
            
				if(data != "Empty Set"){
					
					data = JSON.parse(data);
					var i = 1;
					data.forEach(function(element){
						
						addMixBox(1, i++, element, mixDiv);
					});
				}
            });
        }
        
        function removeItem(itemID){
            
			$.post("deleteItem.php", {"id" : currentBox}, function(data){
				
				console.log(data);
				
				if(data == "success"){
					
					$("#itemQuickDisplayBox" + itemID).remove();
				}
			});
        }
		
        function addMixBox(category, value, itemInfo, mixDiv){
        
            var box = "<div id = 'itemQuickDisplayBox" + itemInfo.id + "' class='mix category-" + category + "' data-value=" + value + " data-name=" + itemInfo.name + " style='display: inline-block;'>";
            var button = "<button type='button' class='displayItemInfo btn btn-info btn-lg' data-toggle='modal' data-target='#myModal'>" + itemInfo.name + "</button></div>";
			var newBox = $.parseHTML(box + button);
			
            mixDiv.mixItUp("prepend", newBox[0]);
			
			$(newBox).click(function(){
				
				currentBox = itemInfo.id;
				
				$("#myModal .modal-header").html("<button type = 'button' class = 'close' data-dismiss = 'modal'>x</button><h4 class = 'modal-title'>" + itemInfo.name + " details</h4>");
				
				console.log(itemInfo);
				
				var itemName = "<p><b>Item Name:</b><span id = 'change-name'>" + itemInfo.name + "</span></p>";
				var itemLocation = "<p><b>Location:</b><select id = 'select-location'>";
				
				$.get("getLocations.php", function(data){
					
					data = JSON.parse(data);
					data.forEach(function(element){
						
						$("#myModal .modal-body p #select-location").append("<option value = '" + element + "'>" + element + "</option>");
					});
					
					$("#myModal .modal-body p #select-location").val(itemInfo.location);
				});
				
				itemLocation += "</select>";
				var tags = "<p><b>Tags:</b>";
				
				itemInfo.tags.forEach(function(tag){
					
					tags += "<span>" + tag + ", </span>";
				});
				
				tags = tags.substring(0, tags.length - 9);
				tags += "</span></p>";
				
				$("#myModal .modal-body").html(itemName + itemLocation + tags);
				$("#myModal .modal-footer .removeItem").attr("onClick", "removeItem(" + itemInfo.id + ")");
				var loanerName = (itemInfo.outName) ? "<p><b>Student Name:</b> <span class='modal-editable'>" + itemInfo.outName + "</span></p>" : "";
			});
        }
    </script>
    
    <body>
        
        <div id="container">
            
            <div id="leftWrapper">
			
                <div id="topRibbon">
				
                    <div id="button-div">
					
                        <form>
						
                            <button type="button">Submit</button>
                        </form>
                    </div>
                    
                    <div id="scannedText-div">
					
                        <div class="individual">
						
                            <form id="studentID-form" class="scannedText-form">
							
                                <label class="scannedText-label">Student ID</label>
                                <input type="text" id="barcode"  class="scanned-text"/> 
                            </form>
                        </div>
                        <div class="individual">
						
                            <form id="barcode-form" class="scannedText-form">
							
                                <label class="scannedText-label">Barcode</label>
                                <input type="text" id="barcode"  class="scanned-text"/> 
                            </form>
                        </div>
                    </div>
                    
                </div>
                
				<div id="searchRibbon" class="ribbon">
					
					<a href="#" class="sortby">Name</a>
					<a href="#" class="sortby">Location</a>
					<a href="#" class="sortby">Time Remaining</a>        
					
					<form> 
						
						<div>
                        
							<input class="searchBar" type="text" >
							<input class="submit" type="submit" value="Search">
						</div>
					</form>            
				</div>
                
                <div class="gradient-border"></div>
                
                <div id="inUse-wrapper">
				
					<div class="fade modal" id="myModal" role="dialog">
                    
						<div class="modal-dialog modal-lg">
                        
							<!-- Modal content-->
                            <div class="modal-content">

								<div class="modal-header"></div>
								<div class="modal-body">
								
									<p><b>Student Name:</b> <span class="modal-editable">John</span></p>
									<p><b>Employee Name:</b> <span class="modal-editable"> Paul </span></p>
								</div>
								<div class="modal-footer">
								
									<button type="button" class="btn btn-danger removeItem" data-dismiss="modal">Remove</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
                    </div> 
                    
                    <div class="fade modal" id="addNew" role="dialog">
						
						<div class="modal-dialog modal-lg">
							
							<!-- Modal content-->
							<div class="modal-content">
								
								<div class="modal-header">
                                
									<button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Information</h4>
                                </div>
								
								<div class="modal-body">
                                
									<p><button type="button" class="btn btn-default" onclick="AddItem('someItem')">Add</button><b>Employee Name:</b> <span><input class="add"></span></p>
                                    <hr>
                                    <p><button type="button" class="btn btn-default" data-dismiss="modal">Add</button><b>Item Name:</b> <span><input class="add"></span></p>
                                    <hr>
                                    <p><button type="button" class="btn btn-default" data-dismiss="modal">Add</button><b>Item Category:</b> <span><input class="add"></span></p>
                                    <hr>
                                    <p><button type="button" class="btn btn-default" data-dismiss="modal">Add</button><b>Location:</b> <span><input class="add"></span></p>
                                    <hr>
                                    <p><button type="button" class="btn btn-default" data-dismiss="modal">Add</button><b>Waiver:</b> <span><input class="add"></span></p>
                                </div>
                                <div class="modal-footer">
                                 
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="control-bar sandbox-control-bar align-left" style="overflow: visible;">
                        
                        <div class="group">
						
							<label>Filter:</label>
                            
							<div class="btn-group">
								
								<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton "> Item <span class="caret"></span></button>
								<ul class="dropdown-menu car">
									<li>
									  
										<input type="checkbox" id="comp" name=".category-1" value="1">
										<label for="comp">Computer</label>
									</li>
									<li>
										
										<input type="checkbox" id="car" name="temp" value="1">
										<label for="car">Car</label>
									</li>
									<li>
										
										<input type="checkbox" id="bike" name=".category-3" value="2">
										<label for="bike">Bike</label>
									</li>
									<li>
										
										<input type="checkbox" id="ping_pong" name="ex2" value="3">
										<label for="ping_pong">Ping pong</label>
									</li>
									<li>
										
										<input type="checkbox" id="donkey" name="ex2" value="4">
										<label for="donkey">Donkey</label>
									</li>
								</ul>
                            </div>

							<div class="btn-group">
								
								<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton"> Category <span class="caret"></span></button>
								<ul class="dropdown-menu other">
									
									<li>
									
										<input type="checkbox" id="group1" name=".category-2" value="1">
										<label for="group1">Group 1</label>
									</li>
									<li>
									
										<input type="checkbox" id="group2" name="ex2" value="1">
										<label for="group2">Group 2</label>
									</li>
								</ul>
                            </div>

                            <div class="btn-group">
								
								<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton"> Condition <span class="caret"></span></button>
								<ul class="dropdown-menu">
									
									<li>
										
										<input type="checkbox" id="ex2_1" name="ex2" value="1">
										<label for="ex2_1">Good</label>
									</li>
									<li>
									
										<input type="checkbox" id="ex2_2" name="ex2" value="2">
										<label for="ex2_2">Damaged</label>
									</li>
								</ul>
                            </div>
                        </div>

                        <div class = "group">
                            
							<label>Sort:</label>
                            <span class="btn sort" data-sort="random">Random</span>
                            <span class="btn sort" data-sort="value:asc">Ascending</span>
                            <span class="btn sort" data-sort="value:desc">Descending</span>
                        </div>

                        <span id="ToggleLayout" class="btn toggle-layout">&nbsp;<i></i></span>
                        <span id="ToggleConfig" class="btn toggle-config">&nbsp;</span>
                        <span class="btn filter" data-filter=".category-1">Blue</span>
                    </div>
                    
                    <button type="button" id="plus "class="btn btn-info btn-lg" data-toggle="modal" data-target="#addNew"> + </button>

                    <div id="SandBox" class="sandbox" style = "overflow: ">
                        
                        <div class="gap"></div>
                        <div class="gap"></div>
                        <form class="live-config" id="LiveConfig">
                            <div class="field effect no-checkbox active" data-effect="duration">
                                <label>Duration</label>
                                <div class="slider long" data-min="0" data-max="1000" data-unit="ms">
                                    <div class="scrubber" data-value="400ms" style="left: 40%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox active" data-effect="fade">
                                <input type="checkbox" checked="">
                                <label>Fade</label>
                            </div>
                            <div class="field effect checkbox" data-effect="scale">
                                <input type="checkbox">
                                <label>Scale</label>
                                <div class="slider" data-min="0.01" data-max="2" data-unit="">
                                    <div class="scrubber" data-value="0.01" style="left: 0%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox" data-effect="translateX">
                                <input type="checkbox">
                                <label>TranslateX</label>
                                <div class="slider" data-min="-100" data-max="100" data-unit="%">
                                    <div class="scrubber" data-value="10%" style="left: 55%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox" data-effect="translateY">
                                <input type="checkbox">
                                <label>TranslateY</label>
                                <div class="slider" data-min="-100" data-max="100" data-unit="%">
                                    <div class="scrubber" data-value="10%" style="left: 55%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox active" data-effect="translateZ">
                                <input type="checkbox" checked="">
                                <label>TranslateZ</label>
                                <div class="slider" data-min="-1000" data-max="1000" data-unit="px">
                                    <div class="scrubber" data-value="-360px" style="left: 32%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox" data-effect="rotateX">
                                <input type="checkbox">
                                <label>RotateX</label>
                                <div class="slider" data-min="-180" data-max="180" data-unit="deg">
                                    <div class="scrubber" data-value="20deg" style="left: 55.5556%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox" data-effect="rotateY">
                                <input type="checkbox">
                                <label>RotateY</label>
                                <div class="slider" data-min="-180" data-max="180" data-unit="deg">
                                    <div class="scrubber" data-value="20deg" style="left: 55.5556%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox" data-effect="rotateZ">
                                <input type="checkbox">
                                <label>RotateZ</label>
                                <div class="slider" data-min="-180" data-max="180" data-unit="deg">
                                    <div class="scrubber" data-value="20deg" style="left: 55.5556%;"></div>
                                </div>
                            </div>
                            <div class="field effect checkbox active" data-effect="stagger">
                                <input type="checkbox" checked="">
                                <label>Stagger</label>
                                <div class="slider" data-min="0" data-max="200" data-unit="ms">
                                    <div class="scrubber" data-value="34ms" style="left: 17%;"></div>
                                </div>
                            </div>
                            <div class="spacer"></div>
                            <div class="field">
                                <div class="dropdown scrollable"><span class="old"><select id="Easing" class="" data-settings="{&quot;cutOff&quot;:4}" onchange="site.sandBox.liveEasing(this.value)">
                                    <option value="ease">ease</option>
                                    <option value="cubic-bezier(0.47, 0, 0.745, 0.715)">easeInSine</option>
                                    <option value="cubic-bezier(0.39, 0.575, 0.565, 1)">easeOutSine</option>
                                    <option value="cubic-bezier(0.445, 0.05, 0.55, 0.95)">easeInOutSine</option>
                                    <option value="cubic-bezier(0.55, 0.085, 0.68, 0.53)">easeInQuad</option>
                                    <option value="cubic-bezier(0.25, 0.46, 0.45, 0.94)">easeOutQuad</option>
                                    <option value="cubic-bezier(0.455, 0.03, 0.515, 0.955)">easeInOutQuad</option>
                                    <option value="cubic-bezier(0.55, 0.055, 0.675, 0.19)">easeInCubic</option>
                                    <option value="cubic-bezier(0.215, 0.61, 0.355, 1)">easeOutCubic</option>
                                    <option value="cubic-bezier(0.645, 0.045, 0.355, 1)">easeInOutCubic</option>
                                    <option value="cubic-bezier(0.6, -0.28, 0.735, 0.045)">easeInBack</option>
                                    <option value="cubic-bezier(0.175, 0.885, 0.32, 1.275)">easeOutBack</option>
                                    <option value="cubic-bezier(0.68, -0.55, 0.265, 1.55)">easeInOutBack</option>
                                </select></span><span class="selected">ease</span><span class="carat"></span><div><ul><li class="active">ease</li><li>easeInSine</li><li>easeOutSine</li><li>easeInOutSine</li><li>easeInQuad</li><li>easeOutQuad</li><li>easeInOutQuad</li><li>easeInCubic</li><li>easeOutCubic</li><li>easeInOutCubic</li><li>easeInBack</li><li>easeOutBack</li><li>easeInOutBack</li></ul></div></div>
                            </div>
                            
                            <div class="spacer"></div>
                            
                            <div class="btn" id="Export">Export Configuration</div>
                        </form>
                    </div>
                </div>
            </div>
            <!--overdue section-->
            <div id = "rightTab" style = "display: none;">
                
				<span class = "glyphicon glyphicon-chevron-left"></span>
            </div>
            
            <div id = "rightWrapper">
			
				<div id = "overdue">
                    
					<h1>Overdue</h1>
                </div>
            </div>
        </div>      
    </body>
    <script>
        $(".sortby").click(function(){
			
			$(this).toggleClass("clicked");
        });
		
        $.fn.extend({
            editable: function () {
                $(this).each(function () {
                    var $el = $(this),
                    $edittextbox = $('<input type="text"></input>').css('min-width', $el.width()),
                    submitChanges = function () {
                        if ($edittextbox.val() !== '') {
                            $el.html($edittextbox.val());
                            $el.show();
                            $el.trigger('editsubmit', [$el.html()]);
                            $(document).unbind('click', submitChanges);
                            $edittextbox.detach();
                        }
                    },
                    tempVal;
                    $edittextbox.click(function (event) {
                        event.stopPropagation();
                    });
  
                    $el.dblclick(function (e) {
                        tempVal = $el.html();
                        $edittextbox.val(tempVal).insertBefore(this)
                        .bind('keypress', function (e) {
                            var code = (e.keyCode ? e.keyCode : e.which);
                            if (code == 13) {
                                submitChanges();
                            }
                        }).select();
                        $el.hide();
                        $(document).click(submitChanges);
                    });
                });
                return this;
            }
        });
        
        $('.modal-editable').editable().on('editsubmit', function (event, val) {
            
			console.log('text changed to ' + val);
        });
    </script>
</html>