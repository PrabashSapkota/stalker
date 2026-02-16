<?php

function mac_macurl()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['server_url']) && !empty($znm['server_url'])) {
        $output = $znm['server_url'];
    }
    return $output;
}

function mac_serverurl()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['server_url']) && !empty($znm['server_url'])) {
        $output = str_replace("/c/", "/server/load.php", $znm['server_url']);
    }
    return $output;
}

function mac_macid()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['mac_id']) && !empty($znm['mac_id'])) {
        $output = $znm['mac_id'];
    }
    return $output;
}

function mac_serial()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['serial']) && !empty($znm['serial'])) {
        $output = $znm['serial'];
    }
    return $output;
}

function mac_device_1()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['device_id1']) && !empty($znm['device_id1'])) {
        $output = $znm['device_id1'];
    }
    return $output;
}

function mac_device_2()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['device_id2']) && !empty($znm['device_id2'])) {
        $output = $znm['device_id2'];
    }
    return $output;
}

function mac_signature()
{
    $output = "";
    $znm = app_macportaldetail("get", "", "", "", "", "", "");
    if(isset($znm['signature']) && !empty($znm['signature'])) {
        $output = $znm['signature'];
    }
    return $output;
}

function mac_handshake()
{
    global $APP_CONFIG;
    $output = array("token" => "", "random" => ""); $token = ""; $random = "";
    $path_toknfile = $APP_CONFIG['DATA_FOLDER']."/axToken.enc";
    if(file_exists($path_toknfile)) {
        $ivbata = json_decode(@file_get_contents($path_toknfile), true);
        if(isset($ivbata['time']) && isset($ivbata['token']) && isset($ivbata['random'])) {
            if(time() < $ivbata['time']) {
                $output = array("token" => $ivbata['token'], "random" => $ivbata['random']);
            }
        }
    }
    if(empty($output['token']))
    {
        $rqlink = mac_serverurl()."?type=stb&action=handshake&token=&JsHttpRequest=1-xml";
        $rqheadz = array("User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux; C) AppleWebKit/533.3 (KHTML, like Gecko) MAG200 stbapp ver: 2 rev: 250 Safari/533.3",
                         "X-User-Agent: Model: MAG250; Link: WiFi",
                         "Referer: ".mac_macurl(),
                         "Cookie: mac=".mac_macid()."; stb_lang=en; timezone=GMT");
        $fetch = getRequest($rqlink, $rqheadz);
        $adata = @json_decode($fetch['data'], true);
        if(isset($adata['js']['token']) && !empty($adata['js']['token'])) { $token = $adata['js']['token']; }
        if(isset($adata['js']['random']) && !empty($adata['js']['random'])) { $random = $adata['js']['random']; }
        if(!empty($token)) {
            $output = array("token" => $token, "random" => $random);
            if(file_put_contents($path_toknfile, json_encode(array("time" => time() + 120, "token" => $token, "random" => $random)))){}
        }
        else
        {
            if($fetch['data'] !== strip_tags($fetch['data'])){ $fetch['data'] = strip_tags($fetch['data']); }
            app_recordalogs("ERROR", "Stalker Portal Handshake Failed :: ".$fetch['data']." (Code ".$fetch['code'].")");
        }
    }
    return $output;
}

function mac_getprofile()
{
    global $APP_CONFIG;
    $path_Profiledt = $APP_CONFIG['DATA_FOLDER']."/axMeta.enc";
    $name = ""; $expiry = ""; $username = ""; $password = ""; $output = array();
    $pfLoad = 'type=stb&action=get_profile&hd=1&ver='.urlencode('ImageDescription: 0.2.18-r14-pub-250; ImageDate: Fri Jan 15 15:20:44 EET 2016; PORTAL version: 5.1.0; API Version: JS API version: 328; STB API version: 134; Player Engine version: 0x566').'&num_banks=2&sn='.mac_serial().'&stb_type=MAG250&image_version=218&video_out=hdmi&device_id='.mac_device_1().'&device_id2='.mac_device_2().'&signature='.mac_signature().'&auth_second_step=1&hw_version=1.7-BD-00&not_valid_token=0&client_type=STB&hw_version_2=36da041e6358ee8f8801105e36a63474&timestamp='.time().'&api_signature=263&metrics={"mac":"'.mac_macid().'","sn":"'.mac_serial().'","model":"MAG250","type":"STB","uid":"","random":"'.mac_handshake()['random'].'"}&JsHttpRequest=1-xml';
    $pfAPI = mac_serverurl().'?'.$pfLoad;
    $pfHeadz = array("User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux; C) AppleWebKit/533.3 (KHTML, like Gecko) MAG200 stbapp ver: 2 rev: 250 Safari/533.3",
                      "X-User-Agent: Model: MAG250; Link: WiFi",
                      "Referer: ".mac_macurl(),
                      "Cookie: mac=".mac_macid()."; stb_lang=en; timezone=GMT",
                      "Authorization: Bearer ".mac_handshake()['token']);
    $fetch = getRequest($pfAPI, $pfHeadz);
    $adata = @json_decode($fetch['data'], true);
    if(isset($adata['js']['fname']) && !empty($adata['js']['fname'])) { $name = $adata['js']['fname']; }
    if(empty($name) && isset($adata['js']['name']) && !empty($adata['js']['name'])) { $name = $adata['js']['name']; }
    if(isset($adata['js']['expirydate']) && !empty($adata['js']['expirydate'])) { $expiry = $adata['js']['expirydate']; }
    if(empty($expiry) && isset($adata['js']['expire_billing_date']) && !empty($adata['js']['expire_billing_date'])) { $expiry = $adata['js']['expire_billing_date']; }

    if(isset($adata['js']['login']) && !empty($adata['js']['login'])) { $username = $adata['js']['login']; }
    if(isset($adata['js']['password']) && !empty($adata['js']['password'])) { $password = $adata['js']['password']; }
    if(!empty($name)) {
        $output = array("name" => $name, "expiry" => $expiry, "username" => $username, "password" => $password);
        @file_put_contents($path_Profiledt, json_encode($output));
    }
    else
    {
        if($fetch['data'] !== strip_tags($fetch['data'])){ $fetch['data'] = strip_tags($fetch['data']); }
        app_recordalogs("ERROR", "Profile Meta-Info Fetch Failed :: ".$fetch['data']." (Code ".$fetch['code'].")");
    }
    return $output;
}

function app_macportalmeta($action)
{
    global $APP_CONFIG;
    $output = array();
    $path_Profiledt = $APP_CONFIG['DATA_FOLDER']."/axMeta.enc";
    if(file_exists($path_Profiledt)) {
        $data_profile = @json_decode(@file_get_contents($path_Profiledt), true);
        if(isset($data_profile['expiry']) && !empty($data_profile['expiry'])) {
            $output = $data_profile;
        }
    }
    return $output;
}

function mac_getallChannels()
{
    global $APP_CONFIG;
    mac_getprofile();
    $output = array();
    $ctv_path = $APP_CONFIG['DATA_FOLDER']."/axCTV.enc";
    if(file_exists($ctv_path)) {
        $ctv_data = @json_decode(@file_get_contents($ctv_path), true);
        if(isset($ctv_data[0])) { $output = $ctv_data; }
    }
    if(empty($output))
    {
        $xvAPI = mac_serverurl()."?type=itv&action=get_all_channels&JsHttpRequest=1-xml";
        $xvHead = array("User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux; C) AppleWebKit/533.3 (KHTML, like Gecko) MAG200 stbapp ver: 2 rev: 250 Safari/533.3",
                        "X-User-Agent: Model: MAG250; Link: WiFi",
                        "Referer: ".mac_macurl(),
                        "Cookie: mac=".mac_macid()."; stb_lang=en; timezone=GMT",
                        "Authorization: Bearer ".mac_handshake()['token']);
        $fetch = getRequest($xvAPI, $xvHead);
        $adata = @json_decode($fetch['data'], true);
        if(isset($adata['js']['data'][0]['cmd']) && !empty($adata['js']['data'][0]['cmd'])) {
            foreach($adata['js']['data'] as $itv) {
                $output[] = array("id" => $itv['id'],
                                  "title" => $itv['name'],
                                  "logo" => $itv['logo'],
                                  "cmd" => $itv['cmd']);
            }
        }
        else
        {
            app_recordalogs("ERROR", "Channel List Fetch Failed :: ".$fetch['data']." (Code ".$fetch['code'].")");
        }
        if(!empty($output)) { app_recordalogs("SUCCESS", "Channel List Updated"); @file_put_contents($ctv_path, json_encode($output)); }
    }
    return $output;
}

function mac_getPlaybackLink($id)
{
    global $APP_CONFIG;
    mac_getprofile();
    $output = false;
    $cdetail = getChannelDetail($id);
    if(!empty($cdetail))
    {
        $mpbAPI = mac_serverurl()."?type=itv&action=create_link&cmd=".urlencode($cdetail['cmd'])."&JsHttpRequest=1-xml";
        $mpbHead = array("User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux; C) AppleWebKit/533.3 (KHTML, like Gecko) MAG200 stbapp ver: 2 rev: 250 Safari/533.3",
                        "X-User-Agent: Model: MAG250; Link: WiFi",
                        "Referer: ".mac_macurl(),
                        "Cookie: mac=".mac_macid()."; stb_lang=en; timezone=GMT",
                        "Authorization: Bearer ".mac_handshake()['token']);
        $fetch = getRequest($mpbAPI, $mpbHead);
        $adata = @json_decode($fetch['data'], true);
        if(isset($adata['js']['cmd']) && !empty($adata['js']['cmd'])) {
            $output = sanitizemacurl($adata['js']['cmd']);
        }
        else
        {
            app_recordalogs("ERROR", "Channel Playback-URL Fetch Failed :: ".$fetch['data']." (Code ".$fetch['code'].")");
        }
    }
    return $output;
}

function sanitizemacurl($url)
{
    $url = str_replace("ffmpeg ", "", $url);
    if(stripos($url, "jiotv.be") && stripos($url, ".ts") !== false)
    {
        $url = str_replace(".ts.ts", ".m3u8", $url);
        $url = str_replace(".ts", ".m3u8", $url);
        $uparts = parse_url($url);
        if(isset($uparts['path']) && !empty($uparts['path'])) {
            $upathx = explode("/", $uparts['path']);
            $url = $uparts['scheme']."://".$uparts['host'].":".$uparts['port']."/".$upathx[1]."/".$upathx[2]."/".$upathx[3]."/".$upathx[8]."?".$uparts['query'];
        }
    }
    return $url;
}

?>