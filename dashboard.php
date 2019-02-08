<?php  

 if($_SERVER['REQUEST_METHOD'] == 'POST')  {

$post = [
    'api_id' => $_POST['api_id'],
    'api_key' => $_POST['api_key'],
    'account_id'   => $_POST['account_nb'],
    'page_size' => 100,    
];

$post_stats = [
    'api_id' => $_POST['api_id'],
    'api_key' => $_POST['api_key'],
    'account_id'   => $_POST['account_nb'],
	'stats' => 'visits_timeseries, hits_timeseries, bandwidth_timeseries, requests_geo_dist_summary, visits_dist_summary, caching, caching, caching_timeseries, threats, incap_rules, incap_rules_timeseries',
	'time_range' => $_POST['period'],
];


/* generic Function for CURL to list sites and settings for page 2 and above*/
function requestList($pageNb) {
	$post_extra = [
    'api_id' => $_POST['api_id'],
    'api_key' => $_POST['api_key'],
    'account_id'   => $_POST['account_nb'],
    'page_size' => 100,
    'page_num' => $pageNb 
];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/sites/list");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_extra));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json_extra = curl_exec($ch);
	return $json_extra;
	curl_close($ch);
}

/* CURL to list sites and settings */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/sites/list");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);


	$json_object = json_decode($json);
	$json_object_2 = json_decode($json,true);
    $array_sites = [];
    
	$array_sites = $json_object_2['sites'] or die("<div>Issue with the API Key or Account Permission</div>");

	if (count ($json_object-> sites) == "100") {
		$number_sites = 100;
		$page_count = 1;

		// start pagination loop
 		while ($number_sites == 100) {
		$json_extra = requestList($page_count);
		$json_extra_object_2 = json_decode($json_extra,true);
		$array_sites = array_merge($array_sites, $json_extra_object_2['sites']);
		$number_sites = count($json_extra_object_2['sites']);
		$page_count = $page_count + 1;
 		}

	}

	$json_export_sites = json_encode($array_sites);
	file_put_contents("export_sites.json",$json_export_sites);


	
/*  curl to get account stats*/ 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/stats/v1");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_stats));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	file_put_contents("export_stats_7_days.json",$json);
	curl_close($ch);
	




/* Curl to get the account Info */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/account");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	file_put_contents("export_account_plan.json",$json);
	curl_close($ch);


    /* Curl to get the account subscription */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/accounts/subscription");
    curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));   // post data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    file_put_contents("export_account_subscriptions.json",$json);
    curl_close($ch);

/* curl to get the list of sub accounts */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/accounts/listSubAccounts");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	file_put_contents("export_subaccounts.json",$json);
	curl_close($ch);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/prov/v1/accounts/list");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	file_put_contents("export_account_list.json",$json);
	curl_close($ch);




function requestSitesStats($site_id) {
	$post_stats_sites = [
    'api_id' => $_POST['api_id'],
    'api_key' => $_POST['api_key'],
    'account_id'   => $_POST['account_nb'],
    'site_id' => $site_id,
	'stats' => 'visits_timeseries, hits_timeseries, bandwidth_timeseries, requests_geo_dist_summary, visits_dist_summary, caching, caching, caching_timeseries, threats, incap_rules, incap_rules_timeseries',
	'time_range' => $_POST['period']
];
	/*  curl to get Site stats*/ 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://my.incapsula.com/api/stats/v1");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_stats_sites));   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json_sites_stats = curl_exec($ch);
	return $json_sites_stats;
	curl_close($ch);
}



if (isset($_POST['sites_checkbox'])  )
	{
	$array_sites_stats = [];
	$json_site_list = file_get_contents('./export_sites.json');
	$json_site_list_data = json_decode($json_site_list,true);
	foreach ($json_site_list_data as $key1 => $site_id) {
	
//		echo $json_site_list_data[$key1]['domain'], '<br>';
		$json_extra_sites_stats = requestSitesStats($json_site_list_data[$key1]['site_id']);

		$json_extra_sites_stats_2 = json_decode($json_extra_sites_stats,true);
//		echo $json_extra_sites_stats_2['caching']['saved_bytes'], '<br>';
//		$array_sites_stats[$json_site_list_data[$key1]['domain']] = $json_extra_sites_stats_2;
        $array_sites_stats[] = array("domain"=>$json_site_list_data[$key1]['domain'], "stats"=>$json_extra_sites_stats_2);
		// $array_sites_stats = array_merge($array_sites_stats, $json_extra_sites_stats_2);
	}
		$json_export_sites_stats = json_encode($array_sites_stats);
		file_put_contents("export_sites_stats_7_days.json",$json_export_sites_stats);

} else
{
}

 }  

 ?>




<html>  



<head>  

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Account Level Dashboard for Imperva Incapsula APIs">
    <meta name="author" content="Jonathan Gruber Imperva">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="favico.png">

    <title>Account Imperva Incapsula Dashboard</title>  
    <!-- CSS LIBRARIES -->  
    <!-- Bootstrap Core CSS -->
    <link href="css/style.css" rel="stylesheet" type="text/css">	
    <!-- Custom CSS -->
    <link href="css/lib/calendar2/semantic.ui.min.css" rel="stylesheet" type="text/css">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
    

	
 
 <!-- OLD JS LIBRARIES WERE --> 
 <style>
 td  {
  min-width: 180px;
  padding: 6px;
}
</style>

      <body class="fix-header fix-sidebar"> 

<!-- page wrapper -->
<div class="main-wrapper">
           
        <!-- HEADER BAR  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header>
                    <a class="navbar-brand"  href="dashboard.php">
                        <!-- Dashboard logo icon  -->
                    <b style="font-size:30;color:#37347F"><img src="incapse.png" alt="homepage" class="dark-logo" style=" height:auto;margin-right: 2.5em"/> </b>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- 3 LINES WHEN BROWSER COMPRESSED  -->
                        <li class="nav-item m-l-4"> <a class="nav-link sidebartoggler text-muted  " href="javascript:void(0)"><i class="fa fa-th-large"></i></a> </li>
                        <li> <h3 class="text-primary"> Account Level Dashboard </h3> </li>

                    </ul>
                </div>
                <!-- End Logo -->
            </nav>
        </div>

         <!-- Left Sidebar  -->
        <div  id="sidebar" class="left-sidebar">
        </div>
        <!-- End Left Sidebar  -->

	   <!-- Container fluid for central page  -->
        <div id="export-pdf" class="page-wrapper">


        <div class="container-fluid">



        <!-- ROW for column of Account information -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-outline-info">
                <div class="card-header">
                        <h2 class="m-b-0 text-white"><span id="account_name" class="m-b-0" style="font-weight: bold;">tbd</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; id: <span id="account_id" class="m-b-0">tbd</span></h2>
                    </div>
                    <table>
                        <tbody>
                        <tr>
                              <td>Onboarded since: </td>
                              <td><span id="trial_end" ">tbd</span></td>
                              <td><span id="nb_users" class="m-b-0 " style="font-weight: bold;"></span> configured Users</td>

                            </tr> 

                            <tr>
                              <td>WAF Base Plan: </td>
                              <td></td>
                              <td>
                              <span id="plan_name">tbd</span>
                              </td>
                            </tr>   
                            <tr>
                              <td>Bandwidth Utilization:</td>
                              <td>                              <span id="used_bandwidth">0 Mbps</span></td>
                              <td>
                              <span id="purchased_bandwidth">0 Mbps</span>
                              </td>
                            </tr>  
                            <tr>
                              <td >Websites Configured:</td>
                              <td></td>
                              <td>
                              <span id="nb_websites" >tbd</span></p>
                              </td>
                            </tr>  
                            <tr>
                              <td >SIEM license: </td>
                              <td> </td>
                              <td>
                               <label class="switch">
                               <input  id="add_on_siem" type="checkbox" disabled>
                               <span class="slider"></span>
                               </label>
                              </td>
                            </tr>     
                            <tr>
                              <td >Attack Analytics: </td>
                              <td> </td>
                              <td>
                               <label class="switch">
                               <input  id="add_on_aa" type="checkbox" disabled>
                               <span class="slider"></span>
                               </label>
                              </td>
                            </tr>        
                            <tr>
                              <td >Load Balancing: </td>
                              <td> </td>
                              <td>
                               <label class="switch">
                               <input id="add_on_lb" type="checkbox" disabled>
                               <span class="slider"></span>
                               </label>
                              </td>
                            </tr>    
                            <tr>
                              <td >DDoS: </td>
                              <td> </td>
                              <td>
                               <label class="switch">
                               <input  id="add_on_ddos"  type="checkbox" disabled>
                               <span class="slider"></span>
                               </label>
                              </td>
                            </tr>    
                            <tr>
                              <td >Support Level: </td>
                              <td> </td>
                              <td>
                               <label class="label label-primary"> <span id="support_level">support level</span> </label>
                               </label>
                              </td>
                            </tr>                 
                    </tbody>
                    </table>
                </div>
            </div>



<!--
 
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Account Structure:</h2></p>
                    </div>
                    <table class="display nowrap table table-hover table-striped table-bordered" id="account_structure">           
                        <tr>
                        <th> Sub Account ID </th>
                        <th> Sub Account Name </th>
                    </table>
                    <p">Sub Account 1: nameabc <span id="subaccounts" class="text-success">4 sites</span></p>
                    <p>Sub Account 2: namecdf <span id="temp2" class="text-success">3 sites</span></p>
                    <p>Sub Account 3: name rfd <span id="nhkklhjkrs" class="text-success">6 sites</span></p> 
                </div>
            </div>
-->
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Executive Summary Assessment</h2> 
                    </div>
                <p class="f-w-600"  style="margin-top: 30px;">SITES FULLY CONFIGURED <span class="pull-right" id="configured right">0%</span></p>
                    <div class="progress ">
                        <div role="progressbar"  id="configured" style="width: 0%;" class="progress-bar bg-success wow animated progress-animated"> <span class="sr-only">60% Complete</span> 
                        </div>
                    </div>
                        <p class="m-t-30 f-w-600"  id="block"  style="margin-top: 30px;">SECURITY SETTINGS IN BLOCKED MODE<span class="pull-right" id="block right">0%</span></p>
                            <div class="progress">
                            <div role="progressbar" id="block progress" style="width: 0%;" class="progress-bar bg-danger wow animated progress-animated"> <span class="sr-only">60% Complete</span> 
                            </div>
                        </div>

                    <p class="m-t-30 f-w-600"  style="margin-top: 30px;">CONFIGURED VS PURCHASED SITES<span class="pull-right" id="configured sites">0%</span></p>
                        <div class="progress">
                            <div role="progressbar" id="configured_sites" style="width: 0%;" class="progress-bar bg-info wow animated progress-animated"> <span class="sr-only">60% Complete</span> 
                            </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>95-percentile Traffic Utilization of last 3 months</h2> 
                    </div>
                <canvas id="95perc" style="max-height: 300px, width:auto"></canvas>
                </div>
            </div>

        </div>

<!-- ROW for line Security Assessment -->
        <div class="pagebreak"> </div>
        <div class="row">
            <div class="col-12" >
                <div class="card" style="background-color: #e6f7ff">
                    <div class="card-body" style="background-color: #e6f7ff"><h1>SECURITY ASSESSMENT SUMMARY </h1></div>
                </div>
            </div>
        </div>
<!-- END ROW for line Security Assessment -->
   

        <!-- ROW 4 boxes for the 4 cards -->    
		<div class="row">
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
						<div class="media-left meida media-middle">
                        <span><i class="fa fa-globe f-s-40 color-success"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="nb_configured" class="text-success">tbd
                         </h2>
                        <p class="m-b-0">Sites Fully Configured</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-exclamation-triangle f-s-40 color-danger"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="nb_security" class="text-danger">tbd</h2>
                        <p class="m-b-0">settings in Block mode</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-bolt f-s-40 color-warning"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="nb_ddos" class="text-warning">tbd</h2>
                        <p class="m-b-0">DDoS thresholds <> Default</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-user f-s-40 color-info"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="nb_incaprules" class="text-info">#</h2>
                        <p class="m-b-0">Incaprules configured</p><span style="font-size: 10px">valid 30days range only</span>
                        </div>
                    </div>
                </div>
            </div>
		</div>

		
        <!-- ROW for column of the progress -->
		<div class="row">	


            <div class="col-lg-6">
                <div class="card">
                        <div class="card-title">
                       <h2>Nb Explicit Security Events per OWASP threat     <span style="font-size: 10pt">(on selected period)</span> </h2> 
                       </div>
                        <canvas id="threatChart"></canvas>
                   </div>
            </div>
	
            <div class="col-lg-6">
                <div class="card">
                        <div class="card-title">
					   <h2>Nb Custom and DDoS Security Events     <span style="font-size: 10pt">(on selected period)</span> </h2> 
					   </div>
				        <canvas id="threatChart_custom"></canvas>
	               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                    <div class="card">
                        <div class="card-title">
                        <h2>Human versus Bot Visits</h2> 
                        </div>

                  <!--  <canvas id="humanGraph"></canvas> --> 
                    </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                        <h2>Visits per Bot type on all sites</h2> 
                        </div>
                    <canvas id="BotsGraph"></canvas>
                </div>
            </div>
<!--            <div class="col-md-4" ">
                <div class="card" style="max-height: 500px"> 
                    <div class="card-title">
                        <h4>List of domains: more details</h4> <a style="color:blue" href="raw tables.html">here</a>
                        </div>
                         <table class="table nowrap table-hover table-bordered table-striped table-responsive" id="api_list_sites">           
                        <tr>
                        <th>Site ID</th>
                        <th>Site Name</th>
                        <th>Site Status</th>
                        </table>
                </div> 
            </div>-->
        </div>
 <!-- END ROW for column of the progress -->


<!-- ROW for line Performance Assessment TITLE-->
        <div class="pagebreak"> </div>
        <div class="row">
            <div class="col-12">
                <div class="card" style="background-color: #e6fff2">
                    <div class="card-body" style="background-color: #e6fff2"><h1>PERFORMANCE ASSESSMENT SUMMARY </h1></div>
                </div>
            </div>
        </div>
<!-- END ROW for Performance Assessment -->


<!-- ROW 4 boxes for the 4 cards -->    
        <div class="row">
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-globe f-s-40 color-success"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="trafficVolume" class="text-success">tbd</h2>
                        <p class="m-b-0">Total Traffic period (Gbytes)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-bolt f-s-40 color-success"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="cachingRatio" class="text-success">tbd</h2>
                        <p class="m-b-0">Global Caching Ratio</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-cloud f-s-40 color-warning"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="cachingSettings" class="text-warning">tbd</h2>
                        <p class="m-b-0">Sites with Static+Dynamic</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                        <span><i class="fa fa-signal f-s-40 color-info"  style="font-size:28"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                        <h2 id="cached_total" class="text-info">tbd</h2>
                        <p class="m-b-0">Cached Bw (Gbytes)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<!--  ROW for Traffic Graphs -->
        <div class="row">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Nb Site Configured Trend last 6 months</h2> <p>
                    </div>
                <canvas id="site_addition" style="max-height: 300px";> </canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Accumulated Cached Bandwidth in Account</h2> 
                    </div>
                <canvas id="bwGraph" style="max-height: 300px; width:auto"></canvas>
                </div>
            </div>
        </div>
<!--  END ROW for Traffic Graphs -->

<!-- ROW for serving Data Centers vs visitors -->
<div class="pagebreak"> </div>

<!-- Map of Visitors per country row -->
        <div class="row">
                <div class="col-md-12">
                     <div class="card">
                            <div class="card-title">
                            <h2>Map of Visitors per Country for all Sites</h2> 
                            </div>
                    <div id="threatmap"></div>
                </div>
            </div>
        </div>


<!--  ROW for Traffic Graphs -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Visitors per Country (on all sites)</h2> <p>
                    </div>
                <canvas id="visitorsCountry"></canvas>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                    <h2>Serving POPs (on all sites)</h2><p>
                    <span style="font-size: 12px">tip: compare with visited countries to see that customer is served by best POP</span>
                    </div>
                <canvas id="POPCountry"></canvas>
                </div>
            </div>
        </div>
<!-- END ROW for serving data centers vs visitors --> 



<button  class="btn btn-info" onclick="printPage()">Generate PDF </button>


            <!-- footer -->
            <footer class="footer"> © 2018 Imperva dashboard for Incapsula APIs, not an Imperva product <a href="https://github.com/imperva">Github Imperva Page</a></footer>
            <!-- End footer -->

    </div>
</div> <!-- end page wrapper--> 























<!-- START OF JAVASCRIPT SCRIPTS --> 

<!--JS LIBRARIES --> 
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>    
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>


    <!--Charts and scripts JavaScript -->
    <script src="js/lib/calendar-2/moment.latest.min.js"></script>
    <script src="js/Chart.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>
    <script src="js/jquery.slimscroll.js"></script> <!-- to have scroll within a div part --> 
    <script src="js/scripts.js"></script>  

    <script> 
        $(function(){
        $("#sidebar").load("sidebar.html"); 
        });
    </script>
</head>  


<script src="js/incapse-graphs.js"></script>
<script>
var default_colors = ['#e1e1ea','#ccffcc','#ccffff','#ffccff',' #ccccff','#ffe0cc','#ccfff5','#ffd9b3','#ffff80','#f0b3ff','#316395','#ff6666','#5353c6','#ff794d','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC']
</script>
<!-- SECURITY JAVASCRIPTS --> 

<!-- Javascript graph for threat type --> 
<script>
let myChart = document.getElementById('threatChart').getContext('2d');
let myChart_Custom = document.getElementById('threatChart_custom').getContext('2d');
//let myChart = document.getElementById('threatChart').getContext('2d');

let threatsGraph = new Chart(threatChart,{
        type:'bar',
        data:{
        labels:['Suspected Bots','Bad Bots', 'XSS', 'SQLi', 'illegal Resource', 'RFi', 'Backdoor'],
        datasets:[{
          label:'threats events',
          data:[
            0,
            0,
            0,
            0,
            0,
            0
          ],
          //backgroundColor:'green',
          backgroundColor:[
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(255, 99, 132, 0.6)'
          ],
          borderWidth:1,
          borderColor:'#777',
          hoverBorderWidth:3,
          hoverBorderColor:'#000'
        }]
      },
      options:{

    plugins: {
      datalabels: {
        display: true,
        anchor: 'end',
        align: 'top',
        formatter: Math.round,
        font: {
          weight: 'bold',
          size: '16'
        }
      }
  },
    

        title:{
          display:false,
          text:'Threat Events for selected period',
          fontSize:14
        },
        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        legend:{
          display:false,
          position:'right',
          labels:{
            fontColor:'#000'
          }
      },
        tooltips:{
          enabled:true
        },
        scales: {
        xAxes: [{

            display: true,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
            
            display: true,
            ticks: {
            beginAtZero: true   // minimum value will be 0.
            }
        }],

        yAxes: [{

            display: true,
                    gridLines: {
                        display: true,
                        drawBorder: false
                    },

            display: true,
            ticks: {
            beginAtZero: true   // minimum value will be 0.
            }
        }]
      }
  }
});


let threatsGraph_custom = new Chart(threatChart_custom,{
        type:'bar',
        data:{
        labels:['Incaprules','Blacklisted URL', 'Blacklisted Country', 'Blacklisted IP', 'DDoS'],
        datasets:[{
          label:'threats events',
          data:[
            0,
            0,
            0,
            0,
            0,
            0
          ],
          //backgroundColor:'green',
          backgroundColor:[
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(255, 99, 132, 0.6)'
          ],
          borderWidth:1,
          borderColor:'#777',
          hoverBorderWidth:3,
          hoverBorderColor:'#000'
        }]
      },
      options:{

    plugins: {
      datalabels: {
        display: true,
        anchor: 'end',
        align: 'top',
        formatter: Math.round,
        font: {
          weight: 'bold',
          size: '16'
        }
      }
  },
    

        title:{
          display:false,
          text:'Threat Events for selected period',
          fontSize:14
        },
        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        legend:{
          display:false,
          position:'right',
          labels:{
            fontColor:'#000'
          }
      },
        tooltips:{
          enabled:true
        },
        scales: {
        xAxes: [{

            display: true,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
            
            display: true,
            ticks: {
            beginAtZero: true   // minimum value will be 0.
            }
        }],

        yAxes: [{

            display: true,
                    gridLines: {
                        display: true,
                        drawBorder: false
                    },

            display: true,
            ticks: {
            beginAtZero: true   // minimum value will be 0.
            }
        }]
      }
  }
});


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
        if (typeof result.incap_rules === 'undefined'){
        threatsGraph_custom.data.datasets[0].data[0] = 0;
                }else{
        threatsGraph_custom.data.datasets[0].data[0] = result.incap_rules[0].incidents;
                    }


    threatsGraph.data.datasets[0].data[2] = result.threats[0].incidents;
    threatsGraph_custom.data.datasets[0].data[1] = result.threats[1].incidents;
    threatsGraph_custom.data.datasets[0].data[2] = result.threats[2].incidents;
    threatsGraph.data.datasets[0].data[3] = result.threats[3].incidents;
    threatsGraph_custom.data.datasets[0].data[3] = result.threats[4].incidents;
    threatsGraph.data.datasets[0].data[4] = result.threats[5].incidents;
    threatsGraph.data.datasets[0].data[0] = result.threats[6].incidents;
    threatsGraph.data.datasets[0].data[6] = result.threats[7].incidents;
    threatsGraph_custom.data.datasets[0].data[4] = result.threats[8].incidents;
    threatsGraph.data.datasets[0].data[5] = result.threats[9].incidents;
    threatsGraph.data.datasets[0].data[1] = result.threats[10].incidents;
    threatsGraph.update();
    threatsGraph_custom.update();

}
});
});


// print console list of managed accounts
$(document).ready(function(){
$.ajax({
    url:'export_account_list.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
    console.log("list of managed accounts");
    console.log(result);
}
});
$.ajax({
    url:'export_sites_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
    console.log("SITES STATS");
    console.log(result);
}
});
});



</script>






<!-- Graph for Human / Bot visits --> 
<script>
var dataHuman = {
    datasets: [{
        data: [
            2,
            4
        ],
        backgroundColor: [
            "#36A2EB",
            '#ffce56'
        ],
        label: 'My dataset' // for legend
    }],
    labels: [
        "Human",
        "Bots"
    ]
};

var optionsHuman = {
        legend:{
          display:true,
          position:'right',
          labels:{
            fontColor:'#000',
            fontSize:14
          }
        },

    plugins: {
      datalabels: {
        display: false
      }
  },

        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        tooltips:{
          enabled:true
        }
      };

var humanGraph2 = document.getElementById("humanGraph").getContext('2d');
var varHumanChart = new Chart(humanGraph2, {
  type: 'doughnut',
  data: dataHuman,
  options: optionsHuman
});

$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
        var totHumans = 0;
        var totBots = 0;
        $(result.visits_timeseries[0].data).each(function(index, value) {
                totHumans = totHumans + value[1];
                });
        $(result.visits_timeseries[1].data).each(function(index, value) {
                totBots = totBots + value[1];
                });

    varHumanChart.data.datasets[0].data[0] = totHumans;
    varHumanChart.data.datasets[0].data[1] = totBots;
    varHumanChart.data.datasets[0].data[0] = totHumans;
    varHumanChart.data.datasets[0].data[1] = totBots;
    varHumanChart.data.labels[0] = Math.round((totHumans/(totHumans+totBots))*100) + "% Humans";
    varHumanChart.data.labels[1]= Math.round((totBots/(totHumans+totBots))*100)+ "% Bots";
    varHumanChart.update();
}
});
});
</script>


<!-- GRAPH PIE for Visiting Bots Statistics --> 
<script>

var dataBots = {
    datasets: [{
        data: [
            3,
            14
        ],
        backgroundColor: default_colors,
        label: 'My dataset' // for legend
    }],
    labels: [
        "Google",
        "Yahoo"
    ]
};

var BotsChart2 = document.getElementById("BotsGraph").getContext('2d');
var varBotsChart = new Chart(BotsChart2, {
  type: 'doughnut',
  data: dataBots,
    options:{

    plugins: {
      datalabels: {
        display: false
      }
  },

        legend:{
          display:true,
          position:'right',
          labels:{
          fontColor:'#000',
          fontSize:14,
          padding: 0
          }
        },
        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        tooltips:{
          enabled:true
        }
      }
    });


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
            var BotsRequestsData = 0;
            var BotsRequestsLabels = 0;
            var totalBotsRequests = 0;
            var othersBots = 0;
            $(result.visits_dist_summary[1].data).each(function(index, value) {
                totalBotsRequests = totalBotsRequests + value[1];
                });

    var i2;
    var j2=1;
    for (i2=0; i2<result.visits_dist_summary[1].data.length; i2++){
    if (result.visits_dist_summary[1].data[i2][1]/totalBotsRequests > 0.04) {  
        varBotsChart.data.datasets[0].data[j2] = Math.round(result.visits_dist_summary[1].data[i2][1]/totalBotsRequests*100);
        varBotsChart.data.labels[j2] = result.visits_dist_summary[1].data[i2][0];
        j2 = j2+1;
    }else{
        othersBots = othersBots + result.visits_dist_summary[1].data[i2][1];    }
    }
    varBotsChart.data.datasets[0].data[0] = Math.round(othersBots/totalBotsRequests*100);
    varBotsChart.data.labels[0] = "others";
    varBotsChart.update();
}
});
});

</script>
<!-- END Pie for Bots statistics --> 

<!-- list of sites / domains --> 

       <!-- print table site list and settings --> 
    <script>
$(document).ready(function() {

        var list_sites = '';
        $.ajax({
        url: 'export_sites.json',
        datatype: 'json',
        type: 'get',
        cache: false,
        success: function(data) {
            $(data).each(function(index, value) {
                list_sites += '<tr>';
                list_sites += '<td>'+value.site_id+'</td>';
                list_sites += '<td>'+value.domain+'</td>';
                list_sites += '<td>'+value.status+'</td>';
                
            });
            $('#api_list_sites').append(list_sites); 
     
     }
 });
});
 </script>  

<!-- end list of sites --> 


 
<script>


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
            var count_incaprules = 0;
    //        var varThroughputData = [];
            $(result.incap_rules_timeseries).each(function(index, value) {
                if(value.name != "Deleted Rule"){
                    count_incaprules++;
                }
                });
            document.getElementById("nb_incaprules").textContent = count_incaprules;
}
});
});

</script>


<!-- graph for Cached Bandwidth per account--> 
<script>
let bwGraph2 = document.getElementById('bwGraph').getContext('2d');

var dataCached = {
    datasets: [{
                backgroundColor: '#26dad2',
                borderColor: '#28a745',
                data: [1,6,3],
                label: 'Cached Bw (Gbytes)', 
                fill: true      },
                {
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                data: [2,7,8],
                label: 'Total Bw (Gbytes)', 
                fill: true
                    }],
    labels: ["01","02","03"]};

var optionsBw = {
            maintainAspectRatio: false,
         //   spanGaps: false,
            display: true,
            elements: {
                line: {
                    tension: 0.4
                }
            },
            plugins: {



            datalabels: {
            display: false
            },

                filler: {
             propagate: false
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    }
                }]
            }
        };

let varbwGraph = new Chart(bwGraph,{
        type:'line',
        data: dataCached,
      options: optionsBw
});


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
        var BwArray_cached = [];
        $(result.caching_timeseries[1].data).each(function(index, value) {   
                BwArray_cached.push([value[0],value[1],0]) ;
                });
        $(result.caching_timeseries[5].data).each(function(index, value) {
                BwArray_cached[index][2]=value[1];
                });


    BwArray_cached.sort(function(a,b) {
        return a[0]-b[0]
    });

for (var i=0; i<BwArray_cached.length;i++){
    varbwGraph.data.labels[i]=moment(BwArray_cached[i][0]).format('DD MMM');

        if (i===0){
    varbwGraph.data.datasets[0].data[0] = Math.round(BwArray_cached[0][1]/1000000000);
    varbwGraph.data.datasets[1].data[0] = Math.round(BwArray_cached[0][2]/1000000000);
        }else{    
        varbwGraph.data.datasets[0].data[i] = Math.round((BwArray_cached[i][1]+BwArray_cached[i-1][1])/1000000);
        varbwGraph.data.datasets[1].data[i] = Math.round((BwArray_cached[i][2]+BwArray_cached[i-1][2])/1000000);  
        BwArray_cached[i][1] = BwArray_cached[i][1] + BwArray_cached[i-1][1]
        BwArray_cached[i][2] = BwArray_cached[i][2] + BwArray_cached[i-1][2]
            }
    }

    varbwGraph.update();
}
});
});
</script>
<!-- END graph for Cached Bandwidth per account--> 


<!--  GRAPH 95 PERC -->
<script>
var graphPerc95 = document.getElementById('95perc');
graphPerc95.height = 200;
var dataPerc95 = {
            labels: ["Earlier Billing Cycle","Previous Billing Cycle","Current Billing Cycle"],            
            type: 'line',     
            datasets: [{
                backgroundColor: 'rgba(0,103,255,.15)',
                borderColor: 'rgba(0,103,255,0.5)',
                data: [0.1,0.2,0.3],
                borderWidth: 3.5,
                pointStyle: 'circle',
                label: '95 percentile Utilization (Mbps)', 
                pointRadius: 5,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'rgba(0,103,255,0.5)'
                      }],
};



var optionsPerc95 = {
        responsive: true,
            tooltips: {
                mode: 'index',
                titleFontSize: 12,
                titleFontColor: '#000',
                bodyFontColor: '#000',
                backgroundColor: '#fff',
                cornerRadius: 3,
                intersect: false,
            },
            maintainAspectRatio: true,
            display: true,
            elements: {
            line: {tension: 0.4 }
            },
            plugins: {
            datalabels: {
            display: false
            },

            filler: {
             propagate: false
                }
            },
        legend: {
                display: false,
                position: 'top',
                labels: {
                    usePointStyle: true,
                },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    }
                }],
                yAxes: [ {
                    display: true,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Mbps'
                    }
                        } ]
            }
        }
        };

let varGraphPerc95 = new Chart(graphPerc95,{
        type:'line',
        data: dataPerc95,
      options: optionsPerc95
});


$(document).ready(function(){
$.ajax({
    url:'export_account_subscriptions.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
        var perc95_array = [];
        $(result.bandwidthHistory).each(function(index, value) {   
                perc95_array.unshift([value.billingCycle,parseFloat(value.alwaysOnBandwidth)]) ;
                });

varGraphPerc95.data.datasets[0].data=[perc95_array[0][1],perc95_array[1][1],perc95_array[2][1]];

/* for (var i=0; i<BwArray_cached.length;i++){
    varGraphPerc95.data.labels[i]=moment(BwArray_cached[i][0]).format('DD MMM');

        if (i===0){
    varGraphPerc95.data.datasets[0].data[0] = Math.round(BwArray_cached[0][1]/1000000);
    graphPerc95.data.datasets[1].data[0] = Math.round(BwArray_cached[0][2]/1000000);
        }else{    
        varGraphPerc95.data.datasets[0].data[i] = Math.round((BwArray_cached[i][1]+BwArray_cached[i-1][1])/1000000);
        gvarGraphPerc95.data.datasets[1].data[i] = Math.round((BwArray_cached[i][2]+BwArray_cached[i-1][2])/1000000);  
        BwArray_cached[i][1] = BwArray_cached[i][1] + BwArray_cached[i-1][1]
        BwArray_cached[i][2] = BwArray_cached[i][2] + BwArray_cached[i-1][2]
            }
    }
*/

    varGraphPerc95.update();
}
});
});
</script>
<!-- END GRAPH 95 PERC -->




<!-- GRAPH PIE for Serving POPs --> 
<script>

var dataPOP = {
    datasets: [{
        data: [
            3,
            14
        ],
        backgroundColor: default_colors,
        label: 'My dataset' // for legend
    }],
    labels: [
        "New York",
        "Paris"
    ]
};

var POPCountry2 = document.getElementById("POPCountry").getContext('2d');
var varPOPChart = new Chart(POPCountry2, {
  type: 'doughnut',
  data: dataPOP,
    options:{
        legend:{
          display:true,
          position:'right',
          labels:{
            fontColor:'#000'
          }
        },

            plugins: {
            datalabels: {
            display: false
            }
        },

        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        tooltips:{
          enabled:true
        }
      }
    });


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
            var POPRequestsData = 0;
            var POPRequestsLabels = 0;
            var totalPOPRequests = 0;
            var others = 0;
            $(result.requests_geo_dist_summary.data).each(function(index, value) {
                totalPOPRequests = totalPOPRequests + value[1];
                });

    var i;
    var j=1;
    for (i=0; i<result.requests_geo_dist_summary.data.length; i++){
    if (result.requests_geo_dist_summary.data[i][1]/totalPOPRequests > 0.01) {  
        varPOPChart.data.datasets[0].data[j] = Math.round(result.requests_geo_dist_summary.data[i][1]/totalPOPRequests*100);
        varPOPChart.data.labels[j] = result.requests_geo_dist_summary.data[i][0];
        j = j+1;
    }else{
        others = others + result.requests_geo_dist_summary.data[i][1];    }
    }
    varPOPChart.data.datasets[0].data[0] = Math.round(others/totalPOPRequests*100);
    varPOPChart.data.labels[0] = "others";
    varPOPChart.update();
}
});
});

</script>

<!-- END GRAPH PIE for Serving POPs --> 


<!-- GRAPH PIE for Visitors Country --> 
<script>

var dataPOP = {
    datasets: [{
        data: [
            3,
            14
        ],
        backgroundColor: default_colors,
        label: 'My dataset' // for legend
    }],
    labels: [
        "New York",
        "Paris"
    ]
};

var VisitorsCountry2 = document.getElementById("visitorsCountry").getContext('2d');
var varVisitorsCountryChart = new Chart(VisitorsCountry2, {
  type: 'doughnut',
  data: dataPOP,
    options:{
            plugins: {
            datalabels: {
            display: false
            }
        },

        legend:{
          display:true,
          position:'right',
          labels:{
            fontColor:'#000'
          }
        },
        layout:{
          padding:{
            left:0,
            right:0,
            bottom:0,
            top:0
          }
        },
        tooltips:{
          enabled:true
        }
      }
    });


$(document).ready(function(){
$.ajax({
    url:'export_stats_7_days.json',
    datatype: 'json',
    type: 'get',
    cache: false,
    success: function(result){
            var CountryRequestsData = 0;
            var CountryRequestsLabels = 0;
            var totalCountryRequests = 0;
            var othersCountry = 0;
            $(result.visits_dist_summary[0].data).each(function(index, value) {
                totalCountryRequests = totalCountryRequests + value[1];
                });

    var i2;
    var j2=1;
    for (i2=0; i2<result.visits_dist_summary[0].data.length; i2++){
    if (result.visits_dist_summary[0].data[i2][1]/totalCountryRequests > 0.02) {  
        varVisitorsCountryChart.data.datasets[0].data[j2] = Math.round(result.visits_dist_summary[0].data[i2][1]/totalCountryRequests*100);
        varVisitorsCountryChart.data.labels[j2] = result.visits_dist_summary[0].data[i2][0];
        j2 = j2+1;
    }else{
        othersCountry = othersCountry + result.visits_dist_summary[0].data[i2][1];    }
    }
    varVisitorsCountryChart.data.datasets[0].data[0] = Math.round(othersCountry/totalCountryRequests*100);
    varVisitorsCountryChart.data.labels[0] = "others";
    varVisitorsCountryChart.update();
}
});
});

</script>



      </body>


<!-- complete the security table --> 
    <script>

$(document).ready(function() {

		var count_block = 0;
		var count_alert = 0;
		var count_ddos = 0;
		var count_configured = 0;
		var count_not_configured = 0;
        var count_advCaching = 0;
		$.ajax({
		url: 'export_sites.json',
		datatype: 'json',
		type: 'get',
		cache: false,
		success: function(data) {
            console.log("JSON of sites and settings");
			console.log(data);

            $(data).each(function(index, value) {

		
				if(value.security.waf.rules[0].action_text === 'Alert Only' | value.security.waf.rules[0].action_text === 'Ignore') {
				count_alert++;
				} else {
				count_block++;
				}
				if(value.security.waf.rules[1].action_text === 'Alert Only' | value.security.waf.rules[1].action_text === 'Ignore') {
				count_alert++;
				} else {
				count_block++;
				}
				if(value.security.waf.rules[2].action_text === 'Alert Only' | value.security.waf.rules[2].action_text === 'Ignore') {
				count_alert++;
				} else { 
				count_block++;
				}
				if(value.security.waf.rules[6].action_text === 'Alert Only' | value.security.waf.rules[6].action_text === 'Ignore') {
				count_alert++;
				} else {
				count_block++;
				}
				if(value.security.waf.rules[4].ddos_traffic_threshold === 1000) {
				count_ddos++;
				}
				if(value.status === 'fully-configured') {
				count_configured++;
				} else {
				count_not_configured++;
				}
                if(value.acceleration_level === 'advanced') {
                count_advCaching++;
                } 
			});
			  
			document.getElementById("block right").innerHTML = Math.round(count_block/(count_alert+count_block)*100) +"%";	
			document.getElementById("block progress").style.width = count_block/(count_alert+count_block)*100 + "%";
			document.getElementById("configured right").innerHTML = Math.round(count_configured/(count_configured+count_not_configured)*100) +"%";	
			document.getElementById("configured").style.width = count_configured/(count_configured+count_not_configured)*100 + "%";
            document.getElementById("cachingSettings").textContent = count_advCaching+"/"+(count_configured+count_not_configured);
			document.getElementById("nb_configured").textContent = count_configured+"/"+(count_configured+count_not_configured);
			document.getElementById("nb_security").textContent = count_block+"/"+(count_block+count_alert);
			document.getElementById("nb_ddos").textContent = (count_configured+count_not_configured-count_ddos)+"/"+(count_configured+count_not_configured);
			
	 

// print Account Structure
            var structure_table = "";
            $.ajax({
            url: 'export_subaccounts.json',
            datatype: 'json',
            type: 'get',
            cache: false,
            success: function(data2) {
                console.log("JSON of account Structure");
                console.log(data2);
                if (data2.res != 0){
                
                }else if (data2.resultList.length == 0){
                structure_table += '<tr>';
                structure_table += '<td>'+data[0].account_id+'</td>'; 
                structure_table += '<td>'+"Main Account (no sub accounts)"+'</td>';
                structure_table += '<td>'+ count_configured+"/"+(count_configured+count_not_configured)+'</td>'; 
                $('#account_structure').append(structure_table); 
                }else{
                $(data2.resultList).each(function(index, value) {

                structure_table += '<tr>';
                structure_table += '<td>'+value.sub_account_id+'</td>'; 
                structure_table += '<td>'+value.sub_account_name+'</td>';
                });
            $('#account_structure').append(structure_table); 
            }
            }
            });
	 }
 });
});
 </script> 


  <!-- print Account Plan -->
  <script>
$(document).ready(function() {
		$.ajax({
		url: 'export_account_plan.json',
		datatype: 'json',
		type: 'get',
		cache: false,
		success: function(data) {
			console.log("JSON of account plan");
            console.log(data);

 			document.getElementById("account_name").textContent = data.account_name;
            document.getElementById("account_id").textContent = data.account_id;
			document.getElementById("plan_name").textContent = data.plan_name;
			document.getElementById("trial_end").textContent = data.account.trial_end_date;
            document.getElementById("nb_users").textContent = data.account.logins.length;
            document.getElementById("support_level").textContent = data.account.support_level;
	
            }
			});
		});	  
 </script>   


 <!-- print Account Subscriptions -->
    <script>
$(document).ready(function() {
        $.ajax({
        url: 'export_account_subscriptions.json',
        datatype: 'json',
        type: 'get',
        cache: false,
        success: function(data) {
            console.log("JSON of Account Subscription");
            console.log(data);
            // Load Balancing add on
            if (data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Load Balancing').purchased !=0){
document.getElementById("add_on_lb").checked = true;
            }
            if (data.planStatus.additionalServices.planSectionRows.find(x => x.name === 'SIEM Integration').purchased !=0){
document.getElementById("add_on_siem").checked = true;
            }
            if (data.planStatus.additionalServices.planSectionRows.find(x => x.name === 'Web Attack Analytics').purchased !=0){
                document.getElementById("add_on_aa").checked = true;
            }
            if (data.planStatus.additionalServices.planSectionRows.find(x => x.name === 'DDoS Protection').purchased !="None"){
                document.getElementById("add_on_ddos").checked = true;
            }
document.getElementById("plan_name").textContent += " - Purchased Always On Bw:" + data.planStatus.additionalServices.planSectionRows[0].purchased;
document.getElementById("used_bandwidth").textContent = data.planStatus.additionalServices.planSectionRows[0].used.slice(0, data.planStatus.additionalServices.planSectionRows[0].used.search("-")) ;
document.getElementById("purchased_bandwidth").textContent = data.planStatus.additionalServices.planSectionRows[0].purchased +" purchased";

document.getElementById("nb_websites").textContent = data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').used + " Used / " + data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').purchased + " Purchased";

 //           document.getElementById("configured_sites").style.width = count_block/(count_alert+count_block)*100 + "%";

if ((data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').purchased)=="Unlimited"){
document.getElementById("configured sites").textContent = "100%";
document.getElementById("configured_sites").style.width = "100%";
}else{
document.getElementById("configured sites").textContent = Math.round(100*parseFloat(data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').used) / parseFloat(data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').purchased)) +"%";
document.getElementById("configured_sites").style.width = Math.round(100*parseFloat(data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').used) / parseFloat(data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').purchased)) +"%";

}
        //    data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').used +  data.planStatus.websiteProtection.planSectionRows.find(x => x.name === 'Additional Sites').purchased ;

        }
        });
});
</script>







 <!-- complete the Caching Ratio--> 
<script>

$(document).ready(function() {
        var varCached_Bw = 0;
        var varTotal_Bw = 0;
        $.ajax({
        url: 'export_stats_7_days.json',
        datatype: 'json',
        type: 'get',
        cache: false,
        success: function(data) {
            console.log("JSON of Account Statistics");
            console.log(data);
            varCached_Bw = data.caching.saved_bytes;
            varTotal_Bw = data.caching.total_bytes;
        document.getElementById("cachingRatio").textContent = Math.round(varCached_Bw/(varTotal_Bw)*100) +"%";  
        document.getElementById("trafficVolume").textContent = Math.round(varTotal_Bw/100000000)/10;
        document.getElementById("cached_total").textContent = Math.round(varCached_Bw/100000000)/10;
        }
     });
});
 </script>



<!-- Map Resources --> 
<script src="https://www.amcharts.com/lib/3/ammap.js" type="text/javascript"></script>
<script src="https://www.amcharts.com/lib/3/maps/js/worldHigh.js" type="text/javascript"></script>
<script src="https://www.amcharts.com/lib/3/themes/dark.js" type="text/javascript"></script>
<script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>


<script type="text/javascript">
var map = AmCharts.makeChart("mapdiv",{
type: "map",
theme: "Map with Bubbles",
projection: "mercator",
panEventsEnabled : true,
backgroundColor : "#535364",
backgroundAlpha : 1,
zoomControl: {
zoomControlEnabled : true
},
dataProvider : {
map : "worldHigh",
getAreasFromMap : true,
areas :
[]
},
areasSettings : {
autoZoom : true,
color : "#B4B4B7",
colorSolid : "#84ADE9",
selectedColor : "#84ADE9",
outlineColor : "#666666",
rollOverColor : "#9EC2F7",
rollOverOutlineColor : "#000000"
}
});
</script>



<!-- Styles -->
<style>
#threatmap {
  width: 100%;
  height: 500px;
}                                                                       
</style>


<script>

// Update Circle value for each country

$(document).ready(function() {
        $.ajax({
        url: 'export_stats_7_days.json',
        datatype: 'json',
        type: 'get',
        cache: false,

        success: function(data) {
            var randomcolor1 = "";
            $(data.visits_dist_summary[0].data).each(function(index, value) {
                for (var i=0;i<mapData.length;i++){
                    if(mapData[i].code.toLowerCase()===value[0]){
                    mapData[i].value = value[1];
                    break;
                    }
                }
            });


// get min and max values
var minBulletSize = 0;
var maxBulletSize = 70;
var min = Infinity;
var max = -Infinity;
for ( var i = 0; i < mapData.length; i++ ) {
  var value = mapData[ i ].value;
  if ( value < min ) {
    min = value;
  }
  if ( value > max ) {
    max = value;
  }
}

// it's better to use circle square to show difference between values, not a radius
var maxSquare = maxBulletSize * maxBulletSize * 2 * Math.PI;
var minSquare = minBulletSize * minBulletSize * 2 * Math.PI;

// create circle for each country

var images = [];
for ( var i = 0; i < mapData.length; i++ ) {
    var dataItem = mapData[ i ];
    var value = dataItem.value;
  // calculate size of a bubble
  var square = ( value - min ) / ( max - min ) * ( maxSquare - minSquare ) + minSquare;
  if ( square < minSquare ) {
    square = minSquare;
  }
  var size = Math.sqrt( square / ( Math.PI * 2 ) );
  var id = dataItem.code;

  if(square > maxSquare/3) {
    dataItem.color = "Red"
  }else if(square > maxSquare/6){
 dataItem.color = "OrangeRed"
  }else if (square > maxSquare/12){
 dataItem.color = "Blue"
  }else if (square > maxSquare/20){
 dataItem.color = "YellowGreen"
  }else{
 dataItem.color = "Grey"
  }

  images.push( {
    "type": "circle",
    "theme": "none",

    "width": size,
    "height": size,
    "color": dataItem.color,
    "longitude": latlong[ id ].longitude,
    "latitude": latlong[ id ].latitude,
    "title": dataItem.name,
    "value": value
  } );
}

console.log("map data info")
console.log(mapData);
// build map
var map = AmCharts.makeChart( "threatmap", {
  "type": "map",
  "projection": "eckert6",
  "titles": [ {
    "text": "",
    "size": 14
  } ],
  "areasSettings": {
    //"unlistedAreasColor": "#000000",
    //"unlistedAreasAlpha": 0.1
  },
  "dataProvider": {
    "map": "worldLow",
    "images": images
  },
  "export": {
    "enabled": true
  }
} );
}
    });
    });
</script>


<!-- Graph of # sites added in last 6 months --> 
<script>
let site_addition3 = document.getElementById('site_addition').getContext('2d');

var dataSiteAddition = {
    datasets: [{
                backgroundColor: '#62d1f3',
                borderColor: 'rgba(54, 162, 235, 0.6)',
                data: [1,6,3,2,3,6],
                    }],
    labels: [ "Month 1", "Month 2","Month 3","Month 4","last Month","this Month",]
};

var optionsSiteAddition = {
            legend: {
                display: false,
                labels: {
                    usePointStyle: true,
                    fontFamily: 'Montserrat',
                },
            },

            maintainAspectRatio: false,
            spanGaps: false,
            elements: {
                line: {
                    tension: 0.4
                }
            },
            plugins: {
                datalabels: {
                display: true,
                anchor: 'center',
                formatter: Math.round,
                font: {
                    weight: 'bold',
                    size: '16'
                }
            },

                filler: {
                    propagate: false
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    }
                }],
                yAxes: [{
            display: true,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
            ticks: {
            beginAtZero: true   // minimum value will be 0.
            }
        }]
            }
        };

let varsiteAddGraph = new Chart(site_addition,{
        type:'bar',
        data: dataSiteAddition,
        options: optionsSiteAddition
});

$(document).ready(function() {
        $.ajax({
        url: 'export_sites.json',
        datatype: 'json',
        type: 'get',
        cache: false,

        success: function(data) {

        var count_sites_month = [0,0,0,0,0,0];
        var months_unix = [];

        for (i=0; i<6; i++){
        months_unix.push(moment().add(i-5,'months').unix());
        }


        $(data).each(function(index, value) {
            for (j=0; j<6; j++){
                if(value.site_creation_date/1000 < months_unix[j]) {
                count_sites_month[j] ++;

                }
            }
        });
        varsiteAddGraph.data.datasets[0].data = count_sites_month;
        varsiteAddGraph.update();
    }
});
    });
</script>

<script>
function printPage() {
    alert("IMPORTANT: enable the option Background graphics in printer option");
  window.print();
}
</script>
 