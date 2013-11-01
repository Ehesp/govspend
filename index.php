<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>GovSpend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">
	<link href="assets/css/select2.css" rel="stylesheet">
	<link href="assets/css/multiple-select.css" rel="stylesheet"/>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="assets/js/select2.js"></script>
	<script src="assets/js/jquery.multiple.select.js"></script>
	
    <script>
  $(function() {
    $( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });
  
  $(function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 10000,
      values: [ 3000, 8000 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "£" + ui.values[ 0 ] + " - £" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "£" + $( "#slider-range" ).slider( "values", 0 ) +
      " - £" + $( "#slider-range" ).slider( "values", 1 ) );
  });
  
 $(document).ready(function() { $("#location").select2(); });
$("#myModal").modal();

  </script>	
  </head>

  <body>
      <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">GovSpend UK</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
            </p>
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Date Picker</li>
				<input type="text" id="from" name="from" class="datepick" placeholder="From Date" />
				<input type="text" id="to" name="to" class="datepick" placeholder="To Date" />
			  <li class="nav-header">Price Range</li>
				 <input type="text" id="amount" class="amount" style="border: 0; color: #f6931f; font-weight: bold;" />
				<div id="slider-range"></div>
			  <li class="nav-header">Location</li>
			    <select id="location" class="locations">
					<option>Manchester</option>
					<option>London</option>
					<option>Liverpool</option>
					<option>Bristol</option>
				</select>
				<li class="nav-header">Confidence</li>
					<select multiple="multiple" class="locations">
						<option value="1">Low</option>
						<option value="2">Medium</option>
						<option value="3">High</option>
					</select>
				<li class="nav-header">Department</li>
					<select multiple="multiple" class="locations">
						<option value="1">Department of Health</option>
						<option value="2">NHS Supply Chain</option>
					</select>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
			<div class="row-fluid">
			<form id="searcher"></form>
			  <input name="criteria" id="criteria" type="text" class="input-medium search-query" style="width:80%" placeholder="Enter your search criteria" >
			  <button id="search" type="submit" class="btn">Search</button>
			</div>
			<script type="text/javascript">
				$("#search").click(function(e) {
					e.preventDefault();
					 var string = $("#criteria").val(); 
					 if(string != '') {
						//console.log(string);
						$.ajax({
							type:'POST',
							data: {
								string: string
							},
							dataType: 'text',
							url:'searchresults.php',
							success:function(data) {
							//console.log(data);
								data = $.parseJSON(data);
								$('.number-results').empty();
								$('.all-results').empty();
								$.each(data.META_DATA, function(meta, metaData) {
									$('.number-results').append("<span style='margin-left: 20px;'>" + meta + ": " + metaData + "</span>");
								});
								$.each(data.RESULTS, function(types, type) {
									$.each(type, function(set, dataSet) {
										if(types == 'CONTRACT_AWARD') {
											var title = dataSet.FD_CONTRACT_AWARD.OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE.DESCRIPTION_AWARD_NOTICE_INFORMATION.TITLE_CONTRACT;
											var desc = dataSet.FD_CONTRACT_AWARD.OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE.DESCRIPTION_AWARD_NOTICE_INFORMATION.SHORT_CONTRACT_DESCRIPTION;
											var desc = jQuery.trim(desc).substring(0, 100).split(" ").slice(0, -1).join(" ") + "...";
											var url = dataSet.FD_CONTRACT_AWARD.CONTRACTING_AUTHORITY_INFORMATION.NAME_ADDRESSES_CONTACT_CONTRACT_AWARD.INTERNET_ADDRESSES_CONTRACT_AWARD.URL_GENERAL;
										}
										if(types == 'CONTRACT') {
											var title = dataSet.FD_CONTRACT.OBJECT_CONTRACT_INFORMATION.DESCRIPTION_CONTRACT_INFORMATION.TITLE_CONTRACT;
											var desc = dataSet.FD_CONTRACT.OBJECT_CONTRACT_INFORMATION.DESCRIPTION_CONTRACT_INFORMATION.SHORT_CONTRACT_DESCRIPTION;
											var desc = jQuery.trim(desc).substring(0, 100).split(" ").slice(0, -1).join(" ") + "...";
											var url = dataSet.FD_CONTRACT.CONTRACTING_AUTHORITY_INFORMATION.NAME_ADDRESSES_CONTACT_CONTRACT.INTERNET_ADDRESSES_CONTRACT.URL_GENERAL;
										}
										if(types == 'PRIOR_INFORMATION') {
											var title = dataSet.FD_PRIOR_INFORMATION.OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION.OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION.TITLE_CONTRACT;
											var desc = '';
											var url = dataSet.FD_PRIOR_INFORMATION.AUTHORITY_PRIOR_INFORMATION.NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION.INTERNET_ADDRESSES_PRIOR_INFORMATION.URL_GENERAL;
										}
										
										$('.all-results').append("<div class='row-fluid results'><h3 class='title'><a href='#myModal' data-toggle='modal'>"+title+"</a></h3><div class='contract-details span8 floatL'><div class='org-name'>Short Organisation Name</div><div class='description'><p>"+desc+"</p></div></div><div class='price span3 floatR conf-high'><p class='value'>£10,000</p></div><div class='clearfix'></div></div>");

									});									
								});
							}
						});
					 }
				});
			</script>
			<div class="number-results"></div>
			<div class="all-results">
				<!--<div class="row-fluid results">
					<h3 class="title"><a href="#myModal" data-toggle="modal">Waste and Recycling Services</a></h3>
						<div class="contract-details span8 floatL">
							<div class="org-name">Eastern Shires Purchasing Organisation (ESPO)</div>
							<div class="description">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris interdum vehicula dui vitae facilisis. Fusce justo enim, aliquet eget magna sit amet, imperdiet dignissim lacus.</p>
							</div>
						</div>
						<div class="price span3 floatR conf-high">
							<p class="value">£10,000</p>
						</div>
						<div class="clearfix"></div>
				</div><!--/row-->
			</div>
        </div><!--/span-->
      </div><!--/row-->
	  
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Waste and Recycling Services</h3>
  </div>
  <div class="modal-body">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam suscipit metus in felis accumsan, vel euismod ligula pellentesque. Etiam lectus tellus, posuere ut iaculis vel, varius vitae enim. Proin bibendum venenatis ligula. Suspendisse varius sapien vel orci venenatis, at auctor lectus dapibus. Donec viverra massa et metus condimentum volutpat. Morbi pharetra, dolor eget rutrum placerat, ante nisi vehicula tortor, vel fermentum erat lectus quis nunc. Nulla vehicula, magna non varius lacinia, mauris odio vulputate libero, vitae interdum ante quam sit amet ligula. Nulla scelerisque diam vehicula augue egestas tempus non a velit. Nullam tincidunt odio vitae rhoncus dictum. Donec mollis bibendum enim, sed interdum metus facilisis ut. Integer volutpat pretium vehicula. Proin hendrerit commodo lorem, eu placerat magna vestibulum vel. Maecenas molestie dictum mattis.

Nam rutrum mi id justo molestie, eget accumsan eros euismod. In tincidunt molestie enim in consequat. In semper dui turpis, sagittis adipiscing lectus ultricies non. Sed posuere, nisl at ornare ullamcorper, eros ante placerat nisi, et vestibulum risus sapien id neque. Nam vitae adipiscing mauris. Donec nec ligula libero. Donec lobortis id tortor quis commodo. Nam tincidunt non lectus quis pharetra. Praesent pulvinar vitae sapien sit amet mattis. Fusce id ipsum nec lacus ornare malesuada vel at urna. Vivamus at ornare odio. Etiam imperdiet nibh ut sem commodo, vel ornare ante facilisis. Vivamus volutpat a justo eget viverra.

Vivamus nibh risus, consectetur et libero ut, suscipit mattis nunc. Mauris lobortis gravida fringilla. Morbi eros arcu, mattis ac euismod in, venenatis ullamcorper enim. Nam convallis at purus in porttitor. Aenean sed nulla euismod, lacinia metus at, malesuada odio. Duis convallis tincidunt lorem. Nulla facilisi.

Pellentesque semper massa quis leo rhoncus tristique. Aliquam justo erat, ultrices sit amet eleifend sit amet, tempus non lacus. Ut bibendum augue et molestie feugiat. Aenean rhoncus lobortis lorem nec vehicula. Aliquam vehicula, nibh vel malesuada placerat, sem enim volutpat lacus, in condimentum turpis tellus sed est. Nulla ac pellentesque augue. Proin ut dolor lacus. Pellentesque dictum laoreet elementum. Vivamus tempus, nulla quis condimentum elementum, augue ipsum aliquet purus, in elementum justo ipsum eu augue. In odio magna, ultrices ac odio ac, vulputate vehicula turpis. Cras at rutrum nunc. Nam ut risus enim.

Sed a turpis non risus mollis dapibus. Nunc id congue nunc. Proin turpis lacus, tempor ac lacinia in, adipiscing eu lacus. Suspendisse diam nibh, placerat sit amet varius quis, pellentesque sed nisi. Nullam varius tortor eu ipsum tempus tincidunt. Etiam tincidunt nec sem ac lacinia. Pellentesque id enim eu neque rutrum aliquam at ut nibh. Donec at dignissim nibh. Maecenas iaculis suscipit feugiat. Nulla velit velit, tempus in rhoncus a, commodo id nulla. Ut ac risus blandit, laoreet mauris ut, ornare augue. Sed eget nisi urna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut sagittis bibendum tincidunt. Maecenas sem sapien, vehicula id imperdiet non, cursus in nisi.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>
</div>
	  
      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script>
	         $('select').multipleSelect();
			 
	</script>

  </body>
</html>
