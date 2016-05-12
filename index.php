<!DOCTYPE html>

<?php
session_start();
$username = empty($_COOKIE['userid']) ? '' : $_COOKIE['userid'];
 if (!$username) {
	header("Location: login.php");
	exit;
 }
?>

	<html xmlns="https://www.w3.org/1999/xhtml">

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
		$(function() {

			$('#SandBox').mixItUp();

      //for filter checked or unchecked
			$('.car input:checkbox').change(function() {
				if ($(this).is(":checked")) {
					selected.push($(this).attr('name'));
					var tempName = GetTempName();
					$('#SandBox').mixItUp('filter', tempName);
			  	}
                else {
					var index = selected.indexOf($(this).attr('name'));
					if (index > -1) {
						selected.splice(index, 1);
					}
					var tempName = GetTempName();
					$('#SandBox').mixItUp('filter', tempName);
			   	}
		   	});

    	addItemMixBoxes($('#SandBox'));   //newfuntion

       //for rightwapper click-show up or not
			$("#rightWrapper").click(function() {
				$(this).animate({
					width: 'toggle'
				}, 512, function() {
					$("#rightTab").animate({
						width: 'toggle'
					});
				});
			});
      //for righttab click-show up or not
    	$("#rightTab").click(function() {
				$(this).animate({
					width: 'toggle'
				}, 512, function() {
					$("#rightWrapper").animate({
						width: 'toggle'
					}, 512);
				});
			});
      //student Id check
    	$("#check").click(function() {
				if ($("#student").val().trim().length < 1) {
					$("#student").attr("placeholder", "Can not be NULL");
				} else if ($("#item").val().trim().length < 1) {
					$("#item").attr("placeholder", "Can not be NULL");
				} else {
					var date = new Date();
					date.setHours(date.getHours() + 2);
					var timeDue = date.getFullYear() + "-" + date.getMonth() + "-" + date.getDay() + " " + date.getHours() + ":" + date.getMinutes() + ":00";
					$.post("checkOut.php", {
						"student": $("#student").val().trim(),
						"item": $("#item").val().trim(),
						"time": timeDue
					}, function(data) {
            if(data=="existed"){
              alert(" this item already is already checked out");
            }
            else if(data=="Success"){
                //alert(" successed");
                location.reload(true);
            }
            else if(data=="No Change"){
              alert(" checked out fail !");
            }

					});
				}
			});

      //addEmployee part dialog  for add employee
    	$("#addEmployee").click(function() {
				if ($("#inputEmployeeUser").val().trim().length < 1) {
          $("#inputEmployeeUser").attr("placeholder", "Can not be NULL");
				} else if ($("#inputEmployeeFName").val().trim().length < 1) {
					$("#inputEmployeeFName").attr("placeholder", "Can not be NULL");
				} else if ($("#inputEmployeeLName").val().trim().length < 1) {
					$("#inputEmployeeLName").attr("placeholder", "Can not be NULL");
				} else if ($("#inputEmployeePass").val().trim().length < 1) {
					$("#inputEmployeePass").attr("placeholder", "Can not be NULL");
				} else {
					$.post("addEmployee.php", {
						"username": $("#inputEmployeeUser").val().trim(),
						"first": $("#inputEmployeeFName").val().trim(),
						"last": $("#inputEmployeeLName").val().trim(),
						"pass": $("#inputEmployeePass").val().trim(),
					}, function(data) {
						console.log(data);
					});
				}
			});
     // add a new Item
    	$("#addItem").click(function() {
				if ($("#inputItem").val().trim().length > 0) {
					$.post("addItem.php", {
						"item": $("#inputItem").val().trim()
					}, function(data) {
						addMixBox(1, 50, data, $('#SandBox'));
					});
				} else {
					$("#inputItem").attr("placeholder", "Can not be NULL");
				}
			});
    // add a new category
    	$("#addCategory").click(function() {
				if ($("#inputCategory").val().trim().length > 0) {
					$.post("addCategory.php", {
						"category": $("#inputCategory").val().trim()
					}, function(data) {
						console.log(data);
					});
				} else {
					$("#inputCategory").attr("placeholder", "Can not be NULL");
				}
			});
    // add a new location in the dialog
    	$("#addLocation").click(function() {
				if ($("#inputWaiver").val().trim().length > 0) {
					$.post("addLocation.php", {
						"location": $("#inputLocation").val().trim()
					}, function(data) {
						console.log(data);
					});
				} else {
					$("#inputLocation").attr("placeholder", "Can not be NULL");
				}
			});
    //add a new waiver
    	$("#addWaiver").click(function() {
				if ($("#inputWaiver").val().trim().length > 0) {
					$.post("addWaiver.php", {
						"waiver": $("#inputWaiver").val().trim()
					}, function(data) {
						console.log(data);
					});
				} else {
					$("#inputWaiver").attr("placeholder", "Can not be NULL");
				}
			});
    // option- find the new way search
    	$("#pickFirstName").click(function() {
				console.log("Print");
				$("#ascendingSort").attr("data-sort", "studentname:asc");
				$("#descendingSort").attr("data-sort", "studentname:desc");
			});
			$("#pickItemName").click(function() {
				console.log("Print");
				$("#ascendingSort").attr("data-sort", "itemname:asc");
				$("#descendingSort").attr("data-sort", "itemname:desc");
			});

            $("#addItem").click(function(){
                if($("#inputItem").val().trim().length < 1){
                    $("#inputItem").css("background-color", "gold");
                }
                else if($("#inputBarcode").val().trim().length < 1){

                    $("#inputBarcode").css("background-color", "gold");
                }
                else{

                    $.post("addItem.php", {"item" : $("#inputItem").val().trim(), "location" : $("#selectLocation").val(), "barcode" : $("#inputBarcode").val().trim(), }, function(data){

                        addMixBox(1, 50, data, $('#SandBox'));
                    });
                }
            });
           
            $("#addLocation").click(function(){
                if($("#inputWaiver").val().trim().length > 0){
                    $.post("addLocation.php", {"location" : $("#inputLocation").val().trim()}, function(data){

                        console.log(data);
                    });
                }
                else{
                    $("#inputLocation").css("background-color", "gold");
                }
            });
            $("#addWaiver").click(function(){
                if($("#inputWaiver").val().trim().length > 0){

                    $.post("addWaiver.php", {"waiver" : $("#inputWaiver").val().trim()}, function(data){

                        console.log(data);
                    });

                }
                else{
                    $("#inputWaiver").css("background-color", "gold");
                }
            });

            $("#pickFirstName").click(function(){
                console.log("Print");
                $("#ascendingSort").attr("data-sort", "studentname:asc");
                $("#descendingSort").attr("data-sort", "studentname:desc");
            });
            // $("#pickLastName").click(function(){
            //     console.log("Print");
            //     $("#ascendingSort").attr("data-sort", "lastName:asc");
            //     $("#descendingSort").attr("data-sort", "lastName:desc");
            // });
            $("#pickItemName").click(function(){
                console.log("Print");
                $("#ascendingSort").attr("data-sort", "itemname:asc");
                $("#descendingSort").attr("data-sort", "itemname:desc");
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

    //for the filter
		function GetTempName() {
			var tempName = "";
			for (var i = 0; i < selected.length; i++) {
				tempName += selected[i];
				if (i < selected.length - 1) {
					tempName += ",";
				}
			}
			if (tempName == "") {
				tempName = "all";
			}
			return tempName;
		}
   //for add a new item in box
		function addItemMixBoxes(mixDiv) { //new
			$.post("getItems.php", function(data) {
				if (data != "Empty Set") {
					data = JSON.parse(data);
					data.forEach(function(element) {
						addMixBox(element, mixDiv);
					});
				}
			});
		}
   //for remve a item in item
		function removeItem(itemID) {
			$.post("deleteItem.php", {
				"id": currentBox
			}, function(data) {
				console.log(data);
				if (data == "success") {
					$("#itemQuickDisplayBox" + itemID).remove();
				}
			});
		}
    function checkinItem(itemID) {
      $.post("checkinItem.php", {
        "id": currentBox
      }, function(data) {
        console.log(data);
        if (data == "success") {
        location.reload(true);
        }
      });
    }
   //add a mixBox
		function addMixBox(itemInfo, mixDiv) {

			var sortFields = " data-itemName = '" + itemInfo.name + "'",
                checkout = "";
            
            if(itemInfo.checkoutInfo){
                
                itemInfo.checkoutInfo.timeExpire = new Date(itemInfo.checkoutInfo.timeExpire);
                sortFields += "data-studentName = '" + itemInfo.checkoutInfo.studentName + "' data-overdueTime = '" + itemInfo.checkoutInfo.timeExpire + "' data-employeeName = '" + itemInfo.checkoutInfo.employeeName + "'";
                checkout = "<hr><p><b>Checked out to:</b><span>" + itemInfo.checkoutInfo.studentName + "</span></p>";
                checkout += "<p><b>Checked out by:</b><span>" + itemInfo.checkoutInfo.employeeName + "</span></p>";
                checkout += "<p><b>Due back by:</b><span>" + itemInfo.checkoutInfo.timeExpire.toString().substring(0, 24) + "</span></p>";
            } else
            {
                sortFields += "data-studentName = '~' data-overdueTime = '~' data-employeeName = '~'";
                
            }

			var tags = "<p id = 'modalTags'><b>Tags:</b>";
			var tagsClass = " ";
			itemInfo.tags.forEach(function(tag) {
				tags += "<span>" + tag + ", </span>";
				tagsClass += "item-" + tag + " ";
			});
			tags = tags.substring(0, tags.length - 9);
			tags += "</span></p>";
			
			var timeExpire = (itemInfo.checkoutInfo) ? "<br>" + itemInfo.checkoutInfo.timeExpire.toTimeString().substring(0, 8) : "";
			var studentName = (itemInfo.checkoutInfo) ? itemInfo.checkoutInfo.studentName + "<br>": "";
			
            var box = "<div id = 'itemQuickDisplayBox" + itemInfo.id + "' class='mix" + tagsClass + itemInfo.condition + "' " + sortFields + "style='display: inline-block;'>";
            var button = "<button type='button' class='displayItemInfo btn btn-info btn-lg' data-toggle='modal' data-target='#myModal'>" + studentName + itemInfo.name + timeExpire + "</button></div>";
			var newBox = $.parseHTML(box + button);
			mixDiv.mixItUp("prepend", newBox[0]);
			$(newBox).click(function() {
				currentBox = itemInfo.id;
				//console.log(itemInfo);
                $("#myModal .modal-footer .checkinItem").attr("onClick", "checkinItem(" + itemInfo.id + ")");
                $("#myModal .modal-footer .removeItem").attr("onClick", "removeItem(" + itemInfo.id + ")");
				
				fillModal(itemInfo, tags, checkout);
			});
        }
		
		function fillModal(itemInfo, tags, checkout){
				
			$("#myModal .modal-header").html("<button type = 'button' class = 'close' data-dismiss = 'modal'>x</button><h4 class = 'modal-title'>" + itemInfo.name + " details</h4>");
			
			var modalBody = "<p><b>Item Name:</b><span id = 'change-name'>" + itemInfo.name + "</span></p>";
			   modalBody += "<p><b>Location:</b><select id = 'select-location'></select>";
			   modalBody += "<p><b>Condtition:</b><select id = 'select-condition'></select>";
			   modalBody += tags;
			   modalBody += "<p><b>Add Tags:</b><input id = 'inputTags' placeholder = 'Enter tags seperated by spaces' type = 'text'><button id='addCategory' type='button' class='btn btn-default'><span class = 'glyphicon glyphicon-plus'></span>Add</button></p>";
			   modalBody += checkout;
			
			$.get("getLocations.php", function(data){
				
				data = JSON.parse(data);
				
				data.forEach(function(element){
					
					$("#myModal .modal-body p #select-location").append("<option value = '" + element + "'>" + element + "</option>");
				});
				
				if(itemInfo.location){
					
					$("#myModal .modal-body p #select-location").val(itemInfo.location);
				}
			});
			
			$.get("getConditions.php", function(data){
				
				data = JSON.parse(data);
				
				data.forEach(function(element){
					
					$("#myModal .modal-body p #select-condition").append("<option value = '" + element + "'>" + element + "</option>");
				});
				
				if(itemInfo.condition){
					
					$("#myModal .modal-body p #select-condition").val(itemInfo.condition);
				}
				
			});
			
			$("#myModal .modal-body").html(modalBody);
			
			$("#addCategory").click(function(){
				
				if($("#inputTags").val().trim().length > 0){
					
					$("#inputTags").val().trim().split(" ").forEach(function(tag){
						
						$.post("addCategory.php", {"category" : tag, "id" : currentBox}, function(data){
							
							if(data == "added"){
								if(itemInfo.tags[0])
                                {
                                    itemInfo.tags.push(tag);
                                }
								$("#modalTags").append(", <b>" + tag + "</b>");
							}
						});
					});
				}
				else{
					$("#inputTags").css("background-color", "gold");
				}
			});
			
			$("#myModal .modal-footer .removeItem").attr("onClick", "removeItem(" + itemInfo.id + ")");
		}
    </script>

	<body>
      <!-- this is header -->
		<nav class="navbar navbar-inverse title">

			<div class="container-fluid">

				<form class="navbar-right">

					<ul class="nav navbar-nav" id="navbar_top">

						<li><a style="color:rgba(241,184,45,.7);" href="#">Welcome! <?php echo $username;?></a></li>
						<li>
							<a style="color:rgba(241,184,45,.7);" href="#">
								<?php if($_SESSION["permissions"]<2){ echo "-Admin Model-";} else{echo "-Employee Model-";};?>
							</a>
						</li>
						<li><a style="color:white" href="login.php"><b>Log out</b></a> </li>
					</ul>
				</form>
			</div>
		</nav>

      <!-- main content -->
		<div id="container">
       <!-- left content -->
			<div id="leftWrapper">
       <!-- studentID and barcode -->
				<div id="topRibbon">

					<div id="button-div">

						<form>
							<button class="submit" id="check" type="button">Submit</button>
						</form>

						<div class="btn-group">

							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton time"> Time <span class="caret"></span></button>

							<ul class="dropdown-menu">

								<li>
									<input id="oneHour" name="oneHour">
									<label for="oneHour">1 hour</label>
								</li>
								<li>
									<input id="twoHours" name="temp" value="1">
									<label for="twoHours">2 hours</label>
								</li>
								<li>
									<input id="threeHours" name="temp" value="1">
									<label for="threeHours">3 hours</label>
								</li>
							</ul>
						</div>
					</div>

					<div id="scannedText-div">

						<div class="individual">

							<form id="studentID-form" class="scannedText-form">

								<label class="scannedText-label">Student ID</label>
								<input type="text" id="student" class="scanned-text" placeholder="From 1 to 10"/>
							</form>
						</div>
						<div class="individual">

							<form id="barcode-form" class="scannedText-form">

								<label class="scannedText-label">Barcode</label>
								<input type="text" id="item" class="scanned-text" placeholder="From 1 to 29" />
							</form>
						</div>
					</div>
				</div>

        <!-- ______________________this like a hr  tag _______________________ -->
				<div class="gradient-border"> </div>

        <!-- user part -->
				<div id="inUse-wrapper">
            <!-- this will show up when u click the item!        first part -->
					<div class="fade modal" id="myModal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header"></div>
								<div class="modal-body">
									<!-- <p><b>Student Name:</b> <span class="modal-editable">John</span></p>
									<p><b>Employee Name:</b> <span class="modal-editable"> Paul </span></p> -->
								</div>
								<div class="modal-footer">
                  <button type="button" class="btn btn-danger checkinItem" data-dismiss="modal">checkin</button>
                	<?php if($_SESSION["permissions"]<2){ ?>
									<button type="button" class="btn btn-danger removeItem " data-dismiss="modal">Remove</button>
									<button type="button" class="btn btn-default " data-dismiss="modal">Save</button>
                  <?php } ?>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
            <!-- this will show up when u click the add button!  second part -->
					<div class="fade modal" id="addNew" role="dialog">
						<div class="modal-dialog modal-lg">
							<!-- Modal content-->
							<div class="modal-content">
                <!-- Modal header-->
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Add Information</h4>
								</div>
                <!-- Modal body-->
								<div class="modal-body">
                                    <p>
                                        <span class="itemFill">
                                            
                                            <button id="addItem" type="button" class="btn btn-default">Add</button>
                                        </span>
                                        <b>Item Name:</b>
                                        <span>
                                            
                                            <input id="inputItem" class="add">
                                        </span>
                                        <b>Barcode:</b>
                                        <span>
                                            
                                            <input id="inputBarcode" class="add">
                                        </span>
                                        <b>Location:</b>
                                    </p>
                                    <div class="selectClass">
                                        <select id="selectLocation">
                                        
                                            <option value=1>Memorial</option>
                                            <option value=2>Student Center</option>
                                        </select>
                                    </div>
                                    
                                    <hr>
                                    <p>
                                        <button id="addLocation" type="button" class="btn btn-default" name="testme">Add</button>
                                        <b>Location:</b>
                                        <span><input id="inputLocation" class="add"></span>
                                    </p>
                                    <hr>
                                    <p>
                                        <button id="addWaiver" type="button" class="btn btn-default" >Add</button>
                                        <b>Waiver:</b>
                                        <span><input id="inputWaiver" class="add"></span>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                 
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control-bar sandbox-control-bar" style="overflow: visible;">
                        
                        <div class="group filterAlign">
							<label>Filter:</label>
							<div class="btn-group">
								<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton "> Tags <span class="caret"></span></button>
								<ul class="dropdown-menu car">
									<li>
										<input type="checkbox" id="comp" name=".item-laptop" value="1">
										<label for="comp">Laptop</label>
									</li>
									<li>
										<input type="checkbox" id="mac" name=".item-mac" value="2">
										<label for="mac">Mac</label>
									</li>
									<li>
										<input type="checkbox" id="bike" name=".item-PC" value="3">
										<label for="bike">PC</label>
									</li>
									<li>
										<input type="checkbox" id="ping_pong" name=".item-charger" value="4">
										<label for="ping_pong">Charger</label>
									</li>
									<li>
										<input type="checkbox" id="donkey" name=".item-bike" value="5">
										<label for="donkey">Bike</label>
									</li>
								</ul>
							</div>

							<div class="btn-group">

								<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle bringButton"> Condition <span class="caret"></span></button>
								<ul class="dropdown-menu car">

									<li>

										<input type="checkbox" id="ex2_1" name=".working" value="1">
										<label for="ex2_1">Working</label>
									</li>
									<li>

										<input type="checkbox" id="ex2_2" name=".broken" value="2">
										<label for="ex2_2">Broken</label>
									</li>
                                    <li>

                                        <input type="checkbox" id="ex2_2" name=".needs_repair_but_works" value="2">
                                        <label for="ex2_2">Needs repair</label>
                                    </li>
								</ul>
							</div>
						</div>

						<div class="group">

							<label>Sort:</label>
							<span class="btn sort" data-sort="random">Random</span>

							<div class="btn-group">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle"> Name <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li>
										<input type="radio" id="pickFirstName" name="ex1" value="1" checked="">
										<label for="pickFirstName">Name</label>
									</li>
									<li>
										<input type="radio" id="pickItemName" name="ex1" value="3">
										<label for="pickItemName">Item Name</label>
									</li>
								</ul>
							</div>

							<span id="ascendingSort" class="btn sort" data-sort="studentname:asc">Ascending</span>
							<span id="descendingSort" class="btn sort" data-sort="studentname:desc">Descending</span>
						</div>
             <!-- this is a button for add more employee and item etc -->
             <?php if($_SESSION["permissions"]<2){ ?>
						<button type="button" id="plus " class="btn btn-info  " data-toggle="modal" data-target="#addNew"> + </button>
            <?php } ?>
					</div>

        <!-- ______________________this like a hr  tag _______________________ -->
					<div class="gradient-border"></div>

        <!-- sandBox-our main content                      fourth part   -->
					<div id="SandBox" class="sandbox">

						<div class="gap"></div>
						<div class="gap"></div>

					</div>
				</div>
			</div>
        <!-- ______________________this like a hr  tag _______________________ -->
			<div id="rightTab" style="display: none;">
			<span class="glyphicon glyphicon-chevron-left"></span>
			</div>
        <!-- our left content-->
			<div id="rightWrapper">
				<div id="overdue">

					<h1>Overdue</h1>
				</div>
			</div>
		</div>
	</body>
	<script>
    //for sort!
		$(".sortby").click(function() {
			$(this).toggleClass("clicked");
		});
    //add more function in jquery
		$.fn.extend({
			editable: function() {
				$(this).each(function() {
					var $el = $(this),
						$edittextbox = $('<input type="text"></input>').css('min-width', $el.width()),
						submitChanges = function() {
							if ($edittextbox.val() !== '') {
								$el.html($edittextbox.val());
								$el.show();
								$el.trigger('editsubmit', [$el.html()]);
								$(document).unbind('click', submitChanges);
								$edittextbox.detach();
							}
						},
						tempVal;
					$edittextbox.click(function(event) {
						event.stopPropagation();
					});
					$el.dblclick(function(e) {
						tempVal = $el.html();
						$edittextbox.val(tempVal).insertBefore(this)
							.bind('keypress', function(e) {
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
		$('.modal-editable').editable().on('editsubmit', function(event, val) {
			console.log('text changed to ' + val);
		});
	</script>

	</html>
