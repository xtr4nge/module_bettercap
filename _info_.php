<?
$mod_name="bettercap";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_type="module";
$mod_isup="ps aux|grep -E 'bettercap' | grep -v grep | awk '{print $2}'";
$mod_alias="BetterCap";

# OPTIONS
$mod_bc_dns_enabled="1";
$mod_bc_gateway="0";
$mod_bc_gateway_value="10.0.0.1";
$mod_bc_target="0";
$mod_bc_target_value="10.0.0.1-255";
$mod_bc_no_discovery="1";
$mod_bc_no_target_nbns="1";
$mod_bc_spoofer="0";
$mod_bc_spoofer_value="ARP";
$mod_bc_kill="0";
$mod_bc_proxy="1";
$mod_bc_proxy_port="10000";
$mod_bc_no_sslstrip="0";

# EXEC
$bin_bettercap = "/usr/local/bin/bettercap";
$bin_python = "/usr/bin/python";
$bin_rm = "/bin/rm";
$bin_echo = "/bin/echo";
$bin_touch = "/bin/touch";
$bin_mv = "/bin/mv";
$bin_sed = "/bin/sed";
$bin_dos2unix = "/usr/bin/dos2unix";
$bin_iptables = "/sbin/iptables";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
?>
