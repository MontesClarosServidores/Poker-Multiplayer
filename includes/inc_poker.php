<? if($valid == false) header('Location: login.php'); if($gameID == '') header('Location: lobby.php'); $time = time(); $tq = mysql_query("select tabletype,tablelimit,tablename, tablelow,tablestyle, move from ".DB_POKER." where gameID = '".$gameID."' "); $tr = mysql_fetch_array($tq); $tablename= $tr['tablename']; $tabletype = $tr['tabletype']; $tablelimit = $tr['tablelimit']; $tablestyle = $tr['tablestyle']; $tomove = $tr['move']; $min = $tr['tablelow']; $sq = mysql_query("select style_name, style_lic from styles where style_name = '".$tablestyle."' "); if(mysql_num_rows($sq) > 0){ $sr = mysql_fetch_array($sq); $nam = addslashes($sr['style_name']); $lic = addslashes($sr['style_lic']); if(kry_officialstyle($nam,$lic) == false){ $officialstylepack = 'default'; } }else{ $officialstylepack = 'default'; } if($_GET['action'] == 'leave') { $pq = mysql_query("select p1name, p2name, p3name, p4name, p5name, p6name, p7name, p8name, p9name, p10name, p1pot, p2pot, p3pot, p4pot, p5pot, p6pot, p7pot, p8pot,p9pot, p10pot from ".DB_POKER." where gameID = '".$gameID."' "); $pr = mysql_fetch_array($pq); $i = 1; $player = ''; while($i < 11){ if($pr['p'.$i.'name'] == $plyrname){ $player = $i; $pot = $pr['p'.$i.'pot']; } $i++; } if($player != ''){ sys_msg($plyrname.' leaves the table',$gameID); $statsq = mysql_query("select winpot from ".DB_STATS." where player = '".$plyrname."' "); $statsr = mysql_fetch_array($statsq); $winnings = $statsr['winpot']; $winpot = $pot; $winpot += $winnings; $result = mysql_query("update ".DB_STATS." set winpot = '".$winpot."' where player  = '".$plyrname."' "); if($tomove == $player){ $nxtp = nextplayer($player); $result = mysql_query("update ".DB_POKER." set p".$player."name = '', p".$player."bet = '', p".$player."pot = '', move = '".$nxtp."', lastmove = '".$time."'  where gameID = '".$gameID."' "); }else{ $result = mysql_query("update ".DB_POKER." set p".$player."name = '', p".$player."bet = '', p".$player."pot = '', lastmove = '".$time."' where gameID = '".$gameID."' "); } } $wait = WAITIMER; $setwait = $time+$wait; $result = mysql_query("update ".DB_PLAYERS." set waitimer = '".$setwait."' where username = '".$plyrname."' "); $result = mysql_query("update ".DB_PLAYERS." set gID = '', vID = '' where username  = '".$plyrname."' "); header('Location: sitout.php'); } ?>