<?php

class SR_DHCP_Host {
	var $Hostname;
	var $HWAddr;
	var $IPAddr;

	public static function Parse($hostBlock){
		// Get the host name
		$hostName = trim(substr($hostBlock, 0, strpos($hostBlock, "{")));
		$hostName = explode(" ", $hostName);

		if($hostName[0] != "host") return NULL;

		$host = new SR_DHCP_Host();
		$host->Hostname = $hostName[1];

		$innerBlock = trim(substr($hostBlock, strpos($hostBlock, "{") + 1, strpos($hostBlock, "}", strpos($hostBlock, "{")) - strpos($hostBlock, "{")));
		$statements = explode(";", $innerBlock);
		foreach($statements as $line){
			$line = explode(" ", trim($line));
			switch($line[0]){
				case "fixed-address":
					$host->IPAddr = trim($line[1]);
					break;
				case "hardware":
					if($line[1] != "ethernet") continue;
					$host->HWAddr = trim($line[2]);
					break;
			}
		}
		return $host;
	}

	public function __toString(){
		return "host " . $this->Hostname . " {
	hardware ethernet " . $this->HWAddr . ";
	fixed-address " . $this->IPAddr . ";
}
";
	}

}

?>
