<? 
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<? include "../../header.php"; ?>
<?
include "../../login_check.php";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>

<link rel="stylesheet" href="../../../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js"></script>

<style>
        .div0 {
                width: 350px;
         }
        .div1 {
                width: 120px;
                display: inline-block;
                text-align: left;
                margin-right: 10px;
        }
        .divEnabled {
                width: 63px;
                color: lime;
                display: inline-block;
                font-weight: bold;
        }
        .divDisabled {
                width: 63px;
                color: red;
                display: inline-block;
                font-weight: bold;
        }
        .divAction {
                width: 20px;
                display: inline-block;
                font-weight: bold;
        }
        .divDivision {
                width: 16px;
                display: inline-block;
        }
</style>
<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?
include "../../config/config.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_GET["tempname"], "msg.php", $regex_extra);
    regex_standard($_POST["proxy"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$proxy = $_POST["proxy"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

?>

<div class="rounded-top" align="left"> &nbsp; <?=$mod_alias?> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;&nbsp; version <?=$mod_version?><br>
    <? 
    if (file_exists("/usr/local/bin/bettercap")) {
        echo "&nbsp; $mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; $mod_alias <span style='color:red'>install</span><br>";
    } 
    ?>

    <?
    $ismodup = exec($mod_isup);
    if ($ismodup != "") {
        echo "&nbsp; $mod_alias <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='includes/module_action.php?service=fruitydns&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp; $mod_alias  <font color='red'><b>disabled</b></font>. | <a href='includes/module_action.php?service=fruitydns&action=start&page=module'><b>start</b></a>"; 
    }
    ?>

</div>

<script type='text/javascript'>
// BLOCK 1
</script>

<br>

<div id="msg" style="font-size:larger">
    Loading, please wait...
</div>

<div id="body" style="display:none;">

<div id="result" class="module">
    <ul>
        <li><a href="#tab-output">Output</a></li>
        <li><a href="#tab-history">History</a></li>
        <li><a href="#tab-options">Options</a></li>
        <li><a href="#tab-about">About</a></li>
    </ul>
    
    <!-- OUTPUT -->
    
    <div id="tab-output">
        <form id="formLogs-Refresh" name="formLogs-Refresh" method="GET" autocomplete="off" action="index.php">
        <input type="submit" value="refresh">
        <br><br>
        <?
            if ($logfile != "" and $action == "view") {
                $filename = $mod_logs_history.$logfile.".log";
            } else {
                $filename = $mod_logs;
            }
            
            if ($mod_sslstrip_filter == "LogEx.py") {
                $exec = "$bin_python $mod_path/includes/filters/LogEx.py $filename";
                $output = exec_fruitywifi($exec);
                
                //$data = implode("\n",$output);
                $data = $output;
            } else if ($mod_sslstrip_filter == "ParseLog.py") {
                $exec = "$bin_python $mod_path/includes/filters/ParseLog.py $filename $mod_path/includes/filters";
                $output = exec_fruitywifi($exec);
                        
                //$data = implode("\n",$output);
                $data = $output;
            } else {
            
                
                $data = open_file($filename);
                $data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                //$data = array_reverse($data_array);
                $data = $data_array;
                
                //exec("/usr/bin/tail -n 100 $filename", $data_array);
                //$data = $data_array;
            }
        
        ?>
        <textarea id="output" class="module-content" style="font-family: courier;"><?
            //htmlentities($data)
        
            for ($i=0; $i < count($data); $i++) {
                if (strlen($data[$i]) > 120) {
                    echo htmlentities(substr($data[$i], 0, 120)) . "... {truncated}\n";
                } else {
                    echo htmlentities($data[$i]) . "\n";
                }
            }
        
        ?></textarea>
        <input type="hidden" name="type" value="logs">
        </form>
    </div>
    
    <!-- HISTORY -->
    
    <div id="tab-history" class="history">
        <input type="button" value="refresh" onclick="window.location='?tab=1';">
        <br><br>
        
        <?
        $logs = glob($mod_logs_history.'*.log');
        print_r($a);

        for ($i = 0; $i < count($logs); $i++) {
            $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=1'><b>x</b></a> ";
            echo $filename . " | ";
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
            echo "<br>";
        }
        ?>
        
    </div>
    
    <!-- OPTIONS -->
    
    <div id="tab-options" >
        <form id="formConfig" name="formTamperer" method="POST" autocomplete="off" action="includes/save.php">
            <input type="button" value="refresh" onclick="window.location='?tab=2';">
            
            <br><br>
            
            <input id="bc_dns_enabled" type="checkbox" name="my-checkbox" <? if ($mod_bc_dns_enabled == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_dns_enabled')" >
            BetterCap DNS
            
            <br><br>
            
            <input id="bc_gateway" type="checkbox" name="my-checkbox" <? if ($mod_bc_gateway == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_gateway')" >
            --gateway
            <br>
            <input id="bc_gateway_value" class="form-control input-sm" placeholder="GATEWAY" value="<?=$mod_bc_gateway_value?>" style="width: 120px; display: inline-block; " type="text" />
            <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('bc_gateway_value', 'mod_bc_gateway_value')">
            
            <br><br>
            
            <input id="bc_target" type="checkbox" name="my-checkbox" <? if ($mod_bc_target == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_target')" >
            --target
            <br>
            <input id="bc_target_value" class="form-control input-sm" placeholder="TARGET" value="<?=$mod_bc_target_value?>" style="width: 120px; display: inline-block; " type="text" />
            <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('bc_target_value', 'mod_bc_target_value')">
            
            <br><br>
            
            <input id="bc_no_discovery" type="checkbox" name="my-checkbox" <? if ($mod_bc_no_discovery == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_no_discovery')" >
            --no-discovery
            
            <br>
            
            <input id="bc_no_target_nbns" type="checkbox" name="my-checkbox" <? if ($mod_bc_no_target_nbns == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_no_target_nbns')" >
            --no-target-nbns
            
            <br><br>
            
            <input id="bc_spoofer" type="checkbox" name="my-checkbox" <? if ($mod_bc_spoofer == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_spoofer')" >
            --spoofer
            <br>
            <input id="bc_spoofer_value" class="form-control input-sm" placeholder="MODULE" value="<?=$mod_bc_spoofer_value?>" style="width: 120px; display: inline-block; " type="text" />
            <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('bc_spoofer_value', 'mod_bc_spoofer_value')">
            
            <br><br>
            
            <input id="bc_kill" type="checkbox" name="my-checkbox" <? if ($mod_bc_kill == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_kill')" >
            --kill
            
            <br><br>
            
            <input id="bc_proxy" type="checkbox" name="my-checkbox" <? if ($mod_bc_proxy == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_proxy')" >
            --proxy
            <br>
            <input id="bc_proxy_port" class="form-control input-sm" placeholder="PORT" value="<?=$mod_bc_proxy_port?>" style="width: 120px; display: inline-block; " type="text" />
            <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('bc_proxy_port', 'mod_bc_proxy_port')">
            
            <br><br>
            
            <input id="bc_no_sslstrip" type="checkbox" name="my-checkbox" <? if ($mod_bc_no_sslstrip == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_bc_no_sslstrip')" >
            --no-sslstrip
            
            
        </form>
    </div>
    
	<!-- ABOUT -->

	<div id="tab-about" class="history">
		<? include "includes/about.php"; ?>
	</div>
	
	<!-- END ABOUT -->    
    
</div>

<div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
    Loading...
</div>

<script>

$('#loading').hide();

</script>

<?
if ($_GET["tab"] == 1) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 1 });";
	echo "</script>";
} else if ($_GET["tab"] == 2) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 2 });";
	echo "</script>";
} else if ($_GET["tab"] == 3) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 3 });";
	echo "</script>";
} else if ($_GET["tab"] == 4) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 4 });";
	echo "</script>";
} else if ($_GET["tab"] == 5) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 5 });";
	echo "</script>";
} else if ($_GET["tab"] == 6) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 6 });";
	echo "</script>";
} else if ($_GET["tab"] == 7) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 7 });";
	echo "</script>";
} else if ($_GET["tab"] == 8) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 8 });";
	echo "</script>";
}
?>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#body').show();
    $('#msg').hide();
});
</script>

</body>
</html>
