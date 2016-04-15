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
<?
include "../../../config/config.php";
include "../_info_.php";
include "../../../login_check.php";
include "../../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["file"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
    regex_standard($_GET["mod_service"], "../msg.php", $regex_extra);
    regex_standard($_GET["mod_action"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];
$mod_service = $_GET['mod_service'];
$mod_action = $_GET['mod_action'];

if($service != "") {
    if ($action == "start") {
        // COPY LOG
        $exec = "$bin_mv $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
        exec_fruitywifi($exec);
        
        // OPTIONS
        $opt ="";
        
        if ($mod_bc_gateway == "1") {
            $opt .= " --gateway '$mod_bc_gateway_value' "; 
        }
        if ($mod_bc_target == "1") {
            $opt .= " --target '$mod_bc_target_value' "; 
        }
        if ($mod_bc_no_discovery == "1") {
            $opt .= " --no-discovery "; 
        }
        if ($mod_bc_no_target_nbns == "1") {
            $opt .= " --no-target-nbns "; 
        }
        if ($mod_bc_spoofer == "1") {
            $opt .= " --spoofer '$mod_bc_spoofer_value' "; 
        } else {
            $opt .= " --no-spoofing "; 
        }
        if ($mod_bc_proxy == "1") {
            $opt .= " --proxy -P POST ";
            $opt .= " --proxy-port $mod_bc_proxy_port ";
        }
        if ($mod_bc_kill == "1") {
            $opt .= " --kill "; 
        }
        if ($mod_bc_no_sslstrip == "1") {
            $opt .= " --no-sslstrip "; 
        }
        
        $exec = "$bin_bettercap -I $io_in_iface $opt --log-timestamp -O $mod_logs > /dev/null &";
        //echo $exec;
        //exit;
        
        //$exec = "$bin_bettercap --proxy -P POST --proxy-port 10000 -I $io_in_iface --no-spoofing --no-discovery --log-timestamp -O $mod_logs > /dev/null &";
        exec_fruitywifi($exec);
        
        $wait = 2;
        
        sleep(2);
        
        for ($i=0; $i <= 4; $i++) {
            $exec = "$bin_iptables -t nat -D POSTROUTING -s 0/0 -j MASQUERADE";
            exec_fruitywifi($exec);
        }
        
        if ($mod_bc_dns_enabled == "0") {
            sleep(2);
        
            $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p tcp --dport 53 -j DNAT --to $io_in_ip:5300";
            exec_fruitywifi($exec);
            
            $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p udp --dport 53 -j DNAT --to $io_in_ip:5300";
            exec_fruitywifi($exec);
            /*	
            $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p tcp --dport 80 -j DNAT --to $io_in_ip:10000";
            exec_fruitywifi($exec);
        
            $exec = "$bin_iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 10000";
            exec_fruitywifi($exec);
            */
        }
        
    } else if($action == "stop") {
        
        $exec = "ps aux|grep -E 'bettercap' | grep -v grep | awk '{print $2}'";
        exec($exec,$output);
        
        $exec = "kill " . $output[0];
        exec_fruitywifi($exec);
        
        // CLEAN IPTABLES RULES
        
        $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p tcp --dport 53 -j DNAT --to $io_in_ip:5300";
        exec_fruitywifi($exec);
        
        $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p udp --dport 53 -j DNAT --to $io_in_ip:5300";
        exec_fruitywifi($exec);
        
        $exec = "$bin_iptables -t nat -D PREROUTING -i $io_in_iface -p tcp --dport 80 -j DNAT --to $io_in_ip:10000";
        exec_fruitywifi($exec);
        
        $exec = "$bin_iptables -t nat -D PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 10000";
        exec_fruitywifi($exec);
    }
}

if ($install == "install_$mod_name") {

    $exec = "chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);
    
    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name.'&wait='.$wait);
}

?>
